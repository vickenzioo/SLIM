<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Portofolio_model extends CI_Model {

    // --- 1. MAPPING FILTER ---
    protected $_filter_map = [
        'category'      => 'c.category_name',
        'app_name'      => 'a.application_name',
        'short_name'    => 'a.short_name',
        'module'        => 'a.module', 
        'db_name'       => 'dbm.database_name',
        'os_name'       => 'os.operating_software_name',
        'app_type'      => 'at.app_type_name',
        'description'   => 'a.apps_description',
        'live_year'     => 'a.live_year',
        'decom_year'    => 'a.decommission_year',
        'resilience'    => 'r.resilience_category', 
        'dr_avail'      => 'r.dr',                  
        'ha'            => 'r.ha',                  
        'network'       => 'n.network_name',
        'deployment'    => "CONCAT_WS(' - ', d.deployment_model, dp.deployment_provider_name, ds.deployment_site_name)",
        'op_hour'       => "CONCAT(oh.start_time, ' - ', oh.end_time)",
        'op_day'        => "CONCAT(od.start_day, ' - ', od.end_day)",
        'solution_vendor' => 'a.solution_vendor',
        'services_vendor' => 'a.services_vendor',
        'lob_directorate' => 'a.lob_directorate',
        'lob_subdirectorate' => 'a.lob_subdirectorate',
        'lob_group'       => 'a.lob_group',
        'lob_group_head'  => 'a.lob_group_head',
        'it_subdirectorate' => 'a.it_subdirectorate',
        'it_department_head'=> 'a.it_department_head',
        'it_support_group'=> 'a.it_support_group',
        'it_group_head'   => 'a.it_group_head',
        'it_support_divison'=> 'a.it_support_divison',
        'it_division_head'=> 'a.it_division_head',
        
        // MAPPING KOLOM TAMBAHAN MASTER (KECUALI TRACKING)
        'app_version'   => 'a.application_version',
        'dev_language'  => 'a.development_language',
        'app_developer' => 'a.application_developer',
        'web_server'    => 'a.supporting_web_server',
        'app_server'    => 'a.supporting_application_server',
        'sup_others'    => 'a.supporting_others',
        'src_code'      => 'a.source_code_owned',
        'url'           => 'a.Url'
    ];

    // --- 2. CORE JOIN (PUSAT RELASI) ---
    private function _only_joins() {
        $this->db->from('tbl_portofolio_apps_master a');
        $this->db->join('tbl_apps_category c', 'a.category_id = c.category_id', 'left');
        $this->db->join('tbl_apps_network n', 'a.network_id = n.network_id', 'left');
        $this->db->join('tbl_app_type at', 'a.app_type_id = at.app_type_id', 'left');
        
        $this->db->join('tbl_apps_deployment d', 'a.deployment_id = d.deployment_id', 'left');
        $this->db->join('tbl_apps_deployment_model dp', 'a.deployment_provider_id = dp.deployment_provider_id', 'left');
        $this->db->join('tbl_apps_deployment_site ds', 'a.deployment_site_id = ds.deployment_site_id', 'left');
        
        $this->db->join('tbl_apps_operational_hour oh', 'a.operational_hour_id = oh.operational_hour_id', 'left');
        $this->db->join('tbl_apps_operational_day od', 'a.operational_day_id = od.operational_day_id', 'left');
        $this->db->join('tbl_apps_database adb', 'a.apps_id = adb.apps_id', 'left');
        $this->db->join('tbl_database_master dbm', 'adb.database_id = dbm.database_id', 'left');
        $this->db->join('tbl_apps_operating_software aos', 'a.apps_id = aos.apps_id', 'left');
        $this->db->join('tbl_operating_software os', 'aos.operating_software_id = os.operating_software_id', 'left');
        $this->db->join('tbl_resilience r', 'a.resilience_id = r.resilience_id', 'left');
    }

    // --- 3. SELECT DATA ---
    private function _query_joins() {
        $this->db->select('
            a.*, 
            c.category_name,
            n.network_name,
            at.app_type_name as application_type,
            r.resilience_category AS resilience,
            r.dr AS dr_availability,
            r.ha,
            GROUP_CONCAT(DISTINCT dbm.database_name SEPARATOR ", ") as database_names,
            GROUP_CONCAT(DISTINCT os.operating_software_name SEPARATOR ", ") as os_names,
            CONCAT_WS(" - ", d.deployment_model, dp.deployment_provider_name, ds.deployment_site_name) AS deployment_info,
            CONCAT(oh.start_time, " - ", oh.end_time) AS operational_hour,
            CONCAT(od.start_day, " - ", od.end_day) AS operational_day
        ');
        $this->_only_joins();
        $this->db->group_by('a.apps_id'); 
    }

    // --- 4. FILTER LOGIC ---
    private function _apply_filters($filters) {
        if (!empty($filters) && is_array($filters)) {
            $applied_any = false; 
            foreach ($filters as $key => $values) {
                if (isset($this->_filter_map[$key]) && !empty($values) && is_array($values)) {
                    $valid_values = array_filter($values, function($v) { return $v !== ''; });
                    if(!empty($valid_values)) {
                        if (!$applied_any) {
                            $this->db->group_start();
                            $applied_any = true;
                        }
                        $col = $this->_filter_map[$key];
                        $this->db->group_start();
                        $first = true;
                        foreach ($valid_values as $val) {
                            $val_esc = $this->db->escape(trim($val));
                            if($first){ 
                                $this->db->where("TRIM($col) = $val_esc", NULL, FALSE); 
                                $first = false; 
                            } else { 
                                $this->db->or_where("TRIM($col) = $val_esc", NULL, FALSE); 
                            }
                        }
                        $this->db->group_end();
                    }
                }
            }
            if ($applied_any) { $this->db->group_end(); }
        }
    }

    private function _apply_sorting() {
        $this->db->order_by('c.category_name', 'ASC');
        $this->db->order_by('a.short_name', 'ASC');
    }

    // --- 5. PUBLIC FUNCTIONS ---
    public function count_all($keyword = null, $filters = []) {
        $this->_query_joins(); 
        if ($keyword) {
            $this->db->group_start();
            $this->db->like('a.application_name', $keyword);
            $this->db->or_like('a.short_name', $keyword);
            $this->db->or_like('dbm.database_name', $keyword);
            $this->db->or_like('os.operating_software_name', $keyword);
            $this->db->group_end();
        }
        $this->_apply_filters($filters);
        return $this->db->get()->num_rows();
    }

    public function get_paginated($limit, $start, $keyword = null, $filters = []) {
        $this->_query_joins(); 
        if ($keyword) {
            $this->db->group_start();
            $this->db->like('a.application_name', $keyword);
            $this->db->or_like('a.short_name', $keyword);
            $this->db->or_like('dbm.database_name', $keyword);
            $this->db->or_like('os.operating_software_name', $keyword);
            $this->db->group_end();
        }
        $this->_apply_filters($filters);
        $this->_apply_sorting();
        if ($limit > 0) $this->db->limit($limit, $start);
        return $this->db->get()->result_array();
    }

    public function get_all_for_export($filters = []) {
        $this->_query_joins();
        $this->_apply_filters($filters);
        $this->_apply_sorting();
        return $this->db->get()->result_array();
    }

    // --- 6. DYNAMIC DROPDOWN OPTION ---
    public function get_dynamic_options($target_key, $current_filters = []) {
        if(!isset($this->_filter_map[$target_key])) return [];
        $column = $this->_filter_map[$target_key];
        $filters_to_apply = $current_filters;
        if(isset($filters_to_apply[$target_key])) {
            unset($filters_to_apply[$target_key]);
        }
        $this->db->select("DISTINCT TRIM($column) as val", FALSE);
        $this->_only_joins(); 
        $this->_apply_filters($filters_to_apply);
        $this->db->where("$column IS NOT NULL", NULL, FALSE);
        $this->db->where("TRIM($column) != ''", NULL, FALSE); 
        $this->db->group_by("val"); 
        $this->db->order_by("val", 'ASC');
        $query = $this->db->get();
        $results = [];
        if($query->num_rows() > 0) {
            foreach ($query->result_array() as $row) {
                if(!empty($row['val'])) {
                    $results[] = $row['val'];
                }
            }
        }
        return array_unique($results);
    }
}