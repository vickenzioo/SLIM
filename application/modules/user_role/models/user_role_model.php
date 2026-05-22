<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User_role_model extends CI_Model {

    // 1. Mapping Filter
    protected $_filter_map = [
        'username'  => 'u.username',
        'email'     => 'u.email',
        'role_name' => 'r.role_name'
    ];

    // Helper Private untuk Query Join
    private function _query_joins() {
        // Tambahkan u.status agar tersedia di result array
        $this->db->select('ur.*, u.username, u.email, u.status, r.role_name'); 
        $this->db->from('tbl_user_role ur');
        $this->db->join('users u', 'ur.id = u.id', 'left');
        $this->db->join('tbl_role r', 'ur.role_id = r.role_id', 'left');
    }

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
    public function count_all_user_roles($keyword = null, $filters = []) {
        $this->_query_joins();
        if($keyword) {
            $this->db->group_start();
            $this->db->like('u.username', $keyword);
            $this->db->or_like('u.email', $keyword);
            $this->db->or_like('r.role_name', $keyword);
            $this->db->group_end();
        }
        $this->_apply_filters($filters);
        return $this->db->count_all_results();
    }

    // 4. Get Paginated (Support Filter + SORT BY ROLE THEN EMAIL)
    public function get_user_roles_paginated($limit, $start, $keyword = null, $filters = []) {
        $this->_query_joins();
        if($keyword) {
            $this->db->group_start();
            $this->db->like('u.username', $keyword);
            $this->db->or_like('u.email', $keyword);
            $this->db->or_like('r.role_name', $keyword);
            $this->db->group_end();
        }
        $this->_apply_filters($filters);
        
        $this->db->order_by('u.status', 'DESC'); 
        $this->db->order_by('u.username', 'ASC');

        if ($limit > 0) {
            $this->db->limit($limit, $start);
        }
        return $this->db->get()->result_array();
    }

    // Tambahkan fungsi update status khusus ke table users
    public function update_user_status($user_id, $status) {
        $this->db->where('id', $user_id);
        return $this->db->update('users', ['status' => $status]);
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
        $this->_query_joins(); 
        $this->_apply_filters($filters_to_apply);

        $this->db->where("$column IS NOT NULL");
        $this->db->where("TRIM($column) !=", ""); 
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

    // 6. Get All for Export (Support Filter)
    public function get_all_user_roles($keyword = null, $filters = []) {
        $this->_query_joins();
        
        // Tambahan join untuk creator/modifier khusus export
        $this->db->join('users creator', 'ur.created_by = creator.id', 'left');
        $this->db->join('users modifier', 'ur.modified_by = modifier.id', 'left');
        
        // Pastikan u.password juga terambil jika ingin ditampilkan di export
        $this->db->select('u.password, creator.username as creator_name, modifier.username as modifier_name');

        if($keyword) {
            $this->db->group_start();
            $this->db->like('u.username', $keyword);
            $this->db->or_like('u.email', $keyword);
            $this->db->or_like('r.role_name', $keyword);
            $this->db->group_end();
        }
        $this->_apply_filters($filters);
        
        $this->db->order_by('u.status', 'DESC'); 
        $this->db->order_by('u.username', 'ASC');
        
        return $this->db->get()->result_array();
    }

    // --- CRUD Helper & Standard Functions ---
    public function check_duplicate($user_id, $id = null) {
        $this->db->where('id', $user_id); // 'id' di tabel tbl_user_role adalah foreign key id user
        if($id) {
            $this->db->where('user_role_id !=', $id);
        }
        return $this->db->get('tbl_user_role')->num_rows() > 0;
    }

    public function get_users_no_role() {
        $this->db->select('u.id, u.username, u.email');
        $this->db->from('users u');
        $this->db->join('tbl_user_role ur', 'u.id = ur.id', 'left');
        $this->db->where('ur.id', NULL); 
        return $this->db->get()->result_array();
    }

    public function get_all_users($current_user_id = null) {
        $this->db->select('u.id, u.username, u.email');
        $this->db->from('users u');
        $this->db->join('tbl_user_role ur', 'u.id = ur.id', 'left');
        
        $this->db->group_start();
        $this->db->where('ur.id', NULL); // User yang belum punya role
        if ($current_user_id) {
            $this->db->or_where('u.id', $current_user_id); // Termasuk user yang sedang diedit
        }
        $this->db->group_end();
        
        $this->db->order_by('u.status', 'DESC');
        $this->db->order_by('u.username', 'ASC');
        return $this->db->get()->result_array();
    }

    public function get_user_by_id($id) {
        return $this->db->get_where('users', ['id' => $id])->row_array();
    }   

    public function get_all_roles() { 
        return $this->db->get('tbl_role')->result_array(); 
    }

    public function insert($data) { 
        // 1. Sinkronisasi key 'user_id' menjadi 'id' agar sesuai database
        if (isset($data['user_id'])) {
            $data['id'] = $data['user_id'];
            unset($data['user_id']);
        }

        // 2. PROTEKSI: Buang 'status' karena kolom ini tidak ada di tbl_user_role
        // Kolom status hanya milik table 'users'
        if (isset($data['status'])) {
            unset($data['status']);
        }

        return $this->db->insert('tbl_user_role', $data); 
    }

    public function update($id, $data) { 
        // 1. Sinkronisasi key 'user_id' menjadi 'id'
        if (isset($data['user_id'])) {
            $data['id'] = $data['user_id'];
            unset($data['user_id']);
        }

        // 2. PROTEKSI: Buang 'status' agar tidak error Unknown Column
        if (isset($data['status'])) {
            unset($data['status']);
        }

        $this->db->where('user_role_id', $id); 
        return $this->db->update('tbl_user_role', $data); 
    }

    public function delete($id) { 
        $this->db->where('user_role_id', $id); return $this->db->delete('tbl_user_role'); 
    }
    
    public function get_by_id($id) {
        $this->_query_joins();
        $this->db->where('ur.user_role_id', $id);
        return $this->db->get()->row_array();
    }
}