<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <title>SLIM | <?= $title ?></title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  
  <?php $this->load->view('layout/head_links'); ?>

</head>

<?php 
    $rid = (int)$this->session->userdata('role_id'); 
?>

<body class="hold-transition sidebar-mini layout-fixed sidebar-collapse">

<script>
    if (localStorage.getItem('theme') === 'dark') { document.body.classList.add('dark-mode'); }
</script>

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
								<div class="alert alert-light text-center border py-4 no-task-card">
									<img src="<?= base_url('assets/img/No_Pending_Tasks.svg') ?>" alt="No Pending Tasks" class="mb-3" style="width: 50px;"><br>
									<span class="text-muted font-weight-bold">No pending tasks.</span>
								</div>
                            <?php endif; ?>
                        </div>

                        <div id="miniTaskView" class="short-task-list">
                            <?php if(!empty($my_tasks)): ?>
                                <ul>
                                <?php foreach($my_tasks as $task): ?>
                                    <?php 
                                        $colorBorder = 'border-left: 4px solid #ffc107;'; 
                                        if(isset($task['task_color'])) {
                                            if($task['task_color'] == 'orange') { $colorBorder = 'border-left: 4px solid #fd7e14;'; } 
                                            elseif($task['task_color'] == 'blue') { $colorBorder = 'border-left: 4px solid #007bff;'; }
                                        }
                                    ?>
                                    <li class="short-task-item mb-2" style="<?= $colorBorder ?> padding-left: 10px; list-style: none; background: #fff; border-radius: 4px; box-shadow: 0 1px 3px rgba(0,0,0,0.1);" title="<?= $task['application_name'] ?>">
                                        <div style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis; font-weight: bold;">
                                            <?= $task['short_name'] ? $task['short_name'] : $task['application_name'] ?>
                                        </div>
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
                                    <button type="button" class="btn btn-export-custom btn-sm ml-2" onclick="confirmExport()">
                                        <i class="fas fa-file-export"></i> Export
                                    </button>
                                </div>
                                <div class="input-group input-group-sm" style="width: 300px;">
                                    <input type="text" name="keyword" class="form-control" placeholder="Search..." value="<?= isset($keyword) ? $keyword : '' ?>">
                                    <div class="input-group-append">
                                        <button class="btn btn-default" type="submit"><i class="fas fa-search"></i></button>
                                        <a href="<?= base_url('home') ?>" class="btn btn-secondary btn-sm">
                                            <i class="fas fa-sync-alt"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>


                            <div class="top-scrollbar-wrapper" style="width: 100%; overflow-x: auto; overflow-y: hidden; height: 16px;">
                                <div class="top-scrollbar-content" style="height: 16px;"></div>
                            </div>

                            <div class="main-table-wrapper" style="flex: 1; overflow-y: auto; overflow-x: auto; max-height: none !important;" id="scrollableTable">
                                <table class="table table-striped table-bordered table-hover text-nowrap">

                                    <thead>
                                        <tr class="bg-info">
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
															echo '<i class="fas fa-filter fa-sm" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="cursor: pointer;"></i>';
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
																echo '<button type="button" class="btn btn-xs btn-primary btn-theme-gradient" onclick="applyFilter(\''.$key.'\')">Apply</button>';
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
												$opt_db_name = isset($opt_db_name) ? $opt_db_name : [];
												$opt_os_name = isset($opt_os_name) ? $opt_os_name : [];
												$opt_app_type = isset($opt_app_type) ? $opt_app_type : [];
												$opt_server_name = isset($opt_server_name) ? $opt_server_name : [];
                                                $opt_standard_cat = isset($opt_standard_cat) ? $opt_standard_cat : [];
												$opt_live_year = isset($opt_live_year) ? $opt_live_year : [];
												$opt_decom_year = isset($opt_decom_year) ? $opt_decom_year : [];
												$opt_resilience = isset($opt_resilience) ? $opt_resilience : [];
												$opt_network = isset($opt_network) ? $opt_network : [];
												
												// Pemisahan Opt Deployment
												$opt_deploy_model = isset($opt_deploy_model) ? $opt_deploy_model : [];
												$opt_deploy_provider = isset($opt_deploy_provider) ? $opt_deploy_provider : [];
												$opt_deploy_site = isset($opt_deploy_site) ? $opt_deploy_site : [];
												
												$opt_op_hour = isset($opt_op_hour) ? $opt_op_hour : [];
												$opt_op_day = isset($opt_op_day) ? $opt_op_day : [];
												$opt_solution_vendor = isset($opt_solution_vendor) ? $opt_solution_vendor : [];
												$opt_services_vendor = isset($opt_services_vendor) ? $opt_services_vendor : [];
												
												$opt_lob_directorate = isset($opt_lob_directorate) ? $opt_lob_directorate : [];
												$opt_lob_subdirectorate = isset($opt_lob_subdirectorate) ? $opt_lob_subdirectorate : [];
												$opt_lob_group = isset($opt_lob_group) ? $opt_lob_group : [];
												$opt_lob_group_head = isset($opt_lob_group_head) ? $opt_lob_group_head : [];
												
												$opt_it_subdirectorate = isset($opt_it_subdirectorate) ? $opt_it_subdirectorate : [];
												$opt_it_department_head = isset($opt_it_department_head) ? $opt_it_department_head : [];
												$opt_it_support_group = isset($opt_it_support_group) ? $opt_it_support_group : [];
												$opt_it_group_head = isset($opt_it_group_head) ? $opt_it_group_head : [];
												$opt_it_support_divison = isset($opt_it_support_divison) ? $opt_it_support_divison : [];
												$opt_it_division_head = isset($opt_it_division_head) ? $opt_it_division_head : [];

												$opt_app_version = isset($opt_app_version) ? $opt_app_version : [];
												$opt_dev_lang = isset($opt_dev_lang) ? $opt_dev_lang : [];
												$opt_app_dev = isset($opt_app_dev) ? $opt_app_dev : [];
												$opt_web_server = isset($opt_web_server) ? $opt_web_server : [];
												$opt_app_server = isset($opt_app_server) ? $opt_app_server : [];
												$opt_sup_others = isset($opt_sup_others) ? $opt_sup_others : [];
												$opt_src_code = isset($opt_src_code) ? $opt_src_code : [];
												$opt_url = isset($opt_url) ? $opt_url : [];

												$opt_yn = ['Yes', 'No']; 
												
												// --- TAMBAHAN BARU: Variabel untuk Filter Status Active/Not Active ---
												$opt_app_status = ['Active', 'Not Active']; 
											?>
											
											<?= render_th('Status', 'app_status', $opt_app_status, $selected_filters, 1) ?>
											
											<?= render_th('Workflow Status', 'status', $opt_status, $selected_filters, 1) ?>
											<?= render_th('Category', 'category', $opt_category, $selected_filters, 1) ?>
											
											<?= render_th('Application Name', 'app_name', $opt_app_name, $selected_filters) ?>
											<?= render_th('Short Name', 'short_name', $opt_short_name, $selected_filters) ?>
											<?= render_th('Module', 'module', $opt_module, $selected_filters) ?>
											<?= render_th('Database', 'db_name', $opt_db_name, $selected_filters) ?>
											<?= render_th('Operating Software', 'os_name', $opt_os_name, $selected_filters) ?>
											<?= render_th('Application Type', 'app_type', $opt_app_type, $selected_filters) ?>
											<?= render_th('Server Type', 'server_name', $opt_server_name, $selected_filters) ?>
                                            <?= render_th('Standard Category', 'standard_category', $opt_standard_cat, $selected_filters) ?>
											<th class="text-center align-middle">Description</th> 
											
											<?= render_th('Live Year', 'live_year', $opt_live_year, $selected_filters) ?>
											<?= render_th('Decommission Year', 'decom_year', $opt_decom_year, $selected_filters) ?>
											<?= render_th('Resilience', 'resilience', $opt_resilience, $selected_filters) ?>
											<?= render_th('DR Availability', 'dr_avail', $opt_yn, $selected_filters) ?>
											<?= render_th('HA', 'ha', $opt_yn, $selected_filters) ?>
											
											<?= render_th('Network', 'network', $opt_network, $selected_filters) ?>
											
											<?= render_th('Deployment Model', 'deployment_model', $opt_deploy_model, $selected_filters) ?>
											<?= render_th('Deployment Provider', 'deployment_provider', $opt_deploy_provider, $selected_filters) ?>
											<?= render_th('Deployment Site', 'deployment_site', $opt_deploy_site, $selected_filters) ?>
											
											<?= render_th('Operational Hour', 'op_hour', $opt_op_hour, $selected_filters) ?>
											<?= render_th('Operational Day', 'op_day', $opt_op_day, $selected_filters) ?>
											
											<?= render_th('Solution Vendor', 'solution_vendor', $opt_solution_vendor, $selected_filters) ?>
											<?= render_th('Services Vendor', 'services_vendor', $opt_services_vendor, $selected_filters) ?>
											
											<?= render_th('LOB Directorate', 'lob_directorate', $opt_lob_directorate, $selected_filters) ?>
											<?= render_th('LOB Sub-Directorate', 'lob_subdirectorate', $opt_lob_subdirectorate, $selected_filters) ?>
											<?= render_th('LOB Group', 'lob_group', $opt_lob_group, $selected_filters) ?>
											<?= render_th('LOB Group Head', 'lob_group_head', $opt_lob_group_head, $selected_filters) ?>
											
											<?= render_th('IT Sub-Directorate', 'it_subdirectorate', $opt_it_subdirectorate, $selected_filters) ?>
											<?= render_th('IT Dept Head', 'it_department_head', $opt_it_department_head, $selected_filters) ?>
											<?= render_th('IT Support Group', 'it_support_group', $opt_it_support_group, $selected_filters) ?>
											<?= render_th('IT Group Head', 'it_group_head', $opt_it_group_head, $selected_filters) ?>
											<?= render_th('IT Support Division', 'it_support_divison', $opt_it_support_divison, $selected_filters) ?>
											<?= render_th('IT Division Head', 'it_division_head', $opt_it_division_head, $selected_filters) ?>

											<?= render_th('App Version', 'app_version', $opt_app_version, $selected_filters) ?>
											<?= render_th('Dev Language', 'dev_language', $opt_dev_lang, $selected_filters) ?>
											<?= render_th('App Developer', 'app_developer', $opt_app_dev, $selected_filters) ?>
											<?= render_th('Supporting Web Server', 'web_server', $opt_web_server, $selected_filters) ?>
											<?= render_th('Supporting App Server', 'app_server', $opt_app_server, $selected_filters) ?>
											<?= render_th('Supporting Others', 'sup_others', $opt_sup_others, $selected_filters) ?>
											<?= render_th('Source Code Owned', 'src_code', $opt_src_code, $selected_filters) ?>
											<?= render_th('URL', 'url', $opt_url, $selected_filters) ?>
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
														
														<?php if(isset($row['status']) && $row['status'] == 0): ?>
															<button class="dropdown-item" type="button" onclick="window.location.href='<?= base_url('home/detail/' . $row['apps_id']) ?>'">
																<i class="fas fa-eye fa-fw text-primary mr-2"></i> View Detail
															</button>
															
															<?php if(!empty($row['attached_document'])): ?>
																<button class="dropdown-item" type="button" onclick="window.open('<?= base_url('uploads/documents/'.$row['attached_document']) ?>', '_blank')">
																	<i class="fas fa-file-pdf fa-fw text-danger mr-2"></i> View Memo
																</button>
															<?php else: ?>
																<button class="dropdown-item text-muted" type="button" disabled style="cursor: not-allowed; background-color: #f8f9fa;">
																	<i class="fas fa-file-pdf fa-fw text-secondary mr-2"></i> No Memo Attached
																</button>
															<?php endif; ?>

														<?php else: ?>
															<button class="dropdown-item" type="button" onclick="window.location.href='<?= base_url('home/detail/' . $row['apps_id']) ?>'">
																<i class="fas fa-eye fa-fw text-primary mr-2"></i> View Detail
															</button>

															<?php 
																$required_roles = [1, 2, 3];
																$total_roles    = count($required_roles); 
																$approved_count = $this->db->where('apps_id', $row['apps_id'])
																						   ->where_in('user_role_id', $required_roles)
																						   ->where('status', 1)
																						   ->count_all_results('tbl_apps_approval');
															?>
															<?php if ($approved_count == $total_roles && $rid == 2): ?>
																<button class="dropdown-item" href="#" onclick="confirmRenewal(<?= $row['apps_id'] ?>); return false;">
																	<i class="fas fa-sync fa-fw text-success mr-2"></i> Renewal
																</button>
															<?php endif; ?>
															
															<?php if($rid == 2 && $row['status_name'] == 'DONE'): ?>
																<button class="dropdown-item" href="javascript:void(0)" onclick="toggleAppStatus(<?= $row['apps_id'] ?>, 0, 'Deactivate')">
																	<i class="fas fa-power-off fa-fw text-danger mr-2"></i> Deactivate
																</button>
															<?php endif; ?>

														<?php endif; ?>
													</div>
												</div>
											</td>
											
											<td class="text-center align-middle">
												<?php 
													// Menggunakan variabel $row['status'] sesuai dengan looping data di home view
													if (isset($row['status']) && $row['status'] == 1) { 
														// Hijau pudar (Soft Pastel Green)
														$status_bg = '#e8f5e9';    
														$status_color = '#2e7d32'; 
														$status_label = 'Active';
													} else { 
														// Merah pudar (Soft Pastel Red)
														$status_bg = '#ffebee';    
														$status_color = '#c62828'; 
														$status_label = 'Non Active';
													}
												?>
												<span class="badge px-3 py-2"
													style="background-color: <?= $status_bg ?>; color: <?= $status_color ?>; border-radius: 6px; font-size: 0.75rem; font-weight: 700; min-width: 85px; display: inline-block; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
													<?= $status_label ?>
												</span>
											</td>
                                            
                                            <td class="text-center align-middle">
                                                <?php 
                                                    $curr = isset($row['current_stage_role']) ? $row['current_stage_role'] : 0;
                                                    $roles_map = [1 => 'IT SLM', 2 => 'EA', 3 => 'IT Dev'];
                                                    
                                                    $status_label = isset($roles_map[$curr]) ? $roles_map[$curr] : 'Unknown';
                                                    $bg = '#f8f9fa'; $color = '#6c757d'; 

                                                    if ($curr == 0) {
                                                        $is_done = $this->db->where(['apps_id' => $row['apps_id'], 'user_role_id' => 1, 'status' => 1])->count_all_results('tbl_apps_approval');
                                                        if ($is_done > 0) {
                                                            $status_label = 'DONE';
                                                            $bg = 'rgba(46, 213, 115, 0.2)'; $color = '#218c74';
                                                        }
                                                    } 
                                                    elseif ($curr == 1) { $bg = 'rgba(0, 210, 211, 0.15)'; $color = '#008a8a'; } // IT SLM
                                                    elseif ($curr == 2) { $bg = 'rgba(162, 155, 254, 0.2)'; $color = '#6c5ce7'; } // EA
                                                    elseif ($curr == 3) { $bg = 'rgba(232, 67, 147, 0.15)'; $color = '#d63031'; } // IT Dev
                                                ?>
                                                <span class="badge px-3 py-2" style="background-color: <?= $bg ?>; color: <?= $color ?>; border-radius: 6px; font-size: 0.75rem; font-weight: 800; letter-spacing: 0.5px; display: inline-block; min-width: 120px;">
                                                    <?= strtoupper($status_label) ?>
                                                </span>
                                            </td>

                                            <td class="text-center align-middle">
                                                <?php 
                                                    $cat_val = isset($row['category_name']) ? $row['category_name'] : '';
                                                    $check_cat = strtolower(trim($cat_val));
                                                    
                                                    // Default (Others)
                                                    $bg = 'rgba(165, 177, 194, 0.2)'; $color = '#4b6584'; 

                                                    if (strpos($check_cat, 'critical') !== false) { 
                                                        // 1. Critical -> Merah
                                                        $bg = 'rgba(235, 59, 90, 0.4)'; $color = '#eb3b5a'; 
                                                    } elseif (strpos($check_cat, 'very important') !== false) { 
                                                        // 2. Very Important -> Oranye
                                                        $bg = 'rgba(255, 159, 67, 0.4)'; $color = '#e67e22'; 
                                                    } elseif (strpos($check_cat, 'important') !== false) { 
                                                        // 3. Important -> Kuning
                                                        $bg = 'rgba(251, 197, 49, 0.4)'; $color = '#b88a00';
                                                    } elseif (strpos($check_cat, 'necessary') !== false) { 
                                                        // 4. Necessary -> Hijau
                                                        $bg = 'rgba(85, 239, 196, 0.4)'; $color = '#00b894'; 
                                                    } elseif (strpos($check_cat, 'others') !== false) { 
                                                        // 5. Others -> Abu-abu
                                                        $bg = 'rgba(165, 177, 194, 0.4)'; $color = '#4b6584'; 
                                                    }
                                                ?>
                                                <span class="badge px-3 py-2" style="background-color: <?= $bg ?>; color: <?= $color ?>; border-radius: 6px; font-size: 0.75rem; font-weight: 800; letter-spacing: 0.5px; display: inline-block; min-width: 120px;">
                                                    <?= strtoupper($cat_val) ?>
                                                </span>
                                            </td>

                                            <td style="min-width: 250px; max-width: 400px; white-space: normal; word-wrap: break-word;"><?= $row['application_name'] ?></td>
                                            <td style="min-width: 100px; max-width: 150px; white-space: normal; word-wrap: break-word;"><?= $row['short_name'] ?></td>
                                            <td style="min-width: 250px; max-width: 400px; white-space: normal; word-wrap: break-word;"><?= $row['module'] ?></td>
                                            <td style="min-width: 220px; max-width: 400px; white-space: normal; word-wrap: break-word;"><?= isset($row['database_names']) ? str_replace(',', ', ', $row['database_names']) : '-' ?></td>
                                            <td style="min-width: 220px; max-width: 400px; white-space: normal; word-wrap: break-word;"><?= isset($row['os_names']) ? str_replace(',', ',<br>', $row['os_names']) : '-' ?></td>
                                            <td style="min-width: 200px; max-width: 400px; white-space: normal; word-wrap: break-word;"><?= isset($row['application_type_name']) ? $row['application_type_name'] : '-' ?></td>
                                            <td style="min-width: 200px; max-width: 400px; white-space: normal; word-wrap: break-word;"><?= isset($row['server_name']) ? str_replace(',', ', ', $row['server_name']) : '-' ?></td>
                                            <td class="text-center"><?= !empty($row['standard_category']) ? $row['standard_category'] . '%' : '' ?></td>
                                            <td style="min-width: 400px; max-width: 400px; white-space: normal; word-wrap: break-word;" title="<?= $row['apps_description'] ?>"><?= substr($row['apps_description'],0,20) ?></td>
                                            <td class="text-center"><?= $row['live_year'] ?></td>
                                            <td class="text-center"><?= $row['decommission_year'] ?></td>
                                            <td class="text-center"><?= isset($row['resilience']) ? $row['resilience'] : '-' ?></td>
                                            <td class="text-center"><?= isset($row['dr_availability']) ? $row['dr_availability'] : '-' ?></td>
                                            <td class="text-center"><?= isset($row['ha']) ? $row['ha'] : '-' ?></td>
                                                                                        
                                            <td><?= isset($row['network_name']) ? $row['network_name'] : '-' ?></td>
                                                                                        
                                            <td><?= isset($row['deployment_model']) ? $row['deployment_model'] : '-' ?></td>
                                            <td><?= isset($row['provider_name']) ? $row['provider_name'] : '-' ?></td>
                                            <td><?= isset($row['site_name']) ? $row['site_name'] : '-' ?></td>
                                                                                        
                                            <td><?= isset($row['operational_hour']) ? $row['operational_hour'] : '-' ?></td>
                                            <td><?= isset($row['operational_day']) ? $row['operational_day'] : '-' ?></td>
                                                                                        
                                            <td><?= isset($row['solution_vendor']) ? $row['solution_vendor'] : '-' ?></td>
                                            <td><?= isset($row['services_vendor']) ? $row['services_vendor'] : '-' ?></td>
                                                                                        
                                            <td><?= $row['lob_directorate'] ?></td>
                                            <td><?= $row['lob_subdirectorate'] ?></td>
                                            <td><?= $row['lob_group'] ?></td>
                                            <td><?= $row['lob_group_head'] ?></td>
                                            <td><?= $row['it_subdirectorate'] ?></td>
                                            <td><?= $row['it_department_head'] ?></td>
                                            <td><?= $row['it_support_group'] ?></td>
                                            <td><?= $row['it_group_head'] ?></td>
                                            <td><?= $row['it_support_divison'] ?></td>
                                            <td><?= $row['it_division_head'] ?></td>
                                                                                        
                                            <td><?= $row['application_version'] ?></td>
                                            <td><?= $row['development_language'] ?></td>
                                            <td><?= $row['application_developer'] ?></td>
                                            <td><?= $row['supporting_web_server'] ?></td>
                                            <td><?= $row['supporting_application_server'] ?></td>
                                            <td><?= $row['supporting_others'] ?></td>
                                            <td class="text-center"><?= $row['source_code_owned'] ?></td>
                                            <td style="min-width: 300px; white-space: normal; word-wrap: break-word;">
                                                <?php if (!empty($row['Url'])): ?>
                                                    <?php
                                                        // Memastikan link memiliki http:// atau https://
                                                        $link_url = $row['Url'];
                                                        if (!preg_match("~^(?:f|ht)tps?://~i", $link_url)) {
                                                            $link_url = "http://" . $link_url;
                                                        }
                                                    ?>
                                                    <a href="<?= $link_url; ?>" target="_blank" rel="noopener noreferrer" class="url-link">
                                                        <?= html_escape($row['Url']); ?>
                                                    </a>
                                                <?php else: ?>
                                                    <span class="text-muted">-</span>
                                                <?php endif; ?>
                                            </td>
                                        </tr>
                                        <?php endforeach; else: ?>
                                        <tr><td colspan="50" class="text-center py-4 text-muted">No Data Found</td></tr>
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

    $(document).ready(function() {
        const navEntries = performance.getEntriesByType("navigation");
        if (navEntries.length > 0 && navEntries[0].type === "reload") {
            sessionStorage.removeItem('portfolioTableScrollLeft');
        }
    $('#btnResetFilter').on('click', function() {
          // Hapus memori scroll saat tombol reset diklik!
          sessionStorage.removeItem('portfolioTableScrollLeft');
      });

        const $topScrollWrapper = $('.top-scrollbar-wrapper');
        const $mainTableWrapper = $('.main-table-wrapper'); 
        const $topScrollContent = $('.top-scrollbar-content');

        function syncScrollWidths() {
            if ($mainTableWrapper.length > 0) {
                let totalWidth = $mainTableWrapper[0].scrollWidth;
                $topScrollContent.css('width', totalWidth + 'px');

                const savedScrollPosition = sessionStorage.getItem('portfolioTableScrollLeft');
                if (savedScrollPosition !== null) {
                    const scrollPos = parseInt(savedScrollPosition, 10);
                    $mainTableWrapper.scrollLeft(scrollPos); 
                    $topScrollWrapper.scrollLeft(scrollPos); 
                }
            }
        }

        setTimeout(syncScrollWidths, 300);

        $(window).resize(function() {
            syncScrollWidths();
        });

        $topScrollWrapper.on('scroll', function() {
            const currentScroll = $(this).scrollLeft();
            $mainTableWrapper.scrollLeft(currentScroll);
            sessionStorage.setItem('portfolioTableScrollLeft', currentScroll); 
        });

        $mainTableWrapper.on('scroll', function() {
            const currentScroll = $(this).scrollLeft();
            $topScrollWrapper.scrollLeft(currentScroll);
            sessionStorage.setItem('portfolioTableScrollLeft', currentScroll); 
        });
    });
    
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
            
            title.innerHTML = 'My Tasks <span class="task-badge-circle ml-2">' + count + '</span>';        

            btn.innerHTML = '<i class="fas fa-chevron-right"></i>';
            
            isMinimized = true;
        } else {
            colLeft.className = "col-md-4";
            colRight.className = "col-md-8";

            miniView.style.display = 'none';
            fullView.style.display = 'block'; 
            
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

    function confirmExport() {
        Swal.fire({
            title: 'Export to Excel?',
            text: "Data tabel ini akan otomatis diunduh.",
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Yes, export!',
            cancelButtonText: 'Cancel',
            buttonsStyling: false,
            customClass: {
                confirmButton: 'btn btn-save-custom px-4 mx-2', 
                cancelButton: 'btn btn-secondary px-4 mx-2'
            }
        }).then((result) => {
            if (result.isConfirmed) {
                const Toast = Swal.mixin({
                    toast: true, position: 'top-end', showConfirmButton: false, timer: 3000, timerProgressBar: true
                });
                
                Toast.fire({ icon: 'success', title: 'Downloading file...' });
                
                var input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'export';
                input.value = '1';
                document.getElementById('mainFilterForm').appendChild(input);
                
                document.getElementById('mainFilterForm').submit();

                setTimeout(() => { input.remove(); }, 100);
            }
        });
    }

    const toggleBtn = document.getElementById('darkModeBtn');
    const body = document.body;
    const icon = toggleBtn ? toggleBtn.querySelector('i') : null;

    function updateIcon(isDark) {
        if (!icon) return;
        if (isDark) {
            icon.classList.remove('fa-moon');
            icon.classList.add('fa-sun'); 
        } else {
            icon.classList.remove('fa-sun');
            icon.classList.add('fa-moon'); 
        }
    }

    document.addEventListener("DOMContentLoaded", function() {
        if (localStorage.getItem('theme') === 'dark') {
            updateIcon(true);
        }

        if(toggleBtn) {
            toggleBtn.addEventListener('click', function(e) {
                e.preventDefault();
                body.classList.toggle('dark-mode');
                const isDark = body.classList.contains('dark-mode');
                
                if (isDark) {
                    localStorage.setItem('theme', 'dark');
                    updateIcon(true);
                } else {
                    localStorage.setItem('theme', 'light');
                    updateIcon(false);
                }

                if (typeof updateChartTheme === 'function') {
                    updateChartTheme(isDark);
                }
            });
        }

        const logoutBtn = document.getElementById('logoutLink');
        const overlay = document.getElementById('loadingOverlay');

        if(logoutBtn) {
            logoutBtn.addEventListener('click', function(e) {
                e.preventDefault(); 
                const urlLogout = this.getAttribute('href');

                Swal.fire({
                    title: 'Berhasil Logout!',
                    text: 'Anda akan keluar dari sistem',
                    icon: 'success',
                    showConfirmButton: true,
                    confirmButtonText: 'OK',
                    customClass: {
                        confirmButton: 'btn-theme-gradient' 
                    }
                }).then((result) => {
                    if (result.isConfirmed || result.isDismissed) {
                        if(overlay) overlay.style.display = 'flex';
                        window.location.href = urlLogout;
                    }
                });
            });
        }
        
        <?php if($this->session->flashdata('success')): ?>
            Swal.fire({
                icon: 'success',
                title: 'Success',
                text: '<?= $this->session->flashdata('success') ?>',
                confirmButtonText: 'OK',
                buttonsStyling: false,
                customClass: { confirmButton: 'btn btn-primary px-4' }
            });
            // PERBAIKAN: Gunakan fungsi unset CodeIgniter
            <?php $this->session->unset_userdata('success'); ?>
        <?php endif; ?>

        <?php if($this->session->flashdata('error')): ?>
            Swal.fire({
                icon: 'error',
                title: 'Gagal!',
                html: '<?= $this->session->flashdata('error') ?>',
                confirmButtonText: 'OK',
                buttonsStyling: false,
                customClass: { confirmButton: 'btn btn-danger px-4' } 
            });
            <?php $this->session->unset_userdata('error'); ?>
        <?php endif; ?>
    });
	
	window.toggleAppStatus = function(apps_id, status, actionName) {
		// 1. Mencegah halaman reload tiba-tiba saat tombol diklik
		if (window.event) {
			window.event.preventDefault();
		}

		let colorBtn = (status === 1) ? '#28a745' : '#dc3545';
		let iconSwal = (status === 1) ? 'question' : 'warning';
		
		// Sesuai perubahanmu: Wajib diubah menjadi tanda bintang merah
		let fileLabel = (status === 0) ? 
			"Attach Document Memo (PDF Only) <span class='text-danger'>*</span>" : 
			"Attach Document (PDF Only) - <i>Opsional</i>";
		
		Swal.fire({
			title: actionName + ' Aplikasi?',
			html: "<p style='margin-bottom: 15px;'>Status aplikasi ini akan diubah menjadi <b>" + (status === 1 ? "Active" : "Not Active") + "</b>.</p>" +
				  "<div class='text-left px-2'>" +
				  "<label class='font-weight-normal' style='font-size: 14px;'>" + fileLabel + "</label>" +
				  "<input type='file' id='swal-file' name='attached_document' class='form-control' accept='application/pdf' style='padding: 3px;'>" +
				  "</div>",
			icon: iconSwal,
			showCancelButton: true,
			confirmButtonColor: colorBtn,
			cancelButtonColor: '#6c757d',
			confirmButtonText: 'Ya, ' + actionName + '!',
			cancelButtonText: 'Batal',
			reverseButtons: true,
			preConfirm: () => {
				let fileInput = document.getElementById('swal-file');
				
				// Jika Deactivate (status === 0) WAJIB ada file
				if (status === 0 && fileInput.files.length === 0) {
					Swal.showValidationMessage('Dokumen Memo wajib diunggah untuk menonaktifkan aplikasi!');
					return false;
				}

				// Validasi ekstensi PDF
				if (fileInput.files.length > 0) {
					let fileName = fileInput.files[0].name;
					let ext = fileName.split('.').pop().toLowerCase();
					if (ext !== 'pdf') {
						Swal.showValidationMessage('Format file tidak didukung! Harus berformat PDF.');
						return false;
					}
				}
				
				// 2. SELAMATKAN FILE INPUT: Sembunyikan dan pindahkan ke body 
				// SEBELUM modal SweetAlert dihancurkan
				fileInput.style.display = 'none';
				document.body.appendChild(fileInput);
				
				return fileInput; 
			}
		}).then((result) => {
			if (result.isConfirmed) {
				$('#loadingOverlay').css('display', 'flex'); 
				
				let form = document.createElement('form');
				form.method = 'POST';
				// Pastikan kode ini berada di file PHP (View), bukan di file .js terpisah
				// agar tag <?= base_url() ?> bisa dieksekusi oleh server
				form.action = '<?= base_url("home/toggle_status/") ?>' + apps_id + '/' + status;
				form.enctype = 'multipart/form-data'; 
				
				// Ambil input file yang sudah kita selamatkan tadi, lalu masukkan ke form
				let safeFileInput = result.value;
				form.appendChild(safeFileInput);
				
				document.body.appendChild(form);
				form.submit();
			} else {
				// Jika user klik Batal, bersihkan sisa input file yang tertinggal di body
				let orphanedInput = document.getElementById('swal-file');
				if (orphanedInput) {
					orphanedInput.remove();
				}
			}
		});
	}
	
	function confirmRenewal(appsId) {
		Swal.fire({
			title: 'Mulai Proses Renewal?',
			text: "Pastikan dokumen Renewal sudah lengkap.",
			icon: 'warning',
			showCancelButton: true,
			confirmButtonColor: '#3085d6',
			cancelButtonColor: '#d33',
			confirmButtonText: 'Ya, Lanjutkan Renewal!',
			cancelButtonText: 'Batal'
		}).then((result) => {
			if (result.isConfirmed) {
				window.location.href = "<?= base_url('home/trigger_renewal/') ?>" + appsId;
			}
		});
	}

    
</script>
</body>
</html>