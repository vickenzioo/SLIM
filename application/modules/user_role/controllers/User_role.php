<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User_role extends CI_Controller {

    public function __construct() {
        parent::__construct();
        date_default_timezone_set('Asia/Jakarta');
        if (!$this->session->userdata('email')) {
             redirect('auth');
        }
        $this->load->model('User_role_model');
        $this->load->model('audit/Audit_model');
    }

    public function index() {
        $this->load->library('pagination');
        $keyword = $this->security->xss_clean($this->input->get('keyword'));
        $filters = $this->input->get('filter'); 

        $config['base_url'] = base_url('user_role/index');
        
        $params = [];
        if($keyword) $params['keyword'] = $keyword;
        if($filters) $params['filter'] = $filters;
        $config['suffix'] = !empty($params) ? '?' . http_build_query($params, '', '&') : '';
        $config['first_url'] = $config['base_url'] . $config['suffix'];

        $config['total_rows'] = $this->User_role_model->count_all_user_roles($keyword, $filters);
        $config['per_page'] = 10; 
        
        $config['enable_query_strings'] = TRUE;
        $config['page_query_string'] = TRUE;
        $config['query_string_segment'] = 'per_page';
        $config['reuse_query_string'] = TRUE;

        // Styling Pagination
        $config['full_tag_open']    = '<ul class="pagination pagination-sm m-0 float-right">';
        $config['full_tag_close']   = '</ul>';
        $config['first_link']       = 'First';
        $config['first_tag_open']   = '<li class="page-item">';
        $config['first_tag_close']  = '</li>';
        $config['last_link']        = 'Last';
        $config['last_tag_open']    = '<li class="page-item">';
        $config['last_tag_close']   = '</li>';
        $config['next_link']        = '&raquo;';
        $config['next_tag_open']    = '<li class="page-item">';
        $config['next_tag_close']   = '</li>';
        $config['prev_link']        = '&laquo;';
        $config['prev_tag_open']    = '<li class="page-item">';
        $config['prev_tag_close']   = '</li>';
        $config['cur_tag_open']     = '<li class="page-item active"><a class="page-link" href="#">';
        $config['cur_tag_close']    = '</a></li>';
        $config['num_tag_open']     = '<li class="page-item">';
        $config['num_tag_close']    = '</li>';
        $config['attributes']       = array('class' => 'page-link');

        $this->pagination->initialize($config);

        $start = $this->input->get('per_page');
        
        // Ambil Data Filtered
        $user_roles = $this->User_role_model->get_user_roles_paginated($config['per_page'], $start, $keyword, $filters);
        // [PERBAIKAN]: Pastikan selalu array agar view tidak offset null
        $data['user_roles'] = !empty($user_roles) ? $user_roles : [];
        
        // Load Options Filter
        $data['opt_username']  = $this->User_role_model->get_dynamic_options('username', $filters);
        $data['opt_email']      = $this->User_role_model->get_dynamic_options('email', $filters);
        $data['opt_role_name'] = $this->User_role_model->get_dynamic_options('role_name', $filters);

        // Data Lain
        $data['users'] = $this->User_role_model->get_all_users();
        $data['roles'] = $this->User_role_model->get_all_roles();
        $data['users_no_role'] = $this->User_role_model->get_users_no_role();

        $data['keyword'] = $keyword;
        $data['selected_filters'] = $filters;
        $data['pagination'] = $this->pagination->create_links();
        $data['total_rows'] = $config['total_rows'];

        $this->load->view('user_role_view', $data);
    }

    public function add_user() {
        $username = $this->security->xss_clean($this->input->post('username'));
        $email = $this->security->xss_clean($this->input->post('email'));
        $password = $this->input->post('password');

        if (empty($username) || empty($email) || empty($password)) {
            $this->session->set_flashdata('error', 'Semua field user wajib diisi!');
            redirect('user_role'); return;
        }

        // 1. Cek apakah Username ATAU Email sudah terdaftar
        $check = $this->db->group_start()
                          ->where('username', $username)
                          ->or_where('email', $email)
                          ->group_end()
                          ->get('users')->num_rows();

        if ($check > 0) {
            $this->session->set_flashdata('error', 'Gagal! Username atau Email sudah digunakan.');
            redirect('user_role'); return;
        }

        $data_user = [
            'username' => $username,
            'email'    => $email,
            'password' => password_hash($password, PASSWORD_DEFAULT), 
            'status'   => 1, // [TAMBAHAN]: Set status otomatis aktif saat daftar
            'created_at' => date("Y-m-d H:i:s")
        ];

        if ($this->db->insert('users', $data_user)) {
            $new_id = $this->db->insert_id();
            
            // Audit Log
            $this->Audit_model->insert_log([
                'username' => $this->session->userdata('username'),
                'action' => 'ADD',
                'table_name' => 'users',
                'foreign_id' => $new_id,
                'field_name' => 'Account Creation',
                'old_value' => '-',
                'new_value' => $username,
                'reason' => 'Created from User Role dropdown',
                'timestamp' => date('Y-m-d H:i:s')
            ]);

            $this->session->set_flashdata('success', 'User berhasil dibuat! Silakan pilih "Assign Role" untuk memberikan akses.');
        } else {
            $this->session->set_flashdata('error', 'Database Error: Gagal menyimpan user.');
        }
        redirect('user_role');
    }


    public function export() {
        $this->Audit_model->insert_log([ 'username' => $this->session->userdata('username'), 'action' => 'EXPORT', 'table_name' => 'tbl_user_role', 'foreign_id' => 0, 'field_name' => '-', 'old_value' => '-', 'new_value' => '-', 'reason' => 'Export Data', 'timestamp' => date('Y-m-d H:i:s') ]);
        
        $keyword = $this->security->xss_clean($this->input->get('keyword'));
        $filters = $this->input->get('filter');

        $data['user_roles'] = $this->User_role_model->get_all_user_roles($keyword, $filters);

        header("Content-type: application/vnd-ms-excel");
        header("Content-Disposition: attachment; filename=User_Role_".date('Y-m-d').".xls");
        header("Pragma: no-cache");
        header("Expires: 0");

        $this->load->view('user_role_export', $data);
    }

    public function save() {
        $id = $this->security->xss_clean($this->input->post('id'));
        $user_id = $this->input->post('user_id');
        $role_id = $this->input->post('role_id');
        $reason = $this->security->xss_clean($this->input->post('reason'));
        $username_session = $this->session->userdata('username');
        $userId_session = $this->session->userdata('user_id');

        if ($id) {
            // EDIT LOGIC
            if(empty($reason)){
                $this->session->set_flashdata('error', 'Gagal update: Alasan wajib diisi!');
                redirect('user_role'); return;
            }

            $oldData = $this->User_role_model->get_user_role_by_id($id);
            $update_data = [
                'role_id' => $role_id,
                'modified_by' => $userId_session,
                'modified_at' => date("Y-m-d H:i:s")
            ];

            if ($this->User_role_model->update($id, $update_data)) {
                $this->Audit_model->insert_log([
                    'username'   => $username_session,
                    'action'     => 'EDIT',
                    'table_name' => 'tbl_user_role',
                    'foreign_id' => $id,
                    'field_name' => 'role_id',
                    'old_value'  => $oldData['role_id'],
                    'new_value'  => $role_id,
                    'reason'     => $reason,
                    'timestamp'  => date('Y-m-d H:i:s')
                ]);
                $this->session->set_flashdata('success', 'Data berhasil diperbarui');
            }
        } else {
            // ADD LOGIC
            $insert_data = [
                'user_id' => $user_id,
                'role_id' => $role_id,
                'created_by' => $userId_session,
                'created_at' => date("Y-m-d H:i:s"),
                'status' => 1
            ];
            $this->User_role_model->insert($insert_data);
            $new_id = $this->db->insert_id();
            
            $this->Audit_model->insert_log([
                'username'   => $username_session,
                'action'     => 'ADD',
                'table_name' => 'tbl_user_role',
                'foreign_id' => $new_id,
                'field_name' => 'user_role_entry',
                'old_value'  => '-',
                'new_value'  => "User ID: $user_id, Role ID: $role_id",
                'reason'     => 'Initial Creation',
                'timestamp'  => date('Y-m-d H:i:s')
            ]);
            $this->session->set_flashdata('success', 'Data berhasil ditambahkan');
        }
        redirect('user_role');
    }


    public function update_status() {
        $id     = $this->input->post('id');     
        $status = $this->input->post('status'); 
        $reason = $this->security->xss_clean($this->input->post('reason'));

        if (!empty($id)) {
            // Ambil data user_role untuk mendapatkan user_id yang asli dari tabel users
            $old_data = $this->User_role_model->get_by_id($id); 
            $username_session = $this->session->userdata('username');

            if (!$old_data) {
                echo json_encode(['success' => false, 'message' => 'Data tidak ditemukan']);
                return;
            }

            // PERBAIKAN: Gunakan id dari tabel users (u.id) karena status ada di tabel users
            $update = $this->User_role_model->update_user_status($old_data['id'], $status);

            if ($update) {
                $this->Audit_model->insert_log([
                    'username'   => $username_session,
                    'action'     => ($status == 0) ? 'DEACTIVATE' : 'ACTIVATE',
                    'table_name' => 'tbl_user_role',
                    'foreign_id' => $id,
                    'field_name' => 'status',
                    'old_value'  => ($status == 0) ? '1' : '0',
                    'new_value'  => ($status == 0) ? '0' : '1',
                    'reason'     => !empty($reason) ? $reason : 'Toggle Status',
                    'timestamp'  => date('Y-m-d H:i:s')
                ]);

                $msg = ($status == 0) ? "Data '" . $old_data['username'] . "' berhasil dinonaktifkan" : "Data '" . $old_data['username'] . "' berhasil diaktifkan kembali";
                echo json_encode(['success' => true, 'message' => $msg]);
            } else {
                echo json_encode(['success' => false, 'message' => 'Gagal mengubah status']);
            }
        } else {
            echo json_encode(['success' => false, 'message' => 'ID tidak ditemukan']);
        }
    }


    public function audit($id) {
        $this->load->library('pagination');
        $user_role_data = $this->User_role_model->get_by_id($id);
        
        // [PERBAIKAN]: Tambahkan pengecekan null pada target_name agar tidak error offset
        if ($user_role_data) {
            $username = isset($user_role_data['username']) ? $user_role_data['username'] : 'Unknown';
            $role_name = isset($user_role_data['role_name']) ? $user_role_data['role_name'] : 'No Role';
            $target_name = $username . ' (' . $role_name . ')';
        } else {
            $target_name = 'Record Not Found (ID: '.$id.')';
        }

        $keyword = $this->security->xss_clean($this->input->get('keyword'));
        $table_name = 'tbl_user_role';
        $config['base_url'] = base_url('user_role/audit/' . $id);
        
        $audit_logs_all = $this->Audit_model->get_audit_logs($id, $keyword, $table_name);
        $config['total_rows'] = !empty($audit_logs_all) ? count($audit_logs_all) : 0;
        
        $config['per_page'] = 5; 
        $config['page_query_string'] = TRUE;
        $config['query_string_segment'] = 'per_page';
        $config['reuse_query_string'] = TRUE;
        $config['full_tag_open'] = '<ul class="pagination pagination-sm m-0 float-right">';
        $config['full_tag_close'] = '</ul>';
        $config['cur_tag_open'] = '<li class="page-item active"><a class="page-link" href="#">';
        $config['cur_tag_close'] = '</a></li>';
        $config['num_tag_open'] = '<li class="page-item">';
        $config['num_tag_close'] = '</li>';
        $config['attributes'] = array('class' => 'page-link');
        $this->pagination->initialize($config);
        
        $start = $this->input->get('per_page');
        $audit_logs = $this->Audit_model->get_audit_logs_paginated($id, $table_name, $config['per_page'], $start, $keyword);
        
        $data['keyword'] = $keyword;
        $data['target_name'] = $target_name; 
        $data['back_url'] = 'user_role'; 
        $data['export_url'] = base_url('audit/export_excel/tbl_user_role/' . $id);
        $data['audit_data'] = !empty($audit_logs) ? $audit_logs : [];
        $data['pagination'] = $this->pagination->create_links();
        $data['total_rows'] = $config['total_rows'];
        $this->load->view('audit/audit_view', $data);
    }
}