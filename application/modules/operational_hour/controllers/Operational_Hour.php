<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Operational_Hour extends CI_Controller {

    public function __construct() {
        parent::__construct();
        date_default_timezone_set('Asia/Jakarta');
        if (!$this->session->userdata('email')) {
             redirect('auth');
        }
        $this->load->model('Operational_Hour_model');
        $this->load->model('audit/Audit_model');
    }

    public function index() {
        $this->load->library('pagination');
        $keyword = $this->security->xss_clean($this->input->get('keyword'));
        $filters = $this->input->get('filter'); 

        $config['base_url'] = base_url('operational_hour/index');
        
        $params = [];
        if($keyword) $params['keyword'] = $keyword;
        if($filters) $params['filter'] = $filters;
        $config['suffix'] = !empty($params) ? '?' . http_build_query($params, '', '&') : '';
        $config['first_url'] = $config['base_url'] . $config['suffix'];

        $config['total_rows'] = $this->Operational_Hour_model->count_all_operational_hours($keyword, $filters);
        $config['per_page'] = 10; 
        
        $config['enable_query_strings'] = TRUE;
        $config['page_query_string'] = TRUE;
        $config['query_string_segment'] = 'per_page';
        $config['reuse_query_string'] = TRUE;
        $config['num_links'] = 5;

        $config['full_tag_open']    = '<ul class="pagination pagination-sm m-0 float-right">';
        $config['full_tag_close']   = '</ul>';
        $config['first_link']       = '&laquo; First';
        $config['first_tag_open']   = '<li class="page-item">';
        $config['first_tag_close']  = '</li>';
        $config['last_link']        = 'Last &raquo;';
        $config['last_tag_open']    = '<li class="page-item">';
        $config['last_tag_close']   = '</li>';
        $config['next_link']        = '&rsaquo;';
        $config['next_tag_open']    = '<li class="page-item">';
        $config['next_tag_close']   = '</li>';
        $config['next_link']        = '&rsaquo;';
        $config['prev_tag_open']    = '<li class="page-item">';
        $config['prev_tag_close']   = '</li>';
        $config['cur_tag_open']     = '<li class="page-item active"><a class="page-link" href="#">';
        $config['cur_tag_close']    = '</a></li>';
        $config['num_tag_open']     = '<li class="page-item">';
        $config['num_tag_close']    = '</li>';
        $config['attributes']       = array('class' => 'page-link');

        $this->pagination->initialize($config);

        $start = $this->input->get('per_page');
        
        $data['operational_hours'] = $this->Operational_Hour_model->get_operational_hours_paginated($config['per_page'], $start, $keyword, $filters);
        
        $data['opt_start_time'] = $this->Operational_Hour_model->get_dynamic_options('start_time', $filters);
        $data['opt_end_time']   = $this->Operational_Hour_model->get_dynamic_options('end_time', $filters);
        $data['opt_total_hour'] = $this->Operational_Hour_model->get_dynamic_options('total_hour', $filters);
		$data['opt_status'] = $this->Operational_Hour_model->get_dynamic_options('status', $filters);

        $data['keyword'] = $keyword;
        $data['selected_filters'] = $filters;
        $data['pagination'] = $this->pagination->create_links();
        $data['total_rows'] = $config['total_rows'];

        $this->load->view('operational_hour_view', $data);
    }

    public function save() {
        $id = $this->security->xss_clean($this->input->post('operational_hour_id'));
        $start_time = trim($this->security->xss_clean($this->input->post('start_time')));
        $end_time   = trim($this->security->xss_clean($this->input->post('end_time')));
        $reason = $this->security->xss_clean($this->input->post('reason'));
        $username = $this->session->userdata('username'); 
        $userId = $this->session->userdata('user_id');
        $t1 = strtotime($start_time);
        $t2 = strtotime($end_time);
        $diff_seconds = $t2 - $t1;
        if ($diff_seconds < 0) {
            $this->session->set_flashdata('error', 'Jam Selesai tidak boleh lebih kecil dari Jam Mulai!');
            redirect('operational_hour'); return;
        }
        $total_hour_val = number_format($diff_seconds / 3600, 2);
        if ($this->Operational_Hour_model->check_duplicate_hour($start_time, $end_time, $id)) {
            $this->session->set_flashdata('error', 'Data Operational Hour dengan jam tersebut sudah ada!');
            redirect('operational_hour'); return;
        }
        if ($id) {
            if(empty($reason)){
                $this->session->set_flashdata('error', 'Gagal update: Alasan wajib diisi!');
                redirect('operational_hour'); return;
            }
            $oldData = $this->db->get_where('tbl_apps_operational_hour', ['operational_hour_id' => $id])->row_array();
            $oldStart = trim($oldData['start_time']);
            $oldEnd   = trim($oldData['end_time']);
            if ($oldStart == $start_time && $oldEnd == $end_time) {
                $this->session->set_flashdata('error', 'Gagal simpan: Tidak ada perubahan data.');
                redirect('operational_hour'); return; 
            }
            $update_data = [ 'start_time' => $start_time, 'end_time' => $end_time, 'total_hour' => $total_hour_val, 'modified_by' => $userId, 'modified_at' => date("Y-m-d H:i:s") ];
            $this->Operational_Hour_model->update_operational_hour($id, $update_data);
            $fields_to_track = [ 'start_time' => ['label' => 'Start Time', 'new' => $start_time], 'end_time' => ['label' => 'End Time', 'new' => $end_time], 'total_hour' => ['label' => 'Total Hour', 'new' => $total_hour_val] ];
            foreach ($fields_to_track as $field => $info) {
                if (trim($oldData[$field]) != trim($info['new'])) {
                    $this->Audit_model->insert_log([ 'username' => $username, 'action' => 'EDIT', 'table_name' => 'tbl_apps_operational_hour', 'foreign_id' => $id, 'field_name' => $info['label'], 'old_value' => $oldData[$field], 'new_value' => $info['new'], 'reason' => $reason, 'timestamp' => date('Y-m-d H:i:s') ]);
                }
            }
            $this->session->set_flashdata('success', 'Data Operational Hour berhasil diperbarui');
        } else {
            $insert_data = [ 'start_time' => $start_time, 'end_time' => $end_time, 'total_hour' => $total_hour_val, 'created_by' => $userId, 'created_at' => date("Y-m-d H:i:s") ];
            $this->Operational_Hour_model->insert_operational_hour($insert_data);
            $new_id = $this->db->insert_id(); 
            $this->Audit_model->insert_log([ 'username' => $username, 'action' => 'ADD', 'table_name' => 'tbl_apps_operational_hour', 'foreign_id' => $new_id, 'field_name' => 'Start Time', 'old_value' => '-', 'new_value' => $start_time, 'reason' => 'Initial Creation', 'timestamp' => date('Y-m-d H:i:s') ]);
            $this->Audit_model->insert_log([ 'username' => $username, 'action' => 'ADD', 'table_name' => 'tbl_apps_operational_hour', 'foreign_id' => $new_id, 'field_name' => 'End Time', 'old_value' => '-', 'new_value' => $end_time, 'reason' => 'Initial Creation', 'timestamp' => date('Y-m-d H:i:s') ]);
            $this->Audit_model->insert_log([ 'username' => $username, 'action' => 'ADD', 'table_name' => 'tbl_apps_operational_hour', 'foreign_id' => $new_id, 'field_name' => 'Total Hour', 'old_value' => '-', 'new_value' => $total_hour_val . ' Hours', 'reason' => 'Initial Creation', 'timestamp' => date('Y-m-d H:i:s') ]);
            $this->session->set_flashdata('success', 'Data Operational Hour berhasil ditambahkan');
        }
        redirect('operational_hour');
    }
    
    public function update_status() {
        // Ambil data dari request AJAX
        $id     = $this->input->post('id');     
        $status = $this->input->post('status'); 
        $reason = $this->security->xss_clean($this->input->post('reason')); // Tangkap alasan

        if (!empty($id)) {
            // Ambil data lama untuk log audit
            $old_data = $this->Operational_Hour_model->get_by_id($id);
            $username = $this->session->userdata('username');

            // Validasi jika data tidak ditemukan di database
            if (!$old_data) {
                $msg = ($status == 0) ? "Gagal menonaktifkan" : "Gagal mengaktifkan kembali";
                echo json_encode(['success' => false, 'message' => $msg . ': Data tidak ditemukan']);
                return;
            }

            // Ambil nama data untuk pesan notifikasi (Rentang Hari)
            $nama_data = $old_data['start_time'] . ' - ' . $old_data['end_time'];

            // Update status di tbl_apps_operational_hour
            $update = $this->Operational_Hour_model->update_status($id, $status);

            if ($update) {
                // Simpan ke Audit Trail
                $this->Audit_model->insert_log([
                    'username'   => $username,
                    'action'     => ($status == 0) ? 'DEACTIVATE' : 'ACTIVATE',
                    'table_name' => 'tbl_apps_operational_hour',
                    'foreign_id' => $id,
                    'field_name' => 'status',
                    'old_value'  => ($status == 0) ? '1' : '0',
                    'new_value'  => ($status == 0) ? '0' : '1',
                    'reason'     => !empty($reason) ? $reason : 'Toggle Status',
                    'timestamp'  => date('Y-m-d H:i:s')
                ]);

                // Response dinamis berdasarkan status
                if ($status == 0) {
                    $response_msg = "Data '" . $nama_data . "' berhasil di nonaktifkan";
                } else {
                    $response_msg = "Data '" . $nama_data . "' berhasil di aktifkan kembali";
                }

                echo json_encode(['success' => true, 'message' => $response_msg]);
            } else {
                $error_msg = ($status == 0) ? "Gagal menonaktifkan" : "Gagal mengaktifkan kembali";
                echo json_encode(['success' => false, 'message' => $error_msg . ' data ' . $nama_data]);
            }
        } else {
            echo json_encode(['success' => false, 'message' => 'ID tidak ditemukan']);
        }
    }
    
    public function audit($id) {
        $this->load->library('pagination');

        $db_data = $this->Operational_Hour_model->get_by_id($id); 
        if (!$db_data) {
            $this->session->set_flashdata('error', 'Data tidak ditemukan.');
            redirect('operational_hour');
        }

        // [XSS CLEAN] Membersihkan keyword pencarian di halaman audit
        $keyword = $this->security->xss_clean($this->input->get('keyword'));
        
        $table_name = 'tbl_apps_operational_hour';

        $config['base_url'] = base_url('operational_hour/audit/' . $id);
        $config['total_rows'] = count($this->Audit_model->get_audit_logs($id, $keyword, $table_name));
        $config['per_page'] = 5; 
        $config['page_query_string'] = TRUE;
        $config['query_string_segment'] = 'per_page';
        $config['reuse_query_string'] = TRUE;

        $config['full_tag_open']    = '<ul class="pagination pagination-sm m-0 float-right">';
        $config['full_tag_close']   = '</ul>';
        $config['cur_tag_open']     = '<li class="page-item active"><a class="page-link" href="#">';
        $config['cur_tag_close']    = '</a></li>';
        $config['num_tag_open']     = '<li class="page-item">';
        $config['num_tag_close']    = '</li>';
        $config['attributes']       = array('class' => 'page-link');

        $this->pagination->initialize($config);

        $start = $this->input->get('per_page');
        $audit_logs = $this->Audit_model->get_audit_logs_paginated($id, $table_name, $config['per_page'], $start, $keyword);

        $data['target_name'] = $db_data['start_time'] . ' - ' . $db_data['end_time'];
        $data['keyword']     = $keyword;
        $data['back_url']    = 'operational_hour';
        $data['export_url']  = base_url('audit/export_excel/tbl_apps_operational_hour/' . $id);
        $data['audit_data']  = $audit_logs;
        $data['pagination']  = $this->pagination->create_links();
        $data['total_rows']  = $config['total_rows']; 

        $this->load->view('audit/audit_view', $data);
    }

    public function export() {
        $this->Audit_model->insert_log([ 'username' => $this->session->userdata('username'), 'action' => 'EXPORT', 'table_name' => 'tbl_apps_operational_hour', 'foreign_id' => 0, 'field_name' => '-', 'old_value' => '-', 'new_value' => '-', 'reason' => 'Export Data', 'timestamp' => date('Y-m-d H:i:s') ]);
        
        // [PERBAIKAN] Ambil keyword dan filter dari URL
        $keyword = $this->security->xss_clean($this->input->get('keyword'));
        $filters = $this->input->get('filter');

        // [PERBAIKAN] Passing parameter ke model
        $data['operational_hours'] = $this->Operational_Hour_model->get_all_operational_hours($keyword, $filters);
        
        header("Content-type: application/vnd-ms-excel");
        header("Content-Disposition: attachment; filename=Operational_Hour_".date("Y-m-d").".xls");
        header("Pragma: no-cache");
        header("Expires: 0");
        $this->load->view('operational_hour_export', $data);
    }
}