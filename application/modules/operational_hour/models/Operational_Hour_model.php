<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Operational_Hour_model extends CI_Model {

    // 1. Mapping Input Filter -> Kolom Database
    protected $_filter_map = [
        'start_time' => 'start_time',
        'end_time'   => 'end_time',
        'total_hour' => 'total_hour'
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
    public function count_all_operational_hours($keyword = null, $filters = []) {
        if($keyword) {
            $this->db->group_start();
            $this->db->like('start_time', $keyword);
            $this->db->or_like('end_time', $keyword);
            $this->db->or_like('total_hour', $keyword);
            $this->db->group_end();
        }
        $this->_apply_filters($filters);
        return $this->db->count_all_results('tbl_apps_operational_hour');
    }

    // 4. Get Paginated
    public function get_operational_hours_paginated($limit, $start, $keyword = null, $filters = []) {
        if($keyword) {
            $this->db->group_start();
            $this->db->like('start_time', $keyword);
            $this->db->or_like('end_time', $keyword);
            $this->db->or_like('total_hour', $keyword);
            $this->db->group_end();
        }
        
        $this->_apply_filters($filters);
        $this->db->order_by('operational_hour_id', 'ASC');
        
        if ($limit > 0) {
            $this->db->limit($limit, $start);
        }
        return $this->db->get('tbl_apps_operational_hour')->result_array();
    }

    // 5. Dynamic Dropdown
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
        
        $query = $this->db->get('tbl_apps_operational_hour');
        
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

    // [PERBAIKAN DISINI] Tambahkan parameter $filters
    public function get_all_operational_hours($keyword = null, $filters = []) {
        if($keyword) {
            $this->db->group_start();
            $this->db->like('start_time', $keyword);
            $this->db->or_like('end_time', $keyword);
            $this->db->or_like('total_hour', $keyword);
            $this->db->group_end();
        }
        
        // Terapkan Filter agar data export sesuai tampilan tabel
        $this->_apply_filters($filters);
		$this->db->order_by('status', 'DESC'); 
        $this->db->order_by('operational_hour_id', 'ASC');
        return $this->db->get('tbl_apps_operational_hour')->result_array();
    }

    public function insert_operational_hour($data) { return $this->db->insert('tbl_apps_operational_hour', $data); }
    public function update_operational_hour($id, $data) { $this->db->where('operational_hour_id', $id); return $this->db->update('tbl_apps_operational_hour', $data); }
    public function delete_operational_hour($id) { $this->db->where('operational_hour_id', $id); return $this->db->delete('tbl_apps_operational_hour'); }
    
    public function check_duplicate_hour($start_time, $end_time, $id = null) {
        $this->db->where('start_time', $start_time);
        $this->db->where('end_time', $end_time);
        if($id) { $this->db->where('operational_hour_id !=', $id); }
        return $this->db->get('tbl_apps_operational_hour')->num_rows() > 0;
    }
    
    public function get_by_id($id) {
        $this->db->where('operational_hour_id', $id);
        return $this->db->get('tbl_apps_operational_hour')->row_array();
    }
	
	public function update_status($id, $status) {
        $this->db->where('operational_hour_id', $id);
        return $this->db->update('tbl_apps_operational_hour', ['status' => $status]);
    }
    
    // (Opsional)
    public function get_audit_trail($id) {
        $this->db->where('table_name', 'tbl_apps_operational_hour');
        $this->db->where('foreign_id', $id);
        $this->db->order_by('timestamp', 'DESC');
        return $this->db->get('tbl_audit_trail')->result_array(); 
    }
}