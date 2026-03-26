<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Server_model extends CI_Model {

    protected $_filter_map = [
        'category'    => 'c.category_name',
        'module'      => 'm.module_name',
        'service_name' => 'svc.service_name',
        'database'     => 'dbm.database_name',
        'operating_sw'     => 'osw.operating_software_name',
        'server_type' => 's.server_name',
        'apps_name'   => 'a.application_name',
        'sla_svr_prod'=> 'sla_svr_prod', // Filter ini akan dilakukan via HAVING karena hasil kalkulasi
        'sla_svr_dr'  => 'sla_svr_dr',   // Filter ini akan dilakukan via HAVING karena hasil kalkulasi
        'readyness'   => 'readyness'     // Ini juga hasil kalkulasi logic
    ];

    // Fungsi baru untuk memastikan hanya aplikasi yang Fully Approved (Role 2-8 status 1)
    private function _apply_approval_filter() {
        $this->db->where("ai.apps_id IN (
            SELECT apps_id 
            FROM tbl_apps_approval 
            WHERE user_role_id IN (2,3,4,5,6,7,8) 
              AND status = 1 
            GROUP BY apps_id 
            HAVING COUNT(DISTINCT user_role_id) = 7
        )", NULL, FALSE);
    }

    public function count_all($keyword = null, $filters = []) {
        $this->db->from('tbl_apps_infra ai');
        $this->db->join('tbl_portofolio_apps_master a', 'a.apps_id = ai.apps_id', 'left');
        $this->db->join('tbl_portofolio_infra_master pim', 'pim.infra_id = ai.infra_id', 'left');
        $this->db->join('tbl_service svc', 'svc.service_id = pim.service_id', 'left');
        
        // PERBAIKAN: Join module langsung dari pim (bukan via srv)
        $this->db->join('tbl_module m', 'm.module_id = pim.module_id', 'left');

        if (!empty($keyword)) {
            $this->db->group_start();
            $this->db->like('a.application_name', $keyword);
            $this->db->or_like('m.module_name', $keyword);
            $this->db->group_end();
        }

        $this->_apply_approval_filter();
        $this->_apply_filters($filters);
        
        $row = $this->db->select('COUNT(DISTINCT CONCAT(ai.apps_id,"-",ai.infra_id)) AS total')->get()->row_array();
        return (int)($row['total'] ?? 0);
    }

    public function get_paginated($limit, $start, $keyword = null, $filters = []) {
        $this->db->select('
            ai.apps_id, 
            ai.infra_id,
            c.category_name AS category,
            m.module_name AS module,
            svc.service_name AS service_name,
            GROUP_CONCAT(DISTINCT dbm.database_name SEPARATOR ", ") AS db_name,
            GROUP_CONCAT(DISTINCT osw.operating_software_name SEPARATOR ", ") AS os_name,
            GROUP_CONCAT(DISTINCT s.server_name SEPARATOR ", ") AS server_type, 
            a.application_name AS apps_name, 
            r.resilience_category AS dr,
            COALESCE(MAX(s.server_sla), 0) AS sla_by_infra_pct, 
            COALESCE(c.standard_category, 0) AS sla_standard,
            COALESCE(MAX(isv.`server_web_prod_count`), 0) AS svr_web_prod, 
            COALESCE(MAX(isv.`server_app_prod_count`), 0) AS svr_apps_prod, 
            COALESCE(MAX(isv.`server_db_prod_count`), 0) AS svr_db_prod, 
            COALESCE(MAX(isv.`server_web_dr_count`), 0) AS svr_web_dr, 
            COALESCE(MAX(isv.`server_app_dr_count`), 0) AS svr_apps_dr, 
            COALESCE(MAX(isv.`server_db_dr_count`), 0) AS svr_db_dr
        ');

        $this->db->from('tbl_apps_infra ai');
        $this->db->join('tbl_portofolio_apps_master a', 'a.apps_id = ai.apps_id', 'left');
        $this->db->join('tbl_portofolio_infra_master pim', 'pim.infra_id = ai.infra_id', 'left');

        // JOIN MULTIPLE DATABASE (Sesuai Screenshot Struktur DB Anda)
        $this->db->join('tbl_apps_database ad', 'ad.apps_id = a.apps_id', 'left');
        $this->db->join('tbl_database_master dbm', 'dbm.database_id = ad.database_id', 'left');

        // JOIN OPERATING SOFTWARE (Tambahkan 2 baris ini)
        $this->db->join('tbl_apps_operating_software aos', 'aos.apps_id = a.apps_id', 'left');
        $this->db->join('tbl_operating_software osw', 'osw.operating_software_id = aos.operating_software_id', 'left');

        // PERBAIKAN: Join module & resilience langsung dari pim (sesuai struktur baru)
        $this->db->join('tbl_module m', 'm.module_id = pim.module_id', 'left');
        $this->db->join('tbl_service svc', 'svc.service_id = pim.service_id', 'left');
        $this->db->join('tbl_resilience r', 'r.resilience_id = pim.resilience_id', 'left'); 
        
        $this->db->join('tbl_apps_category c', 'c.category_id = a.category_id', 'left');
        $this->db->join('tbl_infra_server isv', 'isv.infra_id = ai.infra_id', 'left');
        $this->db->join('tbl_server s', 's.server_id = isv.server_id', 'left');

        if ($keyword) { 
            $this->db->group_start(); 
            $this->db->like('a.application_name', $keyword);
            $this->db->or_like('dbm.database_name', $keyword);
            $this->db->group_end(); 
        }

        $this->_apply_approval_filter();
        $this->_apply_filters($filters);

        $this->db->group_by('ai.apps_id, ai.infra_id');
        $this->db->limit($limit, $start);
        return $this->db->get()->result_array();
    }
    
    public function get_all_for_export($keyword = null, $filters = []) {
        $this->_build_query($keyword, $filters);
        return $this->db->get()->result_array();
    }

    private function _build_query($keyword = null, $filters = []) {
        $this->db->select('
            ai.apps_id, 
            ai.infra_id, 
            m.module_name AS module, 
            GROUP_CONCAT(DISTINCT s.server_name SEPARATOR ", ") AS server_type, 
            a.application_name AS apps_name, 
            r.resilience_category AS dr,
            COALESCE(MAX(s.server_sla), 0) AS sla_by_infra_pct, 
            COALESCE(c.standard_category, 0) AS sla_standard,
            COALESCE(MAX(isv.`server_web_prod_count`), 0) AS svr_web_prod, 
            COALESCE(MAX(isv.`server_app_prod_count`), 0) AS svr_apps_prod, 
            COALESCE(MAX(isv.`server_db_prod_count`), 0) AS svr_db_prod, 
            COALESCE(MAX(isv.`server_web_dr_count`), 0) AS svr_web_dr, 
            COALESCE(MAX(isv.`server_app_dr_count`), 0) AS svr_apps_dr, 
            COALESCE(MAX(isv.`server_db_dr_count`), 0) AS svr_db_dr
        ');

        $this->db->from('tbl_apps_infra ai');
        $this->db->join('tbl_portofolio_apps_master a', 'a.apps_id = ai.apps_id', 'left');
        $this->db->join('tbl_portofolio_infra_master pim', 'pim.infra_id = ai.infra_id', 'left');
        $this->db->join('tbl_resilience r', 'r.resilience_id = pim.resilience_id', 'left');
        $this->db->join('tbl_module m', 'm.module_id = pim.module_id', 'left');
        $this->db->join('tbl_service svc', 'svc.service_id = pim.service_id', 'left');
        $this->db->join('tbl_apps_category c', 'c.category_id = a.category_id', 'left');
        $this->db->join('tbl_infra_server isv', 'isv.infra_id = ai.infra_id', 'left');
        $this->db->join('tbl_server s', 's.server_id = isv.server_id', 'left');

        if ($keyword) { 
            $this->db->group_start(); 
            $this->db->like('a.application_name', $keyword); 
            $this->db->group_end(); 
        }

        $this->_apply_approval_filter();
        $this->_apply_filters($filters);
        $this->db->group_by('ai.apps_id, ai.infra_id');
    }
    public function get_all_modules() {
        return $this->db->get('tbl_module')->result_array();
    }

    public function get_all_server_types() {
        return $this->db->get('tbl_server')->result_array();
    }


    private function _apply_filters($filters) {
        if (empty($filters)) return;

        foreach ($filters as $key => $values) {
            if (!empty($values) && is_array($values)) {
                $col = $this->_filter_map[$key] ?? null;
                if (!$col) continue;

                // Khusus kolom Module, Server Type, Apps Name (Data Database Langsung)
                if (in_array($key, ['category','module', 'service_name', 'database', 'server_type', 'apps_name'])) {
                    $this->db->group_start();
                    foreach ($values as $val) {
                        $this->db->or_where($col, $val);
                    }
                    $this->db->group_end();
                }
            }
        }
    }

    // Tambahkan fungsi untuk mengambil opsi khusus yang ada di tampilan (Readyness & SLA)
    public function get_static_options($key) {
        if ($key == 'readyness') return ['Comply', 'Not Comply'];
        return []; 
    }
}