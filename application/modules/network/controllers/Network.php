<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Network extends CI_Controller {

    public function __construct() {
        parent::__construct();
        date_default_timezone_set('Asia/Jakarta');
        if (!$this->session->userdata('email')) {
             redirect('auth');
        }
        $this->load->model('Network_model');
        $this->load->model('audit/Audit_model');
    }

    public function index() {
        $this->load->library('pagination');
        $keyword = $this->security->xss_clean($this->input->get('keyword'));
        $filters = $this->input->get('filter'); // Ambil filter

        $config['base_url'] = base_url('network/index');
        
        $params = [];
        if($keyword) $params['keyword'] = $keyword;
        if($filters) $params['filter'] = $filters;
        $config['suffix'] = !empty($params) ? '?' . http_build_query($params, '', '&') : '';
        $config['first_url'] = $config['base_url'] . $config['suffix'];

        $config['total_rows'] = $this->Network_model->count_all_networks($keyword, $filters);
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
        
        // Ambil Data Filtered
        $data['networks'] = $this->Network_model->get_networks_paginated($config['per_page'], $start, $keyword, $filters);
        
        // Load Options
        $data['opt_network_name'] = $this->Network_model->get_dynamic_options('network_name', $filters);

        $data['keyword'] = $keyword;
        $data['selected_filters'] = $filters;
        $data['pagination'] = $this->pagination->create_links();
        $data['total_rows'] = $config['total_rows'];

        $this->load->view('network_view', $data);
    }

    public function export() {
        $this->Audit_model->insert_log([ 'username' => $this->session->userdata('username'), 'action' => 'EXPORT', 'table_name' => 'tbl_apps_network', 'foreign_id' => 0, 'field_name' => '-', 'old_value' => '-', 'new_value' => '-', 'reason' => 'Export Data', 'timestamp' => date('Y-m-d H:i:s') ]);
        
        $keyword = $this->security->xss_clean($this->input->get('keyword'));
        $filters = $this->input->get('filter');

        $data['networks'] = $this->Network_model->get_all_networks($keyword, $filters);

        header("Content-type: application/vnd-ms-excel");
        header("Content-Disposition: attachment; filename=Network_".date('Y-m-d').".xls");
        header("Pragma: no-cache");
        header("Expires: 0");

        $this->load->view('network_export', $data);
    }

    public function save() {
        $id = $this->security->xss_clean($this->input->post('network_id'));
        $name = trim($this->security->xss_clean($this->input->post('network_name')));
        $reason = $this->security->xss_clean($this->input->post('reason'));
        $username = $this->session->userdata('username');
        $createdBy = $this->session->userdata('user_id');

        if ($this->Network_model->check_duplicate_network($name, $id)) {
            $this->session->set_flashdata('error', 'Nama Network "'. $name .'" sudah ada! Gagal menyimpan.');
            redirect('network'); return; 
        }
        
        if ($id) {
            if(empty($reason)){
                $this->session->set_flashdata('error', 'Gagal update: Alasan wajib diisi!');
                redirect('network'); return;
            }
            $oldData = $this->db->get_where('tbl_apps_network', ['network_id' => $id])->row_array();
            $oldName = trim($oldData['network_name']);
            if ($oldName == $name) {
                $this->session->set_flashdata('error', 'Gagal simpan: Tidak ada perubahan data.');
                redirect('network'); return;
            }
            $update_data = [ 'network_name' => $name, 'modified_by' => $createdBy, 'modified_at' => date("Y-m-d H:i:s") ];
            $this->Network_model->update_network($id, $update_data);
            $this->Audit_model->insert_log([ 'username' => $username, 'action' => 'EDIT', 'table_name' => 'tbl_apps_network', 'foreign_id' => $id, 'field_name' => 'network_name', 'old_value' => $oldName, 'new_value' => $name, 'reason' => $reason, 'timestamp' => date('Y-m-d H:i:s') ]);
            $this->session->set_flashdata('success', 'Data Network berhasil diperbarui');
        } else {
            $insert_data = [ 'network_name' => $name, 'created_by' => $createdBy, 'created_at' => date("Y-m-d H:i:s") ];
            $this->Network_model->insert_network($insert_data);
            $new_id = $this->db->insert_id();
            $this->Audit_model->insert_log([ 'username' => $username, 'action' => 'ADD', 'table_name' => 'tbl_apps_network', 'foreign_id' => $new_id, 'field_name' => 'network_name', 'old_value' => '-', 'new_value' => $name, 'reason' => 'Initial Creation', 'timestamp' => date('Y-m-d H:i:s') ]);
            $this->session->set_flashdata('success', 'Data Network berhasil ditambah');
        }
        redirect('network');
    }

    public function update_status() {
        // Ambil data dari request AJAX
        $id     = $this->input->post('id');     
        $status = $this->input->post('status'); 
        $reason = $this->security->xss_clean($this->input->post('reason')); // Tangkap alasan

        if (!empty($id)) {
            // Ambil data lama untuk log audit
            $old_data = $this->Network_model->get_by_id($id);
            $username = $this->session->userdata('username');

            // Validasi jika data tidak ditemukan di database
            if (!$old_data) {
                $msg = ($status == 0) ? "Gagal menonaktifkan" : "Gagal mengaktifkan kembali";
                echo json_encode(['success' => false, 'message' => $msg . ': Data tidak ditemukan']);
                return;
            }

            // Ambil nama data untuk pesan notifikasi (sesuaikan field 'database_name' dengan nama kolom di tabel Anda)
            $nama_data = $old_data['network_name'];

            // Update status di tbl_database_master
            $update = $this->Network_model->update_network_status($id, $status);

            if ($update) {
                // Simpan ke Audit Trail
                $this->Audit_model->insert_log([
                    'username'   => $username,
                    'action'     => ($status == 0) ? 'DEACTIVATE' : 'ACTIVATE',
                    'table_name' => 'tbl_apps_network',
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
        // 1. Load Library & Model Audit
        $this->load->library('pagination');
        $this->load->model('audit/Audit_model'); 

        // 1. Ambil data utama network
        $db_data = $this->Network_model->get_by_id($id);
        
        if (!$db_data) {
            $this->session->set_flashdata('error', 'Data tidak ditemukan.');
            redirect('network');
        }

        $keyword = $this->input->get('keyword');
        $table_name = 'tbl_apps_network';

        // 2. Konfigurasi Pagination
        $config['base_url'] = base_url('network/audit/' . $id);
        $config['total_rows'] = count($this->Audit_model->get_audit_logs($id, $keyword, $table_name));
        $config['per_page'] = 5; 
        $config['page_query_string'] = TRUE;
        $config['query_string_segment'] = 'per_page';
        $config['reuse_query_string'] = TRUE;

        // --- STYLE PAGINATION ---
        $config['full_tag_open']    = '<ul class="pagination pagination-sm m-0 float-right">';
        $config['full_tag_close']   = '</ul>';
        $config['cur_tag_open']     = '<li class="page-item active"><a class="page-link" href="#">';
        $config['cur_tag_close']    = '</a></li>';
        $config['num_tag_open']     = '<li class="page-item">';
        $config['num_tag_close']    = '</li>';
        $config['attributes']       = array('class' => 'page-link');

        $this->pagination->initialize($config);

        // 3. Ambil data yang sudah dilimit (Paginated)
        $start = $this->input->get('per_page');
        $audit_logs = $this->Audit_model->get_audit_logs_paginated($id, $table_name, $config['per_page'], $start, $keyword);

        // 3. Susun data untuk dikirim ke view
        $data['keyword']     = $keyword;
        $data['menu_label']  = 'Network';
        $data['target_name'] = $db_data['network_name'];
        $data['back_url']    = 'network';
        $data['export_url']  = base_url('audit/export_excel/tbl_apps_network/' . $id) . ($keyword ? '?keyword=' . urlencode($keyword) : '');
        $data['audit_data']  = $audit_logs;
        $data['pagination']  = $this->pagination->create_links();
        
        // 4. Hitung total baris untuk informasi di header view
        $data['total_rows']  = $config['total_rows']; 

        // 5. Load view audit
        $this->load->view('audit/audit_view', $data);
    }
}