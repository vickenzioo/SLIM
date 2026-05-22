<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Operating_Software_model extends CI_Model {

    // 1. Mapping Filter
    protected $_filter_map = [
        'operating_software_name' => 'operating_software_name'
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

    // 3. Count All (Support Filter)
    public function count_all_operating_softwares($keyword = null, $filters = []) {
        if($keyword) {
            $this->db->group_start();
            $this->db->like('operating_software_name', $keyword);
            $this->db->group_end();
        }
        $this->_apply_filters($filters);
        return $this->db->count_all_results('tbl_operating_software');
    }

    // 4. Get Paginated (Support Filter + SORT NAME ASC)
    public function get_operating_softwares_paginated($limit, $start, $keyword = null, $filters = []) {
        if($keyword) {
            $this->db->group_start();
            $this->db->like('operating_software_name', $keyword);
            $this->db->group_end();
        }
        
        $this->_apply_filters($filters);
		$this->db->order_by('status', 'DESC'); 
        $this->db->order_by('operating_software_name', 'ASC'); 
        
        if ($limit > 0) {
            $this->db->limit($limit, $start);
        }
        return $this->db->get('tbl_operating_software')->result_array();
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
        
        $query = $this->db->get('tbl_operating_software');
        
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

    // 6. Get All for Export (Support Filter)
    public function get_all_operating_softwares($keyword = null, $filters = []) {
        if($keyword) {
            $this->db->group_start();
            $this->db->like('operating_software_name', $keyword);
            $this->db->group_end();
        }
        $this->_apply_filters($filters);
		$this->db->order_by('status', 'DESC'); 
        $this->db->order_by('operating_software_name', 'ASC');
        return $this->db->get('tbl_operating_software')->result_array();
    }

    // --- CRUD Standard ---
    public function insert_operating_software($data) { return $this->db->insert('tbl_operating_software', $data); }
    public function update_operating_software($id, $data) { $this->db->where('operating_software_id', $id); return $this->db->update('tbl_operating_software', $data); }
    public function delete_operating_software($id) { $this->db->where('operating_software_id', $id); return $this->db->delete('tbl_operating_software'); }
    
    public function check_duplicate_software($name, $id = null) {
        $this->db->where('operating_software_name', $name);
        if($id) { $this->db->where('operating_software_id !=', $id); }
        return $this->db->get('tbl_operating_software')->num_rows() > 0;
    }
    
    public function get_by_id($id) {
        return $this->db->get_where('tbl_operating_software', ['operating_software_id' => $id])->row_array();
    }
	
	public function update_os_status($id, $status) {
        $this->db->where('operating_software_id', $id);
        return $this->db->update('tbl_operating_software', ['status' => $status]);
    }

    public function get_audit_trail($id) {
        $this->db->where('table_name', 'tbl_operating_software'); 
        $this->db->where('foreign_id', $id);
        $this->db->order_by('timestamp', 'DESC');
        return $this->db->get('tbl_audit_trail')->result_array();
    }
}