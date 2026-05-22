<?php

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

defined('BASEPATH') OR exit('No direct script access allowed');

class Home extends CI_Controller {

    public function __construct() {
        parent::__construct();
        if (!$this->session->userdata('user_id') || !$this->session->userdata('email')) { redirect('auth'); }
        
        $this->load->model('Home_model');
        date_default_timezone_set('Asia/Jakarta');

        $user_id = (int)$this->session->userdata('user_id');
        $user_role_data = $this->db->get_where('tbl_user_role', ['id' => $user_id])->row();
        if (!$user_role_data) { $user_role_data = $this->db->get_where('tbl_user_role', ['user_role_id' => $user_id])->row(); }
        
        if ($user_role_data) {
            $real_role_id = (int)$user_role_data->role_id;
            $this->session->set_userdata('role_id', $real_role_id);
        } else { $real_role_id = (int)$this->session->userdata('role_id'); }
    }

    public function index() {
        $role_id = $this->session->userdata('role_id');
        $user_id = $this->session->userdata('user_id');
        $data['title'] = 'Home';
        $data['rid'] = $role_id;
        
        // SECURITY: Membersihkan input pencarian dan filter dari XSS
        $keyword = $this->security->xss_clean($this->input->get('keyword'));
        $filters = $this->security->xss_clean($this->input->get('filter'));

        $m = $this->Home_model;

        // --- PENGAMBILAN DATA OPSI FILTER ---
        //$data['opt_app_status']      = $m->get_dynamic_options('app_status', $user_id, $role_id, $filters);
		$data['opt_app_status']      = ['Active', 'Not Active', 'Drafting', 'Renewal'];
        $data['opt_status']          = $m->get_dynamic_options('status', $user_id, $role_id, $filters);
        $data['opt_category']        = $m->get_dynamic_options('category', $user_id, $role_id, $filters);
        $data['opt_app_name']        = $m->get_dynamic_options('app_name', $user_id, $role_id, $filters);
        $data['opt_short_name']      = $m->get_dynamic_options('short_name', $user_id, $role_id, $filters);
        $data['opt_module']          = $m->get_dynamic_options('module', $user_id, $role_id, $filters);
        $data['opt_db_name']         = $m->get_dynamic_options('db_name', $user_id, $role_id, $filters);
        $data['opt_os_name']         = $m->get_dynamic_options('os_name', $user_id, $role_id, $filters);
        $data['opt_app_type']        = $m->get_dynamic_options('app_type', $user_id, $role_id, $filters);
        $data['opt_server_name']     = $m->get_dynamic_options('server_name', $user_id, $role_id, $filters);
        $data['opt_standard_cat']    = $m->get_dynamic_options('standard_category', $user_id, $role_id, $filters);
        $data['opt_live_year']       = $m->get_dynamic_options('live_year', $user_id, $role_id, $filters);
        $data['opt_decom_year']      = $m->get_dynamic_options('decom_year', $user_id, $role_id, $filters);
        $data['opt_resilience']      = $m->get_dynamic_options('resilience', $user_id, $role_id, $filters);
        $data['opt_network']         = $m->get_dynamic_options('network', $user_id, $role_id, $filters);
        
        $data['opt_deploy_model']    = $m->get_dynamic_options('deployment_model', $user_id, $role_id, $filters);
        $data['opt_deploy_provider'] = $m->get_dynamic_options('deployment_provider', $user_id, $role_id, $filters);
        $data['opt_deploy_site']     = $m->get_dynamic_options('deployment_site', $user_id, $role_id, $filters);
        
        $data['opt_op_hour']         = $m->get_dynamic_options('op_hour', $user_id, $role_id, $filters);
        $data['opt_op_day']          = $m->get_dynamic_options('op_day', $user_id, $role_id, $filters);
        $data['opt_solution_vendor'] = $m->get_dynamic_options('solution_vendor', $user_id, $role_id, $filters);
        $data['opt_services_vendor'] = $m->get_dynamic_options('services_vendor', $user_id, $role_id, $filters);
        $data['opt_lob_directorate'] = $m->get_dynamic_options('lob_directorate', $user_id, $role_id, $filters);
        $data['opt_lob_subdirectorate'] = $m->get_dynamic_options('lob_subdirectorate', $user_id, $role_id, $filters);
        $data['opt_lob_group']       = $m->get_dynamic_options('lob_group', $user_id, $role_id, $filters);
        $data['opt_lob_group_head']  = $m->get_dynamic_options('lob_group_head', $user_id, $role_id, $filters);
        $data['opt_lob_department_head'] = $m->get_dynamic_options('lob_department_head', $user_id, $role_id, $filters);
        $data['opt_it_subdirectorate'] = $m->get_dynamic_options('it_subdirectorate', $user_id, $role_id, $filters);
        $data['opt_it_department_head'] = $m->get_dynamic_options('it_department_head', $user_id, $role_id, $filters);
        $data['opt_it_support_group'] = $m->get_dynamic_options('it_support_group', $user_id, $role_id, $filters);
        $data['opt_it_group_head']   = $m->get_dynamic_options('it_group_head', $user_id, $role_id, $filters);
        $data['opt_it_support_divison'] = $m->get_dynamic_options('it_support_divison', $user_id, $role_id, $filters);
        $data['opt_it_division_head']= $m->get_dynamic_options('it_division_head', $user_id, $role_id, $filters);
        
        $data['opt_app_version']     = $m->get_dynamic_options('app_version', $user_id, $role_id, $filters);
        $data['opt_dev_lang']        = $m->get_dynamic_options('dev_language', $user_id, $role_id, $filters);
        $data['opt_app_dev']         = $m->get_dynamic_options('app_developer', $user_id, $role_id, $filters);
        $data['opt_web_server']      = $m->get_dynamic_options('web_server', $user_id, $role_id, $filters);
        $data['opt_app_server']      = $m->get_dynamic_options('app_server', $user_id, $role_id, $filters);
        $data['opt_sup_others']      = $m->get_dynamic_options('sup_others', $user_id, $role_id, $filters);
        $data['opt_src_code']        = $m->get_dynamic_options('src_code', $user_id, $role_id, $filters);
        $data['opt_url']             = $m->get_dynamic_options('url', $user_id, $role_id, $filters);

        $data['opt_yn'] = ['Yes', 'No'];

        $data['selected_filters'] = $filters;
        $data['keyword'] = $keyword;

        $this->load->library('pagination');
        $config['base_url'] = base_url('home/index');
        
        $limit = $this->input->get('limit') ? (int)$this->input->get('limit') : 20;
        $config['per_page'] = $limit;
        $data['current_limit'] = $limit;
        $config['page_query_string'] = TRUE;
        $config['reuse_query_string'] = TRUE;
        $config['query_string_segment'] = 'page';
        
        // --- KONFIGURASI PAGINATION SESUAI PERMINTAAN ---
        $total_rows = $m->count_my_portfolio($user_id, $role_id, $keyword, $filters);
        $last_page_num = (string)ceil($total_rows / $config['per_page']);

        $config['num_links'] = 2; 
        $config['display_pages'] = TRUE;
        
        // Mengganti teks First & Last agar menyatu dengan angka, dan panah menjadi Next/Prev
        $config['first_link'] = '1';
        $config['last_link'] = $last_page_num;
        
        // Tombol panah diletakkan di next_link dan prev_link agar muncul di ujung luar
        $config['next_link'] = '&rsaquo;'; 
        $config['prev_link'] = '&lsaquo;';

        // Mengaktifkan pemisah "..." (Ellipsis)
        $config['full_tag_open'] = '<ul class="pagination pagination-sm m-0">';
        $config['full_tag_close'] = '</ul>';
        
        // Styling untuk simbol ellipsis agar tetap terlihat rapi
        $config['num_tag_open'] = '<li class="page-item">';
        $config['num_tag_close'] = '</li>';
        
        $config['cur_tag_open'] = '<li class="page-item active"><a class="page-link" href="#">';
        $config['cur_tag_close'] = '</a></li>';

        $config['next_tag_open'] = '<li class="page-item">';
        $config['next_tag_close'] = '</li>';
        $config['prev_tag_open'] = '<li class="page-item">';
        $config['prev_tag_close'] = '</li>';

        $config['first_tag_open'] = '<li class="page-item">';
        $config['first_tag_close'] = '</li>';
        $config['last_tag_open'] = '<li class="page-item">';
        $config['last_tag_close'] = '</li>';

        $config['attributes'] = array('class' => 'page-link');
        // ------------------------------------------------

        $raw_page = $this->security->xss_clean($this->input->get('page'));
        $page = ($raw_page) ? (int)$raw_page : 0;
        
        $raw_export = $this->security->xss_clean($this->input->get('export'));
        $is_export = $raw_export == 1;

        if ($is_export) {
            $this->load->model('audit/Audit_model');

            $this->Audit_model->insert_log([
                'username'    => $this->session->userdata('username'),
                'action'      => 'EXPORT',
                'table_name'  => 'tbl_portofolio_apps_master',
                'foreign_id'  => 0,
                'field_name'  => '-',
                'old_value'   => '-',
                'new_value'   => '-',
                'reason'      => 'Export Data',
                'timestamp'   => date('Y-m-d H:i:s')
            ]);

            $all_data = $m->get_my_portfolio($user_id, $role_id, $keyword, $filters, 0, 0);
            
            foreach ($all_data as &$row) {
                $row['status'] = $row['app_status_label'];
            }

            $data['export_data'] = $all_data;

            $this->load->view('home_export', $data);
            return;
        }

        $config['total_rows'] = $total_rows;
        $this->pagination->initialize($config);

        $data['pagination'] = $this->pagination->create_links();
        $data['my_portfolio'] = $m->get_my_portfolio($user_id, $role_id, $keyword, $filters, $config['per_page'], $page);
        $data['total_rows'] = $total_rows;
        if ($role_id == 1) {
            // IT SLM (Role 1) harus bisa melihat Task milik EA (Role 2) agar bisa melihat draf yang baru disave
            $tasks_ea = $m->get_my_tasks($user_id, 2);
            $tasks_slm = $m->get_my_tasks($user_id, 1);
            
            $merged_tasks = array_merge($tasks_ea, $tasks_slm);
            
            // Hapus duplikasi jika ada ID aplikasi yang sama
            $unique_tasks = [];
            $temp_ids = [];
            foreach ($merged_tasks as $task) {
                if (!in_array($task['apps_id'], $temp_ids)) {
                    $unique_tasks[] = $task;
                    $temp_ids[] = $task['apps_id'];
                }
            }
            $data['my_tasks'] = $unique_tasks;
        } else {
            $data['my_tasks'] = $m->get_my_tasks($user_id, $role_id);
        }
        $data['total_tasks'] = count($data['my_tasks']);
        
        $this->load->view('home_view', $data);
    }

