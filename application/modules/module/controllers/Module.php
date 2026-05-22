<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Module extends CI_Controller {

    public function __construct() {
        parent::__construct();
        date_default_timezone_set('Asia/Jakarta');
        if (!$this->session->userdata('email')) {
             redirect('auth');
        }
        $this->load->model('Module_model');
        $this->load->model('audit/Audit_model');
    }

    public function index() {
        $this->load->library('pagination');
        $keyword = $this->security->xss_clean($this->input->get('keyword'));
        $filters = $this->input->get('filter'); // Ambil filter

        $config['base_url'] = base_url('module/index');
        
        // Setup Query String
        $params = [];
        if($keyword) $params['keyword'] = $keyword;
        if($filters) $params['filter'] = $filters;
        $config['suffix'] = !empty($params) ? '?' . http_build_query($params, '', '&') : '';
        $config['first_url'] = $config['base_url'] . $config['suffix'];

        $config['total_rows'] = $this->Module_model->count_all_modules($keyword, $filters);
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
        $data['modules'] = $this->Module_model->get_modules_paginated($config['per_page'], $start, $keyword, $filters);
        
        // Load Options
        $data['opt_module_name'] = $this->Module_model->get_dynamic_options('module_name', $filters);

        $data['keyword'] = $keyword;
        $data['selected_filters'] = $filters;
        $data['pagination'] = $this->pagination->create_links();
        $data['total_rows'] = $config['total_rows'];

        $this->load->view('module_view', $data);
    }
    
    public function export() {
        $this->Audit_model->insert_log([ 'username' => $this->session->userdata('username'), 'action' => 'EXPORT', 'table_name' => 'tbl_infra_module', 'foreign_id' => 0, 'field_name' => '-', 'old_value' => '-', 'new_value' => '-', 'reason' => 'Export Data', 'timestamp' => date('Y-m-d H:i:s') ]);
        
        $keyword = $this->security->xss_clean($this->input->get('keyword'));
        $filters = $this->input->get('filter');

        $data['modules'] = $this->Module_model->get_all_modules($keyword, $filters);

        header("Content-type: application/vnd-ms-excel");
        header("Content-Disposition: attachment; filename=Module_".date('Y-m-d').".xls");
        header("Pragma: no-cache");
        header("Expires: 0");

        $this->load->view('module_export', $data);
    }

    public function save() {
        $id = $this->security->xss_clean($this->input->post('module_id'));
        $name = trim($this->security->xss_clean($this->input->post('module_name')));
        $reason = $this->security->xss_clean($this->input->post('reason'));
        $username = $this->session->userdata('username');
        $userId = $this->session->userdata('user_id');

        if ($this->Module_model->check_duplicate_module($name, $id)) {
            $this->session->set_flashdata('error', 'Nama Module "'. $name .'" sudah ada! Gagal menyimpan.');
            redirect('module'); return; 
        }
        
        if ($id) {
            if(empty($reason)){
                $this->session->set_flashdata('error', 'Gagal update: Alasan wajib diisi!');
                redirect('module'); return;
            }
            $oldData = $this->db->get_where('tbl_infra_module', ['module_id' => $id])->row_array();
            $oldName = trim($oldData['module_name']);
            if ($oldName == $name) {
                $this->session->set_flashdata('error', 'Gagal simpan: Tidak ada perubahan data.');
                redirect('module'); return;
            }
            $update_data = [ 'module_name' => $name, 'modified_by' => $userId, 'modified_at' => date("Y-m-d H:i:s") ];
            $this->Module_model->update_module($id, $update_data);
            $this->Audit_model->insert_log([ 'username' => $username, 'action' => 'EDIT', 'table_name' => 'tbl_infra_module', 'foreign_id' => $id, 'field_name' => 'module_name', 'old_value' => $oldName, 'new_value' => $name, 'reason' => $reason, 'timestamp' => date('Y-m-d H:i:s') ]);
            $this->session->set_flashdata('success', 'Data berhasil diperbarui');
        } else {
            $insert_data = [ 'module_name' => $name, 'created_by' => $userId, 'created_at' => date("Y-m-d H:i:s") ];
            $this->Module_model->insert_module($insert_data);
            $new_id = $this->db->insert_id();
            $this->Audit_model->insert_log([ 'username' => $username, 'action' => 'ADD', 'table_name' => 'tbl_infra_module', 'foreign_id' => $new_id, 'field_name' => 'module_name', 'old_value' => '-', 'new_value' => $name, 'reason' => 'Initial Creation', 'timestamp' => date('Y-m-d H:i:s') ]);
            $this->session->set_flashdata('success', 'Data berhasil ditambahkan');
        }
        redirect('module');
    }
    public function update_status() {
        // Ambil data dari request AJAX
        $id     = $this->input->post('id');     
        $status = $this->input->post('status'); 
        $reason = $this->security->xss_clean($this->input->post('reason')); // Tangkap alasan

        if (!empty($id)) {
            // Ambil data lama untuk log audit
            $old_data = $this->Module_model->get_by_id($id);
            $username = $this->session->userdata('username');

            // Validasi jika data tidak ditemukan di database
            if (!$old_data) {
                $msg = ($status == 0) ? "Gagal menonaktifkan" : "Gagal mengaktifkan kembali";
                echo json_encode(['success' => false, 'message' => $msg . ': Data tidak ditemukan']);
                return;
            }

            // Ambil nama data untuk pesan notifikasi
            $nama_data = $old_data['module_name'];

            // Update status di tbl_infra_module
            $update = $this->Module_model->update_module_status($id, $status);

            if ($update) {
                // Simpan ke Audit Trail
                $this->Audit_model->insert_log([
                    'username'   => $username,
                    'action'     => ($status == 0) ? 'DEACTIVATE' : 'ACTIVATE',
                    'table_name' => 'tbl_infra_module',
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
        $module_data = $this->Module_model->get_by_id($id);
        if (!$module_data) {
            $this->session->set_flashdata('error', 'Data tidak ditemukan.');
            redirect('module');
        }

        $keyword = $this->security->xss_clean($this->input->get('keyword'));
        $table_name = 'tbl_infra_module'; 

        $config['base_url'] = base_url('module/audit/' . $id);
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

        $data['target_name'] = $module_data['module_name'];
        $data['keyword']     = $keyword;
        $data['back_url']    = 'module';
        $data['menu_label']  = 'Module';
        $data['export_url']  = base_url('audit/export_excel/tbl_infra_module/' . $id);
        $data['audit_data']  = $audit_logs;
        $data['pagination']  = $this->pagination->create_links();
        $data['total_rows']  = $config['total_rows'];

        $this->load->view('audit/audit_view', $data);
    }
}