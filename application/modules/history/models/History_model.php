<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class History_model extends CI_Model {

    // 1. Mapping Filter (Sesuai kolom tabel tbl_audit_trail)
    protected $_filter_map = [
        'timestamp'  => 'timestamp',
        'username'   => 'username',
        'action'     => 'action',
        'table_name' => 'table_name', // Page Name
        'field_name' => 'field_name',
        'old_value'  => 'old_value',
        'new_value'  => 'new_value',
        'reason'     => 'reason'
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

    private function _apply_keyword($keyword) {
        if($keyword) {
            $this->db->group_start();
            
            // --- PERBAIKAN: Logika Pintar untuk User Role ---
            // Jika user mengetik "User Role", kita arahkan search ke nama tabel aslinya
            if (stripos('User Role', $keyword) !== false) {
                $this->db->like('table_name', 'tbl_user_role');
                $this->db->or_like('table_name', 'users');
            }
            // -----------------------------------------------

            $this->db->or_like('table_name', $keyword); // Page Name (Normal search)
            $this->db->or_like('field_name', $keyword);
            $this->db->or_like('action', $keyword);
            $this->db->or_like('username', $keyword);
            $this->db->or_like('reason', $keyword);
            $this->db->or_like('old_value', $keyword);
            $this->db->or_like('new_value', $keyword);
            $this->db->group_end();
        }
    }

    // 3. Count All (Support Filter)
    public function count_all_historys($keyword = null, $filters = []) {
        $this->_apply_keyword($keyword);
        $this->_apply_filters($filters);
        return $this->db->count_all_results('tbl_audit_trail');
    }

    // 4. Get Paginated (Support Filter + SORT TIMESTAMP DESC)
    public function get_historys_paginated($limit, $start, $keyword = null, $filters = []) {
        $this->_apply_keyword($keyword);
        $this->_apply_filters($filters);
        
        $this->db->order_by('timestamp', 'DESC'); 
        
        if ($limit > 0) {
            $this->db->limit($limit, $start);
        }
        return $this->db->get('tbl_audit_trail')->result_array();
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
        
        $query = $this->db->get('tbl_audit_trail');
        
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
    public function get_all_historys($keyword = null, $filters = []) {
        $this->_apply_keyword($keyword);
        $this->_apply_filters($filters);
        $this->db->order_by('timestamp', 'DESC');
        return $this->db->get('tbl_audit_trail')->result_array();
    }
}