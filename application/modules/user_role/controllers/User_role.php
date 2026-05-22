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
            $username_session = $this->session->userdata('username');
            $timestamp = date('Y-m-d H:i:s');
            
            // --- AUDIT LOG 1: Username ---
            $this->Audit_model->insert_log([
                'username'   => $username_session,
                'action'     => 'ADD',
                'table_name' => 'users',
                'foreign_id' => $new_id,
                'field_name' => 'Username',
                'old_value'  => '-',
                'new_value'  => $username,
                'reason'     => 'Initial Creation',
                'timestamp'  => $timestamp
            ]);

            // --- AUDIT LOG 2: Email ---
            $this->Audit_model->insert_log([
                'username'   => $username_session,
                'action'     => 'ADD',
                'table_name' => 'users',
                'foreign_id' => $new_id,
                'field_name' => 'Email',
                'old_value'  => '-',
                'new_value'  => $email,
                'reason'     => 'Initial Creation',
                'timestamp'  => $timestamp
            ]);

            // --- AUDIT LOG 3: Password ---
            $this->Audit_model->insert_log([
                'username'   => $username_session,
                'action'     => 'ADD',
                'table_name' => 'users',
                'foreign_id' => $new_id,
                'field_name' => 'Password',
                'old_value'  => '-',
                'new_value'  => '********', // Password disamarkan
                'reason'     => 'Initial Creation',
                'timestamp'  => $timestamp
            ]);

            $this->session->set_flashdata('success', 'User berhasil dibuat! Silahkan assign role.');
        } else {
            $this->session->set_flashdata('error', 'Database Error: Gagal menyimpan user.');
        }
        redirect('user_role');
    }


    public function export() {
        $this->Audit_model->insert_log([ 
            'username' => $this->session->userdata('username'), 
            'action' => 'EXPORT', 
            'table_name' => 'tbl_user_role', 
            'foreign_id' => 0, 
            'field_name' => '-', 
            'old_value' => '-', 
            'new_value' => '-', 
            'reason' => 'Export Data', 
            'timestamp' => date('Y-m-d H:i:s') 
        ]);
        
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
        
        // Tangkap input username & email dari modal Edit
        $username_input = $this->security->xss_clean($this->input->post('username'));
        $email_input = $this->security->xss_clean($this->input->post('email'));
        
        $reason = $this->security->xss_clean($this->input->post('reason'));
        $username_session = $this->session->userdata('username');
        $userId_session = $this->session->userdata('user_id');

        if ($id) {
            // =========================
            // EDIT LOGIC
            // =========================
            if(empty($reason)){
                $this->session->set_flashdata('error', 'Gagal update: Alasan wajib diisi!');
                redirect('user_role'); return;
            }

            // 1. Cek Duplikat Username / Email di tabel users (kecuali milik user ini sendiri)
            $check_duplicate = $this->db->where('id !=', $user_id)
                                        ->group_start()
                                        ->where('username', $username_input)
                                        ->or_where('email', $email_input)
                                        ->group_end()
                                        ->get('users')->num_rows();

            if ($check_duplicate > 0) {
                $this->session->set_flashdata('error', 'Username atau Email sudah digunakan oleh user lain!');
                redirect('user_role'); return;
            }

            // Ambil data lama untuk dicocokkan
            $oldRoleData = $this->User_role_model->get_by_id($id); 
            $oldUserData = $this->db->get_where('users', ['id' => $user_id])->row_array();

            // 2. Cek apakah TIDAK ADA perubahan sama sekali
            if ($oldRoleData['role_id'] == $role_id && 
                trim($oldUserData['username']) == trim($username_input) && 
                trim($oldUserData['email']) == trim($email_input)) {
                
                $this->session->set_flashdata('error', 'Gagal simpan: Tidak ada perubahan data.');
                redirect('user_role'); return;
            }

            $timestamp = date('Y-m-d H:i:s');

            // 3. Update Tabel Users & Log (Jika username atau email berubah)
            if (trim($oldUserData['username']) != trim($username_input) || trim($oldUserData['email']) != trim($email_input)) {
                $this->db->where('id', $user_id);
                $this->db->update('users', [
                    'username' => $username_input,
                    'email'    => $email_input
                ]);

                if (trim($oldUserData['username']) != trim($username_input)) {
                    $this->Audit_model->insert_log([
                        'username'   => $username_session,
                        'action'     => 'EDIT',
                        'table_name' => 'users',
                        'foreign_id' => $user_id, // Ikat ke user_id
                        'field_name' => 'Username',
                        'old_value'  => $oldUserData['username'],
                        'new_value'  => $username_input,
                        'reason'     => $reason,
                        'timestamp'  => $timestamp
                    ]);
                }

                if (trim($oldUserData['email']) != trim($email_input)) {
                    $this->Audit_model->insert_log([
                        'username'   => $username_session,
                        'action'     => 'EDIT',
                        'table_name' => 'users',
                        'foreign_id' => $user_id, // Ikat ke user_id
                        'field_name' => 'Email',
                        'old_value'  => $oldUserData['email'],
                        'new_value'  => $email_input,
                        'reason'     => $reason,
                        'timestamp'  => $timestamp
                    ]);
                }
            }

            // 4. Update Tabel tbl_user_role & Log (Role Assignment)
            $update_data = [
                'role_id' => $role_id,
                'modified_by' => $userId_session, 
                'modified_at' => $timestamp
            ];

            if ($this->User_role_model->update($id, $update_data)) {
                if ($oldRoleData['role_id'] != $role_id) {
                    
                    // --- PERBAIKAN: Ambil nama role baru dari DB untuk di Log ---
                    $newRoleData = $this->db->get_where('tbl_role', ['role_id' => $role_id])->row_array();
                    $newRoleName = $newRoleData ? $newRoleData['role_name'] : $role_id;

                    $this->Audit_model->insert_log([
                        'username'   => $username_session,
                        'action'     => 'EDIT',
                        'table_name' => 'tbl_user_role',
                        'foreign_id' => $id, // Menggunakan user_role_id
                        'field_name' => 'Role', 
                        'old_value'  => isset($oldRoleData['role_name']) ? $oldRoleData['role_name'] : $oldRoleData['role_id'],
                        'new_value'  => $newRoleName,
                        'reason'     => $reason,
                        'timestamp'  => $timestamp
                    ]);
                }
                $this->session->set_flashdata('success', 'Data berhasil diperbarui');
            }
        } else {
            // =========================
            // ADD LOGIC (Assign Role)
            // =========================
            
            // Pengecekan Duplikat Data (Mencegah user memiliki role ganda)
            if ($this->User_role_model->check_duplicate($user_id)) {
                $this->session->set_flashdata('error', 'User tersebut sudah di-assign role! Gagal menyimpan.');
                redirect('user_role'); return; 
            }

            $insert_data = [
                'user_id' => $user_id,
                'role_id' => $role_id,
                'created_by' => $userId_session,
                'created_at' => date("Y-m-d H:i:s"),
                'status' => 1
            ];
            $this->User_role_model->insert($insert_data);
            $new_id = $this->db->insert_id();
            
            // --- PERBAIKAN: Ambil nama role untuk ditampilkan di Log ---
            $roleData = $this->db->get_where('tbl_role', ['role_id' => $role_id])->row_array();
            $roleName = $roleData ? $roleData['role_name'] : $role_id;

            $this->Audit_model->insert_log([
                'username'   => $username_session,
                'action'     => 'ADD',
                'table_name' => 'tbl_user_role',
                'foreign_id' => $new_id,
                'field_name' => 'User Role', 
                'old_value'  => '-',
                'new_value'  => $roleName, // Akan mencetak misal: "IT Dev"
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
        
        $user_id = 0;
        if ($user_role_data) {
            $username = isset($user_role_data['username']) ? $user_role_data['username'] : 'Unknown';
            $role_name = isset($user_role_data['role_name']) ? $user_role_data['role_name'] : 'No Role';
            $target_name = $username . ' (' . $role_name . ')';
            $user_id = isset($user_role_data['id']) ? $user_role_data['id'] : 0; 
        } else {
            $target_name = 'Record Not Found (ID: '.$id.')';
        }

        $keyword = $this->security->xss_clean($this->input->get('keyword'));
        $config['base_url'] = base_url('user_role/audit/' . $id);
        
        // Logika gabungan 2 tabel untuk TAMPILAN WEBSITE
        $audit_role = $this->Audit_model->get_audit_logs($id, $keyword, 'tbl_user_role');
        $audit_user = $this->Audit_model->get_audit_logs($user_id, $keyword, 'users');
        
        $audit_logs_all = array_merge(
            is_array($audit_role) ? $audit_role : [], 
            is_array($audit_user) ? $audit_user : []
        );

        $audit_logs_all = array_filter($audit_logs_all, function($log) {
            return $log['action'] !== 'EXPORT';
        });

        // --- PERBAIKAN URUTAN ---
        usort($audit_logs_all, function($a, $b) {
            $timeDiff = strtotime($b['timestamp']) - strtotime($a['timestamp']);
            
            // Jika detiknya sama persis (kasus Add User), tentukan urutan manual
            if ($timeDiff === 0) {
                // Bobot: makin besar angka, makin tampil di atas
                $order = ['Password' => 3, 'Email' => 2, 'Username' => 1];
                $weightA = isset($order[$a['field_name']]) ? $order[$a['field_name']] : 0;
                $weightB = isset($order[$b['field_name']]) ? $order[$b['field_name']] : 0;
                return $weightB - $weightA;
            }
            return $timeDiff;
        });

        $config['total_rows'] = count($audit_logs_all);
        
        $config['per_page'] = 5; 
        $config['page_query_string'] = TRUE;
        $config['query_string_segment'] = 'per_page';
        $config['reuse_query_string'] = TRUE;
        
        $config['full_tag_open']    = '<ul class="pagination pagination-sm m-0 float-right">';
        $config['full_tag_close']   = '</ul>';
        $config['cur_tag_open']     = '<li class="page-item active"><a class="page-link" href="#">';
        $config['cur_tag_close']    = '</a></li>';
        $config['num_tag_open']     = '<li class="page-item">';
        $config['num_tag_close']    = '</li>';
        $config['attributes']       = array('class' => 'page-link');
        $this->pagination->initialize($config);
        
        $start = $this->input->get('per_page') ? $this->input->get('per_page') : 0;
        $audit_logs = array_slice($audit_logs_all, $start, $config['per_page']);
        
        $data['keyword'] = $keyword;
        $data['target_name'] = $target_name; 
        $data['back_url'] = 'user_role'; 
        
        $data['export_url'] = base_url('audit/export_excel/tbl_user_role/' . $id) . ($keyword ? '?keyword=' . urlencode($keyword) : '');
        
        $data['audit_data'] = !empty($audit_logs) ? $audit_logs : [];
        $data['pagination'] = $this->pagination->create_links();
        $data['total_rows'] = $config['total_rows'];
        
        $this->load->view('audit/audit_view', $data);
    }

}