<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Network_product extends CI_Controller {

    public function __construct() {
        parent::__construct();
        date_default_timezone_set('Asia/Jakarta');
        if (!$this->session->userdata('email')) {
             redirect('auth');
        }
        $this->load->model('Network_product_model');
        $this->load->model('audit/Audit_model');
    }

    public function index() {
        $this->load->library('pagination');
        $keyword = $this->security->xss_clean($this->input->get('keyword'));
        $filters = $this->input->get('filter'); // Ambil filter

        $config['base_url'] = base_url('network_product/index');
        
        // Setup Query String
        $params = [];
        if($keyword) $params['keyword'] = $keyword;
        if($filters) $params['filter'] = $filters;
        $config['suffix'] = !empty($params) ? '?' . http_build_query($params, '', '&') : '';
        $config['first_url'] = $config['base_url'] . $config['suffix'];

        $config['total_rows'] = $this->Network_product_model->count_all_products($keyword, $filters);
        $config['per_page'] = 10; 
        
        $config['enable_query_strings'] = TRUE;
        $config['page_query_string'] = TRUE;
        $config['query_string_segment'] = 'per_page';
        $config['reuse_query_string'] = TRUE;
        $config['num_links'] = 5;
        
        // Styling Pagination
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
        $data['products'] = $this->Network_product_model->get_products_paginated($config['per_page'], $start, $keyword, $filters);
        
        // Load Options untuk Filter Dropdown
        $data['opt_product_name'] = $this->Network_product_model->get_dynamic_options('product_name', $filters);
        $data['opt_product_sla']  = $this->Network_product_model->get_dynamic_options('product_sla', $filters);
        $data['opt_network_name'] = $this->Network_product_model->get_dynamic_options('network_name', $filters);

        // Load Options untuk Modal Form Add/Edit
        $data['network_options'] = $this->Network_product_model->get_all_networks_reference();

        $data['keyword'] = $keyword;
        $data['selected_filters'] = $filters;
        $data['pagination'] = $this->pagination->create_links();
        $data['total_rows'] = $config['total_rows'];

        $this->load->view('network_product_view', $data);
    }

    public function export() {
        $this->Audit_model->insert_log([ 'username' => $this->session->userdata('username'), 'action' => 'EXPORT', 'table_name' => 'tbl_network_product', 'foreign_id' => 0, 'field_name' => '-', 'old_value' => '-', 'new_value' => '-', 'reason' => 'Export Data', 'timestamp' => date('Y-m-d H:i:s') ]);
        
        $keyword = $this->security->xss_clean($this->input->get('keyword'));
        $filters = $this->input->get('filter');

        $data['products'] = $this->Network_product_model->get_all_products($keyword, $filters);

        header("Content-type: application/vnd-ms-excel");
        header("Content-Disposition: attachment; filename=Network_Product_".date('Y-m-d').".xls");
        header("Pragma: no-cache");
        header("Expires: 0");

        $this->load->view('network_product_export', $data);
    }

    // --- Save, Delete, Audit, AJAX (Tetap Sama) ---
    public function save() {
        $id   = $this->input->post('product_id'); 
        $name = trim($this->security->xss_clean($this->input->post('product_name')));
        $sla  = trim($this->security->xss_clean($this->input->post('product_sla')));
        
        $single_network_id = $this->input->post('network_id');
        $network_ids = $single_network_id ? [$single_network_id] : [];
        
        $reason = $this->security->xss_clean($this->input->post('reason'));
        $username = $this->session->userdata('username'); 
        $createdBy = $this->session->userdata('user_id');

        if (empty($single_network_id)) {
            $this->session->set_flashdata('error', 'Network wajib dipilih!');
            redirect('network_product'); return;
        }
        if ($sla === '' || !is_numeric($sla)) {
            $this->session->set_flashdata('error', 'Product SLA wajib diisi angka!');
            redirect('network_product'); return; 
        }
        if ($this->Network_product_model->check_duplicate_data($name, $sla, $single_network_id, $id)) {
            $this->session->set_flashdata('error', 'Duplikat! Data ini sudah ada.');
            redirect('network_product'); return; 
        }

        $this->db->trans_start();

        if ($id) {
            if(empty($reason)){
                $this->session->set_flashdata('error', 'Gagal update: Alasan wajib diisi!');
                redirect('network_product'); return;
            }
            $existing_data = $this->Network_product_model->get_by_id($id);
            $existing_networks = $this->Network_product_model->get_product_networks($id);
            
            $old_name = $existing_data['product_name'];
            $old_sla  = $existing_data['product_sla'];
            $old_net_id = !empty($existing_networks) ? $existing_networks[0] : '';

            if ($name == $old_name && $sla == $old_sla && $single_network_id == $old_net_id) {
                $this->db->trans_complete(); 
                $this->session->set_flashdata('error', 'Tidak ada data yang diubah.');
                redirect('network_product'); return;
            }

            $data = [ 'product_name' => $name, 'product_sla'  => $sla, 'modified_by'  => $createdBy, 'modified_at'  => date("Y-m-d H:i:s") ];
            $this->Network_product_model->update_product($id, $data);
            $this->Network_product_model->save_product_networks($id, $network_ids);

            if ($old_name != $name) { $this->Audit_model->insert_log([ 'username' => $username, 'action' => 'EDIT', 'table_name' => 'tbl_network_product', 'foreign_id' => $id, 'field_name' => 'Product Name', 'old_value' => $old_name, 'new_value' => $name, 'reason' => $reason, 'timestamp' => date('Y-m-d H:i:s') ]); }
            if ($old_sla != $sla) { $this->Audit_model->insert_log([ 'username' => $username, 'action' => 'EDIT', 'table_name' => 'tbl_network_product', 'foreign_id' => $id, 'field_name' => 'Product SLA', 'old_value' => $old_sla, 'new_value' => $sla, 'reason' => $reason, 'timestamp' => date('Y-m-d H:i:s') ]); }
            if ($old_net_id != $single_network_id) {
                $old_net_name = $this->Network_product_model->get_network_name_by_id($old_net_id);
                $new_net_name = $this->Network_product_model->get_network_name_by_id($single_network_id);
                $this->Audit_model->insert_log([ 'username' => $username, 'action' => 'EDIT', 'table_name' => 'tbl_network_product', 'foreign_id' => $id, 'field_name' => 'Network Name', 'old_value' => $old_net_name, 'new_value' => $new_net_name, 'reason' => $reason, 'timestamp' => date('Y-m-d H:i:s') ]);
            }
            $message = 'Data Product berhasil diperbarui';

        } else {
            $data = [ 'product_name' => $name, 'product_sla'  => $sla, 'created_by'   => $createdBy, 'created_at'   => date("Y-m-d H:i:s") ];
            $this->Network_product_model->insert_product($data);
            $new_id = $this->db->insert_id();
            $this->Network_product_model->save_product_networks($new_id, $network_ids);
            
            $new_net_name = $this->Network_product_model->get_network_name_by_id($single_network_id);
            $base_reason = 'Initial Creation';

            $this->Audit_model->insert_log([ 'username' => $username, 'action' => 'ADD', 'table_name' => 'tbl_network_product', 'foreign_id' => $new_id, 'field_name' => 'Product Name', 'old_value' => '-', 'new_value' => $name, 'reason' => $base_reason, 'timestamp' => date('Y-m-d H:i:s') ]);
            $this->Audit_model->insert_log([ 'username' => $username, 'action' => 'ADD', 'table_name' => 'tbl_network_product', 'foreign_id' => $new_id, 'field_name' => 'Product SLA', 'old_value' => '-', 'new_value' => $sla, 'reason' => $base_reason, 'timestamp' => date('Y-m-d H:i:s') ]);
            $this->Audit_model->insert_log([ 'username' => $username, 'action' => 'ADD', 'table_name' => 'tbl_network_product', 'foreign_id' => $new_id, 'field_name' => 'Network Name', 'old_value' => '-', 'new_value' => $new_net_name, 'reason' => $base_reason, 'timestamp' => date('Y-m-d H:i:s') ]);
            $message = 'Data Product berhasil ditambah';
        }

        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE) { $this->session->set_flashdata('error', 'Terjadi kesalahan database.'); } else { $this->session->set_flashdata('success', $message); }
        redirect('network_product');
    }

    public function update_status() {
        $id     = $this->input->post('id'); // Ini adalah network_product_id (junc)
        $status = $this->input->post('status'); 
        $reason = $this->security->xss_clean($this->input->post('reason'));

        if (!empty($id)) {
            // Ambil data detail untuk mendapatkan product_id yang asli
            $old_data = $this->Network_product_model->get_by_id_junc($id);
            $username = $this->session->userdata('username');

            if (!$old_data) {
                echo json_encode(['success' => false, 'message' => 'Data tidak ditemukan']);
                return;
            }

            $real_product_id = $old_data['product_id']; // ID Utama untuk audit trail
            $nama_data = $old_data['product_name'] . ' (' . $old_data['network_name'] . ')';

            $update = $this->Network_product_model->update_product_status($id, $status);

            if ($update) {
                // PENCATATAN AUDIT (Disesuaikan agar muncul di halaman audit)
                $this->Audit_model->insert_log([
                    'username'   => !empty($username) ? $username : 'System',
                    'action'     => ($status == 0) ? 'DEACTIVATE' : 'ACTIVATE',
                    'table_name' => 'tbl_network_product', // DISESUAIKAN: Pakai table utama
                    'foreign_id' => $real_product_id,     // DISESUAIKAN: Pakai product_id asli
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
                echo json_encode(['success' => false, 'message' => 'Gagal update status database']);
            }
        } else {
            echo json_encode(['success' => false, 'message' => 'ID tidak ditemukan']);
        }
    }

    public function audit($id) {
        $this->load->library('pagination');

        $db_data = $this->Network_product_model->get_by_id($id);
        if (!$db_data) {
            redirect('network_product');
        }

        // [XSS CLEAN] Membersihkan keyword pencarian di halaman audit
        $keyword = $this->security->xss_clean($this->input->get('keyword'));
        
        $table_name = 'tbl_network_product';

        $config['base_url'] = base_url('network_product/audit/' . $id);
        $config['total_rows'] = count($this->Audit_model->get_audit_logs($id, $keyword, $table_name));
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

        $start = $this->input->get('per_page');
        $audit_logs = $this->Audit_model->get_audit_logs_paginated($id, $table_name, $config['per_page'], $start, $keyword);


        $data['keyword']     = $keyword;
        $data['menu_label']  = 'Network Product';
        $data['target_name'] = $db_data['product_name'];
        $data['back_url']    = 'network_product';
        $data['export_url']  = base_url('audit/export_excel/tbl_network_product/' . $id) . ($keyword ? '?keyword=' . urlencode($keyword) : '');
        $data['audit_data']  = $audit_logs;
        $data['pagination']  = $this->pagination->create_links();
        $data['total_rows']  = $config['total_rows']; 

        $this->load->view('audit/audit_view', $data);
    }

    public function get_related_networks($product_id) {
        $data = $this->Network_product_model->get_product_networks($product_id);
        echo json_encode($data);
    }
}