<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Server extends CI_Controller {

    public function __construct() {
        parent::__construct();
        if (!$this->session->userdata('email')) {
            redirect('auth');
        }
        $this->load->model('Server_model');
    }

    public function index() {
        $this->load->library('pagination');
        $keyword = $this->input->get('keyword');
        $filters = $this->input->get('filter');

        // Setup Pagination (Jaga agar filter tidak hilang saat pindah halaman)
        $config['base_url'] = base_url('server/index');
        $config['suffix'] = (!empty($filters)) ? '?' . http_build_query(['filter' => $filters]) : '';
        $config['first_url'] = $config['base_url'] . $config['suffix'];
        
        $config['total_rows'] = $this->Server_model->count_all($keyword, $filters);
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
        
        $rows = $this->Server_model->get_paginated($config['per_page'], $start, $keyword, $filters);

        foreach ($rows as &$r) {
            $slaInfra = ((float)$r['sla_by_infra_pct']) / 100.0;
            
            // PROD Calculation
            $r['sla_web_prod']  = ($r['svr_web_prod']  > 0) ? (1 - pow((1 - $slaInfra), (int)$r['svr_web_prod']))  : 0;
            $r['sla_apps_prod'] = ($r['svr_apps_prod'] > 0) ? (1 - pow((1 - $slaInfra), (int)$r['svr_apps_prod'])) : 0;
            $r['sla_db_prod']   = ($r['svr_db_prod']   > 0) ? (1 - pow((1 - $slaInfra), (int)$r['svr_db_prod']))   : 0;
            $r['sla_svr_prod']  = $this->_mul_non_zero([$r['sla_web_prod'],$r['sla_apps_prod'],$r['sla_db_prod']]);

            // DR Calculation
            $r['sla_web_dr']  = ($r['svr_web_dr']  > 0) ? (1 - pow((1 - $slaInfra), (int)$r['svr_web_dr']))  : 0;
            $r['sla_apps_dr'] = ($r['svr_apps_dr'] > 0) ? (1 - pow((1 - $slaInfra), (int)$r['svr_apps_dr'])) : 0;
            $r['sla_db_dr']   = ($r['svr_db_dr']   > 0) ? (1 - pow((1 - $slaInfra), (int)$r['svr_db_dr']))   : 0;
            $r['sla_svr_dr']  = $this->_mul_non_zero([$r['sla_web_dr'],$r['sla_apps_dr'],$r['sla_db_dr']]);

            // --- REVISI RUMUS SLA ACTUAL ---
            $dr_val = (string)$r['dr'];
            $powN = ($dr_val === 'L0') ? 1 : 2;
            $diff = (float)$r['sla_svr_prod'] - (float)$r['sla_svr_dr'];
            
            $r['sla_actual']   = 1 - pow($diff, $powN);
            
            // Standard Category dari DB adalah 99.00 (bagi 100 untuk compare desimal)
            $standard_dec      = (float)$r['sla_standard'] / 100.0; 
            $r['readyness']    = ($r['sla_actual'] < $standard_dec) ? 'Not Comply' : 'Comply';
            $r['suggestion']   = ($r['readyness'] === 'Not Comply') 
                                 ? 'Assesment kembali konfigurasi infra atau kategori kualitas aplikasi' 
                                 : '-';
        }
        unset($r);
        // 2. Filter hasil kalkulasi (SLA & Readyness) secara manual jika ada filter yang dipilih
        if (!empty($filters)) {
            $rows = array_filter($rows, function($row) use ($filters) {
                $match = true;
                
                // Filter Readyness
                if (!empty($filters['readyness'])) {
                    $match = $match && in_array($row['readyness'], $filters['readyness']);
                }
                
                // Filter SLA (Contoh sederhana: mengelompokkan range jika diperlukan)
                // Jika Anda ingin filter SLA spesifik, tambahkan logic di sini
                
                return $match;
            });
        }

        // --- HAPUS KODE LAMA DAN GANTI DENGAN KODE BARU DI SINI ---

        // 0. Opsi Category (Hanya yang memiliki mapping infra)
        $data['opt_category'] = array_column(
            $this->db->select('DISTINCT(c.category_name)')
                ->from('tbl_apps_infra ai')
                ->join('tbl_portofolio_apps_master a', 'a.apps_id = ai.apps_id')
                ->join('tbl_apps_category c', 'c.category_id = a.category_id')
                ->get()->result_array(), 
            'category_name'
        );

        // 1. Opsi Module (Hanya yang ada di mapping infra)
        $data['opt_module'] = array_column(
            $this->db->select('DISTINCT(m.module_name)')
                ->from('tbl_portofolio_infra_master pim')
                ->join('tbl_module m', 'm.module_id = pim.module_id') // Langsung join ke pim
                ->get()->result_array(), 
            'module_name'
        );

        // 1.5 Opsi Service Name (Hanya yang ada di mapping infra)
        $data['opt_service_name'] = array_column(
            $this->db->select('DISTINCT(svc.service_name)')
                ->from('tbl_portofolio_infra_master pim')
                ->join('tbl_service svc', 'svc.service_id = pim.service_id')
                ->get()->result_array(), 
            'service_name'
        );

        $data['opt_database'] = array_column(
            $this->db->select('DISTINCT(dbm.database_name)')
                ->from('tbl_apps_database ad')
                ->join('tbl_database_master dbm', 'dbm.database_id = ad.database_id')
                ->get()->result_array(), 
            'database_name'
        );

        $data['opt_os'] = array_column(
            $this->db->select('DISTINCT(osw.operating_software_name)')
                ->from('tbl_apps_operating_software aos')
                ->join('tbl_operating_software osw', 'osw.operating_software_id = aos.operating_software_id')
                ->get()->result_array(), 
            'operating_software_name'
        );

        // 2. Opsi Server Type (Hanya yang digunakan di infra server)
        $data['opt_server_type'] = array_column(
            $this->db->select('DISTINCT(s.server_name)')
                ->from('tbl_infra_server isv')
                ->join('tbl_server s', 's.server_id = isv.server_id')
                ->get()->result_array(), 
            'server_name'
        );

        // 3. Opsi Apps Name (Hanya yang memiliki mapping infra)
        $data['opt_apps_name'] = array_column(
            $this->db->select('DISTINCT(a.application_name)')
                ->from('tbl_apps_infra ai')
                ->join('tbl_portofolio_apps_master a', 'a.apps_id = ai.apps_id')
                ->get()->result_array(), 
            'application_name'
        );

        $data['opt_readyness'] = ['Comply', 'Not Comply'];

        // --- AKHIR KODE BARU ---

        $data['rows'] = $rows;
        $data['selected_filters'] = $filters;
        $data['pagination'] = $this->pagination->create_links();
        $data['total_rows'] = $config['total_rows'];
        $this->load->view('server_view', $data);
    }

    private function _mul_non_zero($arr) {
        $vals = [];
        foreach ($arr as $v) {
            $v = (float)$v;
            if ($v > 0) $vals[] = $v;
        }
        if (count($vals) === 0) return 0;
        $res = 1;
        foreach ($vals as $v) $res *= $v;
        return $res;
    }

    private function _json($data) {
        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }

    public function export() {
        $keyword = $this->input->get('keyword');
        $filters = $this->input->get('filter');

        // 1. Ambil semua data tanpa limit
        $rows = $this->Server_model->get_all_for_export($keyword, $filters);

        // 2. Kalkulasi Data
        foreach ($rows as &$r) {
            $slaInfra = ((float)$r['sla_by_infra_pct']) / 100.0;
            
            $r['sla_web_prod']  = ($r['svr_web_prod']  > 0) ? (1 - pow((1 - $slaInfra), (int)$r['svr_web_prod']))  : 0;
            $r['sla_apps_prod'] = ($r['svr_apps_prod'] > 0) ? (1 - pow((1 - $slaInfra), (int)$r['svr_apps_prod'])) : 0;
            $r['sla_db_prod']   = ($r['svr_db_prod']   > 0) ? (1 - pow((1 - $slaInfra), (int)$r['svr_db_prod']))   : 0;
            $r['sla_svr_prod']  = $this->_mul_non_zero([$r['sla_web_prod'],$r['sla_apps_prod'],$r['sla_db_prod']]);

            $r['sla_web_dr']  = ($r['svr_web_dr']  > 0) ? (1 - pow((1 - $slaInfra), (int)$r['svr_web_dr']))  : 0;
            $r['sla_apps_dr'] = ($r['svr_apps_dr'] > 0) ? (1 - pow((1 - $slaInfra), (int)$r['svr_apps_dr'])) : 0;
            $r['sla_db_dr']   = ($r['svr_db_dr']   > 0) ? (1 - pow((1 - $slaInfra), (int)$r['svr_db_dr']))   : 0;
            $r['sla_svr_dr']  = $this->_mul_non_zero([$r['sla_web_dr'],$r['sla_apps_dr'],$r['sla_db_dr']]);

            $dr_val = (string)$r['dr'];
            $powN = ($dr_val === 'L0') ? 1 : 2;
            $diff = (float)$r['sla_svr_prod'] - (float)$r['sla_svr_dr'];
            
            $r['sla_actual']   = 1 - pow($diff, $powN);
            $standard_dec      = (float)$r['sla_standard'] / 100.0; 
            $r['readyness']    = ($r['sla_actual'] < $standard_dec) ? 'Not Comply' : 'Comply';
            $r['suggestion']   = ($r['readyness'] === 'Not Comply') ? 'Assesment kembali' : '-';
        }
        unset($r);

        // 3. FILTER MANUAL (Sangat Penting: Agar hasil Excel sesuai filter Readyness di web)
        if (!empty($filters['readyness'])) {
            $rows = array_filter($rows, function($row) use ($filters) {
                return in_array($row['readyness'], $filters['readyness']);
            });
        }

        $data['rows'] = $rows;

        header("Content-type: application/vnd-ms-excel");
        header("Content-Disposition: attachment; filename=Export_Server_Management.xls");
        $this->load->view('server_export', $data);
    }

}