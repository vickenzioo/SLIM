<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Auth_model extends CI_Model {

    public function get_user_by_email($email) {
        $this->db->select('users.*, tbl_role.role_name');
        $this->db->from('users');
        $this->db->join('tbl_user_role', 'tbl_user_role.id = users.id', 'left');
        $this->db->join('tbl_role', 'tbl_role.role_id = tbl_user_role.role_id', 'left');
        $this->db->where('users.email', $email);
        return $this->db->get()->row_array();
    }
}