<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Deployment_site extends CI_Controller {

    public function __construct() {
        parent::__construct();
        date_default_timezone_set('Asia/Jakarta');
        if (!$this->session->userdata('email')) {
             redirect('auth');
        }
        $this->load->model('Deployment_site_model');
        $this->load->model('audit/Audit_model');
    }

    public function index() {
        $this->load->library('pagination');
        $keyword = $this->security->xss_clean($this->input->get('keyword'));
        $filters = $this->input->get('filter');

        $config['base_url'] = base_url('deployment_site/index');
        $params = [];
        if($keyword) $params['keyword'] = $keyword;
        if($filters) $params['filter'] = $filters;
        $config['suffix'] = !empty($params) ? '?' . http_build_query($params, '', '&') : '';
        $config['first_url'] = $config['base_url'] . $config['suffix'];

        $config['total_rows'] = $this->Deployment_site_model->count_all_sites($keyword, $filters);
        $config['per_page'] = 10; 
        $config['page_query_string'] = TRUE;
        $config['query_string_segment'] = 'per_page';
        $config['reuse_query_string'] = TRUE;

        // Style pagination identik dengan database
        $config['full_tag_open']    = '<ul class="pagination pagination-sm m-0 float-right">';
        $config['full_tag_close']   = '</ul>';
        $config['cur_tag_open']     = '<li class="page-item active"><a class="page-link" href="#">';
        $config['cur_tag_close']    = '</a></li>';
        $config['num_tag_open']     = '<li class="page-item">';
        $config['num_tag_close']    = '</li>';
        $config['attributes']       = array('class' => 'page-link');

        $this->pagination->initialize($config);
        $start = $this->input->get('per_page');
        
        $data['deployment_sites'] = $this->Deployment_site_model->get_sites_paginated($config['per_page'], $start, $keyword, $filters);
        $data['opt_deployment_site_name'] = $this->Deployment_site_model->get_dynamic_options('deployment_site_name', $filters);
        $data['keyword'] = $keyword;
        $data['selected_filters'] = $filters;
        $data['pagination'] = $this->pagination->create_links();
        $data['total_rows'] = $config['total_rows'];

        $this->load->view('deployment_site_view', $data);
    }

    public function export() {
        $this->Audit_model->insert_log([ 
            'username' => $this->session->userdata('username'), 
            'action' => 'EXPORT', 
            'table_name' => 'tbl_apps_deployment_site', 
            'foreign_id' => 0, 
            'field_name' => '-', 
            'old_value' => '-', 
            'new_value' => '-', 
            'reason' => 'Export Data', 
            'timestamp' => date('Y-m-d H:i:s') 
        ]);

        $keyword = $this->security->xss_clean($this->input->get('keyword'));
        $filters = $this->input->get('filter');
        $data['deployment_sites'] = $this->Deployment_site_model->get_all_sites($keyword, $filters);

        header("Content-type: application/vnd-ms-excel");
        header("Content-Disposition: attachment; filename=Deployment_Site_".date("Y-m-d").".xls");
        header("Pragma: no-cache");
        header("Expires: 0");

        $this->load->view('deployment_site_export', $data); // Buat view export sederhana jika diperlukan
    }

    public function save() {
        $id = $this->security->xss_clean($this->input->post('deployment_site_id'));
        $name = trim($this->security->xss_clean($this->input->post('deployment_site_name')));
        $reason = $this->security->xss_clean($this->input->post('reason'));
        $username = $this->session->userdata('username');
        $userId = $this->session->userdata('user_id');

        if ($this->Deployment_site_model->check_duplicate_site($name, $id)) {
            $this->session->set_flashdata('error', 'Nama Site "'. $name .'" sudah ada! Gagal menyimpan.');
            redirect('deployment_site'); return; 
        }
        
        if ($id) {
            if(empty($reason)){
                $this->session->set_flashdata('error', 'Gagal update: Alasan wajib diisi!');
                redirect('deployment_site'); return;
            }

            // DISESUAIKAN: Menggunakan DB get_where langsung seperti di Controller Database
            $oldData = $this->db->get_where('tbl_apps_deployment_site', ['deployment_site_id' => $id])->row_array();
            $oldName = trim($oldData['deployment_site_name']);

            if ($oldName == $name) {
                $this->session->set_flashdata('error', 'Gagal simpan: Tidak ada perubahan data.');
                redirect('deployment_site'); return;
            }

            // DISESUAIKAN: Inisialisasi array data sebelum update
            $update_data = [ 
                'deployment_site_name' => $name, 
                'modified_by' => $userId, 
                'modified_at' => date("Y-m-d H:i:s") 
            ];
            
            $this->Deployment_site_model->update_site($id, $update_data);

            $this->Audit_model->insert_log([ 
                'username' => $username, 
                'action' => 'EDIT', 
                'table_name' => 'tbl_apps_deployment_site', 
                'foreign_id' => $id, 
                'field_name' => 'deployment_site_name', 
                'old_value' => $oldName, 
                'new_value' => $name, 
                'reason' => $reason, 
                'timestamp' => date('Y-m-d H:i:s') 
            ]);

            $this->session->set_flashdata('success', 'Data berhasil diperbarui');
        } else {
            // DISESUAIKAN: Inisialisasi array data sebelum insert
            $insert_data = [ 
                'deployment_site_name' => $name, 
                'created_by' => $userId, 
                'created_at' => date("Y-m-d H:i:s") 
            ];

            $this->Deployment_site_model->insert_site($insert_data);
            $new_id = $this->db->insert_id();

            $this->Audit_model->insert_log([ 
                'username' => $username, 
                'action' => 'ADD', 
                'table_name' => 'tbl_apps_deployment_site', 
                'foreign_id' => $new_id, 
                'field_name' => 'deployment_site_name', 
                'old_value' => '-', 
                'new_value' => $name, 
                'reason' => 'Initial Creation', 
                'timestamp' => date('Y-m-d H:i:s') 
            ]);

            $this->session->set_flashdata('success', 'Data berhasil ditambahkan');
        }
        redirect('deployment_site');
    }

    public function update_status() {
        $id = $this->input->post('id');     
        $status = $this->input->post('status'); 
        $reason = $this->security->xss_clean($this->input->post('reason'));

        if (!empty($id)) {
            $old_data = $this->Deployment_site_model->get_by_id($id);
            if (!$old_data) {
                echo json_encode(['success' => false, 'message' => 'Data tidak ditemukan']); return;
            }
            $update = $this->Deployment_site_model->update_status($id, $status);
            if ($update) {
                $this->Audit_model->insert_log([
                    'username' => $this->session->userdata('username'),
                    'action' => ($status == 0) ? 'DEACTIVATE' : 'ACTIVATE',
                    'table_name' => 'tbl_apps_deployment_site',
                    'foreign_id' => $id, 'field_name' => 'status',
                    'old_value' => ($status == 0) ? '1' : '0',
                    'new_value' => ($status == 0) ? '0' : '1',
                    'reason' => !empty($reason) ? $reason : 'Toggle Status',
                    'timestamp' => date('Y-m-d H:i:s')
                ]);
                $msg = "Data '" . $old_data['deployment_site_name'] . "' berhasil " . ($status == 0 ? "dinonaktifkan" : "diaktifkan kembali");
                echo json_encode(['success' => true, 'message' => $msg]);
            } else {
                echo json_encode(['success' => false, 'message' => 'Gagal update status']);
            }
        }
    }

    public function audit($id) {
        $this->load->library('pagination');

        $site_data = $this->Deployment_site_model->get_by_id($id);
        if (!$site_data) {
            $this->session->set_flashdata('error', 'Data tidak ditemukan.');
            redirect('deployment_site'); return;
        }

        // [XSS CLEAN] Membersihkan keyword pencarian di halaman audit
        $keyword = $this->security->xss_clean($this->input->get('keyword'));
        
        $table_name = 'tbl_apps_deployment_site'; 

        $config['base_url'] = base_url('deployment_site/audit/' . $id);
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

        $data['target_name'] = $site_data['deployment_site_name'];
        $data['keyword']     = $keyword;
        $data['back_url']    = 'deployment_site';
        $data['menu_label']  = 'Deployment Site';
        $data['export_url']  = base_url('audit/export_excel/tbl_apps_deployment_site/' . $id) . ($keyword ? '?keyword=' . urlencode($keyword) : '');
        $data['audit_data']  = $audit_logs;
        $data['pagination']  = $this->pagination->create_links();
        $data['total_rows']  = $config['total_rows'];

        $this->load->view('audit/audit_view', $data);
    }
}