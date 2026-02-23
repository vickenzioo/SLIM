<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Home_model extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->db->query("SET sql_mode = ''");
    }

    public function get_infra_master_mapping() {
        $this->db->select('im.infra_id, im.module_id, im.service_id, im.resilience_id');
        $this->db->select('sv.service_name');
        $this->db->select('r.resilience_category');
        $this->db->from('tbl_portofolio_infra_master im');
        $this->db->join('tbl_service sv', 'sv.service_id = im.service_id', 'left');
        $this->db->join('tbl_resilience r', 'r.resilience_id = im.resilience_id', 'left');
        return $this->db->get()->result_array();
    }

    // [PERBAIKAN] Mengubah op_hour dan op_day menggunakan format CONCAT agar memunculkan format full seperti "Monday - Sunday"
    protected $_filter_map = [
        'status'        => "(CASE WHEN (SELECT COUNT(1) FROM tbl_apps_approval ap_done WHERE ap_done.apps_id = a.apps_id AND ap_done.user_role_id = 8 AND ap_done.status = 1) > 0 THEN 'DONE' ELSE tr.role_name END)",
        'category'      => 'c.category_name',
        'app_name'      => 'a.application_name',
        'short_name'    => 'a.short_name',
        'module'        => 'm.module_name',
        'service_name'  => 'sv.service_name',
        'db_name'       => 'dbm.database_name',
        'os_name'       => 'os.operating_software_name',
        'app_type'      => 'a.application_type',
        'live_year'     => 'a.live_year',
        'decom_year'    => 'a.decommission_year',
        'resilience'    => 'r.resilience_category',
        'server_type'   => 'ts.server_name', 
        'dr_avail'      => 'r.dr',
        'ha'            => 'r.ha',
        'flash_copy'    => 'a.flash_copy',
        'eod'           => 'a.end_of_day',
        'network'       => 'n.network_name',
        'deployment'    => "CONCAT_WS(' - ', d.deployment_model, d.deployment_provider, d.main_deployment_site)",
        'op_hour'       => "CONCAT(oh.start_time, ' - ', oh.end_time)",
        'op_day'        => "CONCAT(od.start_day, ' - ', od.end_day)",
        'principle'     => 'a.principle_name',
        'principle_sol' => 'a.principle_solution_name',
        'it_group'      => 'a.it_group_name',
        'it_division'   => 'a.it_division_name',
        'directorate'   => 'a.owner_directorate',
        'sub_directorate'=> 'a.owner_subdirectorate',
        'owner_title'   => 'a.owner_title',
        'nik_head'      => 'a.nik_owner_head',
        'nik_owner'     => 'a.nik_owner',
        'nik_dept'      => 'a.nik_it_department'
    ];

    private function _join_tables() {
        $this->db->from('tbl_portofolio_apps_master a');
        $this->db->join('tbl_apps_infra ai', 'ai.apps_id = a.apps_id', 'left');
        $this->db->join('tbl_portofolio_infra_master im', 'im.infra_id = ai.infra_id', 'left');
        $this->db->join('tbl_service sv', 'sv.service_id = im.service_id', 'left');
        $this->db->join('tbl_module m', 'm.module_id = im.module_id', 'left');
        $this->db->join('tbl_infra_server isv', 'isv.infra_id = ai.infra_id', 'left');
        $this->db->join('tbl_apps_category c', 'c.category_id = a.category_id', 'left');
        $this->db->join('tbl_apps_operational_day od', 'od.operational_day_id = a.operational_day_id', 'left');
        $this->db->join('tbl_apps_operational_hour oh', 'oh.operational_hour_id = a.operational_hour_id', 'left');
        $this->db->join('tbl_apps_network n', 'n.network_id = a.network_id', 'left');
        $this->db->join('tbl_apps_deployment d', 'd.deployment_id = a.deployment_id', 'left');
        $this->db->join('tbl_resilience r', 'r.resilience_id = a.resilience_id', 'left'); 
        $this->db->join('(SELECT apps_id, user_role_id as current_stage_role FROM tbl_apps_approval WHERE current = 1) s', 's.apps_id = a.apps_id', 'left');
        $this->db->join('tbl_role tr', 's.current_stage_role = tr.role_id', 'left');
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
            
            if ($applied_any) {
                $this->db->group_end();
            }
        }
    }

    private function _apply_rbac($user_id, $role_id) {
        $safe_user = (int)$user_id;
        $safe_role = (int)$role_id;
        if ($safe_role != 1) { 
            $this->db->where("(
                a.created_by = $safe_user 
                OR 
                a.apps_id IN (SELECT apps_id FROM tbl_apps_approval WHERE user_role_id = $safe_role AND (current=1 OR status=1))
            )", NULL, FALSE);
        }
    }

    private function _build_portfolio_query($user_id, $role_id, $keyword = null, $filters = []) {
        $this->_join_tables();
        
        $this->db->join('tbl_apps_database adb', 'adb.apps_id = a.apps_id', 'left');
        $this->db->join('tbl_database_master dbm', 'dbm.database_id = adb.database_id', 'left');
        $this->db->join('tbl_apps_operating_software aos', 'aos.apps_id = a.apps_id', 'left');
        $this->db->join('tbl_operating_software os', 'os.operating_software_id = aos.operating_software_id', 'left');
        $this->db->join('tbl_server ts', 'ts.server_id = isv.server_id', 'left');

        $this->_apply_rbac($user_id, $role_id);

        if ($keyword) {
            $this->db->group_start();
            $this->db->like('a.application_name', $keyword);
            $this->db->or_like('a.short_name', $keyword);
            $this->db->or_like('tr.role_name', $keyword);
            $this->db->or_like('sv.service_name', $keyword);
            $this->db->or_where("isv.server_web_prod_count LIKE '%".$this->db->escape_like_str($keyword)."%' ESCAPE '!'", NULL, FALSE);
            $this->db->or_where("isv.server_app_prod_count LIKE '%".$this->db->escape_like_str($keyword)."%' ESCAPE '!'", NULL, FALSE);
            $this->db->or_where("isv.server_db_prod_count LIKE '%".$this->db->escape_like_str($keyword)."%' ESCAPE '!'", NULL, FALSE);
            
            $done_cond = "(CASE WHEN (SELECT COUNT(1) FROM tbl_apps_approval ap_done WHERE ap_done.apps_id = a.apps_id AND ap_done.user_role_id = 8 AND ap_done.status = 1) > 0 THEN 'DONE' ELSE '' END)";
            $this->db->or_where("$done_cond LIKE '%".$this->db->escape_like_str($keyword)."%' ESCAPE '!'", NULL, FALSE);
            
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
        $this->db->select('MAX(tr.role_name) as status_name'); 
        $this->db->select('MAX(m.module_name) as module_name');
        $this->db->select('MAX(sv.service_id) as service_id'); 
        $this->db->select('MAX(sv.service_name) as service_name');
        $this->db->select('MAX(ts.server_name) as server_type_name'); 
        $this->db->select('GROUP_CONCAT(DISTINCT dbm.database_name SEPARATOR ", ") as database_names');
        $this->db->select('GROUP_CONCAT(DISTINCT os.operating_software_name SEPARATOR ", ") as os_names');
        $this->db->select('MAX(n.network_name) as network_name');
        $this->db->select('MAX(r.resilience_category) as resilience'); 
        $this->db->select('MAX(r.dr) as dr_availability');
        $this->db->select('MAX(r.ha) as ha');
        $this->db->select("CONCAT_WS(' - ', MAX(d.deployment_model), MAX(d.deployment_provider), MAX(d.main_deployment_site)) as deployment_info");
        $this->db->select("CONCAT(MAX(od.start_day), ' - ', MAX(od.end_day)) as operational_day", FALSE);
        $this->db->select("CONCAT(MAX(oh.start_time), ' - ', MAX(oh.end_time)) as operational_hour", FALSE);

        $safe_role = (int)$role_id;

        if (in_array($safe_role, [4, 5])) {
            $this->db->group_by(array('a.apps_id', 'ai.apps_infra_id'));
        } else {
            $this->db->group_by('a.apps_id');
        }

        $this->db->order_by("CASE WHEN MAX(s.current_stage_role) IS NULL OR MAX(s.current_stage_role) = 0 THEN 1 ELSE 0 END", 'ASC');
        $this->db->order_by("CASE WHEN MAX(s.current_stage_role) = $safe_role THEN 0 ELSE 1 END", 'ASC');
        $this->db->order_by("CASE 
            WHEN LOWER(MAX(c.category_name)) = 'necessary' THEN 1
            WHEN LOWER(MAX(c.category_name)) = 'critical' THEN 2
            WHEN LOWER(MAX(c.category_name)) = 'very important' THEN 3
            WHEN LOWER(MAX(c.category_name)) = 'important' THEN 4
            ELSE 5 
        END", 'ASC');
        $this->db->order_by('a.apps_id', 'DESC');
        
        if ($limit > 0) { $this->db->limit($limit, $start); }
        return $this->db->get()->result_array();
    }

    public function get_my_infra_portfolio($user_id, $role_id, $keyword = null, $filters = [], $limit = 10, $start = 0) {
        $role_id = $this->_get_fixed_role($user_id, $role_id);
        $this->_build_portfolio_query($user_id, $role_id, $keyword, $filters);

        $this->db->select('im.infra_id'); 
        $this->db->select('MAX(sv.service_id) as service_id'); 
        $this->db->select('MAX(sv.service_name) as service_name'); 
        $this->db->select('GROUP_CONCAT(DISTINCT dbm.database_name SEPARATOR ", ") as database_names');
        $this->db->select('GROUP_CONCAT(DISTINCT os.operating_software_name SEPARATOR ", ") as os_names');
        $this->db->select('MAX(a.apps_id) as apps_id');
        $this->db->select('MAX(tr.role_name) as status_name');
        $this->db->select('MAX(s.current_stage_role) as current_stage_role');
        $this->db->select('MAX(c.category_name) as category_name');
        $this->db->select('MAX(m.module_name) as module_name');
        $this->db->select('(SELECT resilience_category FROM tbl_resilience WHERE resilience_id = im.resilience_id) as resilience_category');
        $this->db->select('MAX(a.application_name) as application_name'); 
        $this->db->select('MAX(ts.server_name) as server_type_name');
        $this->db->select('MAX(ts.server_sla) as sla_by_infra_pct');
        $this->db->select('MAX(c.standard_category) as sla_standard'); 
        $this->db->select('MAX(isv.server_web_prod_count) as server_web_prod_count');
        $this->db->select('MAX(isv.server_app_prod_count) as server_app_prod_count');
        $this->db->select('MAX(isv.server_db_prod_count) as server_db_prod_count');
        $this->db->select('MAX(isv.server_web_dr_count) as server_web_dr_count');
        $this->db->select('MAX(isv.server_app_dr_count) as server_app_dr_count');
        $this->db->select('MAX(isv.server_db_dr_count) as server_db_dr_count');

        $this->db->group_by(array('a.apps_id', 'ai.apps_infra_id'));
        
        $safe_role = (int)$role_id;
        $this->db->order_by("CASE WHEN MAX(s.current_stage_role) IS NULL OR MAX(s.current_stage_role) = 0 THEN 1 ELSE 0 END", 'ASC');
        $this->db->order_by("CASE WHEN MAX(s.current_stage_role) = $safe_role THEN 0 ELSE 1 END", 'ASC');
        $this->db->order_by('im.infra_id', 'DESC');

        if ($limit > 0) { $this->db->limit($limit, $start); }
        
        return $this->db->get()->result_array();
    }
    
    public function count_my_infra_portfolio($user_id, $role_id, $keyword = null, $filters = []) {
        $role_id = $this->_get_fixed_role($user_id, $role_id);
        $this->_build_portfolio_query($user_id, $role_id, $keyword, $filters);
        $this->db->group_by(array('a.apps_id', 'ai.apps_infra_id')); 
        return $this->db->get()->num_rows();
    }

    public function count_my_portfolio($user_id, $role_id, $keyword = null, $filters = []) {
        $role_id = $this->_get_fixed_role($user_id, $role_id);
        $this->_build_portfolio_query($user_id, $role_id, $keyword, $filters);
        
        if (in_array($role_id, [4, 5])) {
            $this->db->group_by(array('a.apps_id', 'ai.apps_infra_id')); 
        } else {
            $this->db->group_by('a.apps_id'); 
        }
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
        $this->db->join('tbl_server ts', 'ts.server_id = isv.server_id', 'left');

        $this->_apply_rbac($user_id, $role_id);
        $this->_apply_filters($current_filters);

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
        $this->db->join('tbl_server ts', 'ts.server_id = isv.server_id', 'left'); 
        
        $this->db->select('a.*');
        $this->db->select('c.category_name, c.category_id, c.standard_category'); 
        $this->db->select('m.module_name, im.module_id');
        
        $this->db->select('im.infra_id, im.service_id, im.resilience_id as infra_resilience_id');
        $this->db->select('sv.service_name'); 
        $this->db->select('(SELECT resilience_category FROM tbl_resilience WHERE resilience_id = im.resilience_id) as infra_resilience_category');

        $this->db->select('n.network_name, n.network_id');
        $this->db->select('d.deployment_model, d.deployment_provider, d.main_deployment_site, d.deployment_id');
        $this->db->select('od.start_day, od.end_day, od.operational_day_id');
        $this->db->select('oh.start_time, oh.end_time, oh.operational_hour_id');
        
        $this->db->select('r.resilience_category, r.dr as dr_availability, r.ha, r.resilience_id');
        
        $this->db->select("CONCAT_WS(' - ', d.deployment_model, d.deployment_provider, d.main_deployment_site) as deployment_info_full");
        $this->db->select("CONCAT(od.start_day, ' - ', od.end_day) as operational_day_full", FALSE);
        $this->db->select("CONCAT(oh.start_time, ' - ', oh.end_time) as operational_hour_full", FALSE);

        $this->db->select('isv.server_id');
        $this->db->select('ts.server_name as server_type_name, ts.server_sla as server_sla_pct');
        $this->db->select('isv.server_web_prod_count, isv.server_app_prod_count, isv.server_db_prod_count');
        $this->db->select('isv.server_web_dr_count, isv.server_app_dr_count, isv.server_db_dr_count');

        $this->db->where('a.apps_id', $apps_id);
        $this->db->group_by('a.apps_id');
        
        $row = $this->db->get()->row_array();

        if ($row) {
            if (!empty($row['infra_resilience_category'])) {
                $row['resilience_category'] = $row['infra_resilience_category'];
            }

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
        }

        return $row;
    }

    public function save_apps_info($apps_id, $post_data, $is_submit = false, $role_id = 0) {
        $user_id = $this->session->userdata('user_id');
        $now = date('Y-m-d H:i:s');
        $remarks = isset($post_data['remarks']) ? $post_data['remarks'] : null;

        if (in_array($role_id, [4])) {
            if (isset($post_data['infra']) && is_array($post_data['infra'])) {
                if (empty($post_data['is_single_edit'])) {
                    $this->db->delete('tbl_apps_infra', ['apps_id' => $apps_id, 'infra_id' => 0]);
                    
                    $posted_svc_ids = array_column($post_data['infra'], 'service_id');
                    if(!empty($posted_svc_ids) && isset($post_data['module_id'])) {
                        $infra_ids_to_keep = [];
                        foreach ($posted_svc_ids as $p_sid) {
                            $this->db->select('infra_id');
                            $this->db->where('service_id', $p_sid);
                            $this->db->where('module_id', $post_data['module_id']);
                            $res = $this->db->get('tbl_portofolio_infra_master')->result();
                            foreach($res as $r) $infra_ids_to_keep[] = $r->infra_id;
                        }
                        if (!empty($infra_ids_to_keep)) {
                            $this->db->where('apps_id', $apps_id);
                            $this->db->where_not_in('infra_id', $infra_ids_to_keep);
                            $this->db->delete('tbl_apps_infra');
                        } else {
                            $this->db->delete('tbl_apps_infra', ['apps_id' => $apps_id]);
                        }
                    }
                }

                foreach ($post_data['infra'] as $idx => $row) {
                    $service_id    = isset($row['service_id']) ? $row['service_id'] : 0;
                    $server_id     = !empty($row['server_id']) ? $row['server_id'] : 0;
                    $resilience_id = !empty($row['resilience_id']) ? $row['resilience_id'] : 0;
                    $module_id     = isset($post_data['module_id']) ? $post_data['module_id'] : 0;
                    
                    if(!$service_id || !$module_id) continue;

                    $where_check = [
                        'module_id'     => $module_id,
                        'service_id'    => $service_id,
                        'resilience_id' => $resilience_id
                    ];
                    
                    $master_infra = $this->db->get_where('tbl_portofolio_infra_master', $where_check)->row();

                    if (!$master_infra) {
                        $this->db->insert('tbl_portofolio_infra_master', $where_check);
                        $target_infra_id = $this->db->insert_id();
                    } else {
                        $target_infra_id = $master_infra->infra_id;
                    }

                    $this->db->select('ai.apps_infra_id');
                    $this->db->from('tbl_apps_infra ai');
                    $this->db->join('tbl_portofolio_infra_master im', 'im.infra_id = ai.infra_id');
                    $this->db->where('ai.apps_id', $apps_id);
                    $this->db->where('im.service_id', $service_id);
                    $existing_link = $this->db->get()->row();

                    if ($existing_link) {
                        $this->db->where('apps_infra_id', $existing_link->apps_infra_id);
                        $this->db->update('tbl_apps_infra', ['infra_id' => $target_infra_id]);
                    } else {
                        $this->db->insert('tbl_apps_infra', [
                            'apps_id' => $apps_id, 
                            'infra_id' => $target_infra_id
                        ]);
                    }

                    $data_server = [
                        'server_id'             => $server_id,
                        'server_web_prod_count' => !empty($row['prod_web']) ? $row['prod_web'] : 0,
                        'server_app_prod_count' => !empty($row['prod_apps']) ? $row['prod_apps'] : 0,
                        'server_db_prod_count'  => !empty($row['prod_db']) ? $row['prod_db'] : 0,
                        'server_web_dr_count'   => !empty($row['dr_web']) ? $row['dr_web'] : 0,
                        'server_app_dr_count'   => !empty($row['dr_apps']) ? $row['dr_apps'] : 0,
                        'server_db_dr_count'    => !empty($row['dr_db']) ? $row['dr_db'] : 0 
                    ];

                    $check_server = $this->db->get_where('tbl_infra_server', ['infra_id' => $target_infra_id])->row();
                    if ($check_server) {
                        $this->db->where('infra_id', $target_infra_id);
                        $this->db->update('tbl_infra_server', $data_server);
                    } else {
                        $data_server['infra_id'] = $target_infra_id;
                        $this->db->insert('tbl_infra_server', $data_server);
                    }
                }
            } 

            if ($is_submit) {
                $mod_id = isset($post_data['module_id']) ? $post_data['module_id'] : 0;
                
                $this->db->select('service_id')->distinct()->where('module_id', $mod_id);
                $req_count = $this->db->get('tbl_portofolio_infra_master')->num_rows();

                $this->db->select('im.service_id')->distinct();
                $this->db->from('tbl_apps_infra ai');
                $this->db->join('tbl_portofolio_infra_master im', 'im.infra_id = ai.infra_id');
                $this->db->join('tbl_infra_server isv', 'isv.infra_id = ai.infra_id');
                $this->db->where('ai.apps_id', $apps_id);
                $this->db->where('im.module_id', $mod_id);
                $this->db->where('isv.server_id >', 0);
                $this->db->where('(isv.server_web_prod_count > 0 OR isv.server_app_prod_count > 0 OR isv.server_db_prod_count > 0 OR isv.server_web_dr_count > 0 OR isv.server_app_dr_count > 0 OR isv.server_db_dr_count > 0)', NULL, FALSE);
                $filled_count = $this->db->get()->num_rows();

                if ($filled_count >= $req_count && $req_count > 0) {
                    $this->_handle_inputter_logic($apps_id, $role_id, $user_id, $now, true, $remarks);
                    return ['apps_id' => $apps_id, 'msg' => 'Semua data Service berhasil disubmit. Lanjut ke Approver.'];
                } else {
                    $this->_handle_inputter_logic($apps_id, $role_id, $user_id, $now, false, $remarks);
                    return ['apps_id' => $apps_id, 'msg' => "Data Service berhasil disimpan! Menunggu service lain disubmit ($filled_count dari $req_count service)."];
                }
            } else {
                $this->_handle_inputter_logic($apps_id, $role_id, $user_id, $now, false, $remarks);
                return $apps_id;
            }
        }

        if ($role_id == 6) {
            $data_bu = [
                'operational_day_id'  => !empty($post_data['operational_day_id']) ? $post_data['operational_day_id'] : NULL,
                'operational_hour_id' => !empty($post_data['operational_hour_id']) ? $post_data['operational_hour_id'] : NULL,
                'modified_at'         => $now,
                'modified_by'         => $user_id
            ];
            $this->db->where('apps_id', $apps_id);
            $this->db->update('tbl_portofolio_apps_master', $data_bu);

            $this->_handle_inputter_logic($apps_id, $role_id, $user_id, $now, $is_submit, $remarks);
            return $apps_id;
        }

        $data = [
            'application_name' => $post_data['application_name'],
            'short_name'       => $post_data['short_name'],
            'apps_description' => $post_data['apps_description'],
            'application_type' => $post_data['application_type'],
            'live_year'        => $post_data['live_year'],
            'decommission_year'=> $post_data['decommission_year'],
            'category_id'      => !empty($post_data['category_id']) ? $post_data['category_id'] : NULL,
            'network_id'       => !empty($post_data['network_id']) ? $post_data['network_id'] : NULL,
            'deployment_id'    => !empty($post_data['deployment_id']) ? $post_data['deployment_id'] : NULL,
            'resilience_id'    => !empty($post_data['resilience_id']) ? $post_data['resilience_id'] : NULL,
            'operational_day_id' => !empty($post_data['operational_day_id']) ? $post_data['operational_day_id'] : NULL,
            'operational_hour_id' => !empty($post_data['operational_hour_id']) ? $post_data['operational_hour_id'] : NULL,
            'flash_copy'       => !empty($post_data['flash_copy']) ? $post_data['flash_copy'] : NULL,
            'end_of_day'       => !empty($post_data['end_of_day']) ? $post_data['end_of_day'] : NULL,
            'it_group_name'    => $post_data['it_group_name'],
            'it_division_name' => $post_data['it_division_name'],
            'owner_directorate'=> $post_data['owner_directorate'],
            'owner_subdirectorate' => $post_data['owner_subdirectorate'],
            'owner_title'      => $post_data['owner_title'],
            'nik_owner_head'   => $post_data['nik_owner_head'],
            'nik_owner'        => $post_data['nik_owner'],
            'nik_it_department'=> $post_data['nik_it_department'],
            'principle_name'   => isset($post_data['principle_name']) ? $post_data['principle_name'] : null,
            'principle_solution_name' => isset($post_data['principle_solution_name']) ? $post_data['principle_solution_name'] : null
        ];

        if (empty($apps_id) || $apps_id == 0) {
            $data['created_at'] = $now;
            $data['created_by'] = $user_id;
            $this->db->insert('tbl_portofolio_apps_master', $data);
            $apps_id = $this->db->insert_id();
            
            $this->db->insert('tbl_apps_infra', ['apps_id' => $apps_id, 'infra_id' => 0]);
            
            $this->generate_initial_workflow_batch($apps_id, $user_id, $now, $is_submit, $remarks);

        } else {
            $data['modified_at'] = $now;
            $data['modified_by'] = $user_id;
            $this->db->where('apps_id', $apps_id);
            $this->db->update('tbl_portofolio_apps_master', $data);
            
            $this->_handle_inputter_logic($apps_id, $role_id, $user_id, $now, $is_submit, $remarks);
        }

        if ($role_id == 2) {
            $this->db->delete('tbl_apps_database', ['apps_id' => $apps_id]);
            if (!empty($post_data['database_ids'])) {
                $db_batch = [];
                foreach ($post_data['database_ids'] as $db_id) { $db_batch[] = ['apps_id' => $apps_id, 'database_id' => $db_id]; }
                $this->db->insert_batch('tbl_apps_database', $db_batch);
            }

            $this->db->delete('tbl_apps_operating_software', ['apps_id' => $apps_id]);
            if (!empty($post_data['os_ids'])) {
                $os_batch = [];
                foreach ($post_data['os_ids'] as $os_id) { $os_batch[] = ['apps_id' => $apps_id, 'operating_software_id' => $os_id]; }
                $this->db->insert_batch('tbl_apps_operating_software', $os_batch);
            }
        }

        return $apps_id;
    }

    private function generate_initial_workflow_batch($apps_id, $user_id, $now, $is_submit, $remarks) {
        $batch = [];
        for ($r = 2; $r <= 8; $r++) {
            $row = [
                'apps_id'       => $apps_id,
                'user_role_id' => $r,
                'created_at'   => null, 
                'created_by'   => null,
                'modified_at'  => null, 
                'modified_by'  => null,
                'status'       => 0, 
                'current'      => 0, 
                'submit_date'  => null, 
                'remarks'      => null
            ];

            if ($r == 2) {
                $row['created_by']  = $user_id; 
                $row['created_at']  = $now;
                $row['modified_by'] = $user_id; 
                $row['modified_at'] = $now;
                
                if ($is_submit) {
                    $row['status'] = 1;
                    $row['submit_date'] = $now;
                    $row['remarks'] = $remarks;
                } else {
                    $row['current'] = 1; 
                }
            }
            elseif ($r == 3) {
                if ($is_submit) {
                    $row['created_by']  = $user_id; 
                    $row['created_at']  = $now;
                    $row['modified_by'] = null; 
                    $row['modified_at'] = null;
                    $row['current'] = 1; 
                }
            }
            $batch[] = $row;
        }
        $this->db->insert_batch('tbl_apps_approval', $batch);
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
            elseif ($role_id == 4) $next_role = 5;  
            elseif ($role_id == 6) $next_role = 7;  
            
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

        $update_data = [
            'status'      => 1, 
            'current'     => 0, 
            'submit_date' => $now, 
            'modified_by' => $user_id,
            'modified_at' => $now,
            'remarks'     => $remarks
        ];

        $row = $this->db->get_where('tbl_apps_approval', ['apps_id' => $apps_id, 'user_role_id' => $current_role_id])->row();
        if(empty($row->created_by)) {
            $update_data['created_by'] = $user_id;
            $update_data['created_at'] = $now;
        }

        $this->db->where(['apps_id' => $apps_id, 'user_role_id' => $current_role_id]);
        $this->db->update('tbl_apps_approval', $update_data);
        
        $next_role = $current_role_id + 1;
        
        if ($next_role <= 8) {
            $this->_upsert_approval($apps_id, $next_role, [
                'status'      => 0,
                'current'     => 1, 
                'modified_by' => null,
                'modified_at' => null
            ]);
        }

        if ($current_role_id == 3) {
            $check_infra = $this->db->get_where('tbl_apps_infra', ['apps_id' => $apps_id])->row();
            if (!$check_infra) {
                $this->db->insert('tbl_apps_infra', ['apps_id' => $apps_id, 'infra_id' => 0]);
            }
        }
    }

    public function reject_workflow($apps_id, $current_role_id, $target_role_id, $remarks = '') {
        $now = date('Y-m-d H:i:s');
        $user_id = $this->session->userdata('user_id');

        $row = $this->db->get_where('tbl_apps_approval', ['apps_id' => $apps_id, 'user_role_id' => $current_role_id])->row();
        $update_data = [
            'current' => 0, 
            'status' => 0,
            'modified_by' => $user_id, 
            'modified_at' => $now,
            'remarks' => $remarks
        ];
        if(empty($row->created_by)) {
            $update_data['created_by'] = $user_id;
            $update_data['created_at'] = $now;
        }

        $this->db->where(['apps_id' => $apps_id, 'user_role_id' => $current_role_id]);
        $this->db->update('tbl_apps_approval', $update_data);

        $this->db->where('apps_id', $apps_id);
        $this->db->where('user_role_id >', $target_role_id);
        $this->db->where('user_role_id <', $current_role_id);
        $this->db->update('tbl_apps_approval', [
            'status' => 0,
            'current' => 0,
            'submit_date' => NULL,
            'remarks' => NULL 
        ]);

        $this->db->where(['apps_id' => $apps_id, 'user_role_id' => $target_role_id]);
        $this->db->update('tbl_apps_approval', [
            'current' => 1, 
            'status' => 0,
            'modified_by' => $user_id, 
            'modified_at' => $now      
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
        $this->db->join('tbl_apps_infra ai', 'ai.apps_id = a.apps_id', 'left');
        $this->db->join('tbl_portofolio_infra_master im', 'im.infra_id = ai.infra_id', 'left');
        $this->db->join('tbl_service sv', 'sv.service_id = im.service_id', 'left');
        $this->db->join('tbl_module m', 'm.module_id = im.module_id', 'left');
        $this->db->where('ap.user_role_id', $role_id);
        $this->db->where('ap.current', 1);
        $this->db->where('ap.status', 0);
    }
    public function get_my_tasks($user_id, $role_id) {
        $this->db->select('ap.approval_id, ap.apps_id, ap.user_role_id, ap.status, ap.current, ap.submit_date, ap.modified_by, ap.modified_at');
        $this->db->select('a.application_name, a.short_name, a.created_at, a.created_by');
        $this->db->select('c.category_name, m.module_name, a.apps_description');
        $this->_build_task_query($user_id, $role_id);
        $this->db->group_by('ap.approval_id');
        $this->db->order_by("CASE WHEN LOWER(c.category_name) = 'necessary' THEN 1 WHEN LOWER(c.category_name) = 'critical' THEN 2 ELSE 5 END", 'ASC');
        $this->db->order_by('ap.approval_id', 'DESC');
        $query = $this->db->get()->result_array();
        foreach ($query as &$row) {
            $start_time = !empty($row['submit_date']) ? $row['submit_date'] : $row['created_at'];
            $row['time_elapsed'] = $this->time_elapsed_string($start_time);
            if (in_array($row['user_role_id'], [2, 4, 6])) { 
                $is_revision = $this->check_is_revision($row['apps_id'], $row['modified_by'], $row['modified_at']);
                $row['task_status_label'] = $is_revision ? 'Needs Revision' : 'Drafting';
                $row['task_color'] = $is_revision ? 'orange' : 'yellow';
                $row['btn_label'] = $is_revision ? 'Fix Now' : 'Take Action';
            } else { 
                $is_dev = ($row['user_role_id'] == 8);
                $row['task_status_label'] = $is_dev ? 'Waiting Acknowledge' : 'Waiting Approval';
                $row['task_color'] = 'blue';
                $row['btn_label'] = $is_dev ? 'View' : 'Review';
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
        $this->db->where_in('user_role_id', [3, 5, 7]); 
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
        $this->db->select('ap.*, r.role_name');
        $this->db->from('tbl_apps_approval ap');
        $this->db->join('tbl_role r', 'r.role_id = ap.user_role_id');
        $this->db->where('ap.apps_id', $apps_id);
        $this->db->where('ap.submit_date IS NOT NULL'); 
        $this->db->order_by('ap.submit_date', 'DESC');
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
    public function is_user_turn($apps_id, $role_id) { 
        return $this->db->where(['apps_id'=>$apps_id, 'user_role_id'=>$role_id, 'current'=>1])->count_all_results('tbl_apps_approval') > 0; 
    }
}