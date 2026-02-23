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
        if ($role_id === 1) { 
            redirect('portofolio'); 
            return;
        }

        $user_id = $this->session->userdata('user_id');
        $data['title'] = 'Home';
        $data['rid'] = $role_id;
        
        $keyword = $this->security->xss_clean($this->input->get('keyword'));
        $filters = $this->input->get('filter');

        $m = $this->Home_model;

        // --- PENGAMBILAN DATA OPSI FILTER ---
        $data['opt_status']          = $m->get_dynamic_options('status', $user_id, $role_id, $filters);
        $data['opt_category']        = $m->get_dynamic_options('category', $user_id, $role_id, $filters);
        $data['opt_app_name']        = $m->get_dynamic_options('app_name', $user_id, $role_id, $filters);
        $data['opt_short_name']      = $m->get_dynamic_options('short_name', $user_id, $role_id, $filters);
        $data['opt_module']          = $m->get_dynamic_options('module', $user_id, $role_id, $filters);
        $data['opt_service_name']    = $m->get_dynamic_options('service_name', $user_id, $role_id, $filters); 
        $data['opt_db_name']         = $m->get_dynamic_options('db_name', $user_id, $role_id, $filters);
        $data['opt_os_name']         = $m->get_dynamic_options('os_name', $user_id, $role_id, $filters);
        
        $data['opt_app_type']        = $m->get_dynamic_options('app_type', $user_id, $role_id, $filters);
        $data['opt_live_year']       = $m->get_dynamic_options('live_year', $user_id, $role_id, $filters);
        $data['opt_decom_year']      = $m->get_dynamic_options('decom_year', $user_id, $role_id, $filters);
        $data['opt_resilience']      = $m->get_dynamic_options('resilience', $user_id, $role_id, $filters);
        $data['opt_network']         = $m->get_dynamic_options('network', $user_id, $role_id, $filters);
        $data['opt_deploy']          = $m->get_dynamic_options('deployment', $user_id, $role_id, $filters);
        $data['opt_op_hour']         = $m->get_dynamic_options('op_hour', $user_id, $role_id, $filters);
        $data['opt_op_day']          = $m->get_dynamic_options('op_day', $user_id, $role_id, $filters);
        $data['opt_principle']       = $m->get_dynamic_options('principle', $user_id, $role_id, $filters);
        $data['opt_principle_sol']   = $m->get_dynamic_options('principle_sol', $user_id, $role_id, $filters);
        $data['opt_it_group']        = $m->get_dynamic_options('it_group', $user_id, $role_id, $filters);
        $data['opt_it_div']          = $m->get_dynamic_options('it_division', $user_id, $role_id, $filters);
        $data['opt_directorate']     = $m->get_dynamic_options('directorate', $user_id, $role_id, $filters);
        $data['opt_sub_dir']         = $m->get_dynamic_options('sub_directorate', $user_id, $role_id, $filters);
        $data['opt_owner_title']     = $m->get_dynamic_options('owner_title', $user_id, $role_id, $filters);
        $data['opt_nik_head']        = $m->get_dynamic_options('nik_head', $user_id, $role_id, $filters);
        $data['opt_nik_owner']       = $m->get_dynamic_options('nik_owner', $user_id, $role_id, $filters);
        $data['opt_nik_dept']        = $m->get_dynamic_options('nik_dept', $user_id, $role_id, $filters);

        // [TAMBAHAN] Filter khusus Server Type dan Readyness
        $data['opt_server_type']     = $m->get_dynamic_options('server_type', $user_id, $role_id, $filters);
        $data['opt_readyness']       = ['Comply', 'Not Comply'];

        $data['selected_filters'] = $filters;
        $data['keyword'] = $keyword;

        $this->load->library('pagination');
        $config['base_url'] = base_url('home/index');
        
        $is_infra_view = in_array($role_id, [4, 5]); 
        $is_bu_view    = in_array($role_id, [6, 7]);

        $data['is_infra'] = $is_infra_view;
        $data['is_bu']    = $is_bu_view;

        $config['per_page'] = 5;
        $config['page_query_string'] = TRUE;
        $config['reuse_query_string'] = TRUE;
        $config['query_string_segment'] = 'page';
        
        $config['full_tag_open'] = '<ul class="pagination pagination-sm m-0">';
        $config['full_tag_close'] = '</ul>';
        $config['first_link'] = '&laquo; First';
        $config['first_tag_open'] = '<li class="page-item">';
        $config['first_tag_close'] = '</li>';
        $config['last_link'] = 'Last &raquo;';
        $config['last_tag_open'] = '<li class="page-item">';
        $config['last_tag_close'] = '</li>';
        $config['next_link'] = '&rsaquo;';
        $config['next_tag_open'] = '<li class="page-item">';
        $config['next_tag_close'] = '</li>';
        $config['prev_link'] = '&lsaquo;';
        $config['prev_tag_open'] = '<li class="page-item">';
        $config['prev_tag_close'] = '</li>';
        $config['cur_tag_open'] = '<li class="page-item active"><a class="page-link" href="#">';
        $config['cur_tag_close'] = '</a></li>';
        $config['num_tag_open'] = '<li class="page-item">';
        $config['num_tag_close'] = '</li>';
        $config['attributes'] = array('class' => 'page-link');
        
        $page = ($this->input->get('page')) ? $this->input->get('page') : 0;

        // Logika PHP Filtering untuk Readyness yang dinamis
        if ($is_infra_view) {
            $readyness_filter = isset($filters['readyness']) ? $filters['readyness'] : [];
            $valid_readyness = array_filter($readyness_filter, function($v) { return $v !== ''; });

            if (!empty($valid_readyness)) {
                $all_infra = $m->get_my_infra_portfolio($user_id, $role_id, $keyword, $filters, 0, 0);
                $filtered_infra = [];
                foreach ($all_infra as $r) {
                    $this->_calculate_sla($r);
                    if (in_array($r['readyness'], $valid_readyness)) {
                        $filtered_infra[] = $r;
                    }
                }
                $config['total_rows'] = count($filtered_infra);
                $this->pagination->initialize($config);
                $data['my_portfolio'] = array_slice($filtered_infra, $page, $config['per_page']);
            } else {
                $config['total_rows'] = $m->count_my_infra_portfolio($user_id, $role_id, $keyword, $filters);
                $this->pagination->initialize($config);
                $data['my_portfolio'] = $m->get_my_infra_portfolio($user_id, $role_id, $keyword, $filters, $config['per_page'], $page);
                if(!empty($data['my_portfolio'])) {
                    foreach ($data['my_portfolio'] as &$r) {
                        $this->_calculate_sla($r);
                    }
                }
            }
        } else {
            $config['total_rows'] = $m->count_my_portfolio($user_id, $role_id, $keyword, $filters);
            $this->pagination->initialize($config);
            $data['my_portfolio'] = $m->get_my_portfolio($user_id, $role_id, $keyword, $filters, $config['per_page'], $page);
        }

        $data['pagination'] = $this->pagination->create_links();
        $data['total_rows'] = $config['total_rows'];
        $data['my_tasks'] = $m->get_my_tasks($user_id, $role_id);
        $data['total_tasks'] = count($data['my_tasks']);
        
        $this->load->view('home_view', $data);
    }

    private function _mul_non_zero($arr) {
        $vals = [];
        foreach ($arr as $v) { $v = (float)$v; if ($v > 0) $vals[] = $v; }
        if (count($vals) === 0) return 0;
        $res = 1;
        foreach ($vals as $v) $res *= $v;
        return $res;
    }

    private function _calculate_sla(&$row) {
        $slaInfra = isset($row['sla_by_infra_pct']) ? ((float)$row['sla_by_infra_pct'] / 100.0) : 
                    (isset($row['server_sla_pct']) ? ((float)$row['server_sla_pct'] / 100.0) : 0);
        
        $w_p = isset($row['server_web_prod_count']) ? (int)$row['server_web_prod_count'] : 0;
        $a_p = isset($row['server_app_prod_count']) ? (int)$row['server_app_prod_count'] : 0;
        $d_p = isset($row['server_db_prod_count']) ? (int)$row['server_db_prod_count'] : 0;
        
        $sla_w_p = ($w_p > 0) ? (1 - pow((1 - $slaInfra), $w_p)) : 0;
        $sla_a_p = ($a_p > 0) ? (1 - pow((1 - $slaInfra), $a_p)) : 0;
        $sla_d_p = ($d_p > 0) ? (1 - pow((1 - $slaInfra), $d_p)) : 0;
        
        $row['sla_svr_prod'] = $this->_mul_non_zero([$sla_w_p, $sla_a_p, $sla_d_p]);

        $w_d = isset($row['server_web_dr_count']) ? (int)$row['server_web_dr_count'] : 0;
        $a_d = isset($row['server_app_dr_count']) ? (int)$row['server_app_dr_count'] : 0;
        $d_d = isset($row['server_db_dr_count']) ? (int)$row['server_db_dr_count'] : 0;

        $sla_w_d = ($w_d > 0) ? (1 - pow((1 - $slaInfra), $w_d)) : 0;
        $sla_a_d = ($a_d > 0) ? (1 - pow((1 - $slaInfra), $a_d)) : 0;
        $sla_d_d = ($d_d > 0) ? (1 - pow((1 - $slaInfra), $d_d)) : 0;

        $row['sla_svr_dr'] = $this->_mul_non_zero([$sla_w_d, $sla_a_d, $sla_d_d]);

        $dr_val = isset($row['resilience_category']) ? (string)$row['resilience_category'] : '';
        $powN = ($dr_val === 'L0') ? 1 : 2;
        $diff = (float)$row['sla_svr_prod'] - (float)$row['sla_svr_dr'];
        $row['sla_actual'] = 1 - pow($diff, $powN);

        $standard = isset($row['standard_category']) ? (float)$row['standard_category'] : 
                    (isset($row['sla_standard']) ? (float)$row['sla_standard'] : 0);
        
        $row['sla_standard'] = $standard; 
        $standard_dec = $standard / 100.0;

        $row['readyness'] = ($row['sla_actual'] < $standard_dec) ? 'Not Comply' : 'Comply';
        $row['suggestion'] = ($row['readyness'] === 'Not Comply') ? 'Assesment kembali konfigurasi infra atau kategori kualitas aplikasi' : '-';
    }

    public function detail($apps_id = 0, $service_id = 0) {
        $user_id = $this->session->userdata('user_id');
        $role_id = $this->session->userdata('role_id');
        $fixed_role = $this->Home_model->_get_fixed_role($user_id, $role_id);

        $data['title'] = ($apps_id == 0) ? 'Create Portofolio' : 'Portofolio Detail';
        $data['apps_id'] = $apps_id;
        $data['rid'] = $fixed_role; 
        $data['service_id_param'] = (int)$service_id; 
        
        $is_infra_view = in_array($fixed_role, [4, 5]);
        $is_bu_view    = in_array($fixed_role, [6, 7]);

        $data['is_infra'] = $is_infra_view;
        $data['is_bu']    = $is_bu_view;

        if ($apps_id == 0) {
            if ($fixed_role != 2) { 
                $this->session->set_flashdata('error', 'Hanya EA Apps Inputter yang boleh membuat.');
                redirect('home');
            }
            $data['mode'] = 'add';
            $data['row']  = []; 
            $data['is_readonly'] = false;
            $data['selected_db_ids'] = [];
            $data['selected_os_ids'] = [];
        } else {
            $data['row'] = $this->Home_model->get_portfolio_full_detail($apps_id);
            if(empty($data['row'])) { show_404(); }
            
            if ($is_infra_view) {
                $this->_calculate_sla($data['row']);
            }

            $data['selected_db_ids'] = !empty($data['row']['database_ids_str']) ? explode(',', $data['row']['database_ids_str']) : [];
            $data['selected_os_ids'] = !empty($data['row']['os_ids_str']) ? explode(',', $data['row']['os_ids_str']) : [];

            $current_stage = $this->Home_model->get_current_approval_stage($apps_id);
            $curr_role_turn = isset($current_stage['user_role_id']) ? $current_stage['user_role_id'] : 0;
            $is_status_pending = (isset($current_stage['status']) && $current_stage['status'] == 0);

            if ($curr_role_turn == $fixed_role && $is_status_pending) {
                if (in_array($fixed_role, [2, 4, 6])) {
                    $data['mode'] = 'edit';
                    $data['is_readonly'] = false;
                } else {
                    $data['mode'] = 'review';
                    $data['is_readonly'] = true;
                }
            } else {
                $data['mode'] = 'view';
                $data['is_readonly'] = true;
            }
        }

        $m = $this->Home_model;
        $data['opt_module']     = $m->get_master_data('tbl_module'); 
        $data['opt_category']   = $m->get_master_data('tbl_apps_category'); 
        $data['opt_deploy']     = $m->get_master_data('tbl_apps_deployment'); 
        $data['opt_network']    = $m->get_master_data('tbl_apps_network');
        $data['opt_day']        = $m->get_master_data('tbl_apps_operational_day');
        $data['opt_hour']       = $m->get_master_data('tbl_apps_operational_hour');
        $data['opt_resilience'] = $m->get_master_data('tbl_resilience');
        $data['opt_database']   = $m->get_master_data('tbl_database_master');
        $data['opt_os']         = $m->get_master_data('tbl_operating_software');
        $data['opt_it_group']     = $m->get_dynamic_options('it_group', $user_id, $fixed_role, []);
        $data['opt_directorate']  = $m->get_dynamic_options('directorate', $user_id, $fixed_role, []);
        $data['opt_it_division']  = $m->get_dynamic_options('it_division', $user_id, $fixed_role, []);
        $data['opt_server']     = $m->get_master_data('tbl_server'); 

        $data['infra_mapping'] = json_encode($m->get_infra_master_mapping());

        if ($apps_id > 0) {
            $data['timeline']    = $m->get_timeline_data($apps_id);
            $data['audit_trail'] = $m->get_audit_trail($apps_id);
            $data['documents']   = $m->get_documents($apps_id);

            $this->db->select('im.service_id, sv.service_name, im.resilience_id, r.resilience_category');
            $this->db->select('ts.server_id, ts.server_name as server_type_name, ts.server_sla');
            $this->db->select('isv.server_web_prod_count, isv.server_app_prod_count, isv.server_db_prod_count');
            $this->db->select('isv.server_web_dr_count, isv.server_app_dr_count, isv.server_db_dr_count');
            
            $this->db->from('tbl_apps_infra ai');
            $this->db->join('tbl_portofolio_infra_master im', 'im.infra_id = ai.infra_id');
            $this->db->join('tbl_service sv', 'sv.service_id = im.service_id');
            $this->db->join('tbl_resilience r', 'r.resilience_id = im.resilience_id', 'left');
            $this->db->join('tbl_infra_server isv', 'isv.infra_id = ai.infra_id', 'left');
            $this->db->join('tbl_server ts', 'ts.server_id = isv.server_id', 'left');
            
            $this->db->where('ai.apps_id', $apps_id);
            
            if ($service_id > 0) {
                $this->db->where('im.service_id', $service_id);
            }
            
            $data['existing_infra_list'] = $this->db->get()->result_array();
            
        } else {
            $data['timeline'] = []; $data['audit_trail'] = []; $data['documents'] = [];
            $data['existing_infra_list'] = []; 
        }

        $this->load->view('home_detail_view', $data);
    }

    public function save_submission() {
        $user_id = $this->session->userdata('user_id');
        $role_id = $this->session->userdata('role_id');
        $apps_id = $this->input->post('apps_id');
        $save_type = $this->input->post('save_type'); 
        $post_data = $this->input->post();
        $remarks = $this->input->post('remarks');
        $target_role_id = $this->input->post('target_role_id');

        $is_submit = ($save_type == 'submit') ? true : false;
        
        if ($save_type == 'draft' || $save_type == 'submit') {
            $result = $this->Home_model->save_apps_info($apps_id, $post_data, $is_submit, $role_id);
            
            if(is_array($result)) {
                $this->session->set_flashdata('success', $result['msg']);
            } else {
                if ($save_type == 'submit') {
                    $this->session->set_flashdata('success', 'Data submitted successfully.');
                } else {
                    $this->session->set_flashdata('success', 'Draft saved.');
                }
            }
        } 
        elseif ($save_type == 'approve') {
            $this->Home_model->advance_workflow($apps_id, $role_id, 'APPROVE', $remarks);
            $this->session->set_flashdata('success', 'Application Approved.');
        }
        elseif ($save_type == 'reject') {
            if(!empty($target_role_id)) {
                $this->Home_model->reject_workflow($apps_id, $role_id, $target_role_id, $remarks);
                $this->session->set_flashdata('success', 'Application Rejected.');
            } else {
                $this->session->set_flashdata('error', 'Failed to Reject: Target role is missing.');
            }
        }
        elseif ($save_type == 'acknowledge') {
             $this->Home_model->advance_workflow($apps_id, $role_id, 'ACKNOWLEDGE', $remarks);
             $this->session->set_flashdata('success', 'Application Acknowledged.');
        }
        
        redirect('home');
    }
}