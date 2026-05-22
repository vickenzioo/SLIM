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
    <div class="content-header"></div>

    <section class="content">
      <div class="container-fluid">
        
        <div class="row row-dashboard">
            
            <div class="col-md-3" id="colLeft">
                <div class="card shadow-sm" style="border-radius: 8px; border-bottom-left-radius: 0px; border-bottom-right-radius: 0px; border-top: 3px solid #ffc107;">

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
                                        $colorClass = 'task-yellow'; // Default Kuning
                                        $btnClass   = 'btn-yellow'; // Default Kuning

                                        if (isset($task['task_color'])) {

                                            if ($task['task_color'] == 'red') {
                                                $colorClass = 'task-red'; // Asumsikan kelas CSS ini sudah ada
                                                $btnClass = 'btn-red-force'; // Gunakan kelas tombol Bootstrap default untuk merah
                                            }
                                            elseif($task['task_color'] == 'orange') { 
                                                $colorClass = 'task-orange'; 
                                                $btnClass = 'btn-orange'; 
                                            } elseif($task['task_color'] == 'blue') { 
                                                $colorClass = 'task-blue'; 
                                                $btnClass = 'btn-blue'; 
                                            }
                                        }
                                    ?>
                                    <div class="card card-task <?= $colorClass ?> mb-3">
                                        <div class="card-body p-3">
                                            <div class="mb-2 clearfix">
                                                <span class="task-meta"><?= $task['time_elapsed'] ?></span>
                                            </div>
                                            <div class="mb-2 clearfix">
                                                <?php 
                                                    $ci =& get_instance();
                                                    $is_renewal = $ci->db->where('apps_id', $task['apps_id'])
                                                                         ->where('action', 'RENEWAL')
                                                                         ->count_all_results('tbl_apps_audit_trail') > 0;
                                                ?>
                                                <span class="task-title">
                                                    <?= $task['application_name'] ? $task['application_name'] : $task['short_name'] ?>
                                                    
                                                    <?php if($is_renewal): ?>
                                                        <span class="text-danger font-weight-bold">(RENEWAL)</span>
                                                    <?php endif; ?>
                                                </span>
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
                                                <?php if(isset($task['is_need_renewal']) && $task['is_need_renewal'] === true): ?>
                                                    <button type="button" onclick="confirmRenewal(<?= $task['apps_id'] ?>)" class="btn btn-task <?= $btnClass ?>">
                                                       <?= isset($task['btn_label']) ? $task['btn_label'] : 'Renewal' ?>
                                                    </button>
                                                <?php else: ?>
                                                    <a href="<?= base_url('home/detail/'.$task['apps_id']) ?>" class="btn btn-task <?= $btnClass ?>">
                                                        <?= isset($task['btn_label']) ? $task['btn_label'] : 'View Detail' ?>
                                                    </a>
                                                <?php endif; ?>
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
                                    <li class="short-task-item mb-3" style="<?= $colorBorder ?> padding: 12px 15px; list-style: none; background: #fff; border-radius: 4px; box-shadow: 0 1px 3px rgba(0,0,0,0.1);" title="<?= $task['application_name'] ?>">
                                        
                                        <!-- Nama Aplikasi -->
                                        <div style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis; font-weight: bold; font-size: 13px;">
                                            <?= $task['application_name'] ? $task['application_name'] : $task['short_name'] ?>
                                        </div>

                                        <!-- Kategori (Dipindah ke baris baru di bawah nama aplikasi) -->
                                        <?php if(!empty($task['category_name'])): ?>
                                            <div style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis; font-size: 11px; font-weight: normal; color: #6c757d; margin-top: 4px;">
                                                Category : <?= $task['category_name'] ?>
                                            </div>
                                        <?php endif; ?>

                                        <!-- Status (Diubah menjadi font-weight: bold; color: #333; agar identik) -->
                                        <div style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis; font-size: 11px; font-weight: bold; color: #333; margin-top: 4px;">
                                            Status : <?= isset($task['task_status_label']) ? $task['task_status_label'] : 'Pending' ?>
                                        </div>
                                    </li>
                                <?php endforeach; ?>
                                </ul>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-md-9 transition-column" id="colRight">
                <div class="card shadow-sm" style="border-radius: 8px; border-top: 3px solid #ffc107; height: 620px; display: flex; flex-direction: column;">
                    
                    <div class="card-header border-0 pb-0 pt-4 px-4 d-flex align-items-center">
						<h4 class="font-weight-bold mb-0">My Portofolio</h4>
						
						<button type="button" class="btn btn-sm bg-transparent border-0 text-secondary ml-auto" id="btnFullscreen" onclick="toggleFullscreen()" title="Expand Table">
							<i class="fas fa-expand fa-lg"></i> 
						</button>
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
                                <div class="d-flex align-items-center" style="gap: 10px;">
                                    <?php if($rid == 2 || $rid == 1 ): ?>
                                        <a href="<?= base_url('home/detail/0') ?>" class="btn btn-add-custom btn-sm">
                                            <i class="fas fa-plus"></i> Create Portofolio
                                        </a>
                                    <?php endif; ?>
                                    
                                    <?php if($this->session->userdata('role_id') == 1): ?>
                                        <button type="button" class="btn btn-sm btn-generate-template" title="Download Excel Template" onclick="confirmGenerateTemplate()">
                                            <i class="fas fa-file-excel"></i> Generate Template
                                        </button>
                                        
                                        <button type="button" class="btn btn-sm btn-import-custom" title="Import Data from Excel" onclick="confirmImport()">
                                            <i class="fas fa-file-import"></i> Import
                                        </button>
                                    <?php endif; ?>
                                    
                                    <button type="button" class="btn btn-export-custom btn-sm" onclick="confirmExport()">
                                        <i class="fas fa-file-export"></i> Export
                                    </button>
                                </div>
                                
                                <div class="input-group input-group-sm" style="width: 300px;">
                                    <input type="text" name="keyword" class="form-control" placeholder="Search..." value="<?= isset($keyword) ? $keyword : '' ?>">
                                    <div class="input-group-append">
                                        <button class="btn btn-default" type="submit"><i class="fas fa-search"></i></button>
                                        <a href="<?= base_url('home') ?>" id="btnResetFilter" class="btn btn-secondary btn-sm" onclick="sessionStorage.removeItem('portfolioTableScrollLeft'); sessionStorage.removeItem('portfolioColumnOrder');">
                                            <i class="fas fa-sync-alt"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>

                            <div class="main-table-wrapper" style="flex: 1; overflow-y: auto; overflow-x: auto; max-height: none !important;" id="scrollableTable">
                                <table class="table table-striped table-bordered table-hover text-nowrap custom-scroll-tabs custom-scroll-kiri table-sticky-header table-freeze-home">
                                    <thead>
                                        <tr class="bg-info" id="sortableHeaderRow">
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
														echo '<span class="drag-handle" style="cursor: grab;" title="Drag to reorder column"><i class="fas fa-grip-vertical text-white-50 mr-2"></i>' . $label . '</span>';
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

                                                $opt_app_name = isset($opt_app_name) ? $opt_app_name : [];
												$opt_status = isset($opt_status) ? $opt_status : [];
												$opt_category = isset($opt_category) ? $opt_category : [];
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
                                                // Menggunakan data dari controller, jika tidak ada baru gunakan default fallback
                                                $opt_app_status = isset($opt_app_status) ? $opt_app_status : ['Active', 'Not Active', 'Drafting', 'Renewal']; 
											?>
											
                                            <?= render_th('Application Name', 'app_name', $opt_app_name, $selected_filters) ?>
											<?= render_th('Status', 'app_status', $opt_app_status, $selected_filters, 1) ?>
											
											<?= render_th('Workflow Status', 'status', $opt_status, $selected_filters, 1) ?>
											<?= render_th('Category', 'category', $opt_category, $selected_filters, 1) ?>
											
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
													<button class="btn btn-sm btn-secondary dropdown-toggle btn-operation" type="button" data-toggle="dropdown" aria-haspopup="true" 
                                                    data-bs-display="static" data-bs-popper="static" 
                                                    data-boundary="window"aria-expanded="false">
														<i class="fas fa-cog mr-2"></i> Actions
													</button>
													<div class="dropdown-menu dropdown-menu-right shadow dropdown-operation-menu" style="transform: none !important;">
														
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
															<?php if ($approved_count == $total_roles && ($rid == 1 || $rid == 2)): ?>
																<button class="dropdown-item" href="#" onclick="confirmRenewal(<?= $row['apps_id'] ?>); return false;">
																	<i class="fas fa-sync fa-fw text-success mr-2"></i> Renewal
																</button>
															<?php endif; ?>
															
															<?php if(($rid == 1 || $rid == 2) && $row['status_name'] == 'DONE'): ?>
																<button class="dropdown-item" href="javascript:void(0)" onclick="toggleAppStatus(<?= $row['apps_id'] ?>, 0, 'Decomission')">
																	<i class="fas fa-power-off fa-fw text-danger mr-2"></i> Decommission
																</button>
															<?php endif; ?>
															
															<?php 
																// CEK UMUR DOKUMEN: Apakah sudah masuk masa NEED RENEWAL (> 1 tahun)?
																$is_need_renewal_btn = false;
																if (isset($row['status']) && $row['status'] == 1 && $row['status_name'] == 'DONE') {
																	$r1_appr = $this->db->where(['apps_id' => $row['apps_id'], 'user_role_id' => 1, 'status' => 1])
																						->get('tbl_apps_approval')->row_array();
																	if ($r1_appr) {
																		$w_sub = !empty($r1_appr['submit_date']) ? $r1_appr['submit_date'] : $r1_appr['modified_at'];
																		if (!empty($w_sub) && strtotime($w_sub) <= strtotime('-1 year')) {
																			$is_need_renewal_btn = true;
																		}
																	}
																}
															?>
															
															<?php if ($rid == 1 && $row['app_status_label'] == 'Active' && $row['status_name'] == 'DONE' && !$is_need_renewal_btn && (!isset($row['is_new_imported']) || $row['is_new_imported'] == 0)): ?>
																<button class="dropdown-item" type="button" onclick="$('#loadingOverlay').css('display', 'flex'); window.location.href='<?= base_url('home/detail/' . $row['apps_id']) ?>?mode=change_owner'">
																	<i class="fas fa-exchange-alt mr-2 text-warning"></i> Edit Ownership
																</button>
															<?php endif; ?>

														<?php endif; ?>
													</div>
												</div>
											</td>
											
                                            <td style="min-width: 250px; max-width: 400px; white-space: normal; word-wrap: break-word;">
                                                <?= $row['application_name'] ?>
                                                
                                                <?php if(isset($row['is_new_imported']) && $row['is_new_imported'] == 1): ?>
                                                    <span class="text-danger font-weight-bold ml-1">*NEW*</span>
                                                <?php endif; ?>
                                            </td>

											<td class="text-center align-middle">
                                                <?php 
                                                    // Warna Default (Abu-abu)
                                                    $bg_color = '#e9ecef'; $text_color = '#495057'; 
                                                    
                                                    // Asumsi variabel looping kamu bernama $row (Ganti $p jika kamu pakai $p)
                                                    $label_status = $row['app_status_label']; 

                                                    if ($label_status == 'Active') {
                                                        $bg_color = '#e8f5e9'; 
                                                        $text_color = '#2e7d32'; // Hijau
                                                    } elseif ($label_status == 'Not Active') {
                                                        $bg_color = '#ffebee'; 
                                                        $text_color = '#c62828'; // Merah
                                                    } elseif ($label_status == 'Drafting') {
                                                        $bg_color = '#fff3e0'; 
                                                        $text_color = '#ef6c00'; // Oranye
                                                    } elseif ($label_status == 'Renewal') {
                                                        $bg_color = '#e3f2fd'; 
                                                        $text_color = '#1565c0'; // Biru (Khusus Renewal)
                                                    }
                                                ?>
                                                <span class="badge px-3 py-2" style="background-color: <?= $bg_color ?>; color: <?= $text_color ?>; border-radius: 6px; font-size: 0.75rem; font-weight: 700; min-width: 85px; display: inline-block;">
                                                    <?= $label_status ?>
                                                </span>
                                            </td>

                                            
                                            <td class="text-center align-middle">
												<?php 
													$curr = isset($row['current_stage_role']) ? $row['current_stage_role'] : 0;
													$roles_map = [1 => 'IT SLM', 2 => 'EA', 3 => 'IT Dev'];
													
													$status_label = isset($roles_map[$curr]) ? $roles_map[$curr] : 'Unknown';
													$bg = '#f8f9fa'; 
                                                    $color = '#6c757d'; 

													if ($curr == 0) {
														// 1. Ambil data persetujuan Role 1 (IT SLM) untuk mengecek tanggal
														$r1_approval = $this->db->where(['apps_id' => $row['apps_id'], 'user_role_id' => 1, 'status' => 1])
																				->get('tbl_apps_approval')
																				->row_array();
														
														if ($r1_approval) {
															$status_label = 'DONE';
															$bg = '#d5f7e3';
                                                            $color = '#218c74'; // Hijau untuk DONE
															
															$waktu_submit = !empty($r1_approval['submit_date']) ? $r1_approval['submit_date'] : $r1_approval['modified_at'];
															
															if (!empty($waktu_submit)) {
																$tanggal_submit = strtotime($waktu_submit);
																$batas_satu_tahun = strtotime('-1 year');
																
																// Jika tanggal submit lebih lama (<=) dari 1 tahun yang lalu
																if ($tanggal_submit <= $batas_satu_tahun) {
																	
																	// PASTIKAN APLIKASINYA MASIH ACTIVE (1). JIKA NOT ACTIVE (0), ABAIKAN.
																	if (isset($row['status']) && $row['status'] == 1) {
																		$status_label = 'NEED RENEWAL';
																		$bg = '#ffe3e6'; // Latar Merah Pudar
																		$color = '#ff4757'; // Teks Merah Terang
																	}
																	
																}
															}
														}
													} 
													elseif ($curr == 1) { $bg = '#d9f8f8'; $color = '#008a8a'; } // IT SLM
													elseif ($curr == 2) { $bg = '#ecebff'; $color = '#6c5ce7'; } // EA
													elseif ($curr == 3) { $bg = '#fce3ef'; $color = '#d63031'; } // IT Dev
												?>
												<span class="badge px-3 py-2" style="background-color: <?= $bg ?>; color: <?= $color ?>; border-radius: 6px; font-size: 0.75rem; font-weight: 800; letter-spacing: 0.5px; display: inline-block; min-width: 120px;">
													<?= strtoupper($status_label) ?>
												</span>
											</td>

                                            <td style="min-width: 100px; max-width: 150px; white-space: normal; word-wrap: break-word;"><?= isset($row['category_name']) ? $row['category_name'] : '' ?>
                                            </td>

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


                    <div class="custom-card-footer d-flex align-items-center" style="border-top: 1px solid #dee2e6;">
                        <div style="flex: 1; text-align: left; font-size: 9px !important;" class="text-muted">
                            Total Portofolio: <b class="text-dark"><?= isset($total_rows) ? $total_rows : 0 ?></b>
                        </div>

                        <div class="d-flex justify-content-center m-0">
                            <?= isset($pagination) ? $pagination : '' ?>
                        </div>

                        <div class="d-flex align-items-center justify-content-end text-muted" style="flex: 1; font-size: 9px !important;">
                            <span class="mr-2">Number of rows:</span>
                            <div class="dropdown" style="width: 50px;"> 
                                <button class="btn btn-sm border dropdown-toggle w-100" type="button" id="limitDropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="font-size: 9px !important; height: 22px; padding: 0 5px; display: flex; align-items: center; justify-content: space-between;">
                                    <?= isset($current_limit) ? $current_limit : '20' ?>
                                </button>
                                <div class="dropdown-menu dropdown-menu-right shadow-sm" aria-labelledby="limitDropdown" style="font-size: 9px !important; min-width: 100% !important;">
                                    <a class="dropdown-item limit-option text-center" href="#" data-value="20">20</a>
                                    <a class="dropdown-item limit-option text-center" href="#" data-value="30">30</a>
                                    <a class="dropdown-item limit-option text-center" href="#" data-value="40">40</a>
                                    <a class="dropdown-item limit-option text-center" href="#" data-value="50">50</a>
                                    <a class="dropdown-item limit-option text-center" href="#" data-value="100">100</a>
                                </div>
                            </div>
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
        
        $('#colLeft, #colRight').css('transition', 'none');

        var btnToggle = document.querySelector('[onclick*="toggleTaskSize"]');
        if (btnToggle && !isMinimized) {
            toggleTaskSize(btnToggle);
        }
        
        setTimeout(function() {
            $('#colLeft, #colRight').css('transition', '');
        }, 50);

        const navEntries = performance.getEntriesByType("navigation");
        if (navEntries.length > 0 && navEntries[0].type === "reload") {
            sessionStorage.removeItem('portfolioTableScrollLeft');
        }
		
    $('#btnResetFilter').on('click', function() {
          // Hapus memori scroll saat tombol reset diklik!
          sessionStorage.removeItem('portfolioTableScrollLeft');
          // Hapus memori urutan kolom agar kembali ke template!
          sessionStorage.removeItem('portfolioColumnOrder');
      });

        const $mainTableWrapper = $('.main-table-wrapper');

        function restoreScrollPosition() {
            if ($mainTableWrapper.length > 0) {
                const savedScrollPosition = sessionStorage.getItem('portfolioTableScrollLeft');
                if (savedScrollPosition !== null) {
                    const scrollPos = parseInt(savedScrollPosition, 10);
                    $mainTableWrapper.scrollLeft(scrollPos);
                }
            }
        }

        setTimeout(restoreScrollPosition, 300);

        $mainTableWrapper.on('scroll', function() {
            const currentScroll = $(this).scrollLeft();
            sessionStorage.setItem('portfolioTableScrollLeft', currentScroll);
        });

        $(document).on('input', 'input[name="keyword"], .filter-search-input', function() {
            // Regex: HANYA izinkan huruf, angka, spasi, titik, koma, strip, dan underscore
            var forbiddenChars = /[^a-zA-Z0-9\s.,_\-:)(/]/g; 
            var currentValue = $(this).val();

            if (forbiddenChars.test(currentValue)) {
                // Langsung hapus karakter terlarang yang diketik
                $(this).val(currentValue.replace(forbiddenChars, ''));
                
                // Beri efek visual border merah berkedip pada kotak pencarian
                var el = $(this);
                el.css({
                    'border-color': '#dc3545',
                    'box-shadow': '0 0 0 0.2rem rgba(220, 53, 69, 0.25)'
                });
                
                // Hilangkan efek merah setelah 400ms
                setTimeout(function() {
                    el.css({
                        'border-color': '',
                        'box-shadow': ''
                    });
                }, 400);
                
                // Jika ini adalah input filter, panggil ulang fungsi pencarian agar list-nya update
                if (el.hasClass('filter-search-input')) {
                    filterList(this);
                }
            }
            $(document).on('paste', allTargetSelectors, function(e) {
                // Ambil data teks dari clipboard
                var pasteData = (e.originalEvent || e).clipboardData.getData('text');

                if (forbiddenChars.test(pasteData)) {
                    // Jika mengandung karakter terlarang, batalkan proses paste
                    e.preventDefault();
                    
                    // Beri feedback visual merah agar user tahu paste ditolak
                    var el = $(this);
                    el.css({
                        'border-color': '#dc3545',
                        'box-shadow': '0 0 0 0.2rem rgba(220, 53, 69, 0.25)'
                    });
                    setTimeout(function() {
                        el.css({ 'border-color': '', 'box-shadow': '' });
                    }, 400);
                }
            });
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
            colLeft.className = "col-md-3";
            colRight.className = "col-md-9";

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
            confirmButtonText: 'Yes, Export',
            cancelButtonText: 'Cancel',
            reverseButtons: true,
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
                
                // 1. Input default untuk trigger export
                var inputExport = document.createElement('input');
                inputExport.type = 'hidden';
                inputExport.name = 'export';
                inputExport.value = '1';
                document.getElementById('mainFilterForm').appendChild(inputExport);
                
                // 2. TANGKAP URUTAN KOLOM DAN KIRIM KE SERVER
                var inputOrder = document.createElement('input');
                inputOrder.type = 'hidden';
                inputOrder.name = 'column_order'; // Nama variabel yang akan ditangkap oleh PHP
                var savedOrder = sessionStorage.getItem('portfolioColumnOrder');
                inputOrder.value = savedOrder ? savedOrder : ''; // Berisi array seperti "[0, 3, 1, 2...]"
                document.getElementById('mainFilterForm').appendChild(inputOrder);
                
                // Submit form
                document.getElementById('mainFilterForm').submit();

                // Bersihkan elemen input sementara
                setTimeout(() => { 
                    inputExport.remove(); 
                    inputOrder.remove(); 
                }, 100);
            }
        });
    }

    function confirmGenerateTemplate() {
        Swal.fire({
            title: 'Generate Excel Template?',
            text: "File template akan otomatis diunduh.",
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Yes, Generate',
            cancelButtonText: 'Cancel',
            reverseButtons: true,
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
                
                // Mengarahkan ke link download setelah dikonfirmasi
                window.location.href = '<?= base_url("home/generate_template") ?>';
            }
        });
    }

    function confirmImport() {
        Swal.fire({
            title: 'Import Application Data',
            html: "<p style='margin-bottom: 15px; font-size: 14px;'>Pastikan template Excel yang digunakan sudah sesuai.</p>" +
                  "<div class='text-left px-2'>" +
                  "<label class='font-weight-normal' style='font-size: 14px;'>Pilih File Excel (.xlsx / .xls) <span class='text-danger'>*</span></label>" +
                  "<input type='file' id='swal-import-file' name='file_excel' class='form-control' accept='.xlsx, .xls' style='padding: 3px;'>" +
                  "</div>",
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Yes, Import',
            cancelButtonText: 'Cancel',
            reverseButtons: true,
            buttonsStyling: false,
            customClass: {
                confirmButton: 'btn btn-save-custom px-4 mx-2', 
                cancelButton: 'btn btn-secondary px-4 mx-2'
            },
            preConfirm: () => {
                let fileInput = document.getElementById('swal-import-file');
                
                // Validasi harus ada file yang diupload
                if (fileInput.files.length === 0) {
                    Swal.showValidationMessage('File Excel wajib dipilih!');
                    return false;
                }

                // Validasi ekstensi
                let fileName = fileInput.files[0].name;
                let ext = fileName.split('.').pop().toLowerCase();
                if (ext !== 'xlsx' && ext !== 'xls') {
                    Swal.showValidationMessage('Format file tidak didukung! Harus .xlsx atau .xls');
                    return false;
                }
                
                // Pindahkan file input agar tidak terhapus saat modal close
                fileInput.style.display = 'none';
                document.body.appendChild(fileInput);
                
                return fileInput;
            }
        }).then((result) => {
            if (result.isConfirmed) {
                let safeFileInput = result.value;
                
                // Tampilkan loading overlay
                $('#loadingOverlay').css('display', 'flex'); 
                
                // Buat form virtual untuk submit file
                let form = document.createElement('form');
                form.method = 'POST';
                form.action = '<?= base_url("home/import_excel") ?>';
                form.enctype = 'multipart/form-data'; 
                
                form.appendChild(safeFileInput);
                document.body.appendChild(form);
                
                // Submit data
                form.submit();
            } else {
                // Bersihkan DOM jika user batal
                let orphanedInput = document.getElementById('swal-import-file');
                if (orphanedInput) orphanedInput.remove();
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

    window.toggleAppStatus = function(apps_id, status, actionName) {
        // 1. Mencegah halaman reload tiba-tiba saat tombol diklik
        if (window.event) {
            window.event.preventDefault();
        }

        let colorBtn = (status === 1) ? '#28a745' : '#dc3545';
        let iconSwal = (status === 1) ? 'question' : 'warning';
        
        let fileLabel = (status === 0) ? 
            "Attach Document Memo (PDF Only) <span class='text-danger'>*</span>" : 
            "Attach Document (PDF Only) - <i>Opsional</i>";
        
        Swal.fire({
            title: actionName + ' Aplikasi?',
            html: "<p style='margin-bottom: 15px;'>Aplikasi akan menjadi <b>" + (status === 1 ? "Active" : "Not Active") + "</b>.</p>" +
                  "<div class='text-left px-2'>" +
                  "<label class='font-weight-normal' style='font-size: 14px;'>" + fileLabel + "</label>" +
                  "<input type='file' id='swal-file' name='attached_document' class='form-control' accept='application/pdf' style='padding: 3px;'>" +
                  "</div>",
            icon: iconSwal,
            showCancelButton: true,
            confirmButtonText: 'Continue',
            cancelButtonText: 'Cancel',
            reverseButtons: true,
            buttonsStyling: false,
            customClass: {
                confirmButton: 'btn btn-save-custom px-4 mx-2',
                cancelButton: 'btn btn-secondary px-4 mx-2'
            },
            preConfirm: () => {
                let fileInput = document.getElementById('swal-file');
                
                // Jika Deactivate/Decomission (status === 0) WAJIB ada file
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
                let safeFileInput = result.value;

                // --- PERBAIKAN: DOUBLE CONFIRMATION KHUSUS DECOMISSION (STATUS 0) ---
                if (status === 0) {
                    Swal.fire({
                        title: 'Apakah Anda sudah yakin?',
                        html:'Aplikasi yang sudah Non-Active TIDAK DAPAT diaktifkan kembali.',
                        icon: 'error',
                        showCancelButton: true,
                        confirmButtonText: 'Yes, Decomission',
                        cancelButtonText: 'Cancel',
                        reverseButtons: true,
                        buttonsStyling: false,
                        customClass: {
                            confirmButton: 'btn btn-deactivate px-4 mx-2', // Pakai warna merah agar lebih hati-hati
                            cancelButton: 'btn btn-secondary px-4 mx-2'
                        }
                    }).then((finalResult) => {
                        if (finalResult.isConfirmed) {
                            submitStatusForm(apps_id, status, safeFileInput);
                        } else {
                            if (safeFileInput) safeFileInput.remove(); // Bersihkan file jika batal
                        }
                    });
                } else {
                    // Jika Activate (Status 1), langsung proses tanpa konfirmasi kedua
                    submitStatusForm(apps_id, status, safeFileInput);
                }

            } else {
                // Jika user klik Batal di pop-up pertama
                let orphanedInput = document.getElementById('swal-file');
                if (orphanedInput) {
                    orphanedInput.remove();
                }
            }
        });
    }

    // Fungsi bantuan agar script form-submit tidak ditulis berulang
    function submitStatusForm(appId, appStatus, fileInput) {
        $('#loadingOverlay').css('display', 'flex'); 
        
        let form = document.createElement('form');
        form.method = 'POST';
        form.action = '<?= base_url("home/toggle_status/") ?>' + appId + '/' + appStatus;
        form.enctype = 'multipart/form-data'; 
        
        if (fileInput) {
            form.appendChild(fileInput);
        }
        
        document.body.appendChild(form);
        form.submit();
    }
    
    function confirmRenewal(appsId) {
        Swal.fire({
            title: 'Mulai Proses Renewal?',
            text: "Pastikan dokumen Renewal sudah lengkap.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes, Continue',
            cancelButtonText: 'Cancel',
            reverseButtons: true, // Menempatkan tombol Cancel di kiri
            buttonsStyling: false,
            customClass: {
                confirmButton: 'btn btn-save-custom px-4 mx-2', 
                cancelButton: 'btn btn-secondary px-4 mx-2'
            }
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = "<?= base_url('home/trigger_renewal/') ?>" + appsId;
            }
        });
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
                    title: 'Konfirmasi Logout',
                    text: 'Apakah Anda yakin ingin keluar dari sistem?',
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonText: 'Logout',
                    cancelButtonText: 'Cancel',
                    reverseButtons: true,
                    customClass: {
                        confirmButton: 'btn btn-save-custom px-4 mx-2', 
                        cancelButton: 'btn btn-secondary px-4 mx-2'
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
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
                customClass: { 
                    confirmButton: 'btn btn-save-custom px-4 mx-2' 
                }
            });
            <?php $this->session->unset_userdata('success'); ?>
        <?php endif; ?>

        <?php if($this->session->flashdata('error')): ?>
            Swal.fire({
                icon: 'error',
                title: 'Gagal!',
                html: '<?= $this->session->flashdata('error') ?>',
                confirmButtonText: 'OK',
                buttonsStyling: false,
                customClass: { 
                    confirmButton: 'btn btn-save-custom px-4 mx-2' 
                }
            });
            <?php $this->session->unset_userdata('error'); ?>
        <?php endif; ?>
    });
	
	// --- FIX DROPDOWN NUMBER OF ROWS ---
    $(document).ready(function() {
        $('.limit-option').on('click', function(e) {
            e.preventDefault(); // Mencegah halaman melompat ke atas saat diklik
            
            // Ambil angka dari atribut data-value (20, 30, 40, dll)
            let newLimit = $(this).attr('data-value'); 
            
            // Ambil URL saat ini dan perbarui parameter 'limit'
            let url = new URL(window.location.href);
            url.searchParams.set('limit', newLimit);
            
            // Hapus parameter 'per_page' atau 'page' agar kembali ke halaman 1 saat limit diubah
            // (Sesuaikan nama parameter ini jika pagination CodeIgniter Anda menggunakan nama lain)
            url.searchParams.delete('per_page'); 
            url.searchParams.delete('page'); 
            
            // Munculkan loading dan reload halaman dengan limit baru
            $('#loadingOverlay').css('display', 'flex'); 
            window.location.href = url.href;
        });
    });
	
	function confirmChangeOwner(apps_id) {
		Swal.fire({
			title: 'Edit Ownership?',
			text: "Anda akan diarahkan ke halaman detail.",
			icon: 'question',
			showCancelButton: true,
			confirmButtonText: 'Yes, Edit',
			cancelButtonText: 'Cancel',
			reverseButtons: true,
			buttonsStyling: false,
			customClass: {
				confirmButton: 'btn btn-save-custom px-4 mx-2', 
				cancelButton: 'btn btn-secondary px-4 mx-2'
			}
		}).then((result) => {
			if (result.isConfirmed) {
				$('#loadingOverlay').css('display', 'flex');
				window.location.href = "<?= base_url('home/detail/') ?>" + apps_id + "?mode=change_owner";
			}
		});
	}
	
	function toggleFullscreen() {
        var leftCol = $('#colLeft');
        var rightCol = $('#colRight');
        var mainRow = leftCol.parent('.row'); 
        var icon = $('#btnFullscreen i');
        var btn = $('#btnFullscreen');

        mainRow.addClass('main-row-nowrap');

        if (!leftCol.hasClass('left-col-hidden')) {
            leftCol.addClass('left-col-hidden');
            rightCol.addClass('right-col-fullscreen');
            
            icon.removeClass('fa-expand').addClass('fa-compress');
            btn.attr('title', 'Minimize Table');

            sessionStorage.setItem('isPortofolioFullscreen', 'true');
        } else {
            leftCol.removeClass('left-col-hidden');
            rightCol.removeClass('right-col-fullscreen');
            
            icon.removeClass('fa-compress').addClass('fa-expand');
            btn.attr('title', 'Expand Table');
            
            sessionStorage.removeItem('isPortofolioFullscreen');
            
            setTimeout(function() {
                if (!leftCol.hasClass('left-col-hidden')) {
                    mainRow.removeClass('main-row-nowrap');
                }
            }, 400);
        }
    }
	
	$(document).ready(function() {
        var navEntries = window.performance.getEntriesByType("navigation");
        if (navEntries.length > 0 && navEntries[0].type === "reload") {
            sessionStorage.removeItem('isPortofolioFullscreen');
        }

        if (sessionStorage.getItem('isPortofolioFullscreen') === 'true') {
            $('#colLeft, #colRight').css('transition', 'none');
            $('#colLeft').addClass('left-col-hidden');
            $('#colRight').addClass('right-col-fullscreen');
            $('#colLeft').parent('.row').addClass('main-row-nowrap');
            
            $('#btnFullscreen i').removeClass('fa-expand').addClass('fa-compress');
            $('#btnFullscreen').attr('title', 'Minimize Table');

            setTimeout(function() {
                $('#colLeft, #colRight').css('transition', '');
            }, 50); 
        }
    });

    // --- FITUR DRAG & DROP COLUMN DENGAN MEMORI (PERSISTENCE) ---
    $(document).ready(function() {
        var $headerRow = $('#sortableHeaderRow');
        var $table = $('#scrollableTable table');
        
        if ($headerRow.length) {
            // 1. Berikan ID index asli ke setiap header (th) saat halaman pertama kali dimuat
            $headerRow.find('th').each(function(index) {
                $(this).attr('data-orig-idx', index);
            });

            // 2. Cek apakah ada urutan kolom yang tersimpan di SessionStorage
            var savedOrder = sessionStorage.getItem('portfolioColumnOrder');
            if (savedOrder) {
                savedOrder = JSON.parse(savedOrder);
                var $headers = $headerRow.find('th');
                
                // Reorder jika jumlah kolom cocok (mencegah error)
                if (savedOrder.length === $headers.length) {
                    // Susun ulang Header
                    var newHeaders = [];
                    savedOrder.forEach(function(idx) {
                        newHeaders.push($headers.filter('[data-orig-idx="' + idx + '"]'));
                    });
                    $headerRow.append(newHeaders);

                    // Susun ulang baris di Body
                    $table.find('tbody tr').each(function() {
                        var $cells = $(this).children('td');
                        // Abaikan baris "No Data Found" yang menggunakan colspan
                        if ($cells.length === $headers.length) {
                            $cells.each(function(index) {
                                $(this).attr('data-orig-idx', index);
                            });
                            var newCells = [];
                            savedOrder.forEach(function(idx) {
                                newCells.push($cells.filter('[data-orig-idx="' + idx + '"]'));
                            });
                            $(this).append(newCells); // jQuery append akan memindahkan elemen ke posisi baru
                        }
                    });
                }
            }
        }

        // 3. Inisialisasi SortableJS
        var el = document.getElementById('sortableHeaderRow');
        if (el) {
            Sortable.create(el, {
                handle: '.drag-handle', // Hanya kolom yang memiliki class ini yang bisa di-drag
                animation: 150,         // Animasi saat bergeser (ms)
                easing: "cubic-bezier(1, 0, 0, 1)",
                onStart: function (evt) {
                    $('.drag-handle').css('cursor', 'grabbing');
                },
                onEnd: function (evt) {
                    $('.drag-handle').css('cursor', 'grab');
                    
                    var oldIdx = evt.oldIndex;
                    var newIdx = evt.newIndex;

                    // Jika posisinya tidak berubah, batalkan
                    if (oldIdx === newIdx) return;

                    // Pindahkan setiap sel <td> di <tbody> agar sejajar dengan header yang baru
                    $('#scrollableTable tbody tr').each(function () {
                        var $cells = $(this).children('td');
                        // Pastikan bukan baris "No Data Found"
                        if ($cells.length > 1) {
                            var $draggedCell = $cells.eq(oldIdx);
                            if (newIdx > oldIdx) {
                                $draggedCell.insertAfter($cells.eq(newIdx));
                            } else {
                                $draggedCell.insertBefore($cells.eq(newIdx));
                            }
                        }
                    });

                    // 4. SIMPAN URUTAN BARU KE SESSION STORAGE
                    var newOrderArray = [];
                    $('#sortableHeaderRow th').each(function() {
                        newOrderArray.push($(this).attr('data-orig-idx'));
                    });
                    sessionStorage.setItem('portfolioColumnOrder', JSON.stringify(newOrderArray));
                }
            });
        }
    });
</script>
</body>
</html>