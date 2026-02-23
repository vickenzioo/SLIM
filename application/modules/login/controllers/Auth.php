<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Auth extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Auth_model');
        $this->load->library('form_validation'); 
        $this->load->library('session'); 
    }

    public function index() {
        if ($this->session->userdata('email')) {
            redirect('portofolio');
        }

        // Ambil pesan error dari session lalu hapus
        $data['pesan_error'] = $this->session->userdata('message');
        if ($this->session->userdata('message')) {
            $this->session->unset_userdata('message');
        }
        
        $this->load->view('login', $data);
    }

    public function process_login() {
        // Cek apakah user masuk kesini tanpa klik tombol Login
        if ($this->input->method() !== 'post') {
            redirect('auth');
        }

        $this->form_validation->set_rules('email', 'Email', 'required|valid_email', [
            'required' => 'Email wajib diisi!',
            'valid_email' => 'Format email tidak benar!'
        ]);
        $this->form_validation->set_rules('password', 'Password', 'required', [
            'required' => 'Password wajib diisi!'
        ]);

        if ($this->form_validation->run() == FALSE) {
            $data['pesan_error'] = ''; 
            $this->load->view('login', $data); 
        } else {
            $email = $this->security->xss_clean(trim($this->input->post('email', TRUE)));
            $password = $this->security->xss_clean(trim($this->input->post('password'))); 
            
            $user = $this->Auth_model->get_user_by_email($email);

            if ($user) {
                // [TAMBAHAN ATURAN 2]: Cek apakah user aktif atau tidak
                if ($user['status'] == 0) {
                    $this->session->set_userdata('message', '<div class="alert alert-warning text-center" role="alert">Akun Anda sudah Non-aktif. Silahkan hubungi Admin!</div>');
                    redirect('auth');
                    return; // Hentikan proses
                }

                // Cek Password Biasa (Plain Text)
                if ($password == $user['password']) {
                    $data = [
                        'user_id'  => $user['id'], 
                        'email'    => $user['email'],
                        'username' => $user['username'],
                        'role'     => $user['role_name']
                    ];
                    
                    $this->session->set_userdata($data);
                    redirect('portofolio'); 
                    
                } else {
                    $this->session->set_userdata('message', '<div class="alert alert-danger text-center" role="alert">Password salah!</div>');
                    redirect('auth');
                }
            } else {
                $this->session->set_userdata('message', '<div class="alert alert-danger text-center" role="alert">Email tidak terdaftar!</div>');
                redirect('auth');
            }
        }
    }
    
    public function logout() {
        $this->session->unset_userdata('email');
        $this->session->unset_userdata('user_id');
        $this->session->unset_userdata('username'); // Hapus session username juga
        $this->session->sess_destroy();
        
        $this->session->set_userdata('message', '<div class="alert alert-success text-center" role="alert">Anda telah logout!</div>');
        redirect('auth');
    }
}