     public function detail($apps_id = 0, $service_id = 0) {
        // SECURITY: Pastikan parameter dari URL selalu berupa integer untuk mencegah SQL Injection
        $apps_id = (int)$apps_id;
        $service_id = (int)$service_id;

        $user_id = $this->session->userdata('user_id');
        $role_id = $this->session->userdata('role_id');
        $fixed_role = $this->Home_model->_get_fixed_role($user_id, $role_id);

        $data['title'] = ($apps_id == 0) ? 'Create Portofolio' : 'Portofolio Detail';
        $data['apps_id'] = $apps_id;
        $data['rid'] = $fixed_role; 
        $data['service_id_param'] = $service_id; 
        
        $data['msg_success'] = $this->session->flashdata('success');
        $data['msg_error']   = $this->session->flashdata('error');

        if ($apps_id == 0) {
            // Tambahkan kondisi OR agar Role 1 (IT SLM) juga bisa masuk
            if ($fixed_role != 2 && $fixed_role != 1) { 
                $this->session->set_flashdata('error', 'Hanya EA atau IT SLM yang boleh membuat.');
                redirect('home');
            }
            $data['mode'] = 'add';
            $data['row']  = []; 
            $data['is_readonly'] = false;
            $data['selected_db_ids'] = [];
            $data['selected_os_ids'] = [];
            $data['selected_srv_ids'] = []; 
            $data['is_draft_status'] = true;
        } else {
            $data['row'] = $this->Home_model->get_portfolio_full_detail($apps_id);
            if(empty($data['row'])) { show_404(); }

            $data['selected_db_ids'] = !empty($data['row']['database_ids_str']) ? explode(',', $data['row']['database_ids_str']) : [];
            $data['selected_os_ids'] = !empty($data['row']['os_ids_str']) ? explode(',', $data['row']['os_ids_str']) : [];
            $data['selected_srv_ids'] = !empty($data['row']['server_ids_str']) ? explode(',', $data['row']['server_ids_str']) : [];
            $current_stage = $this->Home_model->get_current_approval_stage($apps_id);
            $curr_role_turn = isset($current_stage['user_role_id']) ? $current_stage['user_role_id'] : 0;
            $is_status_pending = (isset($current_stage['status']) && $current_stage['status'] == 0);

            if (($curr_role_turn == $fixed_role || ($fixed_role == 1 && $curr_role_turn == 2)) && $is_status_pending) {
                $data['mode'] = 'edit';
                $data['is_readonly'] = false;
            } else {
                $data['mode'] = 'view';
                $data['is_readonly'] = true;
            }
            
            // --- TAMBAHAN 2: Flag untuk memberi tahu view jika data ini masih status "Drafting" ---
            $app_status_label = isset($data['row']['app_status_label']) ? $data['row']['app_status_label'] : '';
            $data['is_draft_status'] = ($app_status_label === 'Drafting' || ($curr_role_turn == 2 && $is_status_pending));
        }

        $m = $this->Home_model;
        
        $data['opt_app_type']   = $m->get_master_data('tbl_app_type'); 
        $data['opt_category']   = $m->get_master_data('tbl_apps_category'); 
        $data['opt_deploy']     = $m->get_master_data('tbl_apps_deployment'); 
        $data['opt_provider']   = $m->get_master_data('tbl_apps_deployment_model'); 
        $data['opt_site']       = $m->get_master_data('tbl_apps_deployment_site'); 
        
        $data['opt_network']    = $m->get_master_data('tbl_apps_network');
        $data['opt_day']        = $m->get_master_data('tbl_apps_operational_day');
        $data['opt_hour']       = $m->get_master_data('tbl_apps_operational_hour');
        $data['opt_resilience'] = $m->get_master_data('tbl_resilience');
        $data['opt_database']   = $m->get_master_data('tbl_database_master');
        $data['opt_os']         = $m->get_master_data('tbl_operating_software');
        $data['opt_server']     = $m->get_master_data('tbl_server');
        
        if ($apps_id > 0) {
            $data['sla_history'] = $this->Home_model->get_sla_history($apps_id);
            $data['timeline']    = $m->get_timeline_data($apps_id);
            $data['audit_trail'] = $m->get_audit_trail($apps_id);
            $data['documents']   = $m->get_documents($apps_id);
            $data['is_done']     = $m->is_app_done($apps_id);
            
            // --- TAMBAHAN DETEKSI RENEWAL ---
            // Jika ada field khusus 'is_renewal' di database, pakai itu. 
            // Jika tidak, logikanya: Aplikasi belum "done" tapi sudah punya riwayat SLA sebelumnya.
            $db_renewal_flag = isset($data['row']['is_renewal']) && $data['row']['is_renewal'] == 1;
            $data['is_in_renewal'] = $db_renewal_flag || (!$data['is_done'] && !empty($data['sla_history']));
        } else {
            $data['sla_history'] = []; 
            // Tetap kirimkan array kosong agar tab Workflow, Audit, dan SLA Doc yang muncul tidak error
            $data['timeline'] = []; 
            $data['audit_trail'] = []; 
            $data['documents'] = [];
            $data['is_done']  = false; 
            
            // --- TAMBAHAN DETEKSI RENEWAL ---
            $data['is_in_renewal'] = false;

            // Inisialisasi variabel pendukung agar View tidak undefined
            $data['row']['database_ids_str'] = "";
            $data['row']['os_ids_str'] = "";
            $data['row']['server_ids_str'] = "";
        }

        if (in_array($fixed_role, [1, 2])) {
            // --- PERBAIKAN: TARIK DRAFT MILIK EA JIKA USER ADALAH IT SLM ---
            $fetch_role_id = ($role_id == 1) ? 2 : $role_id;
            
            // Tambahkan parameter false dan array filter untuk mengecualikan RENEWAL
            // Kita memodifikasi pemanggilan agar tidak mengambil data yang punya action 'RENEWAL' di audit trail
            $data['draft_list'] = $this->Home_model->get_my_tasks($user_id, $fetch_role_id, false);
            // ---------------------------------------------------------------
            
            // Logic Tambahan: Filter manual hasil array agar data RENEWAL tidak muncul di list modal
            if (!empty($data['draft_list'])) {
                foreach ($data['draft_list'] as $key => $task) {
                    $check_renewal = $this->db->where('apps_id', $task['apps_id'])
                                             ->where('action', 'RENEWAL')
                                             ->count_all_results('tbl_apps_audit_trail');
                    if ($check_renewal > 0) {
                        unset($data['draft_list'][$key]);
                    }
                }
                // Reset index array agar tetap rapi
                $data['draft_list'] = array_values($data['draft_list']);
            }
        } else {
            $data['draft_list'] = [];
        }

        $this->load->view('home_detail_view', $data);
    }

