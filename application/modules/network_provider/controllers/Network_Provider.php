<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Network_provider extends CI_Controller {

    public function __construct() {
        parent::__construct();
        date_default_timezone_set('Asia/Jakarta');
        if (!$this->session->userdata('email')) {
             redirect('auth');
        }
        $this->load->model('Network_provider_model');
        $this->load->model('audit/Audit_model');
    }

    public function index() {
        $this->load->library('pagination');
        $keyword = $this->security->xss_clean($this->input->get('keyword'));
        $filters = $this->input->get('filter'); // Ambil filter

        $config['base_url'] = base_url('network_provider/index');
        
        // Setup Query String
        $params = [];
        if($keyword) $params['keyword'] = $keyword;
        if($filters) $params['filter'] = $filters;
        $config['suffix'] = !empty($params) ? '?' . http_build_query($params, '', '&') : '';
        $config['first_url'] = $config['base_url'] . $config['suffix'];

        $config['total_rows'] = $this->Network_provider_model->count_all_providers($keyword, $filters);
        $config['per_page'] = 10; 
        
        $config['enable_query_strings'] = TRUE;
        $config['page_query_string'] = TRUE;
        $config['query_string_segment'] = 'per_page';
        $config['reuse_query_string'] = TRUE;
        $config['num_links'] = 5;
        
        // Styling
        $config['full_tag_open']    = '<ul class="pagination pagination-sm m-0 float-right">';
        $config['full_tag_close']   = '</ul>';
        $config['first_link']       = '&laquo; First';
        $config['first_tag_open']   = '<li class="page-item">';
        $config['first_tag_close']  = '</li>';
        $config['last_link']        = 'Last &raquo;';
        $config['last_tag_open']    = '<li class="page-item">';
        $config['last_tag_close']   = '</li>';
        $config['next_link']        = '&rsaquo;';
        $config['next_tag_open']    = '<li class="page-item">';
        $config['next_tag_close']   = '</li>';
        $config['next_link']        = '&rsaquo;';
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
        $data['providers'] = $this->Network_provider_model->get_providers_paginated($config['per_page'], $start, $keyword, $filters);
        
        // Load Options Filter
        $data['opt_provider_name'] = $this->Network_provider_model->get_dynamic_options('provider_name', $filters);
        $data['opt_network_name']  = $this->Network_provider_model->get_dynamic_options('network_name', $filters);
        
        // Load Options Modal (Tetap Ada)
        $data['network_options'] = $this->Network_provider_model->get_all_networks_reference();

        $data['keyword'] = $keyword;
        $data['selected_filters'] = $filters;
        $data['pagination'] = $this->pagination->create_links();
        $data['total_rows'] = $config['total_rows'];

        $this->load->view('network_provider_view', $data);
    }

    public function export() {
        $this->Audit_model->insert_log([ 'username' => $this->session->userdata('username'), 'action' => 'EXPORT', 'table_name' => 'tbl_network_provider', 'foreign_id' => 0, 'field_name' => '-', 'old_value' => '-', 'new_value' => '-', 'reason' => 'Export Data', 'timestamp' => date('Y-m-d H:i:s') ]);
        
        $keyword = $this->security->xss_clean($this->input->get('keyword'));
        $filters = $this->input->get('filter');

        $data['providers'] = $this->Network_provider_model->get_all_providers($keyword, $filters);

        header("Content-type: application/vnd-ms-excel");
        header("Content-Disposition: attachment; filename=Network_Provider_".date('Y-m-d').".xls");
        header("Pragma: no-cache");
        header("Expires: 0");

        $this->load->view('network_provider_export', $data);
    }

    // --- Save, Delete, Audit, AJAX (Tetap Sama) ---
    public function save() {
        $id   = $this->input->post('provider_id'); 
        $name = trim($this->security->xss_clean($this->input->post('provider_name')));
        $single_network_id = $this->input->post('network_id');
        $network_ids = $single_network_id ? [$single_network_id] : [];
        $reason = $this->security->xss_clean($this->input->post('reason'));
        $username = $this->session->userdata('username'); 
        $createdBy = $this->session->userdata('user_id');

        if (empty($single_network_id)) {
            $this->session->set_flashdata('error', 'Network wajib dipilih!');
            redirect('network_provider'); return;
        }
        if ($this->Network_provider_model->check_duplicate_data($name, $single_network_id, $id)) {
            $this->session->set_flashdata('error', 'Duplikat! Kombinasi Provider Name dan Network ini sudah ada.');
            redirect('network_provider'); return; 
        }

        $this->db->trans_start();

        if ($id) {
            if(empty($reason)){
                $this->session->set_flashdata('error', 'Gagal update: Alasan wajib diisi!');
                redirect('network_provider'); return;
            }
            $existing_data = $this->Network_provider_model->get_by_id($id);
            $existing_networks = $this->Network_provider_model->get_provider_networks($id);
            
            $old_name = $existing_data['provider_name'];
            $old_net_id = !empty($existing_networks) ? $existing_networks[0] : '';

            if ($name == $old_name && $single_network_id == $old_net_id) {
                $this->db->trans_complete(); 
                $this->session->set_flashdata('error', 'Tidak ada data yang diubah.');
                redirect('network_provider'); return;
            }

            $data = [ 'provider_name' => $name, 'modified_by'    => $createdBy, 'modified_at'    => date("Y-m-d H:i:s") ];
            $this->Network_provider_model->update_provider($id, $data);
            $this->Network_provider_model->save_provider_networks($id, $network_ids);

            if ($old_name != $name) { $this->Audit_model->insert_log([ 'username' => $username, 'action' => 'EDIT', 'table_name' => 'tbl_network_provider', 'foreign_id' => $id, 'field_name' => 'Provider Name', 'old_value' => $old_name, 'new_value' => $name, 'reason' => $reason, 'timestamp' => date('Y-m-d H:i:s') ]); }
            if ($old_net_id != $single_network_id) {
                $old_net_name = $this->Network_provider_model->get_network_name_by_id($old_net_id);
                $new_net_name = $this->Network_provider_model->get_network_name_by_id($single_network_id);
                $this->Audit_model->insert_log([ 'username' => $username, 'action' => 'EDIT', 'table_name' => 'tbl_network_provider', 'foreign_id' => $id, 'field_name' => 'Network Name', 'old_value' => $old_net_name, 'new_value' => $new_net_name, 'reason' => $reason, 'timestamp' => date('Y-m-d H:i:s') ]);
            }
            $message = 'Data Provider berhasil diperbarui';

        } else {
            $data = [ 'provider_name' => $name, 'created_by'     => $createdBy, 'created_at'     => date("Y-m-d H:i:s") ];
            $this->Network_provider_model->insert_provider($data);
            $new_id = $this->db->insert_id();
            $this->Network_provider_model->save_provider_networks($new_id, $network_ids);

            $base_reason = 'Initial Creation';
            $new_net_name = $this->Network_provider_model->get_network_name_by_id($single_network_id);

            $this->Audit_model->insert_log([ 'username' => $username, 'action' => 'ADD', 'table_name' => 'tbl_network_provider', 'foreign_id' => $new_id, 'field_name' => 'Provider Name', 'old_value' => '-', 'new_value' => $name, 'reason' => $base_reason, 'timestamp' => date('Y-m-d H:i:s') ]);
            $this->Audit_model->insert_log([ 'username' => $username, 'action' => 'ADD', 'table_name' => 'tbl_network_provider', 'foreign_id' => $new_id, 'field_name' => 'Network Name', 'old_value' => '-', 'new_value' => $new_net_name, 'reason' => $base_reason, 'timestamp' => date('Y-m-d H:i:s') ]);
            $message = 'Data Provider berhasil ditambah';
        }

        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE) { $this->session->set_flashdata('error', 'Terjadi kesalahan database.'); } else { $this->session->set_flashdata('success', $message); }
        redirect('network_provider');
    }

    public function update_status() {
        // Ambil data dari request AJAX
        $id     = $this->input->post('id'); // Ini adalah junction ID (network_provider_id)
        $status = $this->input->post('status'); 
        $reason = $this->security->xss_clean($this->input->post('reason')); 

        if (!empty($id)) {
            // 1. Ambil data lama menggunakan junction ID
            $old_data = $this->Network_provider_model->get_by_id_junc($id);
            
            $username = $this->session->userdata('username');

            if (!$old_data) {
                $msg = ($status == 0) ? "Gagal menonaktifkan" : "Gagal mengaktifkan kembali";
                echo json_encode(['success' => false, 'message' => $msg . ': Data tidak ditemukan']);
                return;
            }

            // 2. Tentukan ID Utama (provider_id) untuk Audit Trail
            // Kita gunakan provider_id agar log muncul di halaman audit provider tersebut
            $main_provider_id = $old_data['provider_id'];

            $network_name = !empty($old_data['network_name']) ? $old_data['network_name'] : 'No Network';
            $nama_data = $old_data['provider_name'] . ' (' . $network_name . ')';

            // Update status di tbl_network_provider_junc
            $update = $this->Network_provider_model->update_provider_status($id, $status);

            if ($update) {
                // 3. Simpan ke Audit Trail dengan penyesuaian:
                // - table_name: tbl_network_provider (tabel utama)
                // - foreign_id: $main_provider_id (bukan junction ID)
                // - value: Menggunakan teks 'Active'/'Non Active' agar konsisten
                $this->Audit_model->insert_log([
                    'username'   => !empty($username) ? $username : 'System',
                    'action'     => ($status == 0) ? 'DEACTIVATE' : 'ACTIVATE',
                    'table_name' => 'tbl_network_provider',
                    'foreign_id' => $main_provider_id,
                    'field_name' => 'Status',
                    'old_value'  => ($status == 0) ? 'Active' : 'Non Active',
                    'new_value'  => ($status == 0) ? 'Non Active' : 'Active',
                    'reason'     => !empty($reason) ? $reason : 'Toggle Status',
                    'timestamp'  => date('Y-m-d H:i:s')
                ]);

                $response_msg = ($status == 0) 
                    ? "Data '" . $nama_data . "' berhasil di nonaktifkan" 
                    : "Data '" . $nama_data . "' berhasil di aktifkan kembali";

                echo json_encode(['success' => true, 'message' => $response_msg]);
            } else {
                $error_msg = ($status == 0) ? "Gagal menonaktifkan" : "Gagal mengaktifkan kembali";
                echo json_encode(['success' => false, 'message' => $error_msg . ' data ' . $nama_data]);
            }
        } else {
            echo json_encode(['success' => false, 'message' => 'ID tidak ditemukan']);
        }
    }

    public function audit($id) {
        $this->load->library('pagination');

        // Mengambil data provider berdasarkan provider_id
        $db_data = $this->Network_provider_model->get_by_id($id);
        if (!$db_data) {
            redirect('network_provider');
        }

        // [XSS CLEAN] Membersihkan keyword pencarian
        $keyword = $this->security->xss_clean($this->input->get('keyword'));
        
        $table_name = 'tbl_network_provider';

        $config['base_url'] = base_url('network_provider/audit/' . $id);
        $config['total_rows'] = count($this->Audit_model->get_audit_logs($id, $keyword, $table_name));
        $config['per_page'] = 5; 
        $config['page_query_string'] = TRUE;
        $config['query_string_segment'] = 'per_page';
        $config['reuse_query_string'] = TRUE;

        // Styling Pagination
        $config['full_tag_open']    = '<ul class="pagination pagination-sm m-0 float-right">';
        $config['full_tag_close']   = '</ul>';
        $config['cur_tag_open']     = '<li class="page-item active"><a class="page-link" href="#">';
        $config['cur_tag_close']    = '</a></li>';
        $config['num_tag_open']     = '<li class="page-item">';
        $config['num_tag_close']    = '</li>';
        $config['attributes']       = array('class' => 'page-link');

        $this->pagination->initialize($config);

        $start = $this->input->get('per_page');
        $audit_logs = $this->Audit_model->get_audit_logs_paginated($id, $table_name, $config['per_page'], $start, $keyword);


        $data['keyword']     = $keyword;
        $data['menu_label']  = 'Network Provider';
        $data['target_name'] = $db_data['provider_name'];
        $data['back_url']    = 'network_provider';
        $data['export_url']  = base_url('audit/export_excel/tbl_network_provider/' . $id); 
        $data['audit_data']  = $audit_logs;
        $data['pagination']  = $this->pagination->create_links();
        $data['total_rows']  = $config['total_rows']; 

        $this->load->view('audit/audit_view', $data);
    }

    public function get_related_networks($provider_id) {
        $data = $this->Network_provider_model->get_provider_networks($provider_id);
        echo json_encode($data);
    }
}