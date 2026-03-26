<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Home_model extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->db->query("SET sql_mode = ''");
    }

    protected $_filter_map = [
        'app_status'         => "(CASE WHEN a.status = 1 THEN 'Active' ELSE 'Not Active' END)", // <--- TAMBAHAN BARU
        'status'             => "(CASE WHEN ad.is_done = 1 THEN 'DONE' ELSE tr.role_name END)",
        'category'           => 'c.category_name',
        'app_name'           => 'a.application_name',
        'short_name'         => 'a.short_name',
        'module'             => 'a.module', 
        'db_name'            => 'dbm.database_name',
        'os_name'            => 'os.operating_software_name', 
        'app_type'           => 'at.app_type_name',
        'server_name'        => 'srv.server_name',
        'standard_category'  => 'a.standard_category',
        'live_year'          => 'a.live_year',
        'decom_year'         => 'a.decommission_year',
        'resilience'         => 'r.resilience_category',
        'dr_avail'           => 'r.dr',
        'ha'                 => 'r.ha',
        'network'            => 'n.network_name',
        'deployment_model'   => "d.deployment_model",
        'deployment_provider'=> "dp.deployment_provider_name",
        'deployment_site'    => "ds.deployment_site_name",
        'op_hour'            => "CONCAT(oh.start_time, ' - ', oh.end_time)",
        'op_day'             => "CONCAT(od.start_day, ' - ', od.end_day)",
        'solution_vendor'    => 'a.solution_vendor',
        'services_vendor'    => 'a.services_vendor',
        'lob_directorate'    => 'a.lob_directorate',
        'lob_subdirectorate' => 'a.lob_subdirectorate',
        'lob_group'          => 'a.lob_group',
        'lob_group_head'     => 'a.lob_group_head',
        'lob_department_head'=> 'a.lob_department_head',
        'it_subdirectorate'  => 'a.it_subdirectorate',
        'it_department_head' => 'a.it_department_head',
        'it_support_group'   => 'a.it_support_group',
        'it_group_head'      => 'a.it_group_head',
        'it_support_divison' => 'a.it_support_divison',
        'it_division_head'   => 'a.it_division_head',
        'app_version'        => 'a.application_version',
        'dev_language'       => 'a.development_language',
        'app_developer'      => 'a.application_developer',
        'web_server'         => 'a.supporting_web_server',
        'app_server'         => 'a.supporting_application_server',
        'sup_others'         => 'a.supporting_others',
        'src_code'           => 'a.source_code_owned',
        'url'                => 'a.Url'
    ];

    private function _join_tables() {
        $this->db->from('tbl_portofolio_apps_master a');
        $this->db->join('tbl_apps_category c', 'c.category_id = a.category_id', 'left');
        $this->db->join('tbl_apps_operational_day od', 'od.operational_day_id = a.operational_day_id', 'left');
        $this->db->join('tbl_apps_operational_hour oh', 'oh.operational_hour_id = a.operational_hour_id', 'left');
        $this->db->join('tbl_apps_network n', 'n.network_id = a.network_id', 'left');
        $this->db->join('tbl_penanganan_insiden pi', 'pi.category_id = a.category_id', 'left'); // <--- TAMBAHKAN INI
        
        $this->db->join('tbl_apps_deployment d', 'd.deployment_id = a.deployment_id', 'left');
        $this->db->join('tbl_apps_deployment_model dp', 'dp.deployment_provider_id = a.deployment_provider_id', 'left');
        $this->db->join('tbl_apps_deployment_site ds', 'ds.deployment_site_id = a.deployment_site_id', 'left');
        $this->db->join('tbl_app_type at', 'at.app_type_id = a.app_type_id', 'left');
        
        $this->db->join('tbl_resilience r', 'r.resilience_id = a.resilience_id', 'left'); 
        $this->db->join('(SELECT apps_id, user_role_id as current_stage_role FROM tbl_apps_approval WHERE current = 1) s', 's.apps_id = a.apps_id', 'left');
        $this->db->join('tbl_role tr', 's.current_stage_role = tr.role_id', 'left');
        
        $this->db->join('(SELECT apps_id, 1 as is_done FROM tbl_apps_approval WHERE user_role_id = 1 AND status = 1) ad', 'ad.apps_id = a.apps_id', 'left');
    }

    private function _apply_filters($filters) {
        if (!empty($filters) && is_array($filters)) {
            $applied_any = false; 

            foreach ($filters as $key => $values) {
                if (isset($this->_filter_map[$key]) && !empty($values) && is_array($values)) {
                    $valid_values = array_filter($values, function($v) { return $v !== ''; });
                    if(!empty($valid_values)) {
                        
                        if (!$applied_any) {
                            $this->db->group_start();
                            $applied_any = true;
                        }

                        $col = $this->_filter_map[$key];
                        $this->db->group_start();
                        $first = true;
                        foreach ($valid_values as $val) {
                            $val_esc = $this->db->escape(trim($val));
                            if($first){ 
                                $this->db->where("TRIM($col) = $val_esc", NULL, FALSE); 
                                $first = false; 
                            } else { 
                                $this->db->or_where("TRIM($col) = $val_esc", NULL, FALSE); 
                            }
                        }
                        $this->db->group_end();
                    }
                }
            }
            if ($applied_any) { $this->db->group_end(); }
        }
    }

    private function _apply_rbac($user_id, $role_id) {
        $safe_user = (int)$user_id;
        $safe_role = (int)$role_id;
        if ($safe_role == 2) {
            $this->db->having("(MAX(s.current_stage_role) IN (2, 3, 1, 0) OR MAX(ad.is_done) = 1)");
        } 
        elseif ($safe_role == 3) {
            $this->db->having("(MAX(s.current_stage_role) IN (3, 1, 0) OR MAX(ad.is_done) = 1)");
        } 
        elseif ($safe_role == 1) {
            $this->db->having("(MAX(s.current_stage_role) IN (1, 0) OR MAX(ad.is_done) = 1)");
        }
    }

    private function _build_portfolio_query($user_id, $role_id, $keyword = null, $filters = []) {
        $this->_join_tables();
        
        // Join tabel untuk Database dan Operating Software
        $this->db->join('tbl_apps_database adb', 'adb.apps_id = a.apps_id', 'left');
        $this->db->join('tbl_database_master dbm', 'dbm.database_id = adb.database_id', 'left');
        $this->db->join('tbl_apps_operating_software aos', 'aos.apps_id = a.apps_id', 'left');
        $this->db->join('tbl_operating_software os', 'os.operating_software_id = aos.operating_software_id', 'left');
        $this->db->join('tbl_apps_server asr', 'asr.apps_id = a.apps_id', 'left');
        $this->db->join('tbl_server srv', 'srv.server_id = asr.server_id', 'left');

        $this->_apply_rbac($user_id, $role_id);

        if ($keyword) {
            $this->db->group_start();
            
            // 1. DATA UTAMA APLIKASI
            $this->db->like('a.application_name', $keyword);
            $this->db->or_like('a.short_name', $keyword);
            $this->db->or_like('a.module', $keyword);
            $this->db->or_like('a.application_version', $keyword); 
            $this->db->or_like('at.app_type_name', $keyword);
            $this->db->or_like('a.Url', $keyword); 
            $this->db->or_like('a.live_year', $keyword);
            $this->db->or_like('a.decommission_year', $keyword);
            $this->db->or_like('a.development_language', $keyword); 
            $this->db->or_like('a.application_developer', $keyword); 
            $this->db->or_like('a.supporting_web_server', $keyword); 
            $this->db->or_like('a.supporting_application_server', $keyword); 
            $this->db->or_like('a.supporting_others', $keyword);
            $this->db->or_like('a.source_code_owned', $keyword);

            // 2. VENDOR & LOB
            $this->db->or_like('a.solution_vendor', $keyword);
            $this->db->or_like('a.services_vendor', $keyword);
            $this->db->or_like('a.standard_category', $keyword);
            $this->db->or_like('a.lob_directorate', $keyword);
            $this->db->or_like('a.lob_subdirectorate', $keyword);
            $this->db->or_like('a.lob_group', $keyword);
            $this->db->or_like('a.lob_group_head', $keyword);
            $this->db->or_like('a.lob_department_head', $keyword);

            // 3. IT STRUCTURE
            $this->db->or_like('a.it_subdirectorate', $keyword);
            $this->db->or_like('a.it_department_head', $keyword);
            $this->db->or_like('a.it_support_group', $keyword);
            $this->db->or_like('a.it_group_head', $keyword);
            $this->db->or_like('a.it_support_divison', $keyword); 
            $this->db->or_like('a.it_division_head', $keyword);

            // 4. DATA DARI TABEL JOIN (STATUS & INFRA)
            $this->db->or_like('c.category_name', $keyword);
            $this->db->or_like('srv.server_name', $keyword);
            $this->db->or_like('tr.role_name', $keyword);
            $this->db->or_like('dbm.database_name', $keyword);
            $this->db->or_like('os.operating_software_name', $keyword);
            $this->db->or_like('r.resilience_category', $keyword);
            $this->db->or_like('n.network_name', $keyword);
            $this->db->or_like('d.deployment_model', $keyword);
            $this->db->or_like('dp.deployment_provider_name', $keyword);
            $this->db->or_like('ds.deployment_site_name', $keyword);

            // --- TAMBAHAN: OPERATIONAL DAY & HOUR ---
            // Mencari berdasarkan format "Day - Day"
            $day_concat = "CONCAT(od.start_day, ' - ', od.end_day)";
            $this->db->or_where("$day_concat LIKE '%".$this->db->escape_like_str($keyword)."%' ESCAPE '!'", NULL, FALSE);

            // Mencari berdasarkan format "00:00:00 - 00:00:00"
            $hour_concat = "CONCAT(oh.start_time, ' - ', oh.end_time)";
            $this->db->or_where("$hour_concat LIKE '%".$this->db->escape_like_str($keyword)."%' ESCAPE '!'", NULL, FALSE);
            
            // Pencarian satuan (opsional agar lebih fleksibel)
            $this->db->or_like('od.start_day', $keyword);
            $this->db->or_like('od.end_day', $keyword);
            $this->db->or_like('oh.start_time', $keyword);
            $this->db->or_like('oh.end_time', $keyword);

            // 5. STATUS KERJA (DONE & ACTIVE)
            $done_cond = "(CASE WHEN ad.is_done = 1 THEN 'DONE' ELSE '' END)";
            $this->db->or_where("$done_cond LIKE '%".$this->db->escape_like_str($keyword)."%' ESCAPE '!'", NULL, FALSE);
            
            $status_cond = "(CASE WHEN a.status = 1 THEN 'Active' ELSE 'Not Active' END)";
            $this->db->or_where("$status_cond LIKE '%".$this->db->escape_like_str($keyword)."%' ESCAPE '!'", NULL, FALSE);
            
            $this->db->group_end();
        }

        $this->_apply_filters($filters);
    }

    public function get_my_portfolio($user_id, $role_id, $keyword = null, $filters = [], $limit = 10, $start = 0) {
        $role_id = $this->_get_fixed_role($user_id, $role_id); 
        $this->_build_portfolio_query($user_id, $role_id, $keyword, $filters);
        
        $this->db->select('a.*'); 
        $this->db->select('MAX(c.category_name) as category_name');
        $this->db->select('MAX(s.current_stage_role) as current_stage_role');
        $this->db->select("(CASE WHEN MAX(ad.is_done) = 1 THEN 'DONE' ELSE MAX(tr.role_name) END) as status_name", FALSE);
        $this->db->select('MAX(at.app_type_name) as application_type_name');
        $this->db->select('GROUP_CONCAT(DISTINCT dbm.database_name SEPARATOR ", ") as database_names');
        $this->db->select('GROUP_CONCAT(DISTINCT os.operating_software_name SEPARATOR ", ") as os_names');
        $this->db->select('GROUP_CONCAT(DISTINCT srv.server_name SEPARATOR ", ") as server_name');
        $this->db->select('MAX(n.network_name) as network_name');
        $this->db->select('MAX(r.resilience_category) as resilience'); 
        $this->db->select('MAX(r.dr) as dr_availability');
        $this->db->select('MAX(r.ha) as ha');
        $this->db->select("MAX(d.deployment_model) as deployment_model");
        $this->db->select("MAX(dp.deployment_provider_name) as provider_name");
        $this->db->select("MAX(ds.deployment_site_name) as site_name");
        $this->db->select("CONCAT(MAX(od.start_day), ' - ', MAX(od.end_day)) as operational_day", FALSE);
        $this->db->select("CONCAT(MAX(oh.start_time), ' - ', MAX(oh.end_time)) as operational_hour", FALSE);

        $safe_role = (int)$role_id;
        $this->db->group_by('a.apps_id');

        // 1. Group by Status: Active (1) selalu di atas Not Active (0)
        $this->db->order_by("a.status", "DESC");

        // 2. Workflow Status: Role user saat ini paling atas, lalu IT SLM(1) -> IT Dev(3) -> EA(2) -> DONE
        $this->db->order_by("CASE 
            WHEN MAX(s.current_stage_role) = $safe_role THEN 0 
            WHEN MAX(s.current_stage_role) = 1 THEN 1
            WHEN MAX(s.current_stage_role) = 3 THEN 2
            WHEN MAX(s.current_stage_role) = 2 THEN 3
            WHEN MAX(ad.is_done) = 1 THEN 4
            ELSE 5 
        END", "ASC", FALSE);

        // 3. Category: NECESSARY -> CRITICAL -> VERY IMPORTANT -> IMPORTANT -> OTHERS
        $this->db->order_by("CASE
            WHEN LOWER(c.category_name) = 'critical' THEN 1
            WHEN LOWER(c.category_name) = 'very important' THEN 2
            WHEN LOWER(c.category_name) = 'important' THEN 3
            WHEN LOWER(c.category_name) = 'necessary' THEN 4
            ELSE 5
        END", 'ASC', FALSE);

        // Tambahan urutan ID terbaru jika semua kriteria di atas sama
        $this->db->order_by('a.apps_id', 'DESC');
        
        if ($limit > 0) { $this->db->limit($limit, $start); }
        return $this->db->get()->result_array();
    }

    public function count_my_portfolio($user_id, $role_id, $keyword = null, $filters = []) {
        $role_id = $this->_get_fixed_role($user_id, $role_id);
        $this->_build_portfolio_query($user_id, $role_id, $keyword, $filters);
        
        $safe_role = (int)$role_id;
        if ($safe_role == 2) {
            $this->db->having("(MAX(s.current_stage_role) IN (2, 3, 1, 0) OR MAX(ad.is_done) = 1)");
        } 
        elseif ($safe_role == 3) {
            $this->db->having("(MAX(s.current_stage_role) IN (3, 1, 0) OR MAX(ad.is_done) = 1)");
        } 
        elseif ($safe_role == 1) {
            $this->db->having("(MAX(s.current_stage_role) IN (1, 0) OR MAX(ad.is_done) = 1)");
        }

        $this->db->group_by('a.apps_id'); 
        return $this->db->get()->num_rows();
    }

    public function get_dynamic_options($target_key, $user_id, $role_id, $current_filters = []) {
        $role_id = $this->_get_fixed_role($user_id, $role_id);
        if(!isset($this->_filter_map[$target_key])) return [];
        $column = $this->_filter_map[$target_key];
        
        $this->db->select("DISTINCT TRIM($column) as val", FALSE);
        $this->_join_tables(); 
        
        $this->db->join('tbl_apps_database adb', 'adb.apps_id = a.apps_id', 'left');
        $this->db->join('tbl_database_master dbm', 'dbm.database_id = adb.database_id', 'left');
        $this->db->join('tbl_apps_operating_software aos', 'aos.apps_id = a.apps_id', 'left');
        $this->db->join('tbl_operating_software os', 'os.operating_software_id = aos.operating_software_id', 'left');
        $this->db->join('tbl_apps_server asr', 'asr.apps_id = a.apps_id', 'left');
        $this->db->join('tbl_server srv', 'srv.server_id = asr.server_id', 'left');

        $this->_apply_rbac($user_id, $role_id);

        $this->db->where("$column IS NOT NULL", NULL, FALSE);
        $this->db->where("TRIM($column) != ''", NULL, FALSE); 
        $this->db->order_by("val", 'ASC');
        
        $query = $this->db->get();
        $results = [];
        foreach ($query->result_array() as $row) { if(!empty($row['val'])) $results[] = $row['val']; }
        return array_unique($results);
    }
    
    public function get_portfolio_full_detail($apps_id) {
        $this->_join_tables(); 
        
        $this->db->select('a.*');
        $this->db->select('c.category_name, c.category_id'); 
        $this->db->select('at.app_type_name'); 
        $this->db->select('n.network_name, n.network_id');
        $this->db->select('d.deployment_model, d.deployment_id');
        $this->db->select('dp.deployment_provider_name as provider_name, ds.deployment_site_name as site_name');
        $this->db->select('od.start_day, od.end_day, od.operational_day_id, od.total_day');
        $this->db->select('oh.start_time, oh.end_time, oh.operational_hour_id, oh.total_hour');
        $this->db->select('r.resilience_category, r.dr as dr_availability, r.ha, r.resilience_id');
        $this->db->select('pdr.recovery_time_dr'); // Tambahkan kolom recovery_time
        $this->db->select('pi.response_time, pi.response_time_sat, pi.recovery_time, pi.recovery_time_sat');
        
        $this->db->join('tbl_penanganan_dr pdr', 'pdr.category_id = a.category_id', 'left'); // Tambahkan Join ke tabel 

        
        $this->db->select("CONCAT(od.start_day, ' - ', od.end_day) as operational_day_full", FALSE);
        $this->db->select("CONCAT(oh.start_time, ' - ', oh.end_time) as operational_hour_full", FALSE);

        $this->db->where('a.apps_id', $apps_id);
        $this->db->group_by('a.apps_id');
        
        $row = $this->db->get()->row_array();

        if ($row) {
            $dbs = $this->db->select('adb.database_id, dbm.database_name')
                ->from('tbl_apps_database adb')
                ->join('tbl_database_master dbm', 'dbm.database_id = adb.database_id')
                ->where('adb.apps_id', $apps_id)->get()->result_array();
            $db_ids = []; $db_names = [];
            foreach ($dbs as $item) { $db_ids[] = $item['database_id']; $db_names[] = $item['database_name']; }
            $row['database_ids_str'] = implode(',', $db_ids); 
            $row['database_names_str'] = implode(', ', $db_names);

            $oss = $this->db->select('aos.operating_software_id, os.operating_software_name')
                ->from('tbl_apps_operating_software aos')
                ->join('tbl_operating_software os', 'os.operating_software_id = aos.operating_software_id')
                ->where('aos.apps_id', $apps_id)->get()->result_array();
            $os_ids = []; $os_names = [];
            foreach ($oss as $item) { $os_ids[] = $item['operating_software_id']; $os_names[] = $item['operating_software_name']; }
            $row['os_ids_str'] = implode(',', $os_ids);
            $row['os_names_str'] = implode(', ', $os_names);
            
            $srvs = $this->db->select('asr.server_id, srv.server_name')
                ->from('tbl_apps_server asr')
                ->join('tbl_server srv', 'srv.server_id = asr.server_id')
                ->where('asr.apps_id', $apps_id)->get()->result_array();
            
            $srv_ids = []; $srv_names = [];
            foreach ($srvs as $item) { 
                $srv_ids[] = $item['server_id']; 
                $srv_names[] = $item['server_name']; 
            }
            $row['server_ids_str'] = implode(',', $srv_ids);
            $row['server_names_str'] = implode(', ', $srv_names);
        }

        return $row;
    }

    public function save_apps_info($apps_id, $post_data, $is_submit = false, $role_id = 0, $remarks = null, $uploaded_filename = null) {
        $user_id = $this->session->userdata('user_id');
        $now = date('Y-m-d H:i:s');

        // MAPPING AMAN: Hanya update jika field benar-benar dikirim via POST.
        $data = [];
        $fields = [
            'application_name', 'short_name', 'apps_description', 'app_type_id', 'live_year',
            'decommission_year', 'category_id', 'network_id', 'deployment_id', 'deployment_provider_id',
            'deployment_site_id', 'resilience_id', 'operational_day_id', 'operational_hour_id', 'module',
            'lob_directorate', 'lob_subdirectorate', 'lob_group', 'lob_group_head', 'lob_department_head', // NEW LOB HEAD
            'it_subdirectorate',
            'it_department_head', 'it_support_group', 'it_group_head', 'it_support_divison', 'it_division_head',
            'application_version', 'development_language', 'application_developer', 'supporting_web_server',
            'supporting_application_server', 'supporting_others', 'source_code_owned', 'Url', 'solution_vendor', 'services_vendor',
            'standard_category'
        ];

        foreach ($fields as $f) {
            if (isset($post_data[$f])) {
                $data[$f] = ($post_data[$f] !== '') ? $post_data[$f] : NULL;
            }
        }

        if ($uploaded_filename !== null) {
            $data['attached_document'] = $uploaded_filename;
        }

        if (empty($apps_id) || $apps_id == 0) {
            $data['created_at'] = $now;
            $data['created_by'] = $user_id;
            $data['status'] = 1; 
            $this->db->insert('tbl_portofolio_apps_master', $data);
            $apps_id = $this->db->insert_id();
            
            $this->generate_initial_workflow_batch($apps_id, $user_id, $now, $is_submit, $remarks);
        } else {
            $data['modified_at'] = $now;
            $data['modified_by'] = $user_id;
            if (!empty($data)) {
                $this->db->where('apps_id', $apps_id);
                $this->db->update('tbl_portofolio_apps_master', $data);
            }
            $this->_handle_inputter_logic($apps_id, $role_id, $user_id, $now, $is_submit, $remarks);
        }

        // Simpan Database & OS hanya jika dikirim
        if (isset($post_data['database_ids'])) {
            $this->db->delete('tbl_apps_database', ['apps_id' => $apps_id]);
            if (!empty($post_data['database_ids'])) {
                $db_batch = [];
                foreach ($post_data['database_ids'] as $db_id) { $db_batch[] = ['apps_id' => $apps_id, 'database_id' => $db_id]; }
                $this->db->insert_batch('tbl_apps_database', $db_batch);
            }
        }

        if (isset($post_data['os_ids'])) {
            $this->db->delete('tbl_apps_operating_software', ['apps_id' => $apps_id]);
            if (!empty($post_data['os_ids'])) {
                $os_batch = [];
                foreach ($post_data['os_ids'] as $os_id) { $os_batch[] = ['apps_id' => $apps_id, 'operating_software_id' => $os_id]; }
                $this->db->insert_batch('tbl_apps_operating_software', $os_batch);
            }
        }
        
        if (isset($post_data['server_ids'])) {
            $this->db->delete('tbl_apps_server', ['apps_id' => $apps_id]);
            if (!empty($post_data['server_ids'])) {
                $srv_batch = [];
                foreach ($post_data['server_ids'] as $srv_id) { $srv_batch[] = ['apps_id' => $apps_id, 'server_id' => $srv_id]; }
                $this->db->insert_batch('tbl_apps_server', $srv_batch);
            }
        }

        if ($is_submit && $apps_id > 0) {
            $this->db->insert('tbl_apps_audit_trail', [
                'apps_id'    => $apps_id,
                'role_id'    => $role_id,
                'action'     => (!empty($action_string)) ? $action_string : 'SUBMIT',
                'remarks'    => !empty($remarks) ? $remarks : 'Application Approved',
                'created_at' => date('Y-m-d H:i:s')
            ]);
        }

        return $apps_id;
    }

    private function generate_initial_workflow_batch($apps_id, $user_id, $now, $is_submit, $remarks) {
        $row_ea = [
            'apps_id'      => $apps_id,
            'user_role_id' => 2,
            'created_at'   => $now,
            'created_by'   => $user_id,
            'modified_at'  => $now,
            'modified_by'  => $user_id,
            'status'       => $is_submit ? 1 : 0,
            'current'      => $is_submit ? 0 : 1,
            'submit_date'  => $is_submit ? $now : null,
            'remarks'      => $is_submit ? $remarks : null
        ];
        $this->db->insert('tbl_apps_approval', $row_ea);

        if ($is_submit) {
            $row_dev = [
                'apps_id'      => $apps_id,
                'user_role_id' => 3,
                'created_at'   => $now,
                'created_by'   => $user_id,
                'modified_at'  => null,
                'modified_by'  => null,
                'status'       => 0,
                'current'      => 1,
                'submit_date'  => null,
                'remarks'      => null
            ];
            $this->db->insert('tbl_apps_approval', $row_dev);
        }
    }

    private function _handle_inputter_logic($apps_id, $role_id, $user_id, $now, $is_submit, $remarks) {
        if (!$is_submit) {
            $this->_upsert_approval($apps_id, $role_id, [
                'status'      => 0,
                'current'     => 1, 
                'created_by'  => $user_id, 
                'created_at'  => $now,      
                'modified_by' => $user_id,
                'modified_at' => $now,
                'submit_date' => NULL,
                'remarks'     => NULL
            ]);
        } else {
            $this->_upsert_approval($apps_id, $role_id, [
                'status'      => 1, 
                'current'     => 0, 
                'created_by'  => $user_id, 
                'created_at'  => $now,
                'modified_by' => $user_id,
                'modified_at' => $now,
                'submit_date' => $now,
                'remarks'     => $remarks
            ]);

            $next_role = 0;
            if ($role_id == 2) $next_role = 3;      
            elseif ($role_id == 3) $next_role = 1;  
            
            if ($next_role > 0) {
                $this->_upsert_approval($apps_id, $next_role, [
                    'status'      => 0,
                    'current'     => 1, 
                    'created_by'  => $user_id, 
                    'created_at'  => $now,
                    'modified_by' => null,
                    'modified_at' => null,
                    'submit_date' => NULL,
                    'remarks'     => NULL
                ]);
            }
        }
    }

    private function _upsert_approval($apps_id, $role_id, $data) {
        $exists = $this->db->where(['apps_id' => $apps_id, 'user_role_id' => $role_id])->count_all_results('tbl_apps_approval');
        if ($exists > 0) {
            if(isset($data['created_by'])) unset($data['created_by']);
            if(isset($data['created_at'])) unset($data['created_at']);
            
            $this->db->where(['apps_id' => $apps_id, 'user_role_id' => $role_id]);
            $this->db->update('tbl_apps_approval', $data);
        } else {
            $data['apps_id'] = $apps_id;
            $data['user_role_id'] = $role_id;
            $this->db->insert('tbl_apps_approval', $data);
        }
    }

    public function advance_workflow($apps_id, $current_role_id, $action, $remarks = '') {
        $now = date('Y-m-d H:i:s');
        $user_id = $this->session->userdata('user_id');
        // TAMBAHKAN BARIS INI: ambil role_id dari parameter agar tidak null
        $role_id = $current_role_id; 

        $update_data = [
            'status'      => 1, 
            'current'     => 0, 
            'submit_date' => $now, 
            'modified_by' => $user_id,
            'modified_at' => $now,
            'remarks'     => $remarks
        ];

        $row = $this->db->get_where('tbl_apps_approval', ['apps_id' => $apps_id, 'user_role_id' => $current_role_id])->row();
        if($row && empty($row->created_by)) {
            $update_data['created_by'] = $user_id;
            $update_data['created_at'] = $now;
        }

        $this->db->where(['apps_id' => $apps_id, 'user_role_id' => $current_role_id]);
        $this->db->update('tbl_apps_approval', $update_data);
        
        $next_role = 0;
        if ($current_role_id == 2) $next_role = 3;
        elseif ($current_role_id == 3) $next_role = 1;
        
        if ($next_role > 0) {
            $this->_upsert_approval($apps_id, $next_role, [
                'status'      => 0,
                'current'     => 1, 
                'created_by'  => $user_id,
                'created_at'  => $now,
                'modified_by' => null,
                'modified_at' => null,
                'submit_date' => null,
                'remarks'     => null
            ]);
        }

        $this->db->insert('tbl_apps_audit_trail', [
            'apps_id'    => $apps_id,
            'role_id'    => $role_id, // Sekarang $role_id sudah memiliki nilai (current_role_id)
            'action'     => $action,
            'remarks'    => !empty($remarks) ? $remarks : "-",
            'created_at' => date('Y-m-d H:i:s')
        ]);
    }

    public function get_master_data($table) {
        if ($this->db->table_exists($table)) { return $this->db->get($table)->result_array(); }
        return [];
    }
    
    public function _get_fixed_role($user_id, $role_id_passed) {
        if (!empty($role_id_passed) && $role_id_passed != 0) return $role_id_passed;
        if ($user_id == 1) return 1;
        $query = $this->db->get_where('tbl_user_role', ['id' => $user_id]);
        $row = $query->row();
        if ($row) return (int) $row->role_id;
        return 0;
    }
    
    private function _build_task_query($user_id, $role_id) {
        $role_id = $this->_get_fixed_role($user_id, $role_id);
        $this->db->from('tbl_apps_approval ap');
        $this->db->join('tbl_portofolio_apps_master a', 'a.apps_id = ap.apps_id');
        $this->db->join('tbl_apps_category c', 'c.category_id = a.category_id', 'left');
        $this->db->where('ap.user_role_id', $role_id);
        $this->db->where('ap.current', 1);
        $this->db->where('ap.status', 0);
    }
    
    public function get_my_tasks($user_id, $role_id) {
        $this->db->select('ap.approval_id, ap.apps_id, ap.user_role_id, ap.status, ap.current, ap.submit_date, ap.modified_by, ap.modified_at');
        $this->db->select('a.application_name, a.short_name, a.created_at, a.created_by');
        $this->db->select('c.category_name, a.module as module_name, a.apps_description');
        $this->_build_task_query($user_id, $role_id);
        $this->db->group_by('ap.approval_id');
        
        // --- LOGIKA PENGURUTAN PRIORITAS KATEGORI ---
        $this->db->order_by("CASE
            WHEN LOWER(c.category_name) = 'critical' THEN 1
            WHEN LOWER(c.category_name) = 'very important' THEN 2
            WHEN LOWER(c.category_name) = 'important' THEN 3
            WHEN LOWER(c.category_name) = 'necessary' THEN 4
            ELSE 5
        END", 'ASC', FALSE);
        // --------------------------------------------
        
        $this->db->order_by('ap.approval_id', 'DESC');
        $query = $this->db->get()->result_array();
        
        foreach ($query as &$row) {
            $start_time = !empty($row['submit_date']) ? $row['submit_date'] : $row['created_at'];
            $row['time_elapsed'] = $this->time_elapsed_string($start_time);
            
            if ($row['user_role_id'] == 2) { 
                $is_revision = $this->check_is_revision($row['apps_id'], $row['modified_by'], $row['modified_at']);
                $row['task_status_label'] = $is_revision ? 'Needs Revision' : 'Drafting';
                $row['task_color'] = $is_revision ? 'orange' : 'yellow';
                $row['btn_label'] = $is_revision ? 'Fix Now' : 'Take Action';
            } elseif ($row['user_role_id'] == 3) {
                $row['task_status_label'] = 'Waiting Review';
                $row['task_color'] = 'yellow';
                $row['btn_label'] = 'Take Action';
            } elseif ($row['user_role_id'] == 1) { 
                $row['task_status_label'] = 'Waiting Final Review';
                $row['task_color'] = 'yellow';
                $row['btn_label'] = 'Take Action';
            }
        }
        return $query;
    }
    
    public function count_my_tasks($user_id, $role_id) {
        $this->_build_task_query($user_id, $role_id);
        $this->db->group_by('ap.approval_id');
        return $this->db->get()->num_rows();
    }
    
    private function check_is_revision($apps_id, $mod_by, $mod_at) {
        if(empty($mod_by) || empty($mod_at)) return false;
        $this->db->where('apps_id', $apps_id);
        $this->db->where_in('user_role_id', [1, 3]); 
        $this->db->where('modified_by', $mod_by);        
        $this->db->where('modified_at', $mod_at);        
        return $this->db->count_all_results('tbl_apps_approval') > 0;
    }
    
    public function time_elapsed_string($datetime, $full = false) {
        if(!$datetime || $datetime == '0000-00-00 00:00:00') return 'Just now';
        try {
            $now = new DateTime;
            $ago = new DateTime($datetime);
            $diff = $now->diff($ago);
            $string = array('y' => 'year', 'm' => 'month', 'd' => 'day', 'h' => 'hour', 'i' => 'minute', 's' => 'second');
            foreach ($string as $k => &$v) { if ($diff->$k) $v = $diff->$k . ' ' . $v . ($diff->$k > 1 ? 's' : ''); else unset($string[$k]); }
            if (!$full) $string = array_slice($string, 0, 1);
            return $string ? implode(', ', $string) . ' ago' : 'just now';
        } catch(Exception $e) { return 'Just now'; }
    }
    
    public function get_timeline_data($apps_id) {
        $this->db->select('ap.user_role_id, ap.status, ap.current, ap.submit_date, ap.remarks, ap.modified_at, ap.modified_by, r.role_name');
        $this->db->from('tbl_apps_approval ap');
        $this->db->join('tbl_role r', 'r.role_id = ap.user_role_id');
        $this->db->where('ap.apps_id', $apps_id);
        $this->db->order_by('ap.user_role_id', 'ASC');
        return $this->db->get()->result_array();
    }
    
    public function get_audit_trail($apps_id) {
        $this->db->select('at.*, r.role_name'); // at.* memastikan kolom 'action' ikut terambil
        $this->db->from('tbl_apps_audit_trail at');
        $this->db->join('tbl_role r', 'at.role_id = r.role_id', 'left');
        $this->db->where('at.apps_id', $apps_id);
        $this->db->order_by('at.created_at', 'DESC');
        return $this->db->get()->result_array();
    }
    
    public function get_documents($apps_id) {
        if ($this->db->table_exists('tbl_apps_documents')) {
            return $this->db->get_where('tbl_apps_documents', ['apps_id' => $apps_id])->result_array();
        }
        return [];
    }
    
    public function get_current_approval_stage($apps_id) {
        $row = $this->db->get_where('tbl_apps_approval', ['apps_id' => $apps_id, 'current' => 1])->row_array();
        if ($row) $row['role_id'] = $row['user_role_id']; 
        return $row;
    }
    
    public function delete_app($apps_id) {
        // Gunakan Transaction agar jika salah satu gagal, semuanya di-rollback
        $this->db->trans_start();

        // 1. Hapus data dari tabel relasi (Child) terlebih dahulu
        $this->db->delete('tbl_apps_database', ['apps_id' => $apps_id]);
        $this->db->delete('tbl_apps_operating_software', ['apps_id' => $apps_id]);
        $this->db->delete('tbl_apps_approval', ['apps_id' => $apps_id]);

        // 2. Hapus data dari tabel utama (Parent)
        $this->db->delete('tbl_portofolio_apps_master', ['apps_id' => $apps_id]);

        $this->db->trans_complete();

        // Kembalikan status keberhasilan eksekusi database
        return $this->db->trans_status();
    }
    
    public function check_duplicate($app_name, $module, $exclude_apps_id = 0) {
        $this->db->where('application_name', trim($app_name));
        $this->db->where('module', trim($module));
        
        // Jika sedang Edit data, abaikan ID miliknya sendiri
        if ($exclude_apps_id > 0) {
            $this->db->where('apps_id !=', $exclude_apps_id);
        }
        
        return $this->db->count_all_results('tbl_portofolio_apps_master') > 0;
    }
    
    public function is_app_done($apps_id) {
        $this->db->where('apps_id', $apps_id);
        $this->db->where('user_role_id', 1); // Role 1 adalah tahap akhir (IT SLM)
        $this->db->where('status', 1);       // 1 = Approved/DONE
        $query = $this->db->get('tbl_apps_approval');
        
        return $query->num_rows() > 0;
    }
    
    public function update_app_status($apps_id, $new_status, $uploaded_filename, $decom_year, $user_id, $role_id, $audit_action, $remarks) {
        $this->db->trans_start();

        // 1. Update data di tabel master
        $update_data = [
            'status' => $new_status,
            'decommission_year' => $decom_year
        ];

        if ($uploaded_filename !== null) {
            $update_data['attached_document'] = $uploaded_filename;
            // Blok SLA History dihapus dari sini agar tidak tercampur
        }

        $this->db->where('apps_id', $apps_id);
        $this->db->update('tbl_portofolio_apps_master', $update_data);

        // 2. Insert ke Audit Trail HANYA SEKALI
        $action_label = ($new_status == 1) ? 'APP ACTIVATED' : 'APP DEACTIVATED';
        $remarks_text = ($new_status == 1) ? 'Aplikasi diaktifkan kembali.' : 'Aplikasi dinonaktifkan (Deactivated).';
        
        if ($uploaded_filename !== null) {
            $remarks_text .= ' Dilengkapi dengan dokumen pendukung terbaru.';
        }

        $this->db->insert('tbl_apps_audit_trail', [
            'apps_id'    => $apps_id,
            'role_id'    => $role_id,
            'action'     => $audit_action, // Gunakan variabel parameter ini
            'remarks'    => ($status == 0) ? 'Application Deactivated' : 'Application Activated',
            'created_at' => date('Y-m-d H:i:s')
        ]);

        $this->db->trans_complete();

        return $this->db->trans_status();
    }

    public function get_sla_history($apps_id) {
        $this->db->where('apps_id', $apps_id);
        $this->db->order_by('version', 'DESC'); 
        
        return $this->db->get('tbl_apps_sla_history')->result_array();
    }
    
    // Fungsi untuk mencatat history SLA hasil generate sistem
    public function insert_sla_history($apps_id, $filename, $remarks) {
        // Cari versi terakhir
        $this->db->where('apps_id', $apps_id);
        $this->db->select_max('version');
        $row = $this->db->get('tbl_apps_sla_history')->row();
        
        $new_version = ($row && $row->version) ? $row->version + 1 : 1;

        // Insert ke history
        $this->db->insert('tbl_apps_sla_history', [
            'apps_id'    => $apps_id,
            'version'    => $new_version,
            'file_name'  => $filename,
            'created_by' => $this->session->userdata('user_id'),
            'created_at' => date('Y-m-d H:i:s'),
            'remarks'    => $remarks
        ]);

        // Opsional: Update dokumen terbaru di tabel master
        $this->db->where('apps_id', $apps_id);
        $this->db->update('tbl_portofolio_apps_master', ['attached_document' => $filename]);
    }
    
    // Tambahkan fungsi ini ke Home_model.php
    public function process_renewal($apps_id, $user_id, $role_id) {
        $this->db->trans_start(); // Mulai transaksi DB

        // 1. Reset Semua Workflow (Jadikan pending dan bukan current)
        // Kosongkan remarks dan submit_date untuk siklus renewal
        $this->db->where('apps_id', $apps_id);
        $this->db->update('tbl_apps_approval', [
            'status'      => 0,
            'current'     => 0,
            'remarks'     => NULL, 
            'submit_date' => NULL
        ]);

        // 2. Set EA (Role 2) sebagai tahap yang aktif saat ini
        $this->db->where('apps_id', $apps_id);
        $this->db->where('user_role_id', 2);
        $this->db->update('tbl_apps_approval', [
            'current' => 1
        ]);

        // 3. TAMBAHKAN/PASTIKAN BAGIAN INI: Simpan log "RENEWAL"
        $this->db->insert('tbl_apps_audit_trail', [
            'apps_id'    => $apps_id,
            'role_id'    => $role_id,
            'action'     => 'RENEWAL', // Ini yang akan jadi badge RENEWAL
            'remarks'    => 'Aplikasi masuk masa perpanjangan(Renewal)',
            'created_at' => date('Y-m-d H:i:s')
         ]);

        $this->db->trans_complete(); // Selesai transaksi

        return $this->db->trans_status();
    }
}