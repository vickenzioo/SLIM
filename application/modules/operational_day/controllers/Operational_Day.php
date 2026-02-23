<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Operational_Day extends CI_Controller {

    public function __construct() {
        parent::__construct();
        date_default_timezone_set('Asia/Jakarta');
        if (!$this->session->userdata('email')) {
             redirect('auth');
        }
        $this->load->model('Operational_Day_model');
        $this->load->model('audit/Audit_model');
    }

    public function index() {
        $this->load->library('pagination');
        $keyword = $this->security->xss_clean($this->input->get('keyword'));
        $filters = $this->input->get('filter'); // Ambil Filter

        $config['base_url'] = base_url('operational_day/index');
        
        // Setup Query String Pagination + Filter
        $params = [];
        if($keyword) $params['keyword'] = $keyword;
        if($filters) $params['filter'] = $filters;
        $config['suffix'] = !empty($params) ? '?' . http_build_query($params, '', '&') : '';
        $config['first_url'] = $config['base_url'] . $config['suffix'];

        $config['total_rows'] = $this->Operational_Day_model->count_all_operational_days($keyword, $filters);
        $config['per_page'] = 10; 
        
        $config['enable_query_strings'] = TRUE;
        $config['page_query_string'] = TRUE;
        $config['query_string_segment'] = 'per_page';
        $config['reuse_query_string'] = TRUE;
        $config['num_links'] = 5;

        // Pagination Style
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
        
        // Ambil Data Utama dengan Filter
        $data['operational_days'] = $this->Operational_Day_model->get_operational_days_paginated($config['per_page'], $start, $keyword, $filters);
        
        // --- LOAD DATA FILTER ---
        $data['opt_start_day'] = $this->Operational_Day_model->get_dynamic_options('start_day', $filters);
        $data['opt_end_day']   = $this->Operational_Day_model->get_dynamic_options('end_day', $filters);
        $data['opt_total_day'] = $this->Operational_Day_model->get_dynamic_options('total_day', $filters);

        $data['keyword'] = $keyword;
        $data['selected_filters'] = $filters;
        $data['pagination'] = $this->pagination->create_links();
        $data['total_rows'] = $config['total_rows'];

        $this->load->view('operational_day_view', $data);
    }

    public function export() {
        // 1. Simpan Log Audit
        $this->Audit_model->insert_log([
            'username'   => $this->session->userdata('username'),
            'action'     => 'EXPORT',
            'table_name' => 'tbl_apps_operational_day', 
            'foreign_id' => 0,
            'field_name' => '-',
            'old_value'  => '-',
            'new_value'  => '-',
            'reason'     => 'Export Data',
            'timestamp'  => date('Y-m-d H:i:s')
        ]);

        // 2. Ambil semua data (tanpa limit)
        $data['operational_days'] = $this->Operational_Day_model->get_all_operational_days();

        header("Content-type: application/vnd-ms-excel");
        header("Content-Disposition: attachment; filename=Operational_Day_".date("Y-m-d").".xls");
        header("Pragma: no-cache");
        header("Expires: 0");

        $this->load->view('operational_day_export', $data);
    }

    // --- Fungsi CRUD Lainnya (Tidak Berubah) ---
    public function save() {
        $id = $this->security->xss_clean($this->input->post('operational_day_id'));
        $start_day = trim($this->security->xss_clean($this->input->post('start_day')));
        $end_day   = trim($this->security->xss_clean($this->input->post('end_day')));
        $reason = $this->security->xss_clean($this->input->post('reason'));
        $username = $this->session->userdata('username'); 
        $userId = $this->session->userdata('user_id');

        if ($this->Operational_Day_model->check_duplicate_day($start_day, $end_day, $id)) {
            $this->session->set_flashdata('error', 'Data Operational Day dengan hari tersebut sudah ada!');
            redirect('operational_day'); return;
        }

        if ($id) {
            if(empty($reason)){
                $this->session->set_flashdata('error', 'Gagal update: Alasan wajib diisi!');
                redirect('operational_day'); return;
            }
            $oldData = $this->db->get_where('tbl_apps_operational_day', ['operational_day_id' => $id])->row_array();
            $oldStart = trim($oldData['start_day']);
            $oldEnd   = trim($oldData['end_day']);
            if ($oldStart == $start_day && $oldEnd == $end_day) {
                $this->session->set_flashdata('error', 'Gagal simpan: Tidak ada perubahan data.');
                redirect('operational_day'); return;
            }
            $update_data = [ 'start_day' => $start_day, 'end_day' => $end_day, 'modified_by' => $userId, 'modified_at' => date("Y-m-d H:i:s") ];
            $this->Operational_Day_model->update_operational_day($id, $update_data);
            $fields_to_track = [ 'start_day' => 'Start Day', 'end_day' => 'End Day' ];
            foreach ($fields_to_track as $field => $display_name) {
                if ($oldData[$field] != ${$field}) {
                    $this->Audit_model->insert_log([ 'username' => $username, 'action' => 'EDIT', 'table_name' => 'tbl_apps_operational_day', 'foreign_id' => $id, 'field_name' => $display_name, 'old_value' => $oldData[$field], 'new_value' => ${$field}, 'reason' => $reason, 'timestamp' => date('Y-m-d H:i:s') ]);
                }
            }
            $this->session->set_flashdata('success', 'Data Operational Day berhasil diperbarui');
        } else {
            $insert_data = [ 'start_day' => $start_day, 'end_day' => $end_day, 'created_by' => $userId, 'created_at' => date("Y-m-d H:i:s") ];
            $this->Operational_Day_model->insert_operational_day($insert_data);
            $new_id = $this->db->insert_id(); 
            $this->Audit_model->insert_log([ 'username' => $username, 'action' => 'ADD', 'table_name' => 'tbl_apps_operational_day', 'foreign_id' => $new_id, 'field_name' => 'Start Day', 'old_value' => '-', 'new_value' => $start_day, 'reason' => 'Initial Creation', 'timestamp' => date('Y-m-d H:i:s') ]);
            $this->Audit_model->insert_log([ 'username' => $username, 'action' => 'ADD', 'table_name' => 'tbl_apps_operational_day', 'foreign_id' => $new_id, 'field_name' => 'End Day', 'old_value' => '-', 'new_value' => $end_day, 'reason' => 'Initial Creation', 'timestamp' => date('Y-m-d H:i:s') ]);
            $this->session->set_flashdata('success', 'Data Operational Day berhasil ditambahkan');
        }
        redirect('operational_day');
    }

    public function update_status() {
        // Ambil data dari request AJAX
        $id     = $this->input->post('id');     
        $status = $this->input->post('status'); 
        $reason = $this->security->xss_clean($this->input->post('reason')); // Tangkap alasan

        if (!empty($id)) {
            // Ambil data lama untuk log audit
            $old_data = $this->Operational_Day_model->get_by_id($id);
            $username = $this->session->userdata('username');

            // Validasi jika data tidak ditemukan di database
            if (!$old_data) {
                $msg = ($status == 0) ? "Gagal menonaktifkan" : "Gagal mengaktifkan kembali";
                echo json_encode(['success' => false, 'message' => $msg . ': Data tidak ditemukan']);
                return;
            }

            // Ambil nama data untuk pesan notifikasi (Rentang Hari)
            $nama_data = $old_data['start_day'] . ' - ' . $old_data['end_day'];

            // Update status di tbl_apps_operational_day
            $update = $this->Operational_Day_model->update_status($id, $status);

            if ($update) {
                // Simpan ke Audit Trail
                $this->Audit_model->insert_log([
                    'username'   => $username,
                    'action'     => ($status == 0) ? 'DEACTIVATE' : 'ACTIVATE',
                    'table_name' => 'tbl_apps_operational_day',
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

        $db_data = $this->Operational_Day_model->get_by_id($id); 
        if (!$db_data) {
            $this->session->set_flashdata('error', 'Data tidak ditemukan.');
            redirect('operational_day');
        }

        // [XSS CLEAN] Membersihkan keyword pencarian di halaman audit
        $keyword = $this->security->xss_clean($this->input->get('keyword'));
        
        $table_name = 'tbl_apps_operational_day';

        $config['base_url'] = base_url('operational_day/audit/' . $id);
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

        $data['target_name'] = $db_data['start_day'] . ' - ' . $db_data['end_day'];
        $data['keyword']     = $keyword;
        $data['back_url']    = 'operational_day';
        $data['export_url']  = base_url('audit/export_excel/tbl_apps_operational_day/' . $id);
        $data['audit_data']  = $audit_logs;
        $data['pagination']  = $this->pagination->create_links();
        $data['total_rows']  = $config['total_rows']; 

        $this->load->view('audit/audit_view', $data);
    }
}