    public function save_submission() {
        $user_id = $this->session->userdata('user_id');
        $role_id = $this->session->userdata('role_id');
        
        $apps_id = (int)$this->security->xss_clean($this->input->post('apps_id'));
        $save_type = $this->security->xss_clean($this->input->post('save_type')); // Kembalikan deteksi tombol
        $post_data = $this->security->xss_clean($this->input->post());
        $remarks = $this->security->xss_clean($this->input->post('remarks'));
		
        // --- 1. TAMBAHKAN KODE INI UNTUK CEK GILIRAN & MANIPULASI ROLE ---
        $current_stage = $this->Home_model->get_current_approval_stage($apps_id);
        $curr_role_turn = isset($current_stage['user_role_id']) ? $current_stage['user_role_id'] : 0;

        $effective_role_id = $role_id;
        
        // JIKA user adalah IT SLM (1) dan ini giliran EA (2) ATAU sedang membuat aplikasi baru (apps_id == 0)
        if ($role_id == 1 && ($curr_role_turn == 2 || $apps_id == 0)) {
            $effective_role_id = 2; // IT SLM "menyamar" menjadi EA
            
            if (empty($remarks)) {
                $ci =& get_instance();
                $cek_renewal = 0;
                
                if ($apps_id > 0) {
                    $cek_renewal = $ci->db->where('apps_id', $apps_id)
                                          ->where('action', 'RENEWAL')
                                          ->count_all_results('tbl_apps_audit_trail');
                }
                
                if ($cek_renewal > 0) {
                    $remarks = 'Renewal Process: Auto-submitted by IT SLM on behalf of EA.';
                } elseif ($save_type == 'submit') {
                    // Catat aktivitas Submit otomatis sebagai EA sesuai request flow
                    $remarks = 'Application Submitted by IT SLM on behalf of Enterprise Architecture (EA).';
                } else {
                    // Catat sebagai Draft EA jika baru Save
                    $remarks = 'Draft Saved by IT SLM on behalf of Enterprise Architecture (EA).';
                }
            }
        }
        // -----------------------------------------------------------------
        
        if ($save_type == 'change_owner') {
            $this->Home_model->process_change_ownership($apps_id, $post_data, $user_id, $role_id);
            $this->session->set_flashdata('success', 'Data Ownership berhasil diubah.');
            redirect('home');
            return;
        }
        
        if ($role_id == 3) {
            $is_submit = true; 
        } else {
            $is_submit = ($save_type == 'submit') ? true : false;
        }
        
        $app_name = isset($post_data['application_name']) ? $post_data['application_name'] : '';
        $module_name = isset($post_data['module']) ? $post_data['module'] : '';

        // Cek Duplikasi Nama dan Modul
        if (!empty($app_name) && !empty($module_name)) {
            $is_duplicate = $this->Home_model->check_duplicate($app_name, $module_name, $apps_id);
            
            if ($is_duplicate) {
                $redirect_id = empty($apps_id) ? 0 : $apps_id;
                $this->session->set_flashdata('duplicate_error', 'Gagal menyimpan! Aplikasi dengan nama <b>"'.$app_name.'"</b> dan module <b>"'.$module_name.'"</b> sudah ada.');
                redirect('home/detail/'.$redirect_id);
                return; 
            }
        }

        $action_string = $is_submit ? 'SUBMIT' : 'SAVE';
        $is_app_done = $this->Home_model->is_app_done($apps_id);
        
        // Logika Renewal khusus jika itu adalah Submit Final
        if ($is_submit && $is_app_done) {
            $action_string = 'SUBMIT-RENEWAL';
            
            $remarks = $this->input->post('remarks_renewal'); 
            if(empty($remarks)) {
                $remarks = 'Aplikasi masuk masa perpanjangan (Renewal).';
            }
            
            $post_data['remarks'] = $remarks; 
            $this->Home_model->insert_audit_trail($apps_id, $role_id, "SUBMIT", $remarks);
        }

        $saved_apps_id = $this->Home_model->save_apps_info($apps_id, $post_data, $is_submit, $effective_role_id, $remarks, $action_string);

        if ($is_submit && $effective_role_id == 1) { 
            // PERBAIKAN: Gunakan $apps_id jika sedang update, cegah boolean TRUE ter-cast menjadi ID 1
            $target_id = ($apps_id > 0) ? $apps_id : (is_array($saved_apps_id) && isset($saved_apps_id['id']) ? $saved_apps_id['id'] : (int)$saved_apps_id);
            
            // Cek apakah setelah disubmit, status aplikasi menjadi selesai (DONE)
            $is_now_done = $this->Home_model->is_app_done($target_id);
            if ($is_now_done) {
                $this->_generate_and_save_sla($target_id); // Akan otomatis jalan di tahap akhir
            }
        }

        
        if ($save_type == 'save_stay' && $effective_role_id == 2 && !empty($remarks)) { 
            $target_apps_id = ($apps_id > 0) ? $apps_id : (is_array($saved_apps_id) && isset($saved_apps_id['id']) ? $saved_apps_id['id'] : $saved_apps_id);
            if ($target_apps_id > 0) {
                $this->db->where('apps_id', $target_apps_id)
                         ->where('user_role_id', 2)
                         ->update('tbl_apps_approval', ['remarks' => $remarks]);
            }
        }

        if (!$is_submit) {
            $this->session->set_flashdata('saved_app_name', $app_name);
            $this->session->set_flashdata('success', 'Draft berhasil tersimpan.');
            
            if ($save_type == 'save_stay') {
                redirect('home/detail/0?keep_name=1');
            } else {
                redirect('home'); 
            }
            return;
        }

        // --- PERBAIKAN REDIRECT SUBMIT ---
        if ($saved_apps_id) { // Asumsi jika query berhasil, variabel ini bernilai true/array/id
            if(is_array($saved_apps_id) && isset($saved_apps_id['msg'])) {
                $this->session->set_flashdata('success', $saved_apps_id['msg']);
            } else {
                $this->session->set_flashdata('success', 'Data submitted successfully.');
            }
            redirect('home'); // Jika BERHASIL, redirect ke Home
        } else {
            $this->session->set_flashdata('error', 'Terjadi kesalahan. Proses Submit gagal!');
            redirect('home/detail/'.$apps_id); // Jika GAGAL, tetap di Detail
        }
    }

