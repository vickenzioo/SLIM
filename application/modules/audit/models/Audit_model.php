<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Audit_model extends CI_Model {

    // Fungsi untuk mencatat aktivitas baru ke tabel audit_trail
    public function insert_log($data) {
        // Menambahkan timestamp lokal secara otomatis saat insert
        $data['timestamp'] = date('Y-m-d H:i:s');
        
        // Sesuaikan nama tabel menjadi tbl_audit_trail sesuai screenshot database Anda
        return $this->db->insert('tbl_audit_trail', $data);
    }

    // Fungsi untuk mengambil riwayat berdasarkan tabel dan ID tertentu
    public function get_logs($table_name, $foreign_id) {
        $this->db->where('table_name', $table_name);
        $this->db->where('foreign_id', $foreign_id);
        $this->db->order_by('timestamp', 'DESC');
        return $this->db->get('tbl_audit_trail')->result_array();
    }

    public function get_audit_logs_paginated($id, $table_name, $limit, $start, $keyword = null) {
        $this->db->select('*');
        $this->db->from('tbl_audit_trail');
        $this->db->where('foreign_id', $id);
        $this->db->where('table_name', $table_name);

        if (!empty($keyword)) {
            $this->db->group_start();
            $this->db->like('username', $keyword);
            $this->db->or_like('action', $keyword);
            $this->db->or_like('field_name', $keyword); // Tambahkan ini
            $this->db->or_like('old_value', $keyword);  // Tambahkan ini
            $this->db->or_like('new_value', $keyword);  // Tambahkan ini
            $this->db->or_like('reason', $keyword);
            $this->db->or_like('timestamp', $keyword); // Tambahkan ini agar bisa cari berdasarkan tanggal
            $this->db->group_end();
        }

        $this->db->order_by('timestamp', 'DESC');
        $this->db->limit($limit, $start); // Inti dari pagination
        return $this->db->get()->result_array();
    }

    public function get_audit_logs($id, $keyword = null, $table_name = null) {
        $this->db->select('*'); 
        $this->db->from('tbl_audit_trail'); 
        
        $this->db->where('foreign_id', $id); 
        if ($table_name) {
            $this->db->where('table_name', $table_name);
        }

        if (!empty($keyword)) {
            $keyword = trim($keyword); // Tambahkan trim agar spasi di ujung tidak mengganggu
            $this->db->group_start();
            $this->db->like('username', $keyword);
            $this->db->or_like('action', $keyword);
            $this->db->or_like('field_name', $keyword); 
            
            // TAMBAHKAN BARIS INI: Agar "Network Name" cocok dengan "network_name"
            $this->db->or_like("REPLACE(field_name, '_', ' ')", $keyword, 'both', FALSE);
            
            $this->db->or_like('old_value', $keyword);  
            $this->db->or_like('new_value', $keyword);  
            $this->db->or_like('reason', $keyword);
            $this->db->or_like('timestamp', $keyword); 
            $this->db->group_end();
        }

        $this->db->order_by('timestamp', 'DESC');
        return $this->db->get()->result_array();
    }

    public function get_target_name($id, $type = 'deployment') {
        // Cek tipe tabel untuk menentukan kolom identitas yang diambil
        if ($type == 'database' || $type == 'tbl_database_master') {
            $this->db->select('database_name as name');
            $this->db->from('tbl_database_master');
            $this->db->where('database_id', $id);
        } elseif ($type == 'network' || $type == 'tbl_apps_network') {
            $this->db->select('network_name as name');
            $this->db->from('tbl_apps_network');
            $this->db->where('network_id', $id);
        } 
        // --- TAMBAHKAN CODE DI BAWAH INI ---
        elseif ($type == 'tbl_network_product') {
            $this->db->select('product_name as name');
            $this->db->from('tbl_network_product');
            $this->db->where('product_id', $id);
        } elseif ($type == 'tbl_network_provider') {
            $this->db->select('provider_name as name');
            $this->db->from('tbl_network_provider');
            $this->db->where('provider_id', $id);
        } 
        // --- BATAS TAMBAHAN ---
        elseif ($type == 'category' || $type == 'tbl_apps_category') {
            $this->db->select('category_name as name');
            $this->db->from('tbl_apps_category');
            $this->db->where('category_id', $id);
        } elseif ($type == 'operating software' || $type == 'tbl_operating_software') {
            $this->db->select('operating_software_name as name');
            $this->db->from('tbl_operating_software');
            $this->db->where('operating_software_id', $id);
        } elseif ($type == 'deployment' || $type == 'tbl_apps_deployment') {
            $this->db->select('deployment_model as name'); 
            $this->db->from('tbl_apps_deployment');
            $this->db->where('deployment_id', $id);
        } elseif ($type == 'tbl_apps_operational_day') {
            $this->db->select("CONCAT(start_day, ' - ', end_day) as name");
            $this->db->from('tbl_apps_operational_day');
            $this->db->where('operational_day_id', $id);
        } elseif ($type == 'tbl_apps_operational_hour') {
            $this->db->select("CONCAT(start_time, ' - ', end_time) as name");
            $this->db->from('tbl_apps_operational_hour');
            $this->db->where('operational_hour_id', $id);
        } elseif ($type == 'tbl_user_role') {
            $this->db->select('b.username as name');
            $this->db->from('tbl_user_role a');
            $this->db->join('users b', 'a.user_id = b.id'); // Join untuk ambil username
            $this->db->where('a.user_role_id', $id);
        } else {
            // Fallback default
            $this->db->select('deployment_model as name');
            $this->db->from('tbl_apps_deployment');
            $this->db->where('deployment_id', $id);
        }

        $query = $this->db->get();
        return ($query->num_rows() > 0) ? $query->row()->name : "Data Not Found";
    }

}