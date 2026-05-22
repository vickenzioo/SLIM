<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Service_model extends CI_Model {

    protected $_filter_map = [
        'module_name'  => 'm.module_name',
        'service_name' => 's.service_name'
    ];

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
                            $this->db->or_where("TRIM($col)", trim($val)); 
                        }
                        $this->db->group_end();
                    }
                }
            }
            $this->db->group_end(); 
        }
    }

    private function _base_query() {
        /**
         * PERBAIKAN: 
         * Karena tbl_service tidak punya module_id, kita harus join 
         * lewat tbl_portofolio_infra_master (tabel junction)
         */
        $this->db->select('s.*, m.module_name, m.module_id');
        $this->db->from('tbl_service s');
        $this->db->join('tbl_portofolio_infra_master infra', 's.service_id = infra.service_id', 'left');
        $this->db->join('tbl_module m', 'infra.module_id = m.module_id', 'left');
    }

    public function count_all_services($keyword = null, $filters = []) {
        $this->db->from('tbl_service s');
        // Sesuaikan JOIN di sini juga agar tidak error 'Unknown column'
        $this->db->join('tbl_portofolio_infra_master infra', 's.service_id = infra.service_id', 'left');
        $this->db->join('tbl_module m', 'infra.module_id = m.module_id', 'left');
        
        if($keyword) {
            $this->db->group_start();
            $this->db->like('s.service_name', $keyword);
            $this->db->or_like('m.module_name', $keyword);
            $this->db->group_end();
        }
        $this->_apply_filters($filters);
        return $this->db->count_all_results();
    }

    public function get_services_paginated($limit, $start, $keyword = null, $filters = []) {
        $this->_base_query();
        if($keyword) {
            $this->db->group_start();
            $this->db->like('s.service_name', $keyword);
            $this->db->or_like('m.module_name', $keyword);
            $this->db->group_end();
        }
        $this->_apply_filters($filters);
        $this->db->order_by('s.status', 'DESC');
        $this->db->order_by('m.module_name', 'ASC');
        $this->db->order_by('s.service_name', 'ASC');
        
        if ($limit > 0) { $this->db->limit($limit, $start); }
        return $this->db->get()->result_array();
    }

    public function get_dynamic_options($target_key, $current_filters = []) {
        if(!isset($this->_filter_map[$target_key])) return [];
        $column = $this->_filter_map[$target_key];
        
        $filters_to_apply = $current_filters;
        if(isset($filters_to_apply[$target_key])) { unset($filters_to_apply[$target_key]); }

        $this->db->select("DISTINCT TRIM($column) as val", FALSE);
        $this->db->from('tbl_service s');
        // Sesuaikan JOIN di sini juga
        $this->db->join('tbl_portofolio_infra_master infra', 's.service_id = infra.service_id', 'left');
        $this->db->join('tbl_module m', 'infra.module_id = m.module_id', 'left');
        
        $this->_apply_filters($filters_to_apply);
        $this->db->where("$column IS NOT NULL");
        $this->db->where("TRIM($column) !=", ""); 
        $this->db->order_by("val", 'ASC');
        
        $query = $this->db->get();
        return array_column($query->result_array(), 'val');
    }

    public function get_module_options() {
        return $this->db->where('status', 1)->order_by('module_name', 'ASC')->get('tbl_module')->result_array();
    }

    public function insert_service($data) { 
        $this->db->insert('tbl_service', $data); 
        return $this->db->insert_id();
    }
    
    public function update_service($id, $data) { 
        $this->db->where('service_id', $id); 
        return $this->db->update('tbl_service', $data);
    }

    public function check_duplicate_service_global($name, $id = null) {
        $this->db->where('service_name', $name);
        if($id) { 
            $this->db->where('service_id !=', $id); 
        }
        return $this->db->get('tbl_service')->num_rows() > 0;
    }

    public function get_by_id($id) {
        $this->db->select('s.*, m.module_name, m.module_id');
        $this->db->from('tbl_service s');
        $this->db->join('tbl_portofolio_infra_master infra', 's.service_id = infra.service_id', 'left');
        $this->db->join('tbl_module m', 'infra.module_id = m.module_id', 'left');
        $this->db->where('s.service_id', $id);
        return $this->db->get()->row_array();
    }
}