    public function bulk_submit() {
        $user_id = $this->session->userdata('user_id');
        $role_id = $this->session->userdata('role_id');
        
        $modal_remarks = $this->security->xss_clean($this->input->post('remarks'));
        $selected_apps = $this->security->xss_clean($this->input->post('selected_apps')); 

        if(empty($selected_apps) || !is_array($selected_apps)) {
            $this->session->set_flashdata('error', 'Tidak ada aplikasi yang dipilih untuk disubmit.');
            redirect('home/detail/0');
            return;
        }

        $incomplete = [];
        
        $mandatory_fields = [
            'application_name', 'short_name', 'module', 'apps_description',
            'category_id', 'app_type_id', 'deployment_id', 'deployment_provider_id', 'deployment_site_id',
            'lob_directorate', 'lob_subdirectorate', 'lob_department_head', 'lob_group', 'lob_group_head',
            'it_subdirectorate', 'it_department_head', 'it_support_group', 'it_group_head', 'it_support_divison', 'it_division_head',
            'solution_vendor', 'services_vendor', 'live_year', 'resilience_id', 'network_id', 'source_code_owned', 'Url',
            // Checkbox Multiple (Database akan membacanya sebagai string teks)
            'database_names_str', 'os_names_str', 'server_names_str' 
        ];

        // ATURAN KHUSUS ROLE 1 IT SLM
        if ($role_id == 1) {
            array_push($mandatory_fields, 'operational_day_id', 'operational_hour_id', 'standard_category');
        }
        // ====================================================================

        foreach($selected_apps as $apps_id) {
            $app = $this->Home_model->get_portfolio_full_detail((int)$apps_id);
            if (!$app) continue;

            foreach($mandatory_fields as $field) {
                // Cek apakah data kosong (Angka 0 tetap dianggap sah)
                if (empty($app[$field]) && $app[$field] !== '0' && $app[$field] !== 0) { 
                    $app_name_err = !empty($app['application_name']) ? $app['application_name'] : 'Draft ID '.$apps_id;
                    if(!in_array($app_name_err, $incomplete)) {
                        $incomplete[] = $app_name_err;
                    }
                    break; // Jika ketemu 1 saja form yang kosong, langsung stop cek form lain dan tandai aplikasinya
                }
            }
        }
        
        if (!empty($incomplete)) {
            $this->session->set_flashdata('error', 'Gagal Submit! Ada data yang belum lengkap pada aplikasi: <br><br><b>' . implode(', ', $incomplete).'</b><br><br>');
            redirect('home/detail/0');
            return;
        }
        
        foreach($selected_apps as $apps_id) {
            $final_remarks = $modal_remarks;

            // Tarik remarks "titipan" saat EA melakukan Bulk Submit
            if ($role_id == 2) {
                $saved_approval = $this->db->get_where('tbl_apps_approval', ['apps_id' => $apps_id, 'user_role_id' => 2])->row_array();
                
                if (!empty($saved_approval['remarks'])) {
                    // Gabungkan remarks dari popup Modal dengan Auto-Remarks
                    if (empty($final_remarks) || trim($final_remarks) == '-') {
                        $final_remarks = $saved_approval['remarks'];
                    } else {
                        $final_remarks = trim($final_remarks) . "<br><br>" . $saved_approval['remarks'];
                    }
                }
            }


            // --- 1. UBAH KODE INI UNTUK CEK GILIRAN & MANIPULASI ROLE ---
            $current_stage = $this->Home_model->get_current_approval_stage($apps_id);
            $curr_role_turn = isset($current_stage['user_role_id']) ? $current_stage['user_role_id'] : 0;

            $effective_role_id = $role_id;
            
            // IT SLM (1) hanya menyamar menjadi EA (2) jika sedang buat baru ATAU edit draf EA
            // Jika curr_role_turn == 1 (tahap final), maka tidak perlu menyamar
            if ($role_id == 1 && ($apps_id == 0 || $curr_role_turn == 2)) {
                $effective_role_id = 2; // IT SLM "menyamar" menjadi EA
                
                // PERBAIKAN 1: Ganti $remarks menjadi $final_remarks
                if (empty($final_remarks)) {
                    $cek_renewal = 0;
                    
                    if ($apps_id > 0) {
                        // Tidak perlu $ci =& get_instance(), langsung pakai $this->db
                        $cek_renewal = $this->db->where('apps_id', $apps_id)
                                              ->where('action', 'RENEWAL')
                                              ->count_all_results('tbl_apps_audit_trail');
                    }
                    
                    if ($cek_renewal > 0) {
                        $final_remarks = 'Renewal Process: Auto-submitted by IT SLM on behalf of EA.';
                    } else {
                        // PERBAIKAN 2: Hapus pengecekan $save_type karena di bulk_submit aksinya selalu SUBMIT
                        $final_remarks = 'Application Submitted by IT SLM on behalf of Enterprise Architecture (EA).';
                    }
                }
            }
            // -----------------------------------------------------------------

            // ==============================================================
            // PERBAIKAN 3: INI BARIS YANG HILANG (EKSEKUSI KE DATABASE)
            // ==============================================================
            $this->Home_model->advance_workflow($apps_id, $effective_role_id, 'SUBMIT', $final_remarks);

            // Opsional: Jika yang melakukan submit adalah tahap akhir (Role 1), generate SLA
            if ($effective_role_id == 1) {
                if ($this->Home_model->is_app_done($apps_id)) {
                    $this->_generate_and_save_sla($apps_id);
                }
            }

        } // Akhir dari foreach
        
        $this->session->set_flashdata('success', count($selected_apps) . ' aplikasi berhasil disubmit.');
        redirect('home');
    }
    
    public function delete_draft($apps_id) {
        $apps_id = (int)$apps_id; // SECURITY: Type casting mencegah SQL Injection
        $role_id = $this->session->userdata('role_id');
        
        if ($role_id != 2) {
            $this->session->set_flashdata('error', 'Anda tidak memiliki hak akses untuk menghapus data ini.');
            redirect('home');
            return;
        }

        $deleted = $this->Home_model->delete_app($apps_id);

        if ($deleted) {
            $this->session->set_flashdata('success', 'Draft berhasil dihapus.');
        } else {
            $this->session->set_flashdata('error', 'Gagal menghapus draft. Terjadi kesalahan pada database.');
        }

        redirect('home/detail/0');
    }
    
    public function export_sla_pdf($apps_id) {
        $apps_id = (int)$apps_id; 

        $is_done = $this->Home_model->is_app_done($apps_id);

        if (!$is_done) {
            $this->session->set_flashdata('error', 'SLA Document belum bisa diunduh karena aplikasi belum berstatus DONE.');
            redirect('home/detail/'.$apps_id);
            return;
        }

        // --- PASTIKAN MENGGUNAKAN get_portfolio_full_detail ---
        // Fungsi ini yang menarik kolom server_names_str, os_names_str, dll.
        $data['app'] = $this->Home_model->get_portfolio_full_detail($apps_id);
        
        if (empty($data['app'])) {
            show_404();
        }

        $html = $this->load->view('document_sla_export', $data, TRUE);

        require_once FCPATH . 'vendor/autoload.php';

        try {
            $html2pdf = new \Spipu\Html2Pdf\Html2Pdf('P', 'A4', 'en', true, 'UTF-8', array(10, 10, 10, 10));
            $html2pdf->pdf->SetDisplayMode('fullpage');
            $html2pdf->writeHTML($html);
            
            // Logika penamaan file... (tetap seperti kode Anda)
            $category_id = isset($data['app']['category_id']) ? (int)$data['app']['category_id'] : 0;
            $cat_name = isset($data['app']['category_name']) ? strtoupper($data['app']['category_name']) : '';
            
            $total_in_category = $this->db->where('category_id', $category_id)->count_all_results('tbl_portofolio_apps_master');
            $total_count = ($total_in_category > 0) ? $total_in_category : 1;
            $doc_version = str_pad($total_count, 4, '0', STR_PAD_LEFT);
            
            $cat_initial = 'O'; 
            if ($cat_name == 'CRITICAL') { $cat_initial = 'C'; } 
            elseif ($cat_name == 'VERY IMPORTANT') { $cat_initial = 'V'; } 
            elseif ($cat_name == 'IMPORTANT') { $cat_initial = 'I'; } 
            elseif ($cat_name == 'NECESSARY') { $cat_initial = 'N'; }
            
            $app_date = !empty($data['app']['modified_at']) ? $data['app']['modified_at'] : date('Y-m-d H:i:s');
            $doc_month = date('m', strtotime($app_date));
            $doc_year  = date('Y', strtotime($app_date));
            
            $filename = "SLA.{$doc_version}.{$cat_initial}.{$doc_month}.{$doc_year}.pdf";
            
            $html2pdf->output($filename, 'D'); 
            
        } catch (\Spipu\Html2Pdf\Exception\Html2PdfException $e) {
            $html2pdf->clean();
            $formatter = new \Spipu\Html2Pdf\Exception\ExceptionFormatter($e);
            echo $formatter->getHtmlMessage();
        }
    }
    
