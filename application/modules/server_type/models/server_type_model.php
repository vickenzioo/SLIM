<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Server_type_model extends CI_Model {

    protected $_filter_map = [
        'server_name' => 'server_name'
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

    public function count_all_servers($keyword = null, $filters = []) {
        if($keyword) {
            $this->db->group_start();
            $this->db->like('server_name', $keyword);
            $this->db->group_end();
        }
        $this->_apply_filters($filters);
        return $this->db->count_all_results('tbl_server');
    }

    public function get_servers_paginated($limit, $start, $keyword = null, $filters = []) {
        if($keyword) {
            $this->db->group_start();
            $this->db->like('server_name', $keyword);
            $this->db->group_end();
        }
        
        $this->_apply_filters($filters);
        
        $this->db->order_by('status', 'DESC');
        $this->db->order_by('server_name', 'ASC');
        
        if ($limit > 0) {
            $this->db->limit($limit, $start);
        }
        return $this->db->get('tbl_server')->result_array();
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
        
        $query = $this->db->get('tbl_server');
        
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

    public function get_all_servers($keyword = null, $filters = []) {
        if($keyword) {
            $this->db->group_start();
            $this->db->like('server_name', $keyword);
            $this->db->group_end();
        }
        $this->_apply_filters($filters);
        
        $this->db->order_by('status', 'DESC'); 
        $this->db->order_by('server_name', 'ASC');
        
        return $this->db->get('tbl_server')->result_array();
    }

    public function insert_server($data) { return $this->db->insert('tbl_server', $data); }
    public function update_server($id, $data) { $this->db->where('server_id', $id); return $this->db->update('tbl_server', $data); }
    
    public function check_duplicate_server($name, $id = null) {
        $this->db->where('server_name', $name);
        if($id) { $this->db->where('server_id !=', $id); }
        return $this->db->get('tbl_server')->num_rows() > 0;
    }
    
    public function get_by_id($id) {
        return $this->db->get_where('tbl_server', ['server_id' => $id])->row_array();
    }
    
    public function update_status($id, $status) {
        $this->db->where('server_id', $id);
        return $this->db->update('tbl_server', ['status' => $status]);
    }
}