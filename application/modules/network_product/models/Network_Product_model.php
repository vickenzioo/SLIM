<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Network_product_model extends CI_Model {

    private $table = 'tbl_network_product';
    private $id    = 'product_id';

    // 1. Mapping Filter
    protected $_filter_map = [
        'product_name' => 'p.product_name',
        'product_sla'  => 'p.product_sla',
        'network_name' => 'n.network_name'
    ];

    // Helper Private untuk Query Join (PENTING untuk Filter)
    private function _query_joins() {
		// Tambahkan j.network_product_id dan j.status agar bisa dibaca di View
		$this->db->select('p.*, n.network_name, j.network_product_id, j.status'); 
		$this->db->from('tbl_network_product p');
		$this->db->join('tbl_network_product_junc j', 'p.product_id = j.product_id', 'left');
		$this->db->join('tbl_apps_network n', 'j.network_id = n.network_id', 'left');
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

    // 3. Hitung Total Data (Support Filter)
    public function count_all_products($keyword = null, $filters = []) {
        $this->_query_joins(); // Load Join dulu
        if($keyword) {
            $this->db->group_start();
            $this->db->like('p.product_name', $keyword);
            $this->db->or_like('p.product_sla', $keyword);
            $this->db->or_like('n.network_name', $keyword);
            $this->db->group_end();
        }
        $this->_apply_filters($filters);
        return $this->db->count_all_results();
    }

    // 4. Ambil Data Pagination (Support Filter + SORT BY PRODUCT NAME ASC)
    public function get_products_paginated($limit, $start, $keyword = null, $filters = []) {
        $this->_query_joins(); // Load Join dulu
        if($keyword) {
            $this->db->group_start();
            $this->db->like('p.product_name', $keyword);
            $this->db->or_like('p.product_sla', $keyword);
            $this->db->or_like('n.network_name', $keyword);
            $this->db->group_end();
        }
        
        $this->_apply_filters($filters);
        
        // [PERUBAHAN UTAMA: Sort by Product Name ASC]
		$this->db->order_by('j.status', 'DESC');
        $this->db->order_by('p.product_name', 'ASC');

        if ($limit > 0) {
            $this->db->limit($limit, $start);
        }
        return $this->db->get()->result_array();
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
        $this->_query_joins(); // Wajib Join karena kolom ada di tabel lain (network)
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

    // 6. Export Excel (Support Filter + SORT BY PRODUCT NAME ASC)
    public function get_all_products($keyword = null, $filters = []) {
        $this->_query_joins();
        if($keyword) {
            $this->db->group_start();
            $this->db->like('p.product_name', $keyword);
            $this->db->or_like('p.product_sla', $keyword);
            $this->db->or_like('n.network_name', $keyword);
            $this->db->group_end();
        }
        $this->_apply_filters($filters);
        
        // [PERUBAHAN UTAMA]
		$this->db->order_by('j.status', 'DESC'); 
        $this->db->order_by('p.product_name', 'ASC');
        
        return $this->db->get()->result_array();
    }

    // --- CRUD Standard ---
    public function get_all_networks_reference() {
        $this->db->where('status', 1); // Menampilkan hanya network yang aktif
        $this->db->order_by('network_name', 'ASC');
        return $this->db->get('tbl_apps_network')->result_array();
    }

    public function get_product_networks($product_id) {
        $this->db->select('network_id');
        $this->db->where('product_id', $product_id);
        $result = $this->db->get('tbl_network_product_junc')->result_array();
        return array_column($result, 'network_id');
    }

    public function save_product_networks($product_id, $network_ids) {
        $this->db->where('product_id', $product_id);
        $this->db->delete('tbl_network_product_junc');

        if (!empty($network_ids) && is_array($network_ids)) {
            $data = [];
            foreach ($network_ids as $net_id) {
                $data[] = [
                    'product_id' => $product_id,
                    'network_id' => $net_id
                ];
            }
            if(!empty($data)){
                $this->db->insert_batch('tbl_network_product_junc', $data);
            }
        }
    }

    public function check_duplicate_data($name, $sla, $network_id, $id = null) {
        $this->db->select('p.product_id');
        $this->db->from('tbl_network_product p');
        $this->db->join('tbl_network_product_junc j', 'p.product_id = j.product_id');
        
        $this->db->where('p.product_name', $name);
        $this->db->where('p.product_sla', $sla);
        $this->db->where('j.network_id', $network_id);

        if($id) {
            $this->db->where('p.product_id !=', $id);
        }

        return $this->db->get()->num_rows() > 0;
    }

    public function insert_product($data) { return $this->db->insert($this->table, $data); }
    public function update_product($id, $data) { $this->db->where($this->id, $id); return $this->db->update($this->table, $data); }
    public function delete_product($id) { $this->db->where($this->id, $id); return $this->db->delete($this->table); }
    public function get_by_id($id) { return $this->db->get_where($this->table, [$this->id => $id])->row_array(); }
    
    public function get_network_name_by_id($network_id) {
        if(empty($network_id)) return '-';
        $query = $this->db->get_where('tbl_apps_network', ['network_id' => $network_id]);
        $row = $query->row_array();
        return ($row) ? $row['network_name'] : '-';
    }
	
	public function update_product_status($id, $status) {
        $this->db->where('network_product_id', $id);
        return $this->db->update('tbl_network_product_junc', ['status' => $status]);
    }
	
    public function get_by_id_junc($id) {
        $this->_query_joins();
        $this->db->where('j.network_product_id', $id);
        return $this->db->get()->row_array();
    }
	
	// AJAX Get Related Network
    public function get_related_networks($product_id) {
        return $this->get_product_networks($product_id);
    }
	
}

