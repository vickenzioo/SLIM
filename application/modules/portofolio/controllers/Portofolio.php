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
        $filters = $this->input->get('filter'); 

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
        $config['next_link']        = '&rsaquo;'; 
        $config['next_tag_open']    = '<li class="page-item">';
        $config['next_tag_close']   = '</li>';
        $config['prev_link']        = '&lsaquo;'; 
        $config['prev_tag_open']    = '<li class="page-item">';
        $config['prev_tag_close']   = '</li>';
        $config['cur_tag_open']     = '<li class="page-item active"><a class="page-link" href="#">';
        $config['cur_tag_close']    = '</a></li>';
        $config['num_tag_open']     = '<li class="page-item">';
        $config['num_tag_close']    = '</li>';
        $config['attributes']       = array('class' => 'page-link');

        $this->pagination->initialize($config);

        $data['list'] = $this->Portofolio_model->get_paginated($config['per_page'], $this->input->get('per_page'), $keyword, $filters);
        
        // --- LOAD DYNAMIC FILTER OPTIONS ---
        $m = $this->Portofolio_model;
        
        $data['opt_category']       = $m->get_dynamic_options('category', $filters);
        $data['opt_app_name']       = $m->get_dynamic_options('app_name', $filters);
        $data['opt_short_name']     = $m->get_dynamic_options('short_name', $filters);
        $data['opt_module']         = $m->get_dynamic_options('module', $filters);
        $data['opt_db_name']        = $m->get_dynamic_options('db_name', $filters);
        $data['opt_os_name']        = $m->get_dynamic_options('os_name', $filters);
        $data['opt_app_type']       = $m->get_dynamic_options('app_type', $filters);
        $data['opt_live_year']      = $m->get_dynamic_options('live_year', $filters);
        $data['opt_decom_year']     = $m->get_dynamic_options('decom_year', $filters);
        $data['opt_resilience']     = $m->get_dynamic_options('resilience', $filters);
        $data['opt_network']        = $m->get_dynamic_options('network', $filters);
        $data['opt_deploy']         = $m->get_dynamic_options('deployment', $filters);
        $data['opt_op_hour']        = $m->get_dynamic_options('op_hour', $filters);
        $data['opt_op_day']         = $m->get_dynamic_options('op_day', $filters);
        
        $data['opt_solution_vendor']= $m->get_dynamic_options('solution_vendor', $filters); 
        $data['opt_services_vendor']= $m->get_dynamic_options('services_vendor', $filters); 
        
        $data['opt_lob_directorate']    = $m->get_dynamic_options('lob_directorate', $filters);
        $data['opt_lob_subdirectorate'] = $m->get_dynamic_options('lob_subdirectorate', $filters);
        $data['opt_lob_group']          = $m->get_dynamic_options('lob_group', $filters);
        $data['opt_lob_group_head']     = $m->get_dynamic_options('lob_group_head', $filters);
        $data['opt_it_subdirectorate']  = $m->get_dynamic_options('it_subdirectorate', $filters);
        $data['opt_it_department_head'] = $m->get_dynamic_options('it_department_head', $filters);
        $data['opt_it_support_group']   = $m->get_dynamic_options('it_support_group', $filters);
        $data['opt_it_group_head']      = $m->get_dynamic_options('it_group_head', $filters);
        $data['opt_it_support_divison'] = $m->get_dynamic_options('it_support_divison', $filters);
        $data['opt_it_division_head']   = $m->get_dynamic_options('it_division_head', $filters);
        
        $data['opt_app_version']    = $m->get_dynamic_options('app_version', $filters);
        $data['opt_dev_lang']       = $m->get_dynamic_options('dev_language', $filters);
        $data['opt_app_dev']        = $m->get_dynamic_options('app_developer', $filters);
        $data['opt_web_server']     = $m->get_dynamic_options('web_server', $filters);
        $data['opt_app_server']     = $m->get_dynamic_options('app_server', $filters);
        $data['opt_sup_others']     = $m->get_dynamic_options('sup_others', $filters);
        $data['opt_src_code']       = $m->get_dynamic_options('src_code', $filters);
        $data['opt_url']            = $m->get_dynamic_options('url', $filters);

        $data['opt_yn'] = ['Y', 'N']; 

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