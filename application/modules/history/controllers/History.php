<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class History extends CI_Controller {

    public function __construct() {
        parent::__construct();
        date_default_timezone_set('Asia/Jakarta');
        if (!$this->session->userdata('email')) {
             redirect('auth');
        }
        $this->load->model('History_model');
        $this->load->model('audit/Audit_model');
    }

    public function index() {
        $this->load->library('pagination');
        $keyword = $this->security->xss_clean($this->input->get('keyword'));
        $filters = $this->input->get('filter'); // Ambil filter

        $config['base_url'] = base_url('history/index');
        
        // ... kode di atasnya tetap ...
        $config['base_url'] = base_url('history/index');
        $total_rows = $this->History_model->count_all_historys($keyword, $filters);
        $config['total_rows'] = $total_rows;
        $config['per_page'] = 10; 
        
        $config['enable_query_strings'] = TRUE;
        $config['page_query_string'] = TRUE;
        $config['query_string_segment'] = 'per_page';
        
        // KUNCINYA DI SINI: Ini sudah otomatis membawa parameter keyword & filter
        $config['reuse_query_string'] = TRUE; 

        $this->pagination->initialize($config);

        // PERBAIKAN: Tangkap nilai start dan PASTIKAN diubah menjadi angka bulat (Integer)
        $start_param = $this->input->get('per_page');
        $start = ($start_param != '') ? (int)$start_param : 0; 

        // Ambil Data Filtered menggunakan $start yang sudah bersih
        $data['historys'] = $this->History_model->get_historys_paginated($config['per_page'], $start, $keyword, $filters);

        $total_rows = $this->History_model->count_all_historys($keyword, $filters);
        $config['total_rows'] = $total_rows;
        $config['per_page'] = 10; 
        
        $config['enable_query_strings'] = TRUE;
        $config['page_query_string'] = TRUE;
        $config['query_string_segment'] = 'per_page';
        $config['reuse_query_string'] = TRUE;

        // --- PENERAPAN STYLE PAGINATION DARI HOME ---
        $last_page_num = (int)ceil($total_rows / $config['per_page']);
        $config['num_links'] = 2; // Mengikuti style home (2 angka di kiri/kanan)
        $config['display_pages'] = TRUE;

        // Tombol angka pertama (1) dan angka terakhir tetap muncul permanen
        $config['first_link'] = '1';
        $config['last_link'] = (string)$last_page_num;

        // Mengubah panah menjadi gabungan titik-titik sesuai posisi
        // Halaman Pertama: angka terakhir + ..... + >
        $config['next_link'] = '&rsaquo;'; 
        // Halaman Terakhir: < + ..... + angka pertama
        $config['prev_link'] = '&lsaquo;';

        $config['full_tag_open']    = '<ul class="pagination pagination-sm m-0 float-right">';
        $config['full_tag_close']   = '</ul>';
        
        $config['num_tag_open']     = '<li class="page-item">';
        $config['num_tag_close']    = '</li>';
        
        $config['cur_tag_open']     = '<li class="page-item active"><a class="page-link" href="#">';
        $config['cur_tag_close']    = '</a></li>';

        $config['next_tag_open']    = '<li class="page-item">';
        $config['next_tag_close']   = '</li>';
        $config['prev_tag_open']    = '<li class="page-item">';
        $config['prev_tag_close']   = '</li>';

        $config['first_tag_open']   = '<li class="page-item">';
        $config['first_tag_close']  = '</li>';
        $config['last_tag_open']    = '<li class="page-item">';
        $config['last_tag_close']   = '</li>';

        $config['attributes']       = array('class' => 'page-link');
        // --------------------------------------------

        $this->pagination->initialize($config);

        $start = $this->input->get('per_page');
        // Ambil Data Filtered
        $data['historys'] = $this->History_model->get_historys_paginated($config['per_page'], $start, $keyword, $filters);
        
        // Load Options Filter
        $data['opt_timestamp']  = $this->History_model->get_dynamic_options('timestamp', $filters);
        $data['opt_username']   = $this->History_model->get_dynamic_options('username', $filters);
        $data['opt_action']     = $this->History_model->get_dynamic_options('action', $filters);
        $data['opt_table_name'] = $this->History_model->get_dynamic_options('table_name', $filters);
        $data['opt_field_name'] = $this->History_model->get_dynamic_options('field_name', $filters);
        $data['opt_old_value']  = $this->History_model->get_dynamic_options('old_value', $filters);
        $data['opt_new_value']  = $this->History_model->get_dynamic_options('new_value', $filters);
        $data['opt_reason']     = $this->History_model->get_dynamic_options('reason', $filters);

        $data['keyword'] = $keyword;
        $data['selected_filters'] = $filters;
        $data['pagination'] = $this->pagination->create_links();
        $data['total_rows'] = $total_rows;

        $this->load->view('history_view', $data);
    }

    public function export() {
        $this->Audit_model->insert_log([ 'username' => $this->session->userdata('username'), 'action' => 'EXPORT', 'table_name' => 'tbl_history', 'foreign_id' => 0, 'field_name' => '-', 'old_value' => '-', 'new_value' => '-', 'reason' => 'Export Data', 'timestamp' => date('Y-m-d H:i:s') ]);
        
        $keyword = $this->security->xss_clean($this->input->get('keyword'));
        $filters = $this->input->get('filter');

        $data['historys'] = $this->History_model->get_all_historys($keyword, $filters);

        $data['table_map'] = [
            'tbl_portofolio_apps_master' => 'My Portfolio',
            'tbl_server'                 => 'Server Type',
            'tbl_operating_software'     => 'Operating Software',
            'tbl_apps_operational_hour'  => 'Operational Hour',
            'tbl_apps_deployment'        => 'Deployment',
            'tbl_apps_deployment_model'  => 'Deployment Provider',
            'tbl_apps_deployment_site'   => 'Deployment Site',
            'tbl_app_type'               => 'Application Type',
            'tbl_apps_category'          => 'Category',
            'tbl_apps_network'           => 'Network',
            'tbl_network_product'        => 'Network Product',
            'tbl_network_provider'       => 'Network Provider',
            'tbl_apps_operational_day'   => 'Operational Day',
            'tbl_database_master'        => 'Database',
            'tbl_audit_trail'            => 'History',
            'tbl_history'                => 'History',
            'tbl_holiday'                => 'Holiday',
            'tbl_user_role'              => 'User Role',
            'users'                      => 'User Role'
        ];
        
        header("Content-type: application/vnd-ms-excel");
        header("Content-Disposition: attachment; filename=Audit_Trail_".date('Y-m-d').".xls");
        header("Pragma: no-cache");
        header("Expires: 0");

        $this->load->view('history_export', $data);
    }
}