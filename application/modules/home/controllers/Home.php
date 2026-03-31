<?php
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
        $data['opt_app_status']      = $m->get_dynamic_options('app_status', $user_id, $role_id, $filters);
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
        
        $config['per_page'] = 5;
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
            // 1. Load model audit terlebih dahulu
            $this->load->model('audit/Audit_model');

            // 2. Masukkan log ekspor ke tbl_audit_trail
           $this->Audit_model->insert_log([
                'username'    => $this->session->userdata('username'),
                'action'      => 'EXPORT',
                'table_name'  => 'tbl_portofolio_apps_master', // Ubah ke nama tabel master Anda
                'foreign_id'  => 0,
                'field_name'  => '-',
                'old_value'   => '-',
                'new_value'   => '-',
                'reason'      => 'Export Data',
                'timestamp'   => date('Y-m-d H:i:s')
            ]);

            $all_data = $m->get_my_portfolio($user_id, $role_id, $keyword, $filters, 0, 0);
            $data['export_data'] = $all_data;
            $this->load->view('home_export', $data);
            return;
        }

        $config['total_rows'] = $total_rows;
        $this->pagination->initialize($config);

        $data['pagination'] = $this->pagination->create_links();
        $data['my_portfolio'] = $m->get_my_portfolio($user_id, $role_id, $keyword, $filters, $config['per_page'], $page);
        $data['total_rows'] = $total_rows;
        $data['my_tasks'] = $m->get_my_tasks($user_id, $role_id);
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
            if ($fixed_role != 2) { 
                $this->session->set_flashdata('error', 'Hanya EA yang boleh membuat.');
                redirect('home');
            }
            $data['mode'] = 'add';
            $data['row']  = []; 
            $data['is_readonly'] = false;
            $data['selected_db_ids'] = [];
            $data['selected_os_ids'] = [];
            $data['selected_srv_ids'] = []; 
        } else {
            $data['row'] = $this->Home_model->get_portfolio_full_detail($apps_id);
            if(empty($data['row'])) { show_404(); }

            $data['selected_db_ids'] = !empty($data['row']['database_ids_str']) ? explode(',', $data['row']['database_ids_str']) : [];
            $data['selected_os_ids'] = !empty($data['row']['os_ids_str']) ? explode(',', $data['row']['os_ids_str']) : [];
            $data['selected_srv_ids'] = !empty($data['row']['server_ids_str']) ? explode(',', $data['row']['server_ids_str']) : [];
            $current_stage = $this->Home_model->get_current_approval_stage($apps_id);
            $curr_role_turn = isset($current_stage['user_role_id']) ? $current_stage['user_role_id'] : 0;
            $is_status_pending = (isset($current_stage['status']) && $current_stage['status'] == 0);

            if ($curr_role_turn == $fixed_role && $is_status_pending) {
                $data['mode'] = 'edit';
                $data['is_readonly'] = false;
            } else {
                $data['mode'] = 'view';
                $data['is_readonly'] = true;
            }
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
        } else {
            $data['sla_history'] = []; 
            $data['timeline'] = []; $data['audit_trail'] = []; $data['documents'] = [];
            $data['is_done']  = false; 
        }

        if ($fixed_role == 2) {
            $data['draft_list'] = $this->Home_model->get_my_tasks($user_id, $role_id, false);
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
        
        // ========================================================
        // LOGIKA KUNCI: 
        // Role 1 & 3: Selalu paksa jadi SUBMIT (Fitur Draft mati)
        // Role 2 (EA): Bebas, bisa simpan ke list (draft) atau submit
        // ========================================================
        if ($role_id == 1 || $role_id == 3) {
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
            $this->Home_model->insert_audit_trail($apps_id, "SUBMIT", $remarks);
        }

        // Simpan data ke Database
        $saved_apps_id = $this->Home_model->save_apps_info($apps_id, $post_data, $is_submit, $role_id, $remarks, $action_string);
        
        // Generate SLA jika Role 1 yang mensubmit
        if ($is_submit && $role_id == 1) {
            $this->_generate_and_save_sla($saved_apps_id);
        }

        // Simpan titipan Remarks EA jika hanya di-save biasa
        if ($save_type == 'save_stay' && $role_id == 2 && !empty($remarks)) {
            $target_apps_id = ($apps_id > 0) ? $apps_id : (is_array($saved_apps_id) && isset($saved_apps_id['id']) ? $saved_apps_id['id'] : $saved_apps_id);
            if ($target_apps_id > 0) {
                $this->db->where('apps_id', $target_apps_id)
                         ->where('user_role_id', 2)
                         ->update('tbl_apps_approval', ['remarks' => $remarks]);
            }
        }

        // ========================================================
        // PENGATURAN REDIRECT & NOTIFIKASI
        // ========================================================
        
        // 1. Jika ini adalah Draft (Role 2)
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

        // 2. Jika ini adalah Final Submit (Role 1, 3, atau 2 yang menekan Submit)
        if(is_array($saved_apps_id) && isset($saved_apps_id['msg'])) {
            $this->session->set_flashdata('success', $saved_apps_id['msg']);
        } else {
            $this->session->set_flashdata('success', 'Data submitted successfully.');
        }
        
        redirect('home');
    }

    public function bulk_submit() {
        $user_id = $this->session->userdata('user_id');
        $role_id = $this->session->userdata('role_id');
        
        // SECURITY: Membersihkan input dari potensi XSS
        $modal_remarks = $this->security->xss_clean($this->input->post('remarks'));
        $selected_apps = $this->security->xss_clean($this->input->post('selected_apps')); 

        if(empty($selected_apps) || !is_array($selected_apps)) {
            $this->session->set_flashdata('error', 'Tidak ada aplikasi yang dipilih untuk disubmit.');
            redirect('home/detail/0');
            return;
        }

        $incomplete = [];
        
        // ====================================================================
        // DAFTAR FORM WAJIB (Menyamakan dengan validasi ketat di Frontend)
        // ====================================================================
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

            $this->Home_model->advance_workflow((int)$apps_id, $role_id, 'SUBMIT', $final_remarks);
            
            if ($role_id == 1) {
                $this->_generate_and_save_sla((int)$apps_id);
            }
        }
        
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
        $apps_id = (int)$apps_id; // SECURITY: Type casting

        $is_done = $this->Home_model->is_app_done($apps_id);

        if (!$is_done) {
            $this->session->set_flashdata('error', 'SLA Document belum bisa diunduh karena aplikasi belum berstatus DONE.');
            redirect('home/detail/'.$apps_id);
            return;
        }

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
            
            $clean_name = preg_replace('/[^A-Za-z0-9\-]/', '_', $data['app']['application_name']);
            $filename = 'SLA_Document_' . $clean_name . '.pdf';
            
            $html2pdf->output($filename, 'I'); 
            
        } catch (\Spipu\Html2Pdf\Exception\Html2PdfException $e) {
            $html2pdf->clean();
            $formatter = new \Spipu\Html2Pdf\Exception\ExceptionFormatter($e);
            echo $formatter->getHtmlMessage();
        }
    }
    
    public function toggle_status($apps_id = 0, $status = null) {
        // SECURITY: Ambil dari parameter URL atau dari POST jika parameter URL kosong
        $apps_id = ($apps_id > 0) ? (int)$apps_id : (int)$this->input->post('apps_id');
        
        // Jika status tidak ada di URL (null), ambil dari input hidden di form modal
        if ($status === null) {
            $status = (int)$this->input->post('status');
        } else {
            $status = (int)$status;
        }

        $role_id = $this->session->userdata('role_id');
        $user_id = $this->session->userdata('user_id'); 
        
        if ($role_id != 2) {
            $this->session->set_flashdata('error', 'Anda tidak memiliki hak akses.');
            redirect('home');
            return;
        }

        // Pastikan apps_id valid sebelum lanjut
        if ($apps_id <= 0) {
            $this->session->set_flashdata('error', 'ID Aplikasi tidak valid.');
            redirect('home');
            return;
        }

        $new_status = ($status == 1) ? 1 : 0;
        
        $decom_year = null;
        if ($new_status == 0) {
            $decom_year = date('Y'); 
        }
        
        $uploaded_filename = null;
        if (!empty($_FILES['attached_document']['name'])) {
            $config['upload_path']   = './uploads/documents/';
            $config['allowed_types'] = 'pdf';
            $config['max_size']      = 5120;
            $config['encrypt_name']  = TRUE;

            $this->load->library('upload', $config);

            if ($this->upload->do_upload('attached_document')) {
                $upload_data = $this->upload->data();
                $uploaded_filename = $upload_data['file_name'];
            } else {
                $error_msg = strip_tags($this->upload->display_errors());
                $this->session->set_flashdata('error', 'Gagal merubah status. Upload dokumen error: ' . $error_msg);
                redirect('home');
                return;
            }
        }
        
        $audit_action = ($new_status == 1) ? 'ACTIVATE' : 'DEACTIVATE';
        
        // Ambil remarks dari modal memo
        $remarks = $this->security->xss_clean($this->input->post('remarks'));
        if (empty($remarks)) {
            $remarks = ($new_status == 1) ? "Application Activated" : "Application Deactivated";
        }

        // Panggil model dengan menyertakan parameter remarks agar audit trail benar
        $this->Home_model->update_app_status($apps_id, $new_status, $uploaded_filename, $decom_year, $user_id, $role_id, $audit_action, $remarks);

        $action_name = ($new_status == 1) ? 'di-Activate' : 'di-Deactivate';
        $msg = "Aplikasi berhasil $action_name.";

        $this->session->set_flashdata('success', $msg);
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
        $apps_id = (int)$apps_id; // SECURITY

        $data['app'] = $this->Home_model->get_portfolio_full_detail($apps_id);
        if (empty($data['app'])) return false;

        $html = $this->load->view('document_sla_export', $data, TRUE);
        require_once FCPATH . 'vendor/autoload.php';

        try {
            $html2pdf = new \Spipu\Html2Pdf\Html2Pdf('P', 'A4', 'en', true, 'UTF-8', array(10, 10, 10, 10));
            $html2pdf->pdf->SetDisplayMode('fullpage');
            $html2pdf->writeHTML($html);
            
            $clean_name = preg_replace('/[^A-Za-z0-9\-]/', '_', $data['app']['application_name']);
            $timestamp = date('Ymd_His');
            $filename = 'SLA_' . $clean_name . '_' . $timestamp . '.pdf';
            
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
        
        if ($role_id != 2) {
            $this->session->set_flashdata('error', 'Akses Ditolak: Hanya Enterprise Architecture (EA) yang dapat melakukan Renewal.');
            redirect('home');
            return;
        }
        
        $is_success = $this->Home_model->process_renewal($apps_id, $user_id, $role_id);

        if ($is_success) {
            $this->session->set_flashdata('success', 'Proses Renewal berhasil dimulai. Silahkan review data dan klik Submit.');
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
		
		if ($role_id != 2) {
			$this->session->set_flashdata('error', 'Akses Ditolak: Hanya Enterprise Architecture (EA) yang dapat melakukan ini.');
			redirect('home');
			return;
		}
		
		$remarks = $this->security->xss_clean($this->input->post('remarks'));
		
		$is_success = $this->Home_model->cancel_renewal($apps_id, $user_id, $role_id, $remarks);

		if ($is_success) {
			$this->session->set_flashdata('success', 'Renewal berhasil dibatalkan.');
		} else {
			$this->session->set_flashdata('error', 'Terjadi kesalahan saat membatalkan renewal.');
		}

		redirect('home/detail/'.$apps_id);
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
}