<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Module_model extends CI_Model {

    protected $_filter_map = [
        'module_name' => 'module_name'
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

    public function count_all_modules($keyword = null, $filters = []) {
        if($keyword) {
            $this->db->group_start();
            $this->db->like('module_name', $keyword);
            $this->db->group_end();
        }
        $this->_apply_filters($filters);
        // Changed table name
        return $this->db->count_all_results('tbl_module');
    }

    public function get_modules_paginated($limit, $start, $keyword = null, $filters = []) {
        if($keyword) {
            $this->db->group_start();
            $this->db->like('module_name', $keyword);
            $this->db->group_end();
        }
        $this->_apply_filters($filters);
        $this->db->order_by('status', 'DESC');
        $this->db->order_by('module_name', 'ASC');
        if ($limit > 0) {
            $this->db->limit($limit, $start);
        }
        // Changed table name
        return $this->db->get('tbl_module')->result_array();
    }

    public function get_dynamic_options($target_key, $current_filters = []) {
        if(!isset($this->_filter_map[$target_key])) return [];
        $column = $this->_filter_map[$target_key];

        $filters_to_apply = $current_filters;
        if(isset($filters_to_apply[$target_key])) {
            unset($filters_to_apply[$target_key]);
        }

        $this->db->select("DISTINCT TRIM($column) as val", FALSE);
        $this->_apply_filters($filters_to_apply);
        $this->db->where("$column IS NOT NULL");
        $this->db->where("TRIM($column) !=", ""); 
        $this->db->order_by("val", 'ASC');
        
        // Changed table name
        $query = $this->db->get('tbl_module');
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

    public function get_all_modules($keyword = null, $filters = []) {
        if($keyword) {
            $this->db->group_start();
            $this->db->like('module_name', $keyword);
            $this->db->group_end();
        }
        $this->_apply_filters($filters);
        $this->db->order_by('status', 'DESC'); 
        $this->db->order_by('module_name', 'ASC');
        // Changed table name
        return $this->db->get('tbl_module')->result_array();
    }

    // Changed table names for all CRUD operations
    public function insert_module($data) { return $this->db->insert('tbl_module', $data); }
    public function update_module($id, $data) { $this->db->where('module_id', $id); return $this->db->update('tbl_module', $data); }
    
    public function check_duplicate_module($name, $id = null) {
        $this->db->where('module_name', $name);
        if($id) { $this->db->where('module_id !=', $id); }
        // Changed table name
        return $this->db->get('tbl_module')->num_rows() > 0;
    }
    
    public function get_by_id($id) {
        // Changed table name
        return $this->db->get_where('tbl_module', ['module_id' => $id])->row_array();
    }
    
    public function update_module_status($id, $status) {
        $this->db->where('module_id', $id);
        // Changed table name
        return $this->db->update('tbl_module', ['status' => $status]);
    }
}