    public function toggle_status($apps_id = 0, $status = null) {
        // SECURITY: Ambil dari parameter URL atau dari POST jika parameter URL kosong
        $apps_id = ($apps_id > 0) ? (int)$apps_id : (int)$this->input->post('apps_id');
        
        if ($status === null) {
            $status = (int)$this->input->post('status');
        } else {
            $status = (int)$status;
        }

        $role_id = $this->session->userdata('role_id');
        $user_id = $this->session->userdata('user_id'); 
        
        if ($role_id != 1 && $role_id != 2) {
            $this->session->set_flashdata('error', 'Anda tidak memiliki hak akses.');
            redirect('home');
            return;
        }

        if ($apps_id <= 0) {
            $this->session->set_flashdata('error', 'ID Aplikasi tidak valid.');
            redirect('home');
            return;
        }

        $new_status = ($status == 1) ? 1 : 0;
        $decom_year = ($new_status == 0) ? date('Y') : null;
        
        $uploaded_filename = null;
        if (!empty($_FILES['attached_document']['name'])) {
            $config['upload_path']   = './uploads/documents/'; 
            
            // [PERBAIKAN] Gunakan '*' untuk bypass deteksi MIME CI yang bermasalah
            $config['allowed_types'] = '*';
            $config['max_size']      = 0;
            $config['encrypt_name']  = FALSE; 
            $config['detect_mime']   = FALSE; 
            $config['xss_clean']     = FALSE;

            $this->load->library('upload');
            $this->upload->initialize(array()); 
            $this->upload->initialize($config); 

            if ($this->upload->do_upload('attached_document')) {
                $upload_data = $this->upload->data();
                
                // [VALIDASI MANUAL] Cek ekstensi secara manual demi keamanan
                $file_ext = strtolower(pathinfo($_FILES['attached_document']['name'], PATHINFO_EXTENSION));
                if ($file_ext !== 'pdf') {
                    unlink($upload_data['full_path']); // Hapus file jika bukan PDF
                    $this->session->set_flashdata('error', 'Gagal: Hanya file PDF yang diizinkan untuk Memo Decommission.');
                    redirect('home');
                    return;
                }
                
                $uploaded_filename = $upload_data['file_name'];
            } else {
                $error_msg = strip_tags($this->upload->display_errors('', ''));
                $this->session->set_flashdata('error', 'Gagal merubah status. Upload dokumen error: ' . $error_msg);
                redirect('home');
                return;
            }
        }
        
        $audit_action = ($new_status == 1) ? 'ACTIVATE' : 'DEACTIVATE';
        $remarks = $this->security->xss_clean($this->input->post('remarks'));
        if (empty($remarks)) {
            $remarks = ($new_status == 1) ? "Application Activated" : "Application Decomissioned";
        }

        $this->Home_model->update_app_status($apps_id, $new_status, $uploaded_filename, $decom_year, $user_id, $role_id, $audit_action, $remarks);

        $action_name = ($new_status == 1) ? 'di-Activate' : 'di-Decomission';
        $this->session->set_flashdata('success', "Aplikasi berhasil $action_name.");
        redirect('home');
    }
    
    public function download_sla_version($file_name) {
        // SECURITY: sanitize_filename mencegah penyerang mengunduh file sistem 
        // dengan teknik Path Traversal seperti '../../etc/passwd'
        $file_name = $this->security->sanitize_filename($file_name);

        $this->load->helper('download');
        $path = './uploads/documents/' . $file_name;
        
        if (file_exists($path) && is_file($path)) {
            force_download($path, NULL);
        } else {
            $this->session->set_flashdata('error', 'File fisik tidak ditemukan di server.');
            redirect($_SERVER['HTTP_REFERER']);
        }
    }
    
    private function _generate_and_save_sla($apps_id) {
        $apps_id = (int)$apps_id;

        // --- PASTIKAN MEMANGGIL get_portfolio_full_detail ---
        $data['app'] = $this->Home_model->get_portfolio_full_detail($apps_id);
        if (empty($data['app'])) return false;

        $html = $this->load->view('document_sla_export', $data, TRUE);
        require_once FCPATH . 'vendor/autoload.php';

        try {
            $html2pdf = new \Spipu\Html2Pdf\Html2Pdf('P', 'A4', 'en', true, 'UTF-8', array(10, 10, 10, 10));
            $html2pdf->pdf->SetDisplayMode('fullpage');
            $html2pdf->writeHTML($html);
            
            // Logika penamaan file... (tetap seperti kode Anda)
            $category_id = isset($data['app']['category_id']) ? (int)$data['app']['category_id'] : 0;
            $cat_name = isset($data['app']['category_name']) ? strtoupper($data['app']['category_name']) : '';
            
            $total_in_category = $this->db->where('category_id', $category_id)->count_all_results('tbl_portofolio_apps_master');
            $total_count = ($total_in_category > 0) ? $total_in_category : 1;
            $doc_version = str_pad($total_count, 4, '0', STR_PAD_LEFT);
            
            $cat_initial = 'O'; 
            if ($cat_name == 'CRITICAL') { $cat_initial = 'C'; } 
            elseif ($cat_name == 'VERY IMPORTANT') { $cat_initial = 'V'; } 
            elseif ($cat_name == 'IMPORTANT') { $cat_initial = 'I'; } 
            elseif ($cat_name == 'NECESSARY') { $cat_initial = 'N'; }
            
            $app_date = !empty($data['app']['modified_at']) ? $data['app']['modified_at'] : date('Y-m-d H:i:s');
            $doc_month = date('m', strtotime($app_date));
            $doc_year  = date('Y', strtotime($app_date));
            
            $filename = "SLA.{$doc_version}.{$cat_initial}.{$doc_month}.{$doc_year}.pdf";
            
            $filepath = FCPATH . 'uploads/documents/' . $filename;
            $html2pdf->output($filepath, 'F'); 
            
            $this->Home_model->insert_sla_history($apps_id, $filename, 'Auto-Generated SLA on Final Approval');
            
            return true;
        } catch (\Spipu\Html2Pdf\Exception\Html2PdfException $e) {
            return false;
        }
    }
    
    public function trigger_renewal($apps_id) {
        $apps_id = (int)$apps_id; // SECURITY: Type casting

        $user_id = $this->session->userdata('user_id');
        $role_id = $this->session->userdata('role_id');
        
        if ($role_id != 1 && $role_id != 2) {
            $this->session->set_flashdata('error', 'Akses Ditolak: Hanya Enterprise Architecture (EA) yang dapat melakukan Renewal.');
            redirect('home');
            return;
        }
        
        $is_success = $this->Home_model->process_renewal($apps_id, $user_id, $role_id);

        if ($is_success) {
            $this->session->set_flashdata('success', 'Proses Renewal berhasil dimulai.');
            // --- UBAH REDIRECT DI SINI ---
            redirect('home/detail/'.$apps_id);
        } else {
            $this->session->set_flashdata('error', 'Gagal memproses Renewal. Terjadi kesalahan pada database.');
        }

        redirect('home');
    }
    
    public function check_duplicate_ajax() {
        $app_name = $this->security->xss_clean($this->input->post('application_name'));
        $module_name = $this->security->xss_clean($this->input->post('module'));
        $apps_id = (int)$this->input->post('apps_id');

        $is_duplicate = $this->Home_model->check_duplicate($app_name, $module_name, $apps_id);
        
        echo json_encode(['is_duplicate' => $is_duplicate]);
    }
	
