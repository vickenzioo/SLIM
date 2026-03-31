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
		header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
		header("Cache-Control: post-check=0, pre-check=0", false);
		header("Pragma: no-cache");
        // Cek apakah sudah login
        if ($this->session->userdata('email')) {
            $role_id = (int)$this->session->userdata('role_id');
            // Jika Role 2 (EA) atau Role 3 (IT Dev), arahkan ke Home
            if (in_array($role_id, [2, 3])) {
                redirect('home');
            } else {
                redirect('home');
            }
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
                // Cek apakah user aktif atau tidak
                if ($user['status'] == 0) {
                    $this->session->set_userdata('message', '<div class="alert alert-warning text-center" role="alert">Akun Anda sudah Non-aktif. Silahkan hubungi Admin!</div>');
                    redirect('auth');
                    return; // Hentikan proses
                }

                // Cek Password Biasa (Plain Text)
                if ($password == $user['password']) {
                    
                    // Pastikan kita menyimpan role_id ke dalam session sejak awal login
                    $role_id = isset($user['role_id']) ? (int)$user['role_id'] : 0;

                    $data = [
                        'user_id'  => $user['id'], 
                        'email'    => $user['email'],
                        'username' => $user['username'],
                        'role'     => $user['role_name'],
                        'role_id'  => $role_id // Tambahan penting untuk Gatekeeper
                    ];
                    
                    $this->session->set_userdata($data);

                    // Arahkan Halaman Berdasarkan Role ID
                    if (in_array($role_id, [2, 3])) {
                        redirect('home'); // EA Apps & IT Dev langsung ke Home
                    } else {
                        redirect('Home'); // IT SLM bebas ke Portofolio
                    }
                    
                } else {
                    $this->session->set_flashdata('old_email', $email);
                    $this->session->set_flashdata('old_password', $password);
                    
                    $this->session->set_flashdata('error_password', 'Email atau password tidak valid!');
                    redirect('auth');
                }
            } else {
                $this->session->set_flashdata('old_email', $this->input->post('email'));
                $this->session->set_flashdata('old_password', $this->input->post('password'));

                $this->session->set_flashdata('error_password', 'Email atau password tidak valid?');
                redirect('auth');
            }
        }
    }
    
    public function logout() {
        $this->session->unset_userdata('email');
        $this->session->unset_userdata('user_id');
        $this->session->unset_userdata('username');
        $this->session->unset_userdata('role_id'); 
        $this->session->sess_destroy();
        
        $this->session->set_userdata('message', '<div class="alert alert-success text-center" role="alert">Anda telah logout!</div>');
        redirect('auth');
    }
}