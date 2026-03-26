<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Application_type extends CI_Controller {

    public function __construct() {
        parent::__construct();
        date_default_timezone_set('Asia/Jakarta');
        if (!$this->session->userdata('email')) {
             redirect('auth');
        }
        $this->load->model('Application_type_model');
        $this->load->model('audit/Audit_model');
    }

    public function index() {
        $this->load->library('pagination');
        $keyword = $this->security->xss_clean($this->input->get('keyword'));
        $filters = $this->input->get('filter');

        $config['base_url'] = base_url('application_type/index');
        
        $params = [];
        if($keyword) $params['keyword'] = $keyword;
        if($filters) $params['filter'] = $filters;
        $config['suffix'] = !empty($params) ? '?' . http_build_query($params, '', '&') : '';
        $config['first_url'] = $config['base_url'] . $config['suffix'];

        $config['total_rows'] = $this->Application_type_model->count_all_app_types($keyword, $filters);
        $config['per_page'] = 10; 
        
        $config['enable_query_strings'] = TRUE;
        $config['page_query_string'] = TRUE;
        $config['query_string_segment'] = 'per_page';
        $config['reuse_query_string'] = TRUE;

        // Pagination Style (Sama dengan Database)
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
        
        $data['app_types'] = $this->Application_type_model->get_app_types_paginated($config['per_page'], $start, $keyword, $filters);
        $data['opt_app_type_name'] = $this->Application_type_model->get_dynamic_options('app_type_name', $filters);

        $data['keyword'] = $keyword;
        $data['selected_filters'] = $filters;
        $data['pagination'] = $this->pagination->create_links();
        $data['total_rows'] = $config['total_rows'];

        $this->load->view('application_type_view', $data);
    }

    public function export() {
        $this->Audit_model->insert_log([ 'username' => $this->session->userdata('username'), 'action' => 'EXPORT', 'table_name' => 'tbl_app_type', 'foreign_id' => 0, 'field_name' => '-', 'old_value' => '-', 'new_value' => '-', 'reason' => 'Export Data', 'timestamp' => date('Y-m-d H:i:s') ]);
        
        $keyword = $this->security->xss_clean($this->input->get('keyword'));
        $filters = $this->input->get('filter');

        $data['app_types'] = $this->Application_type_model->get_all_app_types($keyword, $filters);

        header("Content-type: application/vnd-ms-excel");
        header("Content-Disposition: attachment; filename=Application_Type_".date('Y-m-d').".xls");
        header("Pragma: no-cache");
        header("Expires: 0");

        $this->load->view('application_type_export', $data);
    }


    public function save() {
        $id = $this->security->xss_clean($this->input->post('app_type_id'));
        $name = trim($this->security->xss_clean($this->input->post('app_type_name')));
        $reason = $this->security->xss_clean($this->input->post('reason'));
        $username = $this->session->userdata('username');
        $userId = $this->session->userdata('user_id');

        if ($this->Application_type_model->check_duplicate_app_type($name, $id)) {
            $this->session->set_flashdata('error', 'Nama Application Type "'. $name .'" sudah ada!');
            redirect('application_type'); return; 
        }
        
        if ($id) {
            if(empty($reason)){
                $this->session->set_flashdata('error', 'Gagal update: Alasan wajib diisi!');
                redirect('application_type'); return;
            }
            $oldData = $this->db->get_where('tbl_app_type', ['app_type_id' => $id])->row_array();
            $oldName = trim($oldData['app_type_name']);
            if ($oldName == $name) {
                $this->session->set_flashdata('error', 'Gagal simpan: Tidak ada perubahan data.');
                redirect('application_type'); return;
            }
            $update_data = [ 'app_type_name' => $name, 'modified_by' => $userId, 'modified_at' => date("Y-m-d H:i:s") ];
            $this->Application_type_model->update_app_type($id, $update_data);
            $this->Audit_model->insert_log([ 'username' => $username, 'action' => 'EDIT', 'table_name' => 'tbl_app_type', 'foreign_id' => $id, 'field_name' => 'app_type_name', 'old_value' => $oldName, 'new_value' => $name, 'reason' => $reason, 'timestamp' => date('Y-m-d H:i:s') ]);
            $this->session->set_flashdata('success', 'Data berhasil diperbarui');
        } else {
            $insert_data = [ 'app_type_name' => $name, 'status' => 1, 'created_by' => $userId, 'created_at' => date("Y-m-d H:i:s") ];
            $this->Application_type_model->insert_app_type($insert_data);
            $new_id = $this->db->insert_id();
            $this->Audit_model->insert_log([ 'username' => $username, 'action' => 'ADD', 'table_name' => 'tbl_app_type', 'foreign_id' => $new_id, 'field_name' => 'app_type_name', 'old_value' => '-', 'new_value' => $name, 'reason' => 'Initial Creation', 'timestamp' => date('Y-m-d H:i:s') ]);
            $this->session->set_flashdata('success', 'Data berhasil ditambahkan');
        }
        redirect('application_type');
    }

    public function update_status() {
        $id     = $this->input->post('id');     
        $status = $this->input->post('status'); 
        $reason = $this->security->xss_clean($this->input->post('reason'));

        if (!empty($id)) {
            $old_data = $this->Application_type_model->get_by_id($id);
            $username = $this->session->userdata('username');

            if (!$old_data) {
                echo json_encode(['success' => false, 'message' => 'Data tidak ditemukan']);
                return;
            }

            $nama_data = $old_data['app_type_name'];
            $update = $this->Application_type_model->update_status($id, $status);

            if ($update) {
                $this->Audit_model->insert_log([
                    'username'   => $username,
                    'action'     => ($status == 0) ? 'DEACTIVATE' : 'ACTIVATE',
                    'table_name' => 'tbl_app_type',
                    'foreign_id' => $id,
                    'field_name' => 'status',
                    'old_value'  => ($status == 0) ? '1' : '0',
                    'new_value'  => ($status == 0) ? '0' : '1',
                    'reason'     => !empty($reason) ? $reason : 'Toggle Status',
                    'timestamp'  => date('Y-m-d H:i:s')
                ]);

                $response_msg = "Data '" . $nama_data . "' berhasil " . (($status == 0) ? "dinonaktifkan" : "diaktifkan kembali");
                echo json_encode(['success' => true, 'message' => $response_msg]);
            } else {
                echo json_encode(['success' => false, 'message' => 'Gagal memperbarui status']);
            }
        } else {
            echo json_encode(['success' => false, 'message' => 'ID tidak ditemukan']);
        }
    }

    public function audit($id) {
        $this->load->library('pagination');
        $app_data = $this->Application_type_model->get_by_id($id);
        if (!$app_data) {
            $this->session->set_flashdata('error', 'Data tidak ditemukan.');
            redirect('application_type');
        }

        $keyword = $this->security->xss_clean($this->input->get('keyword'));
        $table_name = 'tbl_app_type'; 

        $config['base_url'] = base_url('application_type/audit/' . $id);
        $config['total_rows'] = count($this->Audit_model->get_audit_logs($id, $keyword, $table_name));
        $config['per_page'] = 5; 
        $config['page_query_string'] = TRUE;
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

        $data['target_name'] = $app_data['app_type_name'];
        $data['keyword']     = $keyword;
        $data['back_url']    = 'application_type';
        $data['menu_label']  = 'Application Type';
        $data['export_url']  = base_url('audit/export_excel/tbl_app_type/' . $id);
        $data['audit_data']  = $audit_logs;
        $data['pagination']  = $this->pagination->create_links();
        $data['total_rows']  = $config['total_rows'];

        $this->load->view('audit/audit_view', $data);
    }
}