	public function cancel_renewal($apps_id) {
        $apps_id = (int)$apps_id;
        $user_id = $this->session->userdata('user_id');
        $role_id = $this->session->userdata('role_id');
        
        // Izinkan jika role_id adalah IT SLM (1) ATAU EA (2)
        if ($role_id != 1 && $role_id != 2) {
            $this->session->set_flashdata('error', 'Akses Ditolak: Hanya Enterprise Architecture (EA) dan IT SLM yang dapat membatalkan renewal.');
            redirect('home');
            return;
        }
        
        $remarks = $this->security->xss_clean($this->input->post('remarks'));
        
        $is_success = $this->Home_model->cancel_renewal($apps_id, $user_id, $role_id, $remarks);

        // --- PERBAIKAN REDIRECT CANCEL RENEWAL ---
        if ($is_success) {
            $this->session->set_flashdata('success', 'Renewal berhasil dibatalkan.');
            redirect('home'); // Jika BERHASIL, redirect ke Home
        } else {
            $this->session->set_flashdata('error', 'Terjadi kesalahan saat membatalkan renewal.');
            redirect('home/detail/'.$apps_id); // Jika GAGAL, tetap di Detail
        }
    }
	
	// Tambahkan di dalam Controller Home.php
    public function api_check_master_usage() {
        // Pastikan hanya bisa diakses via AJAX
        if (!$this->input->is_ajax_request()) {
            exit('No direct script access allowed');
        }

        $master_table = $this->security->xss_clean($this->input->post('table_name'));
        $id_value     = (int)$this->input->post('id_value');

        // Tanya ke Model apakah data dipakai
        $is_used = $this->Home_model->check_master_usage($master_table, $id_value);

        // Kembalikan jawaban dalam bentuk JSON
        echo json_encode([
            'status'  => 'success',
            'is_used' => $is_used
        ]);
    }
	
	public function export_audit($apps_id) {
        $apps_id = (int)$apps_id;

        $app = $this->Home_model->get_portfolio_full_detail($apps_id);
        if (!$app) {
            $this->session->set_flashdata('error', 'Aplikasi tidak ditemukan.');
            redirect('home');
            return;
        }

        $data['audit_trail'] = $this->Home_model->get_audit_trail($apps_id);
        $data['app_name'] = $app['application_name'];
        
        $this->load->model('audit/Audit_model');
        $this->Audit_model->insert_log([
            'username'    => $this->session->userdata('username'),
            'action'      => 'EXPORT',
            'table_name'  => 'tbl_apps_audit_trail', 
            'foreign_id'  => $apps_id,
            'field_name'  => '-',
            'old_value'   => '-',
            'new_value'   => '-',
            'reason'      => 'Export Audit Data untuk Aplikasi ID: ' . $apps_id,
            'timestamp'   => date('Y-m-d H:i:s')
        ]);

        $this->load->view('audit_export_view', $data);
    }
	
	public function generate_template() {
		if ($this->session->userdata('role_id') != 1) {
			show_error('Akses Ditolak. Anda tidak memiliki izin untuk mengunduh template ini.', 403, 'Forbidden');
			return;
		}
		
		$spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();

		$sheet1 = $spreadsheet->getActiveSheet();
		$sheet1->setTitle('tbl_portofolio_apps_master');

		$fields = $this->db->list_fields('tbl_portofolio_apps_master');

		$exclude_columns = [
            'apps_id',
            'server_id', // TAMBAHKAN INI
            'attached_document',
            'created_by',
            'created_at',
            'modified_by',
            'modified_at',
            'approved_by',
            'approved_at',
            'status'
        ];

		$allowed_columns = array_diff($fields, $exclude_columns);
		
		// ===== VIRTUAL COLUMNS UNTUK MULTIPLE INFRASTRUCTURE =====
		$allowed_columns[] = 'database_ids';
		$allowed_columns[] = 'os_ids';
		$allowed_columns[] = 'server_ids';
		// =========================================================

		$colLetter = 'A';
		foreach ($allowed_columns as $col_name) {
			$sheet1->setCellValue($colLetter . '1', $col_name);
			$sheet1->getStyle($colLetter . '1')->getFont()->setBold(true);
			$spreadsheet->getActiveSheet()->getColumnDimension($colLetter)->setAutoSize(true);
			$colLetter++;
		}

		// ===== TAMBAHKAN 3 TABEL INFRA KE DALAM MASTER TABLES =====
		$master_tables = [
			'tbl_resilience',
            'tbl_apps_deployment',
            'tbl_apps_deployment_model',
            'tbl_apps_deployment_site',
            'tbl_apps_network',
            'tbl_apps_category',
            'tbl_apps_operational_hour',
            'tbl_apps_operational_day',
            'tbl_app_type',
            'tbl_database_master',
            'tbl_operating_software',
            'tbl_server'
		];

		$sheetIndex = 1;
		foreach ($master_tables as $tableName) {
			// Cegah error jika ternyata tabel master belum dibuat di database
			if (!$this->db->table_exists($tableName)) continue; 

			$spreadsheet->createSheet($sheetIndex);
			$spreadsheet->setActiveSheetIndex($sheetIndex);
			$currentSheet = $spreadsheet->getActiveSheet();
			
			$currentSheet->setTitle($tableName);

			$data = $this->db->get($tableName)->result_array();

			if (!empty($data)) {
				$headers = array_keys($data[0]);
				$colLtr = 'A';
				foreach ($headers as $header) {
					$currentSheet->setCellValue($colLtr . '1', $header);
					$currentSheet->getStyle($colLtr . '1')->getFont()->setBold(true);
					$currentSheet->getColumnDimension($colLtr)->setAutoSize(true);
					$colLtr++;
				}

				$rowNum = 2;
				foreach ($data as $row) {
					$colLtr = 'A';
					foreach ($row as $val) {
						$currentSheet->setCellValue($colLtr . $rowNum, $val);
						$colLtr++;
					}
					$rowNum++;
				}
			}
			$sheetIndex++;
		}

		$spreadsheet->setActiveSheetIndex(0);

		$filename = 'Template_Excel_SLIM.xlsx';

		header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		header('Content-Disposition: attachment;filename="' . $filename . '"');
		header('Cache-Control: max-age=0');

		$writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
		$writer->save('php://output');
		exit;
	}
	
