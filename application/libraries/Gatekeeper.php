<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Gatekeeper {

    protected $CI;

    public function __construct() {
        $this->CI =& get_instance();

        // 1. Jangan jalankan Gatekeeper di halaman Login/Auth atau CLI
        // Agar tidak terjadi Loop (Redirect berulang-ulang)
        $current_class = strtolower($this->CI->router->fetch_class());
        if ($current_class === 'auth' || $current_class === 'login' || is_cli()) {
            return;
        }

        // 2. Pastikan Session & Database Terload
        if (!isset($this->CI->session)) {
            $this->CI->load->library('session');
        }
        if (!isset($this->CI->db)) {
            $this->CI->load->database();
        }

        // 3. Cek Login
        if (!$this->CI->session->userdata('user_id')) {
            redirect('auth');
        }

        // =========================================================
        // [AUTO-FIX] PERBAIKI ROLE ID JIKA 0 / HILANG
        // =========================================================
        $user_id = (int) $this->CI->session->userdata('user_id');
        $session_role = (int) $this->CI->session->userdata('role_id');

        // Jika Session Role 0, kita paksa cari di database lagi
        if ($session_role === 0) {
            $query = $this->CI->db->get_where('tbl_user_role', ['id' => $user_id]);
            $row = $query->row();
            
            if ($row) {
                // Update Session dengan Role yang benar
                $session_role = (int) $row->role_id;
                $this->CI->session->set_userdata('role_id', $session_role);
            }
        }
        // =========================================================

        // 4. LOGIKA PEMANTULAN (GLOBAL REDIRECT)
        // ---------------------------------------------------------
        
        // SKENARIO A: User adalah IT SLM (Role 1)
        if ($session_role === 1) {
            // IT SLM DILARANG masuk halaman 'Home'
            // Jika dia ada di 'home', tendang ke 'portofolio'
            if ($current_class === 'home') {
                redirect('portofolio');
            }
            // (Dia bebas akses controller lain seperti server, database, admin, dll)
        }

        // SKENARIO B: User adalah OPERATIONAL (Role 2 - 8)
        else {
            // User Ops HANYA BOLEH di halaman 'Home'
            // Jika dia mencoba akses controller SELAIN 'home', tendang balik ke 'home'
            if ($current_class !== 'home') {
                // Tambahkan flashdata agar user tahu kenapa dia dipantulkan
                $this->CI->session->set_flashdata('error', 'Akses Ditolak! Anda tidak memiliki izin ke halaman tersebut.');
                redirect('home');
            }
        }
    }
}