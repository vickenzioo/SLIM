<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Database_model extends CI_Model {

    // 1. Mapping Filter
    protected $_filter_map = [
        'database_name' => 'database_name'
    ];

    // 2. Logic Filter Utama
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

    // 3. Count All
    public function count_all_databases($keyword = null, $filters = []) {
        if($keyword) {
            $this->db->group_start();
            $this->db->like('database_name', $keyword);
            $this->db->group_end();
        }
        $this->_apply_filters($filters);
        return $this->db->count_all_results('tbl_database_master');
    }

    // 4. Get Paginated (SORT BY DATABASE NAME ASC)
    public function get_databases_paginated($limit, $start, $keyword = null, $filters = []) {
        if($keyword) {
            $this->db->group_start();
            $this->db->like('database_name', $keyword);
            $this->db->group_end();
        }
        
        $this->_apply_filters($filters);
        
        // [PERUBAHAN DISINI]
		$this->db->order_by('status', 'DESC');
        $this->db->order_by('database_name', 'ASC');
        
        if ($limit > 0) {
            $this->db->limit($limit, $start);
        }
        return $this->db->get('tbl_database_master')->result_array();
    }

    // 5. Dynamic Dropdown (Cascading)
    public function get_dynamic_options($target_key, $current_filters = []) {
        if(!isset($this->_filter_map[$target_key])) return [];
        $column = $this->_filter_map[$target_key];

        // Hapus filter diri sendiri
        $filters_to_apply = $current_filters;
        if(isset($filters_to_apply[$target_key])) {
            unset($filters_to_apply[$target_key]);
        }

        $this->db->select("DISTINCT TRIM($column) as val", FALSE);
        $this->_apply_filters($filters_to_apply);

        $this->db->where("$column IS NOT NULL");
        $this->db->where("TRIM($column) !=", ""); 
        $this->db->order_by("val", 'ASC');
        
        $query = $this->db->get('tbl_database_master');
        
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

    // 6. Get All for Export (SORT BY DATABASE NAME ASC)
    public function get_all_databases($keyword = null, $filters = []) {
        if($keyword) {
            $this->db->group_start();
            $this->db->like('database_name', $keyword);
            $this->db->group_end();
        }
        $this->_apply_filters($filters);
        
        // [PERUBAHAN DISINI]
		$this->db->order_by('status', 'DESC'); 
        $this->db->order_by('database_name', 'ASC');
        
        return $this->db->get('tbl_database_master')->result_array();
    }

    // CRUD Helper
    public function insert_database($data) { return $this->db->insert('tbl_database_master', $data); }
    public function update_database($id, $data) { $this->db->where('database_id', $id); return $this->db->update('tbl_database_master', $data); }
    public function delete_database($id) { $this->db->where('database_id', $id); return $this->db->delete('tbl_database_master'); }
    
    public function check_duplicate_database($name, $id = null) {
        $this->db->where('database_name', $name);
        if($id) { $this->db->where('database_id !=', $id); }
        return $this->db->get('tbl_database_master')->num_rows() > 0;
    }
    
    public function get_by_id($id) {
        return $this->db->get_where('tbl_database_master', ['database_id' => $id])->row_array();
    }
	
	public function update_db_status($id, $status) {
        $this->db->where('database_id', $id);
        return $this->db->update('tbl_database_master', ['status' => $status]);
    }

    // (Opsional)
    public function get_audit_trail($id) {
        $this->db->where('table_name', 'tbl_database_master'); 
        $this->db->where('foreign_id', $id);
        $this->db->order_by('timestamp', 'DESC');
        return $this->db->get('tbl_audit_trail')->result_array();
    }
}