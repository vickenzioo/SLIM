<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Portofolio_model extends CI_Model {

    // --- 1. MAPPING FILTER ---
    protected $_filter_map = [
        'category'      => 'c.category_name',
        'app_name'      => 'a.application_name',
        'short_name'    => 'a.short_name',
        'module'        => 'm.module_name',
        'db_name'       => 'dbm.database_name',
        'os_name'       => 'os.operating_software_name',
        'service'       => 's.service_name', 
        'app_type'      => 'a.application_type',
        'description'   => 'a.apps_description',
        'live_year'     => 'a.live_year',
        'decom_year'    => 'a.decommission_year',
        'resilience'    => 'r.resilience_category', 
        'dr_avail'      => 'r.dr',                  
        'ha'            => 'r.ha',                  
        'flash_copy'    => 'a.flash_copy',
        'eod'           => 'a.end_of_day',
        'network'       => 'n.network_name',
        'deployment'    => 'd.deployment_model',
        'deployment_info' => 'deployment_info',
        'op_hour'       => 'oh.start_time',
        'op_day'        => 'od.start_day',
        'principle'     => 'a.principle_name',
        'principle_sol' => 'a.principle_solution_name',
        'it_group'      => 'a.it_group_name',
        'it_division'   => 'a.it_division_name',
        'directorate'   => 'a.owner_directorate',
        'sub_directorate'=> 'a.owner_subdirectorate',
        'owner_title'   => 'a.owner_title',
        'nik_head'      => 'a.nik_owner_head',
        'nik_owner'     => 'a.nik_owner',
        'nik_dept'      => 'a.nik_it_department'
    ];

    // --- 2. CORE JOIN (PUSAT RELASI) ---
    private function _only_joins() {
        $this->db->from('tbl_portofolio_apps_master a');
        $this->db->join('tbl_apps_infra j', 'a.apps_id = j.apps_id', 'left');
        $this->db->join('tbl_portofolio_infra_master i', 'j.infra_id = i.infra_id', 'left');
        
        // Perbaikan Join ke service
        $this->db->join('tbl_service s', 'i.service_id = s.service_id', 'left'); 
        
        /**
         * PERBAIKAN:
         * module_id sekarang ada di tbl_portofolio_infra_master (i), 
         * bukan di tbl_service (s).
         */
        $this->db->join('tbl_module m', 'i.module_id = m.module_id', 'left'); 
        
        $this->db->join('tbl_apps_category c', 'c.category_id = a.category_id', 'left');
        $this->db->join('tbl_apps_network n', 'n.network_id = a.network_id', 'left');
        $this->db->join('tbl_apps_deployment d', 'd.deployment_id = a.deployment_id', 'left');
        $this->db->join('tbl_apps_operational_hour oh', 'oh.operational_hour_id = a.operational_hour_id', 'left');
        $this->db->join('tbl_apps_operational_day od', 'od.operational_day_id = a.operational_day_id', 'left');
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
            m.module_name,
            s.service_name, 
            c.category_name,
            n.network_name,
            r.resilience_category AS resilience,
            r.dr AS dr_availability,
            r.ha,
            GROUP_CONCAT(DISTINCT dbm.database_name SEPARATOR ", ") as database_names,
            GROUP_CONCAT(DISTINCT os.operating_software_name SEPARATOR ", ") as os_names,
            d.deployment_model, d.deployment_provider, d.main_deployment_site,
            CONCAT_WS(" - ", d.deployment_model, d.deployment_provider, d.main_deployment_site) AS deployment_info,
            CONCAT(oh.start_time, " - ", oh.end_time) AS operational_hour,
            CONCAT(od.start_day, " - ", od.end_day) AS operational_day
        ');
        $this->_only_joins();
        $this->db->group_by('a.apps_id'); 
    }

    // --- 4. FILTER LOGIC ---
    private function _apply_filters($filters) {
        if (!empty($filters) && is_array($filters)) {
            $this->db->group_start(); 
            foreach ($filters as $key => $values) {
                if (isset($this->_filter_map[$key]) && !empty($values) && is_array($values)) {
                    $valid_values = array_filter($values, function($v) { return $v !== ''; });
                    if(!empty($valid_values)) {
                        $col = $this->_filter_map[$key];
                        $this->db->group_start(); 
                        foreach ($valid_values as $val) {
                            $val = trim($val);
                            if($key == 'deployment_info') {
                                $this->db->or_group_start();
                                $this->db->like('d.deployment_model', $val);
                                $this->db->or_like('d.deployment_provider', $val);
                                $this->db->or_like('d.main_deployment_site', $val);
                                $this->db->group_end();
                            } else {
                                $this->db->or_where("TRIM($col)", $val); 
                            }
                        }
                        $this->db->group_end(); 
                    }
                }
            }
            $this->db->group_end(); 
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
        $this->db->where("$column IS NOT NULL");
        $this->db->where("TRIM($column) !=", ""); 
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
    
    // Helpers
    public function get_infra_list() { 
        $this->db->select('i.infra_id, s.service_name');
        $this->db->from('tbl_portofolio_infra_master i');
        $this->db->join('tbl_service s', 'i.service_id = s.service_id', 'left');
        $this->db->order_by('s.service_name', 'ASC');
        return $this->db->get()->result_array(); 
    }

    public function insert_app($data) { 
        $this->db->insert('tbl_portofolio_apps_master', $data); 
        return $this->db->insert_id(); 
    }
    public function insert_junction($apps_id, $infra_id) { 
        return $this->db->insert('tbl_apps_infra', ['apps_id' => $apps_id, 'infra_id' => $infra_id]); 
    }
    public function check_duplicate_app($name) { 
        $this->db->where('application_name', $name); 
        return $this->db->get('tbl_portofolio_apps_master')->num_rows() > 0; 
    }
}