	public function import_excel() {
        if ($this->session->userdata('role_id') != 1) {
            show_error('Akses Ditolak.', 403);
            return;
        }

        if (!isset($_FILES['file_excel']) || $_FILES['file_excel']['error'] !== UPLOAD_ERR_OK) {
            $error_code = isset($_FILES['file_excel']['error']) ? $_FILES['file_excel']['error'] : 'Unknown';
            $this->session->set_flashdata('error', 'Gagal mengunggah file. (Error Code: ' . $error_code . '). Pastikan ukuran file aman.');
            redirect('home');
            return;
        }

        if (isset($_FILES['file_excel']['name'])) {
            $ext = strtolower(pathinfo($_FILES['file_excel']['name'], PATHINFO_EXTENSION));
            $upload_path = './uploads/documents/';
            
            if (!is_dir($upload_path)) {
                mkdir($upload_path, 0777, true);
            }
            
            $temp_filename = 'temp_import_' . time() . '_' . rand(1000, 9999) . '.' . $ext;
            $temp_filepath = $upload_path . $temp_filename;

            if (move_uploaded_file($_FILES['file_excel']['tmp_name'], $temp_filepath)) {
                try {
                    $inputFileType = \PhpOffice\PhpSpreadsheet\IOFactory::identify($temp_filepath);
                    $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader($inputFileType);
                    
                    $reader->setReadDataOnly(true);
                    
                    $spreadsheet = $reader->load($temp_filepath);
                    
                    if (file_exists($temp_filepath)) unlink($temp_filepath);
                    
                    $sheet = $spreadsheet->getSheetByName('tbl_portofolio_apps_master');
                    
                    if (!$sheet) {
                        $this->session->set_flashdata('error', 'Sheet <b>tbl_portofolio_apps_master</b> tidak ditemukan.');
                        redirect('home');
                        return; 
                    }

                    $data = $sheet->toArray();
                    
                    if (count($data) <= 1) {
                        $this->session->set_flashdata('error', 'File Excel Kosong!');
                        redirect('home');
                        return; 
                    }

                    $all_fields = $this->db->list_fields('tbl_portofolio_apps_master');
                    $exclude_columns = [
                        'apps_id', 
                        'server_id', 
                        'attached_document', 
                        'created_by', 
                        'created_at', 
                        'modified_by', 
                        'modified_at', 
                        'approved_by', 
                        'approved_at', 
                        'status' // <-- Pastikan tidak ada karakter aneh setelah kata 'status'
                    ];
                    
                    $expected_header = array_values(array_diff($all_fields, $exclude_columns));
                    $expected_header[] = 'database_ids';
                    $expected_header[] = 'os_ids';
                    $expected_header[] = 'server_ids';
                    
                    $expected_header = array_values(array_map('trim', $expected_header));

                    $raw_uploaded_header = $data[0]; 
                    $uploaded_header = array_filter($raw_uploaded_header, function($val) {
                        return !is_null($val) && trim((string)$val) !== '';
                    });
                    $uploaded_header = array_values(array_map('trim', $uploaded_header));

                    if ($uploaded_header !== $expected_header) {
                        $this->session->set_flashdata('error', 'Template Excel tidak sesuai.');
                        redirect('home');
                        return;
                    }

                    $import_data = [];
                    $error_messages = []; 
                    $now = date('Y-m-d H:i:s');

                    $master_map = [
                        'resilience_id'          => 'tbl_resilience',
                        'deployment_id'          => 'tbl_apps_deployment',
                        'deployment_provider_id' => 'tbl_apps_deployment_model',
                        'deployment_site_id'     => 'tbl_apps_deployment_site',
                        'network_id'             => 'tbl_apps_network',
                        'category_id'            => 'tbl_apps_category',
                        'operational_hour_id'    => 'tbl_apps_operational_hour',
                        'operational_day_id'     => 'tbl_apps_operational_day',
                        'app_type_id'            => 'tbl_app_type'
                    ];

                    $optional_columns = [
                        'decommission_year', 'application_version', 'development_language',
                        'application_developer', 'supporting_web_server', 
                        'supporting_application_server', 'supporting_others'
                    ];

                    $collected_ids = array_fill_keys(array_keys($master_map), []);

                    $existing_data = $this->db->select('application_name, module')->get('tbl_portofolio_apps_master')->result_array();
                    $existing_keys = [];
                    foreach ($existing_data as $row) {
                        $existing_keys[] = strtolower(trim((string)$row['application_name'])) . '|' . strtolower(trim((string)$row['module']));
                    }
                    $excel_keys_tracker = [];

                    for ($i = 1; $i < count($data); $i++) {
                        if (!array_filter($data[$i])) continue; 

                        $row_data = [];
                        $virtual_data = [];
                        $excel_row_num = $i + 1; 
                        $is_row_empty = true;
                        $empty_columns = [];

                        foreach ($uploaded_header as $index => $col_name) {
                            $raw_val = isset($data[$i][$index]) ? $data[$i][$index] : '';
                            $val = trim((string)$raw_val);
                            if ($val !== '') $is_row_empty = false;

                            if (in_array($col_name, ['database_ids', 'os_ids', 'server_ids'])) {
                                $virtual_data[$col_name] = ($val !== '') ? array_map('trim', explode(',', $val)) : [];
                            } else {
                                // PERBAIKAN: Ubah string kosong ("") menjadi NULL murni
                                // agar MySQL Strict Mode tidak menolak data saat masuk ke kolom tipe Integer
                                $row_data[$col_name] = ($val === '') ? null : $val;
                                
                                if (!in_array($col_name, $optional_columns) && $val === '') $empty_columns[] = $col_name;
                                if (array_key_exists($col_name, $master_map) && $val !== '') $collected_ids[$col_name][] = $val;
                            }
                        }
                        
                        if ($is_row_empty) continue; 
                        if (!empty($empty_columns)) $error_messages[] = "Baris <b>{$excel_row_num}</b>: Data wajib kosong (" . implode(', ', $empty_columns) . ")";

                        $app_name = isset($row_data['application_name']) ? $row_data['application_name'] : '';
                        $mod_name = isset($row_data['module']) ? $row_data['module'] : '';
                        if ($app_name !== '') {
                            $check_key = strtolower($app_name) . '|' . strtolower($mod_name);
                            if (in_array($check_key, $existing_keys)) {
                                $error_messages[] = "Baris <b>{$excel_row_num}</b>: Aplikasi <b>{$app_name}</b> sudah ada di database.";
                            } elseif (in_array($check_key, $excel_keys_tracker)) {
                                $error_messages[] = "Baris <b>{$excel_row_num}</b>: Aplikasi <b>{$app_name}</b> ganda di file ini.";
                            } else {
                                $excel_keys_tracker[] = $check_key;
                            }
                        }
                        
                        $row_data['status'] = 1; 
                        $row_data['created_by'] = 1; 
                        $row_data['created_at'] = $now;
                        $row_data['excel_row'] = $excel_row_num;
                        $row_data['virtual_data'] = $virtual_data; 
                        $import_data[] = $row_data;
                    }

                    if (empty($import_data)) {
                        $this->session->set_flashdata('error', 'Tidak ada data valid untuk diproses.');
                        redirect('home');
                        return;
                    }

                    $valid_ids_db = [];
                    foreach ($master_map as $fk_col => $table_name) {
                        $valid_ids_db[$fk_col] = [];
                        $unique_excel_ids = array_unique($collected_ids[$fk_col]);
                        if (!empty($unique_excel_ids)) {
                            $query = $this->db->select($fk_col)->where_in($fk_col, $unique_excel_ids)->get($table_name)->result_array();
                            $valid_ids_db[$fk_col] = array_column($query, $fk_col);
                        }
                    }

                    foreach ($import_data as $row) {
                        $invalid_fks = [];
                        foreach ($master_map as $fk_col => $table_name) {
                            $val = $row[$fk_col];
                            if ($val !== null && $val !== '' && !in_array($val, $valid_ids_db[$fk_col])) $invalid_fks[] = $fk_col;
                        }
                        if (!empty($invalid_fks)) $error_messages[] = "Baris <b>" . $row['excel_row'] . "</b>: ID Master tidak valid (" . implode(', ', $invalid_fks) . ")";
                    }

                    if (!empty($error_messages)) {
                        $display_errors = array_slice($error_messages, 0, 10);
                        $error_text = implode("<br>", $display_errors);
                        if (count($error_messages) > 10) $error_text .= "<br><i>... dan " . (count($error_messages) - 10) . " error lainnya.</i>";
                        $this->session->set_flashdata('error', addslashes('<b>Import Dibatalkan!</b><br><br>' . $error_text));
                    } else {
                        $this->db->trans_start();

                        foreach ($import_data as $row) {
                            $virtual_data = $row['virtual_data'];
                            $app_name_log = isset($row['application_name']) ? $row['application_name'] : 'Unknown';
                            
                            unset($row['excel_row'], $row['virtual_data']);

                            $this->db->insert('tbl_portofolio_apps_master', $row);
                            $new_apps_id = $this->db->insert_id();

                            // PERBAIKAN: Jika insert_id() kosong (berarti query di atas gagal)
                            // Kita lemparkan pesan error yang asli dari MySQL agar tidak crash di proses selanjutnya
                            if (empty($new_apps_id)) {
                                $db_error = $this->db->error();
                                throw new \Exception("Gagal menyimpan aplikasi '{$app_name_log}' ke master. Database Error: " . $db_error['message']);
                            }

                            foreach (['database_ids' => 'tbl_apps_database', 'os_ids' => 'tbl_apps_operating_software', 'server_ids' => 'tbl_apps_server'] as $key => $table) {
                                $id_col = ($key == 'database_ids') ? 'database_id' : (($key == 'os_ids') ? 'operating_software_id' : 'server_id');
                                if (!empty($virtual_data[$key])) {
                                    $batch = [];
                                    foreach ($virtual_data[$key] as $vid) {
                                        if ($vid !== '') $batch[] = ['apps_id' => $new_apps_id, $id_col => $vid];
                                    }
                                    if (!empty($batch)) $this->db->insert_batch($table, $batch);
                                }
                            }

                            $approval_roles = [2, 3, 1]; 
                            foreach ($approval_roles as $role) {
                                $this->db->insert('tbl_apps_approval', [
                                    'apps_id'      => $new_apps_id,
                                    'user_role_id' => $role,
                                    'status'       => 1,     
                                    'current'      => 0,     
                                    'created_by'   => 1,     
                                    'created_at'   => $now,
                                    'modified_by'  => 1,
                                    'modified_at'  => $now,
                                    'submit_date'  => $now,
                                    'remarks'      => 'Aplikasi di-import'
                                ]);
                            }

                            $this->db->insert('tbl_apps_audit_trail', [
                                'apps_id'    => $new_apps_id,
                                'role_id'    => 1,
                                'action'     => 'IMPORT',
                                'remarks'    => 'Aplikasi di-import',
                                'created_at' => $now
                            ]);
                        }

                        $this->db->trans_complete(); 

                        if ($this->db->trans_status() === FALSE) {
                            // Jika ada kegagalan yang tidak ter-catch
                            $this->session->set_flashdata('error', 'Transaksi dibatalkan. Gagal menyimpan sebagian data ke database.');
                        } else {
                            $this->load->model('audit/Audit_model');
                            $this->Audit_model->insert_log([
                                'username'    => $this->session->userdata('username'),
                                'action'      => 'IMPORT',
                                'table_name'  => 'tbl_portofolio_apps_master',
                                'foreign_id'  => 0,
                                'field_name'  => '-',
                                'old_value'   => '-',
                                'new_value'   => '-',
                                'reason'      => 'Import Data Aplikasi (Total: ' . count($import_data) . ' Data)',
                                'timestamp'   => $now
                            ]);

                            $this->session->set_flashdata('success', count($import_data) . ' Data aplikasi berhasil di-import.');
                        }
                    }

                } catch (\Throwable $e) {
                    if (file_exists($temp_filepath)) unlink($temp_filepath);
                    // Pesan error di sini akan menangkap MySQL error yang sebenarnya
                    $this->session->set_flashdata('error', 'Error Proses Import: <br>' . addslashes($e->getMessage()));
                }
            } else {
                $this->session->set_flashdata('error', 'Gagal memindahkan file temporary. Silakan cek perizinan (permission) folder uploads/documents/.');
            }
        }
        redirect('home');
    }
	
