<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Audit extends CI_Controller {

    public function __construct() {
        parent::__construct();
		date_default_timezone_set('Asia/Jakarta');
        if (!$this->session->userdata('email')) {
            redirect('auth');
        }
        $this->load->model('Audit_model');
    }

    public function audit($id) {
        // 1. Load Library Pagination
        $this->load->library('pagination');

        // 2. Ambil Keyword dan TYPE (Nama Tabel) dari URL
        $keyword = $this->input->get('keyword');
        $type = $this->input->get('type'); // TAMBAHKAN INI: Untuk membedakan tabel
        
        // 3. Konfigurasi Pagination
        $config['base_url'] = base_url('audit/audit/' . $id);
        
        // SINKRONISASI MODEL: Kirim variabel $type agar model mencari di tabel yang benar
        $all_logs = $this->Audit_model->get_audit_logs($id, $keyword, $type); 
        $config['total_rows'] = count($all_logs);
        
        $config['per_page'] = 10; 
        
        $config['enable_query_strings'] = TRUE;
        $config['page_query_string'] = TRUE;
        $config['query_string_segment'] = 'per_page';
        $config['reuse_query_string'] = TRUE;

        // --- STYLE BOOTSTRAP 4 ---
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

        // 4. Inisialisasi Config
        $this->pagination->initialize($config);

        // 5. Ambil Data Sesuai Halaman
        $start = $this->input->get('per_page');
        $data['audit_data'] = array_slice($all_logs, (int)$start, $config['per_page']);

        $data['keyword'] = $keyword;

        // 6. Persiapkan data untuk View
        // SINKRONISASI MODEL: Berikan $type agar target_name muncul (Provider Name / Product Name)
        $data['target_name'] = $this->Audit_model->get_target_name($id, $type);
        
        // Tentukan URL kembali secara dinamis berdasarkan tabel
        if ($type == 'tbl_network_provider') {
            $data['back_url'] = 'network_provider';
            $data['menu_label'] = 'Network Provider';
        } elseif ($type == 'tbl_network_product') {
            $data['back_url'] = 'network_product';
            $data['menu_label'] = 'Network Product';
        } elseif ($type == 'tbl_apps_network') { 
            $data['back_url'] = 'network';
            $data['menu_label'] = 'Network';
        } elseif ($type == 'tbl_user_role') {
            $data['back_url'] = 'user_role';
            $data['menu_label'] = 'User';
        } elseif ($type == 'tbl_database_master' || $type == 'database') {
            $data['back_url'] = 'database';
            $data['menu_label'] = 'Database';
        } elseif ($type == 'tbl_apps_operational_day' || $type == 'operational_day') {
            // PERBAIKAN: Set back_url dan menu_label agar breadcrumb muncul benar
            $data['back_url'] = 'operational_day';
            $data['menu_label'] = 'Operational Day';
        } else {
            $data['back_url'] = 'deployment';
            $data['menu_label'] = 'Deployment';
        } // Penutup bracket yang tadi hilang ada di sini

        // Sekarang variabel di bawah ini akan terbaca dengan benar
        $data['export_url'] = base_url('audit/audit_export/'.$id . ($keyword ? '?keyword='.$keyword : '') . ($type ? '&type='.$type : ''));

        $data['pagination'] = $this->pagination->create_links(); 
        $data['total_rows'] = $config['total_rows']; 

        $this->load->view('audit/audit_view', $data);
    }
    
    // Fungsi export yang dipanggil dari halaman Audit Trail
    public function audit_export($id) {
        $keyword = $this->input->get('keyword');
        $type = $this->input->get('type');
        
        // Mengambil nama target
        $target = $this->Audit_model->get_target_name($id, $type);
        
        // SINKRONISASI: Kirim kedua variabel agar aman di view mana pun
        $data['target_name'] = $target; 
        $data['audit_target'] = $target; 
        
        $data['audit_logs'] = $this->Audit_model->get_audit_logs($id, $keyword);

        header("Content-type: application/vnd-ms-excel");
        header("Content-Disposition: attachment; filename=Audit_Trail_".date('Y-m-d').".xls");
        header("Pragma: no-cache");
        header("Expires: 0");

        $this->load->view('audit/audit_export', $data);
    }

    // Fungsi export umum (jika digunakan dari modul lain)
    // Fungsi export umum (jika digunakan dari modul lain)
    public function export_excel($table_name, $foreign_id) {
        $data['audit_logs'] = $this->Audit_model->get_logs($table_name, $foreign_id);
    
        // UBAH BARIS INI: 
        // Menggunakan get_target_name yang sudah ada di model Anda 
        // daripada get_target_name_global yang menyebabkan error undefined method.
        $data['audit_target'] = $this->Audit_model->get_target_name($foreign_id, $table_name);

        header("Content-type: application/vnd-ms-excel");
        header("Content-Disposition: attachment; filename=Audit_Trail_".date('Y-m-d').".xls");
        header("Pragma: no-cache");
        header("Expires: 0");

        // Sesuaikan path view dengan lokasi file Anda
        $this->load->view('audit/audit_export', $data);
    }
}