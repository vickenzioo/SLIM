<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <title>SLIM | <?= $title ?></title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <?php $this->load->view('layout/head_links'); ?>
  
  <link rel="stylesheet" href="<?= base_url('assets/dist/css/slim/portofolio.css')?>">


<style>
#titleTask {
    display: flex;
    align-items: center; /* Ini yang membuat teks dan lingkaran sejajar lurus di tengah */
    line-height: 1;      /* Menjaga tinggi baris agar tidak ada extra space di bawah teks */
}

.task-badge-circle {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 30px;           /* Ukuran diameter lingkaran */
    height: 30px;
    background-color: #ffc107; /* Warna kuning sesuai border-top card */
    color: #212529;        /* Warna teks gelap agar kontras */
    font-size: 0.75rem;
    font-weight: 800;
    border-radius: 50%;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}
</style>

</head>

<?php 
    $rid = (int)$this->session->userdata('role_id'); 
    $is_infra = isset($is_infra) ? $is_infra : in_array($rid, [4, 5]);
    $is_bu    = isset($is_bu) ? $is_bu : in_array($rid, [6, 7]);
?>

<body class="hold-transition sidebar-mini layout-fixed sidebar-collapse">
<div class="wrapper">
  <?php $this->load->view('layout/header'); ?>
  <?php $this->load->view('layout/sidebar'); ?>

  <div class="content-wrapper">
    <div class="content-header"><div class="container-fluid"></div></div>

    <section class="content">
      <div class="container-fluid">
        
        <div class="row row-dashboard">
            
            <div class="col-md-4" id="colLeft">
                <div class="card shadow-sm" style="border-radius: 8px; border-top: 3px solid #ffc107;">
                    
                    <div class="card-header bg-white border-0 pb-0 pt-4 pl-4 pr-3 d-flex justify-content-between align-items-center">
                        <h4 class="font-weight-bold mb-0 d-flex align-items-center" id="titleTask" data-count="<?= !empty($my_tasks) ? count($my_tasks) : 0 ?>">
                            My Tasks
                            <span class="task-badge-circle ml-2">
                                <?= !empty($my_tasks) ? count($my_tasks) : 0 ?>
                            </span>
                        </h4>
                        <button type="button" class="btn-minimize-task ml-auto" onclick="toggleTaskSize(this)">
                            <i class="fas fa-chevron-left"></i>
                        </button>
                    </div>

                    <div class="card-body">
                        
                        <div id="fullTaskView" class="task-scroll-container">
                            <?php if(!empty($my_tasks)): ?>
                                <?php foreach($my_tasks as $task): ?>
                                    <?php 
                                        $colorClass = 'task-yellow'; 
                                        $btnClass   = 'btn-yellow';
                                        if(isset($task['task_color'])) {
                                            if($task['task_color'] == 'orange') { $colorClass = 'task-orange'; $btnClass = 'btn-orange'; } 
                                            elseif($task['task_color'] == 'blue') { $colorClass = 'task-blue'; $btnClass = 'btn-blue'; }
                                        }
                                    ?>
                                    <div class="card card-task <?= $colorClass ?> mb-3">
                                        <div class="card-body p-3">
                                            <div class="mb-2 clearfix">
                                                <span class="task-meta"><?= $task['time_elapsed'] ?></span>
                                                <span class="task-title"><?= $task['application_name'] ? $task['application_name'] : $task['short_name'] ?></span>
                                            </div>
                                            <div class="mb-3">
                                                <?php if(!empty($task['category_name'])): ?>
                                                    <div class="task-info-item">
                                                        Category : <?= $task['category_name'] ?>
                                                    </div>
                                                <?php endif; ?>

                                                <?php if(!empty($task['module_name'])): ?>
                                                    <div class="task-info-item">
                                                        Module : <?= $task['module_name'] ?>
                                                    </div>
                                                <?php endif; ?>
                                            </div>
                                            <div class="task-status-text">Status : <?= isset($task['task_status_label']) ? $task['task_status_label'] : 'Pending' ?></div>
                                            <div class="clearfix mt-2">
                                                <a href="<?= base_url('home/detail/'.$task['apps_id']) ?>" class="btn btn-task <?= $btnClass ?>"><?= isset($task['btn_label']) ? $task['btn_label'] : 'View Detail' ?></a>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <div class="alert alert-light text-center border">
                                    <i class="fas fa-check-circle text-success mb-2" style="font-size: 2rem;"></i><br>No pending tasks.
                                </div>
                            <?php endif; ?>
                        </div>

                        <div id="miniTaskView" class="short-task-list">
                            <?php if(!empty($my_tasks)): ?>
                                <ul>
                                <?php foreach($my_tasks as $task): ?>
                                    <?php 
                                        $miniBorderClass = 'mini-border-yellow'; 
                                        if(isset($task['task_color'])) {
                                            if($task['task_color'] == 'orange') $miniBorderClass = 'mini-border-orange';
                                            elseif($task['task_color'] == 'blue') $miniBorderClass = 'mini-border-blue';
                                        }
                                    ?>
                                    <li class="short-task-item <?= $miniBorderClass ?>" title="<?= $task['application_name'] ?>">
                                        <?= $task['short_name'] ? $task['short_name'] : $task['application_name'] ?>
                                    </li>
                                <?php endforeach; ?>
                                </ul>
                            <?php endif; ?>
                        </div>
                    </div>

                </div>
            </div>

            <div class="col-md-8 transition-column" id="colRight">
                <div class="card shadow-sm" style="border-radius: 8px; border-top: 3px solid #ffc107; height: 680px; display: flex; flex-direction: column;">
                    
                    <div class="card-header bg-white border-0 pb-0 pt-4 pl-4">
                        <h4 class="font-weight-bold">My Portofolio</h4>
                    </div>
                    
                    <div class="card-body" style="flex: 1; display: flex; flex-direction: column; overflow: hidden; padding-bottom: 0;">
                        <form id="mainFilterForm" action="<?= base_url('home') ?>" method="get" style="display: flex; flex-direction: column; height: 100%;">
                            
                            <div id="activeFiltersContainer">
                                <?php if(!empty($selected_filters) && is_array($selected_filters)): ?>
                                    <?php foreach($selected_filters as $key => $values): ?>
                                        <?php if(is_array($values)): foreach($values as $val): ?>
                                            <input type="hidden" name="filter[<?= $key ?>][]" value="<?= htmlspecialchars($val) ?>" class="filter-applied-<?= $key ?>">
                                        <?php endforeach; endif; ?>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </div>

                            <div class="mb-3 d-flex justify-content-between align-items-center">
                                <div>
                                    <?php if($rid == 2): ?>
                                        <a href="<?= base_url('home/detail/0') ?>" class="btn btn-add-custom btn-sm">
                                            <i class="fas fa-plus"></i> Create Portofolio
                                        </a>
                                    <?php endif; ?>
                                    <button type="button" class="btn btn-export-custom btn-sm ml-2"><i class="fas fa-file-export"></i> Export</button>
                                </div>
                                <div class="input-group input-group-sm" style="width: 200px;">
                                    <input type="text" name="keyword" class="form-control" placeholder="Search..." value="<?= isset($keyword) ? $keyword : '' ?>">
                                    <div class="input-group-append">
                                        <button class="btn btn-default" type="submit"><i class="fas fa-search"></i></button>
                                        <a href="<?= base_url('home') ?>" class="btn btn-secondary btn-sm">
                                            <i class="fas fa-sync-alt"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>

                            <div class="portfolio-scroll-container" style="flex: 1; overflow-y: auto; max-height: none !important;">
                                <table class="table table-striped table-bordered table-hover text-nowrap table-custom-fixed mb-0">
                                    <thead>
                                        <tr>
                                            <th class="text-center align-middle">Action</th>
                                            <?php 
                                                $selected_filters = isset($selected_filters) ? $selected_filters : [];
                                                
                                                function render_th($label, $key, $options, $selected, $rowspan=1) {
                                                    $isActive = isset($selected[$key]) && !empty($selected[$key]);
                                                    $iconClass = $isActive ? 'filter-active' : ''; 
                                                    if(!empty($options) && is_array($options)) {
                                                        $options = array_unique($options);
                                                        sort($options);
                                                    }
                                                    echo '<th class="text-center align-middle" rowspan="'.$rowspan.'" style="color: var(--text-dark);">';
                                                    echo '<div class="d-inline-flex align-items-center justify-content-center w-100">';
                                                        echo '<span>' . $label . '</span>';
                                                        echo '<div class="btn-group ml-2 filter-icon-wrapper '.$iconClass.'" style="position: static; transform: none; padding: 0;">';
                                                            echo '<i class="fas fa-filter fa-sm" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"></i>';
                                                            echo '<div class="dropdown-menu dropdown-menu-right custom-filter-dropdown" onclick="event.stopPropagation()">';
                                                                echo '<div class="filter-header"><input type="text" class="filter-search-input" placeholder="Find..." onkeyup="filterList(this)"></div>';
                                                                echo '<div class="filter-body">';
                                                                if(!empty($options)) {
                                                                    foreach($options as $opt) {
                                                                        if(trim($opt) === '') continue;
                                                                        $checked = ($isActive && in_array($opt, $selected[$key])) ? 'checked' : '';
                                                                        echo '<label class="filter-item"><input type="checkbox" value="'.htmlspecialchars($opt).'" '.$checked.' data-key="'.$key.'"> '.htmlspecialchars($opt).'</label>';
                                                                    }
                                                                } else { echo '<div class="p-2 text-muted text-center small">No Options</div>'; }
                                                                echo '</div>';
                                                                echo '<div class="filter-footer">';
                                                                echo '<button type="button" class="btn btn-xs btn-default" onclick="clearFilter(\''.$key.'\')">Clear</button>';
                                                                echo '<button type="button" class="btn btn-xs btn-primary" onclick="applyFilter(\''.$key.'\')">Apply</button>';
                                                                echo '</div>';
                                                            echo '</div>';
                                                        echo '</div>';
                                                    echo '</div>';
                                                    echo '</th>';
                                                }

                                                $opt_status = isset($opt_status) ? $opt_status : [];
                                                $opt_category = isset($opt_category) ? $opt_category : [];
                                                $opt_app_name = isset($opt_app_name) ? $opt_app_name : [];
                                                $opt_short_name = isset($opt_short_name) ? $opt_short_name : [];
                                                $opt_module = isset($opt_module) ? $opt_module : [];
                                                $opt_service_name = isset($opt_service_name) ? $opt_service_name : [];
                                                $opt_db_name = isset($opt_db_name) ? $opt_db_name : [];
                                                $opt_os_name = isset($opt_os_name) ? $opt_os_name : [];
                                                $opt_app_type = isset($opt_app_type) ? $opt_app_type : [];
                                                $opt_live_year = isset($opt_live_year) ? $opt_live_year : [];
                                                $opt_decom_year = isset($opt_decom_year) ? $opt_decom_year : [];
                                                $opt_resilience = isset($opt_resilience) ? $opt_resilience : [];
                                                $opt_server_type = isset($opt_server_type) ? $opt_server_type : [];
                                                $opt_readyness = isset($opt_readyness) ? $opt_readyness : [];
                                                $opt_network = isset($opt_network) ? $opt_network : [];
                                                $opt_deploy = isset($opt_deploy) ? $opt_deploy : [];
                                                $opt_op_hour = isset($opt_op_hour) ? $opt_op_hour : [];
                                                $opt_op_day = isset($opt_op_day) ? $opt_op_day : [];
                                                $opt_principle = isset($opt_principle) ? $opt_principle : [];
                                                $opt_principle_sol = isset($opt_principle_sol) ? $opt_principle_sol : [];
                                                $opt_it_group = isset($opt_it_group) ? $opt_it_group : [];
                                                $opt_it_div = isset($opt_it_div) ? $opt_it_div : [];
                                                $opt_directorate = isset($opt_directorate) ? $opt_directorate : [];
                                                $opt_sub_dir = isset($opt_sub_dir) ? $opt_sub_dir : [];
                                                $opt_owner_title = isset($opt_owner_title) ? $opt_owner_title : [];
                                                $opt_nik_head = isset($opt_nik_head) ? $opt_nik_head : [];
                                                $opt_nik_owner = isset($opt_nik_owner) ? $opt_nik_owner : [];
                                                $opt_nik_dept = isset($opt_nik_dept) ? $opt_nik_dept : [];
                                                $opt_yn = ['Y', 'N']; 
                                            ?>
                                            
                                            <?= render_th('Status', 'status', $opt_status, $selected_filters, 1) ?>
                                            <?= render_th('Category', 'category', $opt_category, $selected_filters, 1) ?>

                                            <?php if($is_infra): ?>
                                                <?= render_th('Application Name', 'app_name', $opt_app_name, $selected_filters) ?>
                                                <?= render_th('Module Name', 'module', $opt_module, $selected_filters) ?>
                                                <?= render_th('Service Name', 'service_name', $opt_service_name, $selected_filters) ?>
                                                <?= render_th('Database', 'db_name', $opt_db_name, $selected_filters) ?>
                                                <?= render_th('Operating Software', 'os_name', $opt_os_name, $selected_filters) ?>
                                                
                                                <?= render_th('Resilience', 'resilience', $opt_resilience, $selected_filters) ?>
                                                <?= render_th('Server Type', 'server_type', $opt_server_type, $selected_filters) ?>
                                                
                                                <th class="text-center align-middle">Prod Web</th>
                                                <th class="text-center align-middle">Prod Apps</th>
                                                <th class="text-center align-middle">Prod DB</th>
                                                <th class="text-center align-middle">SLA SVR PROD</th>
                                                
                                                <th class="text-center align-middle">DR Web</th>
                                                <th class="text-center align-middle">DR Apps</th>
                                                <th class="text-center align-middle">DR DB</th>
                                                <th class="text-center align-middle">SLA SVR DR</th>
                                                
                                                <th class="text-center align-middle">SLA SCCA Standard</th>
                                                <th class="text-center align-middle">SLA Actual</th>
                                                <?= render_th('Readyness', 'readyness', $opt_readyness, $selected_filters) ?>
                                                <th class="text-center align-middle">Suggestion</th>
                                                
                                            <?php elseif($is_bu): ?>
                                                <?= render_th('Application Name', 'app_name', $opt_app_name, $selected_filters) ?>
                                                <?= render_th('Operational Day', 'op_day', $opt_op_day, $selected_filters) ?>
                                                <?= render_th('Operational Hour', 'op_hour', $opt_op_hour, $selected_filters) ?>
                                                
                                            <?php else: ?>
                                                <?= render_th('Application Name', 'app_name', $opt_app_name, $selected_filters) ?>
                                                <?= render_th('Short Name', 'short_name', $opt_short_name, $selected_filters) ?>
                                                
                                                <?= render_th('Database', 'db_name', $opt_db_name, $selected_filters) ?>
                                                <?= render_th('Operating Software', 'os_name', $opt_os_name, $selected_filters) ?>
                                                <?= render_th('Application Type', 'app_type', $opt_app_type, $selected_filters) ?>
                                                <th class="text-center align-middle">Description</th> 
                                                <?= render_th('Live Year', 'live_year', $opt_live_year, $selected_filters) ?>
                                                <?= render_th('Decommission Year', 'decom_year', $opt_decom_year, $selected_filters) ?>
                                                <?= render_th('Resilience', 'resilience', $opt_resilience, $selected_filters) ?>
                                                <?= render_th('DR Availability', 'dr_avail', $opt_yn, $selected_filters) ?>
                                                <?= render_th('HA', 'ha', $opt_yn, $selected_filters) ?>
                                                <?= render_th('Flash Copy', 'flash_copy', $opt_yn, $selected_filters) ?>
                                                <?= render_th('End of Day', 'eod', $opt_yn, $selected_filters) ?>
                                                <?= render_th('Network', 'network', $opt_network, $selected_filters) ?>
                                                <?= render_th('Deployment', 'deployment', $opt_deploy, $selected_filters) ?>
                                                <?php if(!in_array($rid, [2, 3])): ?>
                                                    <?= render_th('Operational Hour', 'op_hour', $opt_op_hour, $selected_filters) ?>
                                                    <?= render_th('Operational Day', 'op_day', $opt_op_day, $selected_filters) ?>
                                                <?php endif; ?>
                                                <?= render_th('Principle', 'principle', $opt_principle, $selected_filters) ?>
                                                <?= render_th('Principle Solution', 'principle_sol', $opt_principle_sol, $selected_filters) ?>
                                                <?= render_th('IT Group', 'it_group', $opt_it_group, $selected_filters) ?>
                                                <?= render_th('IT Division', 'it_division', $opt_it_div, $selected_filters) ?>
                                                <?= render_th('Directorate', 'directorate', $opt_directorate, $selected_filters) ?>
                                                <?= render_th('Sub-Directorate', 'sub_directorate', $opt_sub_dir, $selected_filters) ?>
                                                <?= render_th('Owner Title', 'owner_title', $opt_owner_title, $selected_filters) ?>
                                            <?php endif; ?>
                                    </tr>
                                    </thead>
                                    <tbody>
                                        <?php if(!empty($my_portfolio)): foreach($my_portfolio as $row): ?>
                                        <tr>
                                            <td class="text-center align-middle">
                                                <div class="dropdown">
                                                    <button class="btn btn-sm btn-secondary dropdown-toggle btn-operation" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                        <i class="fas fa-cog mr-2"></i> Operations
                                                    </button>
                                                    <div class="dropdown-menu dropdown-menu-right shadow dropdown-operation-menu">
                                                        <?php 
                                                            $svcParam = (isset($row['service_id']) && $row['service_id'] > 0 && in_array($rid, [4, 5])) ? '/' . $row['service_id'] : '';
                                                        ?>
                                                        <button class="dropdown-item" type="button" onclick="window.location.href='<?= base_url('home/detail/' . $row['apps_id'] . $svcParam) ?>'">
                                                            <i class="fas fa-eye fa-fw text-primary mr-2"></i> View Detail
                                                        </button>
                                                        
                                                        <?php 
                                                            $required_roles = [2, 3, 4, 5, 6, 7, 8];
                                                            $total_roles    = count($required_roles); 
                                                            $approved_count = $this->db->where('apps_id', $row['apps_id'])
                                                                                        ->where_in('user_role_id', $required_roles)
                                                                                        ->where('status', 1)
                                                                                        ->count_all_results('tbl_apps_approval');
                                                        ?>
                                                        <?php if ($approved_count == $total_roles): ?>
                                                            <button class="dropdown-item" type="button" onclick="window.location.href='<?= base_url('home/renewal/' . $row['apps_id']) ?>'">
                                                                <i class="fas fa-sync-alt fa-fw text-success mr-2"></i> Renewal
                                                            </button>
                                                        <?php endif; ?>
                                                    </div>
                                                </div>
                                            </td>
                                            
                                            <td class="text-center align-middle">
                                                <?php 
                                                    $curr = isset($row['current_stage_role']) ? $row['current_stage_role'] : 0;
                                                    $roles_map = [1 => 'IT SLM', 2 => 'EA Apps Inputter', 3 => 'EA Apps Approver', 4 => 'EA Infra Inputter', 5 => 'EA Infra Approver', 6 => 'BU Inputter', 7 => 'BU Approver', 8 => 'IT Dev'];
                                                    
                                                    $status_label = isset($roles_map[$curr]) ? $roles_map[$curr] : 'Unknown';
                                                    
                                                    // Default Style (Gray)
                                                    $bg = '#f8f9fa'; $color = '#6c757d'; 

                                                    if ($curr == 0) {
                                                        $is_done = $this->db->where(['apps_id' => $row['apps_id'], 'user_role_id' => 8, 'status' => 1])->count_all_results('tbl_apps_approval');
                                                        if ($is_done > 0) {
                                                            $status_label = 'DONE';
                                                            $bg = 'rgba(46, 213, 115, 0.15)'; $color = '#2ed573';
                                                        }
                                                    } 
                                                    // 1. Kelompok INPUTTER (ID: 2, 4, 6) -> Tosca
                                                    elseif (in_array($curr, [2, 4, 6])) {
                                                        $bg = 'rgba(0, 210, 211, 0.15)'; $color = '#008a8a'; 
                                                    } 
                                                    // 2. Kelompok APPROVER (ID: 3, 5, 7) -> Purple
                                                    elseif (in_array($curr, [3, 5, 7])) {
                                                        $bg = 'rgba(162, 155, 254, 0.2)'; $color = '#6c5ce7'; 
                                                    } 
                                                    // 3. IT SLM (ID: 1) -> Gold/Yellow
                                                    elseif ($curr == 1) {
                                                        $bg = 'rgba(251, 197, 49, 0.15)'; $color = '#b88a00'; 
                                                    } 
                                                    // 4. IT DEV (ID: 8) -> Emerald Green
                                                    elseif ($curr == 8) {
                                                        $bg = 'rgba(29, 209, 161, 0.15)'; $color = '#10ac84'; 
                                                    }
                                                ?>
                                                <span class="badge px-3 py-2" style="background-color: <?= $bg ?>; color: <?= $color ?>; border-radius: 6px; font-size: 0.75rem; font-weight: 800; letter-spacing: 0.5px; display: inline-block; min-width: 120px;">
                                                    <?= strtoupper($status_label) ?>
                                                </span>
                                            </td>

                                            <td class="text-center align-middle">
                                                <?php 
                                                    $cat_val = isset($row['category_name']) ? $row['category_name'] : '';
                                                    $check_cat = strtolower($cat_val);
                                                    $cat_badge = 'badge-secondary'; 
                                                    if (strpos($check_cat, 'necessary') !== false) { $cat_badge = 'badge-necessary'; } 
                                                    elseif (strpos($check_cat, 'critical') !== false) { $cat_badge = 'badge-critical'; } 
                                                    elseif (strpos($check_cat, 'very important') !== false) { $cat_badge = 'badge-very-important'; } 
                                                    elseif (strpos($check_cat, 'important') !== false) { $cat_badge = 'badge-important'; }
                                                ?>
                                                <span class="badge <?= $cat_badge ?>" style="font-size: 0.85rem; padding: 6px 10px; width: 100%; display: inline-block;">
                                                    <?= $cat_val ?>
                                                </span>
                                            </td>

                                            <?php if($is_infra): ?>
                                                <td><?= isset($row['application_name']) ? $row['application_name'] : '-' ?></td>
                                                <td><?= isset($row['module_name']) ? $row['module_name'] : '-' ?></td>
                                                <td><?= isset($row['service_name']) ? $row['service_name'] : '-' ?></td>
                                                
                                                <td><?= isset($row['database_names']) ? str_replace(',', ',<br>', $row['database_names']) : '-' ?></td>
                                                <td><?= isset($row['os_names']) ? str_replace(',', ',<br>', $row['os_names']) : '-' ?></td>
                                                
                                                <td><?= isset($row['resilience_category']) && $row['resilience_category'] != '' ? $row['resilience_category'] : '' ?></td>

                                                <td><?= isset($row['server_type_name']) ? $row['server_type_name'] : '-' ?></td>

                                                <td class="text-center"><?= (int)$row['server_web_prod_count'] ?></td>
                                                <td class="text-center"><?= (int)$row['server_app_prod_count'] ?></td>
                                                <td class="text-center"><?= (int)$row['server_db_prod_count'] ?></td>
                                                <td class="text-center"><?= number_format(((float)$row['sla_svr_prod'])*100, 2) ?>%</td>
                                                
                                                <td class="text-center"><?= (int)$row['server_web_dr_count'] ?></td>
                                                <td class="text-center"><?= (int)$row['server_app_dr_count'] ?></td>
                                                <td class="text-center"><?= (int)$row['server_db_dr_count'] ?></td>
                                                <td class="text-center"><?= number_format(((float)$row['sla_svr_dr'])*100, 2) ?>%</td>
                                                
                                                <td class="text-center"><?= number_format((float)$row['sla_standard'], 2) ?>%</td>
                                                <td class="text-center font-weight-bold"><?= number_format(((float)$row['sla_actual'])*100, 2) ?>%</td>
                                                
                                                <td class="text-center font-weight-bold" style="color: <?= (strtolower($row['readyness']) == 'not comply') ? '#dc3545' : '#28a745' ?>;">
                                                    <?= $row['readyness'] ?>
                                                </td>
                                                <td class="text-center"><?= $row['suggestion'] ?></td>
                                            
                                            <?php elseif($is_bu): ?>
                                                <td><?= isset($row['application_name']) ? $row['application_name'] : '-' ?></td>
                                                <td><?= isset($row['operational_day']) ? $row['operational_day'] : '-' ?></td>
                                                <td><?= isset($row['operational_hour']) ? $row['operational_hour'] : '-' ?></td>
                                            
                                            <?php else: ?>
                                                <td><?= $row['application_name'] ?></td>
                                                <td><?= $row['short_name'] ?></td>
                                                
                                                <td><?= isset($row['database_names']) ? str_replace(',', ',<br>', $row['database_names']) : '' ?></td>
                                                <td><?= isset($row['os_names']) ? str_replace(',', ',<br>', $row['os_names']) : '-' ?></td>
                                                <td><?= $row['application_type'] ?></td>
                                                <td title="<?= $row['apps_description'] ?>"><?= substr($row['apps_description'],0,20) ?></td>
                                                <td class="text-center"><?= $row['live_year'] ?></td>
                                                <td class="text-center"><?= $row['decommission_year'] ?></td>
                                                <td class="text-center"><?= isset($row['resilience']) ? $row['resilience'] : '-' ?></td>
                                                <td class="text-center"><?= isset($row['dr_availability']) ? $row['dr_availability'] : '-' ?></td>
                                                <td class="text-center"><?= isset($row['ha']) ? $row['ha'] : '-' ?></td>
                                                <td class="text-center"><?= $row['flash_copy'] ?></td>
                                                <td class="text-center"><?= $row['end_of_day'] ?></td>
                                                <td><?= isset($row['network_name']) ? $row['network_name'] : '-' ?></td>
                                                <td><?= isset($row['deployment_info']) ? $row['deployment_info'] : '-' ?></td>
                                                <?php if(!in_array($rid, [2, 3])): ?>
                                                    <td><?= $row['operational_hour'] ?></td>
                                                    <td><?= $row['operational_day'] ?></td>
                                                <?php endif; ?>
                                                <td><?= $row['principle_name'] ?></td>
                                                <td><?= $row['principle_solution_name'] ?></td>
                                                <td><?= $row['it_group_name'] ?></td>
                                                <td><?= $row['it_division_name'] ?></td>
                                                <td><?= $row['owner_directorate'] ?></td>
                                                <td><?= $row['owner_subdirectorate'] ?></td>
                                                <td><?= $row['owner_title'] ?></td>
                                            <?php endif; ?>
                                        </tr>
                                        <?php endforeach; else: ?>
                                        <tr><td colspan="40" class="text-center py-4 text-muted">No Data Found</td></tr>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>
                        </form>
                    </div>

                    <div class="card-footer bg-white clearfix" style="border-top: 1px solid #dee2e6;">
                        <div class="float-right">
                            <?= isset($pagination) ? $pagination : '' ?>
                        </div>
                        <div class="float-left small text-muted" style="padding-top: 5px;">
                            Total Portofolio: <b><?= isset($total_rows) ? $total_rows : 0 ?></b>
                        </div>
                    </div>
                    
                </div>
            </div>

        </div>
      </div>
    </section>
  </div>
  <?php $this->load->view('layout/footer'); ?>