	public function upload_imported_sla($apps_id) {
        // Validasi hanya Role 1 (IT SLM) yang boleh akses
        if ($this->session->userdata('role_id') != 1) {
            show_error('Akses Ditolak.', 403);
            return;
        }

        // Ambil data aplikasi beserta nama kategorinya
        $app = $this->db->select('a.*, c.category_name')
                        ->from('tbl_portofolio_apps_master a')
                        ->join('tbl_apps_category c', 'a.category_id = c.category_id', 'left')
                        ->where('a.apps_id', $apps_id)
                        ->get()->row_array();

        if (!$app) {
            $this->session->set_flashdata('error', 'Aplikasi tidak ditemukan.');
            redirect('home');
        }

        $category_id = isset($app['category_id']) ? (int)$app['category_id'] : 0;
        $total_in_category = $this->db->where('category_id', $category_id)->count_all_results('tbl_portofolio_apps_master');
        $total_count = ($total_in_category > 0) ? $total_in_category : 1;
        $doc_version = str_pad($total_count, 4, '0', STR_PAD_LEFT);

        $cat_name = isset($app['category_name']) ? strtoupper($app['category_name']) : '';
        $cat_initial = 'O'; 

        if ($cat_name == 'CRITICAL') {
            $cat_initial = 'C';
        } elseif ($cat_name == 'VERY IMPORTANT') {
            $cat_initial = 'V';
        } elseif ($cat_name == 'IMPORTANT') {
            $cat_initial = 'I';
        } elseif ($cat_name == 'NECESSARY') {
            $cat_initial = 'N';
        }
        
        $app_date = !empty($app['modified_at']) ? $app['modified_at'] : date('Y-m-d H:i:s');
        $doc_month = date('m', strtotime($app_date));
        $doc_year  = date('Y', strtotime($app_date));
        $no_dokumen = "SLA.{$doc_version}.{$cat_initial}.{$doc_month}.{$doc_year}";

        // Proses Upload PDF
        $upload_path = './uploads/documents/'; // Sesuaikan dengan folder upload di servermu
        if (!is_dir($upload_path)) {
            mkdir($upload_path, 0777, true);
        }

        $safe_app_name = preg_replace('/[^A-Za-z0-9\-]/', '_', $app['application_name']);
        $safe_doc_no = preg_replace('/[^A-Za-z0-9\.\-]/', '_', $no_dokumen);
		$config['file_name']     = $no_dokumen . ".pdf";
        $config['overwrite']     = TRUE;

        // [PENJELASAN] BYPASS TOTAL: Izinkan semua tipe file di level CodeIgniter ('*')
        // Ini dilakukan untuk mengatasi bug CI yang sering salah mengenali MIME type PDF.
        $config['upload_path']   = $upload_path;
        $config['allowed_types'] = '*'; 
        $config['max_size']      = 0; // Biarkan nol agar tidak dibatasi CI (dibatasi php.ini saja)
        // [PENJELASAN] Matikan fitur security bawaan yang sering bentrok dengan file PDF
        $config['detect_mime']   = FALSE; // Jangan suruh CI menebak isi file
        $config['xss_clean']     = FALSE; // Jangan bersihkan file (Sering membuat PDF error)

        $this->load->library('upload');
        // [PENJELASAN] Kosongkan cache config sebelumnya, lalu load yang baru
        $this->upload->initialize(array()); 
        $this->upload->initialize($config); // WAJIB panggil initialize setelah set config

        if (!$this->upload->do_upload('sla_file')) {
            // Tampilkan error yang sangat mendetail jika masih gagal
            $error = $this->upload->display_errors('', '');
            $this->session->set_flashdata('error', 'Gagal upload SLA: ' . $error);
            redirect('home/detail/' . $apps_id); // [PENJELASAN] Tambahkan redirect agar tidak stuck
            return;
        } else {
            $upload_data = $this->upload->data();
            
            // [PENJELASAN] VALIDASI MANUAL: Karena kita bypass (pakai '*'), kita harus cek manual ekstensinya!
            $file_ext = strtolower(pathinfo($_FILES['sla_file']['name'], PATHINFO_EXTENSION));
            if ($file_ext !== 'pdf') {
                unlink($upload_data['full_path']); // Hapus file yang bukan PDF
                $this->session->set_flashdata('error', 'Gagal upload: Hanya file berformat .pdf yang diizinkan!');
                redirect('home/detail/' . $apps_id);
                return;
            }

            $file_name = $upload_data['file_name'];

            // PERBAIKAN: Hapus kolom document_no karena tidak ada di tabel database.
            // Nomor dokumen sudah aman karena tercetak langsung di dalam nama file PDF-nya.
            $insert_data = [
                'apps_id'     => $apps_id,
                'version'     => 1, // Otomatis menjadi Versi 1
                'file_name'   => $file_name,
                'created_by'  => $this->session->userdata('user_id'),
                'created_at'  => date('Y-m-d H:i:s')
            ];
            $this->db->insert('tbl_apps_sla_history', $insert_data);

            $this->session->set_flashdata('success', "Dokumen <b>{$no_dokumen}</b> berhasil diunggah dan ditetapkan sebagai SLA Versi 1.");
        }

        // Kembali ke halaman detail
        redirect('home/detail/' . $apps_id);
    }
}