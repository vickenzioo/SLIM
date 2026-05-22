<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Operating_Software extends CI_Controller {

    public function __construct() {
        parent::__construct();
        date_default_timezone_set('Asia/Jakarta');
        if (!$this->session->userdata('email')) {
             redirect('auth');
        }
        $this->load->model('Operating_Software_model');
        $this->load->model('audit/Audit_model');
    }

    public function index() {
        $this->load->library('pagination');
        $keyword = $this->security->xss_clean($this->input->get('keyword'));
        $filters = $this->input->get('filter'); // Ambil filter

        $config['base_url'] = base_url('operating_software/index');
        
        $params = [];
        if($keyword) $params['keyword'] = $keyword;
        if($filters) $params['filter'] = $filters;
        $config['suffix'] = !empty($params) ? '?' . http_build_query($params, '', '&') : '';
        $config['first_url'] = $config['base_url'] . $config['suffix'];

        $config['total_rows'] = $this->Operating_Software_model->count_all_operating_softwares($keyword, $filters);
        $config['per_page'] = 5; 
        
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
        
        // Ambil Data Filtered
        $data['operating_softwares'] = $this->Operating_Software_model->get_operating_softwares_paginated($config['per_page'], $start, $keyword, $filters);
        
        // Load Options
        $data['opt_operating_software_name'] = $this->Operating_Software_model->get_dynamic_options('operating_software_name', $filters);

        $data['keyword'] = $keyword;
        $data['selected_filters'] = $filters;
        $data['pagination'] = $this->pagination->create_links();
        $data['total_rows'] = $config['total_rows'];

        $this->load->view('operating_software_view', $data);
    }

    public function export() {
        $this->Audit_model->insert_log([ 'username' => $this->session->userdata('username'), 'action' => 'EXPORT', 'table_name' => 'tbl_operating_software', 'foreign_id' => 0, 'field_name' => '-', 'old_value' => '-', 'new_value' => '-', 'reason' => 'Export Data', 'timestamp' => date('Y-m-d H:i:s') ]);
        
        $keyword = $this->security->xss_clean($this->input->get('keyword'));
        $filters = $this->input->get('filter');

        $data['operating_softwares'] = $this->Operating_Software_model->get_all_operating_softwares($keyword, $filters);

        header("Content-type: application/vnd-ms-excel");
        header("Content-Disposition: attachment; filename=Operating_Software_".date('Y-m-d').".xls");
        header("Pragma: no-cache");
        header("Expires: 0");

        $this->load->view('operating_software_export', $data);
    }

    public function save() {
        $id = $this->security->xss_clean($this->input->post('operating_software_id'));
        $name = trim($this->security->xss_clean($this->input->post('operating_software_name')));
        $reason = $this->security->xss_clean($this->input->post('reason'));
        $username = $this->session->userdata('username');
        $userId = $this->session->userdata('user_id');

        if ($this->Operating_Software_model->check_duplicate_software($name, $id)) {
            $this->session->set_flashdata('error', 'Nama Operating Software "'. $name .'" sudah ada! Gagal menyimpan.');
            redirect('operating_software'); return; 
        }
        
        if ($id) {
            if(empty($reason)){
                $this->session->set_flashdata('error', 'Gagal update: Alasan wajib diisi!');
                redirect('operating_software'); return;
            }
            $oldData = $this->db->get_where('tbl_operating_software', ['operating_software_id' => $id])->row_array();
            $oldName = trim($oldData['operating_software_name']);
            if ($oldName == $name) {
                $this->session->set_flashdata('error', 'Gagal simpan: Tidak ada perubahan data.');
                redirect('operating_software'); return;
            }
            $update_data = [ 'operating_software_name' => $name, 'modified_by' => $userId, 'modified_at' => date("Y-m-d H:i:s") ];
            $this->Operating_Software_model->update_operating_software($id, $update_data);
            $this->Audit_model->insert_log([ 'username' => $username, 'action' => 'EDIT', 'table_name' => 'tbl_operating_software', 'foreign_id' => $id, 'field_name' => 'operating_software_name', 'old_value' => $oldName, 'new_value' => $name, 'reason' => $reason, 'timestamp' => date('Y-m-d H:i:s') ]);
            $this->session->set_flashdata('success', 'Data berhasil diperbarui');
        } else {
            $insert_data = [ 'operating_software_name' => $name, 'created_by' => $userId, 'created_at' => date("Y-m-d H:i:s") ];
            $this->Operating_Software_model->insert_operating_software($insert_data);
            $new_id = $this->db->insert_id();
            $this->Audit_model->insert_log([ 'username' => $username, 'action' => 'ADD', 'table_name' => 'tbl_operating_software', 'foreign_id' => $new_id, 'field_name' => 'operating_software_name', 'old_value' => '-', 'new_value' => $name, 'reason' => 'Initial Creation', 'timestamp' => date('Y-m-d H:i:s') ]);
            $this->session->set_flashdata('success', 'Data berhasil ditambahkan');
        }
        redirect('operating_software');
    }

    public function update_status() {
        // Ambil data dari request AJAX
        $id     = $this->input->post('id');     
        $status = $this->input->post('status'); 
        $reason = $this->security->xss_clean($this->input->post('reason')); // Tangkap alasan

        if (!empty($id)) {
            // Ambil data lama untuk log audit
            $old_data = $this->Operating_Software_model->get_by_id($id);
            $username = $this->session->userdata('username');

            // Validasi jika data tidak ditemukan
            if (!$old_data) {
                $msg = ($status == 0) ? "Gagal menonaktifkan" : "Gagal mengaktifkan kembali";
                echo json_encode(['success' => false, 'message' => $msg . ': Data tidak ditemukan']);
                return;
            }

            $nama_data = $old_data['operating_software_name'];

            // Update status di tbl_operating_software
            // Pastikan Anda membuat method update_os_status di model nantinya
            $update = $this->Operating_Software_model->update_os_status($id, $status);

            if ($update) {
                // Simpan ke Audit Trail
                $this->Audit_model->insert_log([
                    'username'   => $username,
                    'action'     => ($status == 0) ? 'DEACTIVATE' : 'ACTIVATE',
                    'table_name' => 'tbl_operating_software',
                    'foreign_id' => $id,
                    'field_name' => 'status',
                    'old_value'  => ($status == 0) ? '1' : '0',
                    'new_value'  => ($status == 0) ? '0' : '1',
                    'reason'     => !empty($reason) ? $reason : 'Toggle Status',
                    'timestamp'  => date('Y-m-d H:i:s')
                ]);

                // Response dinamis
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

        // 1. Ambil data utama (Anda menggunakan nama variabel $db_data di sini)
        $db_data = $this->Operating_Software_model->get_by_id($id);
        if (!$db_data) {
            $this->session->set_flashdata('error', 'Data tidak ditemukan.');
            redirect('operating_software');
        }

        // [XSS CLEAN] Membersihkan keyword pencarian di halaman audit
        $keyword = $this->security->xss_clean($this->input->get('keyword'));
        
        $table_name = 'tbl_operating_software';

        $config['base_url'] = base_url('operating_software/audit/' . $id);
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

        // 3. Susun data untuk dikirim ke view
        $data['keyword']     = $keyword;
        $data['menu_label']  = 'Operating Software';
        
        // PERBAIKAN: Gunakan $db_data (sesuai yang didefinisikan di atas)
        // Dan ambil kolom 'operating_software_name'
        $data['target_name'] = $db_data['operating_software_name']; 
        
        $data['back_url']    = 'operating_software';
        $data['export_url']  = base_url('audit/export_excel/tbl_operating_software/' . $id) . ($keyword ? '?keyword=' . urlencode($keyword) : '');
        $data['audit_data']  = $audit_logs;
        $data['pagination']  = $this->pagination->create_links();
        $data['total_rows']  = $config['total_rows'];

        $this->load->view('audit/audit_view', $data);
    }
}