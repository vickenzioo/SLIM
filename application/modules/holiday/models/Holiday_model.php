<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Holiday_model extends CI_Model {

    public function get_all_holidays() {
        return $this->db->get('tbl_holiday')->result();
    }

    public function get_holiday_by_id($id) {
        return $this->db
            ->where('Holiday_ID', $id)
            ->get('tbl_holiday')
            ->row();
    }

    public function insert_holiday($data) {
        return $this->db->insert('tbl_holiday', $data);
    }

    public function count_all_holidays() {
        return $this->db->count_all('tbl_holiday');
    }

    public function update_holiday($id, $data) {
        $this->db->where('Holiday_ID', $id);
        return $this->db->update('tbl_holiday', $data);
    }

    public function delete_holiday($id) {
        $this->db->where('Holiday_ID', $id);
        return $this->db->delete('tbl_holiday');
    }

    public function get_audit_trail($id) {
        $this->db->select('*');
        $this->db->from('tbl_audit_trail'); // Menggunakan tabel audit yang sama
        
        $this->db->where('table_name', 'tbl_holiday'); // Filter khusus tabel holiday
        $this->db->where('foreign_id', $id);
        $this->db->order_by('timestamp', 'DESC');
        
        return $this->db->get()->result_array();
    }
}
