<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Operational_Day_model extends CI_Model {

    // 1. Mapping Kolom Filter
    protected $_filter_map = [
        'start_day' => 'start_day',
        'end_day'   => 'end_day',
        'total_day' => 'total_day'
    ];

    // 2. Logic Filter
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
                            // Gunakan TRIM agar data bersih
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
    public function count_all_operational_days($keyword = null, $filters = []) {
        if($keyword) {
            $this->db->group_start(); 
            $this->db->like('start_day', $keyword);
            $this->db->or_like('end_day', $keyword);
            $this->db->or_like('total_day', $keyword);
            $this->db->group_end(); 
        }
        $this->_apply_filters($filters);
        return $this->db->count_all_results('tbl_apps_operational_day');
    }

    // 4. Get Paginated (Support Filter)
    public function get_operational_days_paginated($limit, $start, $keyword = null, $filters = []) {
        if($keyword) {
            $this->db->group_start();
            $this->db->like('start_day', $keyword);
            $this->db->or_like('end_day', $keyword);
            $this->db->or_like('total_day', $keyword);
            $this->db->group_end();
        }
        
        $this->_apply_filters($filters);
        $this->db->order_by('operational_day_id', 'ASC'); 
        
        if ($limit > 0) {
            $this->db->limit($limit, $start);
        }
        
        return $this->db->get('tbl_apps_operational_day')->result_array();
    }

    // 5. Dynamic Dropdown (Cascading)
    public function get_dynamic_options($target_key, $current_filters = []) {
        if(!isset($this->_filter_map[$target_key])) return [];
        $column = $this->_filter_map[$target_key];

        // Hapus filter kolom ini agar dropdown menampilkan semua opsi yang tersedia
        $filters_to_apply = $current_filters;
        if(isset($filters_to_apply[$target_key])) {
            unset($filters_to_apply[$target_key]);
        }

        $this->db->select("DISTINCT TRIM($column) as val", FALSE);
        $this->_apply_filters($filters_to_apply); // Terapkan filter lain

        $this->db->where("$column IS NOT NULL");
        $this->db->where("TRIM($column) !=", ""); 
        $this->db->order_by("val", 'ASC');
        
        $query = $this->db->get('tbl_apps_operational_day');
        
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
    public function get_all_operational_days($keyword = null, $filters = []) {
        if($keyword) {
            $this->db->group_start();
            $this->db->like('start_day', $keyword);
            $this->db->or_like('end_day', $keyword);
            $this->db->or_like('total_day', $keyword);
            $this->db->group_end();
        }
        $this->_apply_filters($filters);
		$this->db->order_by('status', 'DESC'); 
        $this->db->order_by('operational_day_id', 'ASC');
        return $this->db->get('tbl_apps_operational_day')->result_array();
    }

    // --- Fungsi CRUD Standar ---
    public function insert_operational_day($data) { return $this->db->insert('tbl_apps_operational_day', $data); }
    public function update_operational_day($id, $data) { $this->db->where('operational_day_id', $id); return $this->db->update('tbl_apps_operational_day', $data); }
    public function delete_operational_day($id) { $this->db->where('operational_day_id', $id); return $this->db->delete('tbl_apps_operational_day'); }
    
	public function check_duplicate_day($start_day, $end_day, $id = null) {
        $this->db->where('start_day', $start_day);
        $this->db->where('end_day', $end_day);

        if($id) {
            $this->db->where('operational_day_id !=', $id);
        }

        $query = $this->db->get('tbl_apps_operational_day');
        return $query->num_rows() > 0;
    }
	
    public function get_by_id($id) {
        $this->db->where('operational_day_id', $id);
        return $this->db->get('tbl_apps_operational_day')->row_array();
    }

    public function update_status($id, $status) {
        $this->db->where('operational_day_id', $id);
        return $this->db->update('tbl_apps_operational_day', ['status' => $status]);
    }
    
    // (Opsional)
    public function get_audit_trail($id) {
        $this->db->where('table_name', 'tbl_apps_operational_day');
        $this->db->where('foreign_id', $id);
        $this->db->order_by('timestamp', 'DESC');
        return $this->db->get('tbl_audit_trail')->result_array(); 
    }
}