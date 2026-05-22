<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Gatekeeper {

    protected $CI;

    public function __construct() {
        $this->CI =& get_instance();

        // 1. Jangan jalankan Gatekeeper di halaman Login/Auth atau CLI
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
            exit; // Pastikan proses berhenti setelah redirect
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
        
        // SKENARIO A: User adalah IT SLM (Role 1)
        if ($session_role === 1) {
            // IT SLM memiliki akses Penuh. Biarkan lolos.
            return;
        }

        // SKENARIO B: User adalah EA Apps (Role 2) atau IT Dev (Role 3)
        elseif (in_array($session_role, [2, 3])) {
            // EA Apps & IT Dev HANYA BOLEH di halaman 'home'
            if ($current_class !== 'home') {
                
                // [KUNCI PERBAIKAN]: 
                // Fitur $this->CI->session->set_flashdata('error', ...) DIHAPUS.
                // Sistem hanya akan mengarahkan (redirect) kembali ke home secara diam-diam.
                // Hal ini menjamin popup "Akses Ditolak" tidak akan pernah muncul secara salah / nyasar lagi.
                
                redirect('home');
                exit; // Pastikan proses berhenti setelah redirect
            }
        }
    }
}