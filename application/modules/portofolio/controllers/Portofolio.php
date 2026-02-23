<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Portofolio extends CI_Controller {

    public function __construct() {
        parent::__construct();
        if (!$this->session->userdata('email')) redirect('auth');
        $this->load->model('Portofolio_model');
        $this->load->model('audit/Audit_model');
        date_default_timezone_set('Asia/Jakarta');
    }

    public function index() {
        $this->load->library('pagination');
        
        $keyword = $this->security->xss_clean($this->input->get('keyword'));
        $filters = $this->input->get('filter'); // Array Filter dari View

        // Pagination
        $config['base_url'] = base_url('portofolio/index');
        $params = [];
        if($keyword) $params['keyword'] = $keyword;
        if($filters) $params['filter'] = $filters;
        $config['suffix'] = !empty($params) ? '?' . http_build_query($params, '', '&') : '';
        $config['first_url'] = $config['base_url'] . $config['suffix'];

        $config['total_rows'] = $this->Portofolio_model->count_all($keyword, $filters);
        $config['per_page'] = 10;

        $config['enable_query_strings'] = TRUE;
        $config['page_query_string'] = TRUE;
        $config['query_string_segment'] = 'per_page';
        $config['reuse_query_string'] = TRUE; 
        $config['num_links'] = 5;


         // Pagination Style
        $config['full_tag_open']    = '<ul class="pagination pagination-sm m-0 float-right">';
        $config['full_tag_close']   = '</ul>';
        $config['first_link']       = FALSE; 
        $config['last_link']        = FALSE;
        $config['next_link']        = '&rsaquo;'; // Simbol >
        $config['next_tag_open']    = '<li class="page-item">';
        $config['next_tag_close']   = '</li>';
        $config['prev_link']        = '&lsaquo;'; // Simbol <
        $config['prev_tag_open']    = '<li class="page-item">';
        $config['prev_tag_close']   = '</li>';
        $config['cur_tag_open']     = '<li class="page-item active"><a class="page-link" href="#">';
        $config['cur_tag_close']    = '</a></li>';
        $config['num_tag_open']     = '<li class="page-item">';
        $config['num_tag_close']    = '</li>';
        $config['attributes']       = array('class' => 'page-link');

        $this->pagination->initialize($config);

        $data['list'] = $this->Portofolio_model->get_paginated($config['per_page'], $this->input->get('per_page'), $keyword, $filters);
        $data['infra_options'] = $this->Portofolio_model->get_infra_list();
        
        // --- LOAD DYNAMIC FILTER OPTIONS ---
        $m = $this->Portofolio_model;
        
        $data['opt_category']    = $m->get_dynamic_options('category', $filters);
        $data['opt_app_name']    = $m->get_dynamic_options('app_name', $filters);
        $data['opt_short_name']  = $m->get_dynamic_options('short_name', $filters);
        $data['opt_module']      = $m->get_dynamic_options('module', $filters);
        $data['opt_db_name']     = $m->get_dynamic_options('db_name', $filters);
        $data['opt_os_name']     = $m->get_dynamic_options('os_name', $filters);
        $data['opt_service']     = $m->get_dynamic_options('service', $filters);
        $data['opt_app_type']    = $m->get_dynamic_options('app_type', $filters);
        $data['opt_live_year']   = $m->get_dynamic_options('live_year', $filters);
        $data['opt_decom_year']  = $m->get_dynamic_options('decom_year', $filters);
        $data['opt_resilience']  = $m->get_dynamic_options('resilience', $filters);
        $data['opt_network']     = $m->get_dynamic_options('network', $filters);
        $data['opt_deploy']      = $m->get_dynamic_options('deployment', $filters);
        $data['opt_op_hour']     = $m->get_dynamic_options('op_hour', $filters);
        $data['opt_op_day']      = $m->get_dynamic_options('op_day', $filters);
        $data['opt_principle']     = $m->get_dynamic_options('principle', $filters);
        $data['opt_principle_sol'] = $m->get_dynamic_options('principle_sol', $filters);
        $data['opt_it_group']    = $m->get_dynamic_options('it_group', $filters);
        $data['opt_it_div']      = $m->get_dynamic_options('it_division', $filters);
        $data['opt_directorate'] = $m->get_dynamic_options('directorate', $filters);
        $data['opt_sub_dir']     = $m->get_dynamic_options('sub_directorate', $filters);
        $data['opt_owner_title'] = $m->get_dynamic_options('owner_title', $filters);
        $data['opt_nik_head']    = $m->get_dynamic_options('nik_head', $filters);
        $data['opt_nik_owner']   = $m->get_dynamic_options('nik_owner', $filters);
        $data['opt_nik_dept']    = $m->get_dynamic_options('nik_dept', $filters);
        
        $data['opt_yn'] = ['Y', 'N']; // Statis

        $data['keyword'] = $keyword;
        $data['selected_filters'] = $filters; 
        $data['pagination'] = $this->pagination->create_links();
        $data['total_rows'] = $config['total_rows'];

        $this->load->view('portofolio_view', $data);
    }

    public function export() {
        $filters = $this->input->get('filter');
        $this->Audit_model->insert_log([
            'username'   => $this->session->userdata('username'), 'action' => 'EXPORT', 'table_name' => 'tbl_portofolio_apps_master',
            'old_value'  => '-', 'new_value' => '-', 'foreign_id' => 0, 'reason' => 'Export Data', 'timestamp' => date('Y-m-d H:i:s')
        ]);
        $data['list'] = $this->Portofolio_model->get_all_for_export($filters);
        header("Content-type: application/vnd-ms-excel");
        header("Content-Disposition: attachment; filename=Portofolio_".date('Y-m-d').".xls");
        header("Pragma: no-cache");
        header("Expires: 0");
        $this->load->view('portofolio_export', $data); 
    }
}