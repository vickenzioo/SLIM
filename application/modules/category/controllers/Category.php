<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Category extends CI_Controller {

    public function __construct() {
        parent::__construct();
        date_default_timezone_set('Asia/Jakarta');
        if (!$this->session->userdata('email')) {
             redirect('auth');
        }
        $this->load->model('Category_model');
        $this->load->model('audit/Audit_model');
    }

    public function index() {
        $this->load->library('pagination');
        $keyword = $this->security->xss_clean($this->input->get('keyword'));
        $filters = $this->input->get('filter'); // Ambil filter

        $config['base_url'] = base_url('category/index');
        
        // Setup Query String
        $params = [];
        if($keyword) $params['keyword'] = $keyword;
        if($filters) $params['filter'] = $filters;
        $config['suffix'] = !empty($params) ? '?' . http_build_query($params, '', '&') : '';
        $config['first_url'] = $config['base_url'] . $config['suffix'];

        $config['total_rows'] = $this->Category_model->count_all_categorys($keyword, $filters);
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
        
        // Ambil Data Filtered
        $data['categorys'] = $this->Category_model->get_categorys_paginated($config['per_page'], $start, $keyword, $filters);
        
        // Load Options
        $data['opt_category_name']     = $this->Category_model->get_dynamic_options('category_name', $filters);
        $data['opt_standard_category'] = $this->Category_model->get_dynamic_options('standard_category', $filters);

        $data['keyword'] = $keyword;
        $data['selected_filters'] = $filters;
        $data['pagination'] = $this->pagination->create_links();
        $data['total_rows'] = $config['total_rows'];

        $this->load->view('category_view', $data);
    }

    public function export() {
        $this->Audit_model->insert_log([ 'username' => $this->session->userdata('username'), 'action' => 'EXPORT', 'table_name' => 'tbl_apps_category', 'foreign_id' => 0, 'field_name' => '-', 'old_value' => '-', 'new_value' => '-', 'reason' => 'Export Data', 'timestamp' => date('Y-m-d H:i:s') ]);
        
        $keyword = $this->security->xss_clean($this->input->get('keyword'));
        $filters = $this->input->get('filter');

        $data['categorys'] = $this->Category_model->get_all_categorys($keyword, $filters);

        header("Content-type: application/vnd-ms-excel");
        header("Content-Disposition: attachment; filename=Category_".date('Y-m-d').".xls");
        header("Pragma: no-cache");
        header("Expires: 0");

        $this->load->view('category_export', $data);
    }

    public function save() {
        $id = $this->security->xss_clean($this->input->post('category_id'));
        $name = $this->security->xss_clean($this->input->post('category_name'));
        $category = $this->security->xss_clean($this->input->post('standard_category'));
        $reason = $this->security->xss_clean($this->input->post('reason'));
        $username = $this->session->userdata('username');
        $createdBy = $this->session->userdata('user_id');

        if ($this->Category_model->check_duplicate_category($name, $id)) {
            $this->session->set_flashdata('error', 'Nama Category "'. $name .'" sudah ada! Gagal menyimpan.');
            redirect('category'); return; 
        }

        if (!is_numeric($category)) {
            $this->session->set_flashdata('error', 'Standard Category harus berupa angka!');
            redirect('category'); return; 
        }

        if ($id) {
            if(empty($reason)){
                $this->session->set_flashdata('error', 'Gagal update: Alasan wajib diisi!');
                redirect('category'); return;
            }
            $oldData = $this->db->get_where('tbl_apps_category', ['category_id' => $id])->row_array();
            
            if (trim($oldData['category_name']) == $name && $oldData['standard_category'] == $category) {
                $this->session->set_flashdata('error', 'Gagal simpan: Tidak ada perubahan data.');
                redirect('category'); return; 
            }

            $data = [ 'category_name' => $name, 'standard_category' => $category, 'modified_by' => $createdBy, 'modified_at' => date("Y-m-d H:i:s") ];
            $this->Category_model->update_category($id, $data);

            $fields_to_track = [ 'category_name' => ['label' => 'Category Name', 'new' => $name], 'standard_category' => ['label' => 'Standard Category', 'new' => $category] ];
            foreach ($fields_to_track as $field => $info) {
                if ($oldData[$field] != $info['new']) {
                    $this->Audit_model->insert_log([ 'username' => $username, 'action' => 'EDIT', 'table_name' => 'tbl_apps_category', 'foreign_id' => $id, 'field_name' => $info['label'], 'old_value' => $oldData[$field], 'new_value' => $info['new'], 'reason' => $reason, 'timestamp' => date('Y-m-d H:i:s') ]);
                }
            }
            $this->session->set_flashdata('success', 'Data Category berhasil diperbarui');
        } else {
            $data = [ 'category_name' => $name, 'standard_category' => $category, 'created_by' => $createdBy, 'created_at' => date("Y-m-d H:i:s") ];
            $this->Category_model->insert_category($data);
            $new_id = $this->db->insert_id(); 
            
            $new_fields = [ 'Category Name' => $name, 'Standard Category' => $category ];
            foreach ($new_fields as $label => $value) {
                $this->Audit_model->insert_log([ 'username' => $username, 'action' => 'ADD', 'table_name' => 'tbl_apps_category', 'foreign_id' => $new_id, 'field_name' => $label, 'old_value' => '-', 'new_value' => $value, 'reason' => 'Initial Creation', 'timestamp' => date('Y-m-d H:i:s') ]);
            }
            $this->session->set_flashdata('success', 'Data Category berhasil ditambahkan');
        }
        redirect('category');
    }

    public function update_status() {
        // Ambil data dari request AJAX
        $id     = $this->input->post('id');     
        $status = $this->input->post('status'); 
        $reason = $this->security->xss_clean($this->input->post('reason')); // Tangkap alasan

        if (!empty($id)) {
            // Ambil data lama untuk log audit
            $old_data = $this->Category_model->get_by_id($id);
            $username = $this->session->userdata('username');

            // Validasi jika data tidak ditemukan di database
            if (!$old_data) {
                $msg = ($status == 0) ? "Gagal menonaktifkan" : "Gagal mengaktifkan kembali";
                echo json_encode(['success' => false, 'message' => $msg . ': Data tidak ditemukan']);
                return;
            }

            // Ambil nama data untuk pesan notifikasi (menggunakan field 'category_name' sesuai tabel Anda)
            $nama_data = $old_data['category_name'];

            // Update status di tbl_apps_category
            $update = $this->Category_model->update_status($id, $status);

            if ($update) {
                // Simpan ke Audit Trail
                $this->Audit_model->insert_log([
                    'username'   => $username,
                    'action'     => ($status == 0) ? 'DEACTIVATE' : 'ACTIVATE',
                    'table_name' => 'tbl_apps_category',
                    'foreign_id' => $id,
                    'field_name' => 'status',
                    'old_value'  => ($status == 0) ? '1' : '0',
                    'new_value'  => ($status == 0) ? '0' : '1',
                    'reason'     => !empty($reason) ? $reason : 'Toggle Status',
                    'timestamp'  => date('Y-m-d H:i:s')
                ]);

                // Response dinamis berdasarkan status
                if ($status == 0) {
                    $response_msg = "Category '" . $nama_data . "' berhasil di nonaktifkan";
                } else {
                    $response_msg = "Category '" . $nama_data . "' berhasil di aktifkan kembali";
                }

                echo json_encode(['success' => true, 'message' => $response_msg]);
            } else {
                $error_msg = ($status == 0) ? "Gagal menonaktifkan" : "Gagal mengaktifkan kembali";
                echo json_encode(['success' => false, 'message' => $error_msg . ' category ' . $nama_data]);
            }
        } else {
            echo json_encode(['success' => false, 'message' => 'ID tidak ditemukan']);
        }
    }

    public function audit($id) {
        $this->load->library('pagination');

        // 1. Ambil data utama category
        $category_data = $this->Category_model->get_by_id($id);
        if (!$category_data) {
            $this->session->set_flashdata('error', 'Data tidak ditemukan.');
            redirect('category');
        }

        // [XSS CLEAN] Membersihkan keyword pencarian di halaman audit
        $keyword = $this->security->xss_clean($this->input->get('keyword'));
        
        $table_name = 'tbl_apps_category'; 

        $config['base_url'] = base_url('category/audit/' . $id);
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
        $data['menu_label']  = 'Category';
        
        // PERBAIKAN DI SINI: Gunakan variabel $category_data (bukan $db_data)
        $data['target_name'] = $category_data['category_name']; 
        
        $data['back_url']    = 'category';
        $data['export_url']  = base_url('audit/export_excel/tbl_apps_category/' . $id) . ($keyword ? '?keyword=' . urlencode($keyword) : '');
        $data['audit_data']  = $audit_logs;
        $data['pagination']  = $this->pagination->create_links();
        $data['total_rows']  = $config['total_rows'];

        $this->load->view('audit/audit_view', $data);
    
    }
}