<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Service extends CI_Controller {

    public function __construct() {
        parent::__construct();
        date_default_timezone_set('Asia/Jakarta');
        if (!$this->session->userdata('email')) { redirect('auth'); }
        $this->load->model('Service_model');
        $this->load->model('audit/Audit_model');
    }

    public function index() {
        $this->load->library('pagination');
        $keyword = $this->security->xss_clean($this->input->get('keyword'));
        $filters = $this->input->get('filter');

        $config['base_url'] = base_url('service/index');
        $params = [];
        if($keyword) $params['keyword'] = $keyword;
        if($filters) $params['filter'] = $filters;
        $config['suffix'] = !empty($params) ? '?' . http_build_query($params, '', '&') : '';
        $config['first_url'] = $config['base_url'] . $config['suffix'];

        $config['total_rows'] = $this->Service_model->count_all_services($keyword, $filters);
        $config['per_page'] = 10;
        $config['page_query_string'] = TRUE;
        $config['query_string_segment'] = 'per_page';

        $config['full_tag_open'] = '<ul class="pagination pagination-sm m-0 float-right">';
        $config['full_tag_close'] = '</ul>';
        $config['cur_tag_open'] = '<li class="page-item active"><a class="page-link" href="#">';
        $config['cur_tag_close'] = '</a></li>';
        $config['attributes'] = array('class' => 'page-link');

        $this->pagination->initialize($config);
        $start = $this->input->get('per_page');

        $data['services'] = $this->Service_model->get_services_paginated($config['per_page'], $start, $keyword, $filters);
        $data['opt_module'] = $this->Service_model->get_dynamic_options('module_name', $filters);
        $data['opt_service'] = $this->Service_model->get_dynamic_options('service_name', $filters);
        $data['modules'] = $this->Service_model->get_module_options(); 
        
        $data['keyword'] = $keyword;
        $data['selected_filters'] = $filters;
        $data['pagination'] = $this->pagination->create_links();
        $data['total_rows'] = $config['total_rows'];

        $this->load->view('service_view', $data);
    }

    public function save() {
        $id = $this->security->xss_clean($this->input->post('service_id'));
        $module_id = $this->security->xss_clean($this->input->post('module_id'));
        $name = trim($this->security->xss_clean($this->input->post('service_name')));
        $reason = $this->security->xss_clean($this->input->post('reason'));
        $username = $this->session->userdata('username');
        $userId = $this->session->userdata('user_id');

        // 1. Validasi Duplikat Global
        // Menggunakan fungsi global (pastikan di Service_model pengecekan hanya berdasarkan service_name)
        if ($this->Service_model->check_duplicate_service_global($name, $id)) {
            $this->session->set_flashdata('error', 'Gagal Simpan: Nama Service "'. $name .'" sudah terdaftar di sistem!');
            redirect('service'); return; 
        }
        
        if ($id) {
            // --- PROSES EDIT ---
            
            // 2. Validasi Alasan (Wajib diisi saat edit)
            if(empty($reason)){
                $this->session->set_flashdata('error', 'Gagal update: Alasan wajib diisi!');
                redirect('service'); return;
            }

            $oldData = $this->Service_model->get_by_id($id);
            
            // 3. Validasi Perubahan Data
            if ($oldData['service_name'] == $name && $oldData['module_id'] == $module_id) {
                $this->session->set_flashdata('error', 'Gagal simpan: Tidak ada perubahan data.');
                redirect('service'); return;
            }

            $update_data = [ 
                'module_id' => $module_id,
                'service_name' => $name, 
                'modified_by' => $userId, 
                'modified_at' => date("Y-m-d H:i:s") 
            ];

            if ($this->Service_model->update_service($id, $update_data)) {
                // Audit Log untuk perubahan nama
                if($oldData['service_name'] != $name) {
                    $this->Audit_model->insert_log([
                        'username' => $username,
                        'action' => 'EDIT',
                        'table_name' => 'tbl_service',
                        'foreign_id' => $id,
                        'field_name' => 'service_name',
                        'old_value' => $oldData['service_name'],
                        'new_value' => $name,
                        'reason' => $reason,
                        'timestamp' => date('Y-m-d H:i:s')
                    ]);
                }
                // Audit Log untuk perubahan module
                if($oldData['module_id'] != $module_id) {
                    $this->Audit_model->insert_log([
                        'username' => $username,
                        'action' => 'EDIT',
                        'table_name' => 'tbl_service',
                        'foreign_id' => $id,
                        'field_name' => 'module_id',
                        'old_value' => $oldData['module_id'],
                        'new_value' => $module_id,
                        'reason' => $reason,
                        'timestamp' => date('Y-m-d H:i:s')
                    ]);
                }
                $this->session->set_flashdata('success', 'Data berhasil diperbarui');
            }
        } else {
            // --- PROSES ADD ---
            $insert_data = [ 
                'module_id' => $module_id,
                'service_name' => $name, 
                'status' => 1,
                'created_by' => $userId, 
                'created_at' => date("Y-m-d H:i:s") 
            ];

            $new_id = $this->Service_model->insert_service($insert_data);
            if ($new_id) {
                $this->Audit_model->insert_log([
                    'username' => $username,
                    'action' => 'ADD',
                    'table_name' => 'tbl_service',
                    'foreign_id' => $new_id,
                    'field_name' => 'service_name',
                    'old_value' => '-',
                    'new_value' => $name,
                    'reason' => 'Initial Creation',
                    'timestamp' => date('Y-m-d H:i:s')
                ]);
                $this->session->set_flashdata('success', 'Data berhasil ditambahkan');
            }
        }
        redirect('service');
    }

    public function update_status() {
        $id = $this->input->post('id');
        $status = $this->input->post('status');
        $reason = $this->security->xss_clean($this->input->post('reason'));
        
        $old = $this->Service_model->get_by_id($id);
        if ($old) {
            $this->Service_model->update_service($id, ['status' => $status]);
            $this->Audit_model->insert_log([
                'username' => $this->session->userdata('username'),
                'action' => ($status == 0) ? 'DEACTIVATE' : 'ACTIVATE',
                'table_name' => 'tbl_service', // Changed to tbl_service
                'foreign_id' => $id,
                'field_name' => 'status', 'old_value' => ($status == 0 ? '1':'0'), 'new_value' => ($status == 0 ? '0':'1'),
                'reason' => $reason, 'timestamp' => date('Y-m-d H:i:s')
            ]);
            echo json_encode(['success' => true]);
        }
    }
}