</div>
<?php $this->load->view('layout/foot_links'); ?>

<script>
    var isMinimized = false;

    function toggleTaskSize(btn) {
        var colLeft = document.getElementById('colLeft');
        var colRight = document.getElementById('colRight');
        var fullView = document.getElementById('fullTaskView');
        var miniView = document.getElementById('miniTaskView');
        var title = document.getElementById('titleTask');
        
        var count = title.getAttribute('data-count');

        if(!isMinimized) {
            colLeft.className = "col-md-2";  
            colRight.className = "col-md-10"; 
            
            fullView.style.display = 'none'; 
            miniView.style.display = 'block'; 
            
            // Cukup ubah teks saja, lingkaran biasanya disembunyikan saat minimize agar rapi
            title.innerHTML = 'My Tasks';        

            btn.innerHTML = '<i class="fas fa-chevron-right"></i>';
            
            isMinimized = true;
        } else {
            colLeft.className = "col-md-4";
            colRight.className = "col-md-8";

            miniView.style.display = 'none';
            fullView.style.display = 'block'; 
            
            // PERBAIKAN DI SINI: Kembalikan struktur HTML lingkaran (Badge Circle)
            title.innerHTML = 'My Tasks <span class="task-badge-circle ml-2">' + count + '</span>';

            btn.innerHTML = '<i class="fas fa-chevron-left"></i>';
            
            isMinimized = false;
        }
    }

    function filterList(input) {
        var filter = input.value.toUpperCase();
        var div = input.parentNode.nextElementSibling;
        var labels = div.getElementsByTagName("label");
        for (var i = 0; i < labels.length; i++) {
            var txtValue = labels[i].textContent || labels[i].innerText;
            if (txtValue.toUpperCase().indexOf(filter) > -1) { labels[i].style.display = "block"; } 
            else { labels[i].style.display = "none"; }
        }
    }

    function applyFilter(key) {
        $('.filter-applied-' + key).remove();
        var checkboxes = document.querySelectorAll('input[type="checkbox"][data-key="' + key + '"]:checked');
        var container = document.getElementById('activeFiltersContainer');
        checkboxes.forEach(function(cb) {
            var input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'filter[' + key + '][]';
            input.value = cb.value;
            input.className = 'filter-applied-' + key;
            container.appendChild(input);
        });
        document.getElementById('mainFilterForm').submit();
    }

    function clearFilter(key) {
        var checkboxes = document.querySelectorAll('input[type="checkbox"][data-key="' + key + '"]');
        checkboxes.forEach(cb => cb.checked = false);
        $('.filter-applied-' + key).remove();
        document.getElementById('mainFilterForm').submit();
    }
</script>
</body>
</html>