<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <title>SLIM | <?= $title ?></title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <?php $this->load->view('layout/head_links'); ?>
  
  <style>
      .select2-container--bootstrap4 .select2-selection--single { height: calc(2.25rem + 2px) !important; }
      .dropdown-menu.keep-open { max-height: 250px; overflow-y: auto; }
      
      .form-control[readonly] { 
          background-color: #e9ecef !important; 
          opacity: 1; 
          pointer-events: none; 
          cursor: not-allowed;
      }
      
      .infra-header { color: var(--theme-blue-primary, #007bff); font-weight: 600; border-bottom: 1px solid #dee2e6; padding-bottom: 5px; margin-bottom: 15px; margin-top: 10px; }
      .calc-field { background-color: #f8f9fa; font-weight: bold; color: #495057; }
      
      .service-row { transition: all 0.3s; }
      .service-row:hover { box-shadow: 0 .5rem 1rem rgba(0,0,0,.15)!important; }
      .is-invalid { border-color: #dc3545 !important; }
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
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0 font-weight-bold text-dark">Portofolio Detail</h1>
          </div>
        </div>
      </div>
    </section>

    <section class="content">
      <div class="container-fluid">
        
        <form id="formDetail" action="<?= base_url('home/save_submission') ?>" method="post" enctype="multipart/form-data">
            <input type="hidden" name="apps_id" value="<?= $apps_id ?>">
            <input type="hidden" name="save_type" id="saveType" value="">
            
            <?php if(isset($service_id_param) && $service_id_param > 0): ?>
                <input type="hidden" name="is_single_edit" value="1">
            <?php endif; ?>

            <div class="row dashboard-main-card">
                <div class="col-md-8">
                    <div class="card shadow-sm" style="border-top: 3px solid var(--theme-yellow-primary); min-height: 750px;">
                        
                        <div class="card-header bg-white border-bottom">
                            <div class="d-flex align-items-center">
                                <a href="<?= base_url('home') ?>" class="btn btn-secondary btn-sm mr-3" title="Back">
                                    <i class="fas fa-arrow-left"></i> Back
                                </a>
                                <h5 class="mb-0 font-weight-bold">
                                    <?= ($mode == 'add') ? 'Add New Portofolio' : 'Application Data' ?>
                                </h5>
                            </div>
                        </div>

                        <div class="card-body">
                            
                            <?php if($is_infra): ?>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Application Name</label>
                                            <input type="text" class="form-control" value="<?= isset($row['application_name']) ? $row['application_name'] : '' ?>" readonly>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Category</label>
                                            <input type="text" class="form-control" value="<?= isset($row['category_name']) ? $row['category_name'] : '' ?>" readonly>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="row mb-3">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>Module Name <span class="text-danger">*</span></label>
                                            <?php if($is_readonly || (isset($service_id_param) && $service_id_param > 0)): ?>
                                                <input type="text" class="form-control" value="<?= isset($row['module_name']) ? $row['module_name'] : '' ?>" readonly>
                                                <input type="hidden" name="module_id" id="module_id_hidden" value="<?= isset($row['module_id']) ? $row['module_id'] : '' ?>">
                                            <?php else: ?>
                                                <select class="form-control select2" name="module_id" id="module_id_select" data-placeholder="-- Select Module --" required>
                                                    <option>
                                                        
                                                    </option>
                                                    <?php if(!empty($opt_module)): foreach($opt_module as $mod): ?>
                                                        <option value="<?= $mod['module_id'] ?>" <?= (isset($row['module_id']) && $row['module_id'] == $mod['module_id']) ? 'selected' : '' ?>><?= $mod['module_name'] ?></option>
                                                    <?php endforeach; endif; ?>
                                                </select>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>

                                <div id="dynamic_infra_container"></div>
                                
                                <input type="hidden" id="global_sla_standard" value="<?= isset($row['standard_category']) ? $row['standard_category'] : (isset($row['sla_standard']) ? $row['sla_standard'] : 0) ?>">

                            <?php elseif($is_bu): ?>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Category</label>
                                            <input type="text" class="form-control" value="<?= isset($row['category_name']) ? $row['category_name'] : '' ?>" readonly>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Application Name</label>
                                            <input type="text" class="form-control" value="<?= isset($row['application_name']) ? $row['application_name'] : '' ?>" readonly>
                                        </div>
                                    </div>
                                </div>
                                
                                <h6 class="infra-header"></h6>
                                
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Operational Day <span class="text-danger">*</span></label>

                                            <?php if($rid == 6 && !$is_readonly): ?>
                                                <select name="operational_day_id" class="form-control select2" required>
                                                    <option value="">-- Select Day --</option>

                                                    <?php if(!empty($opt_day)): foreach($opt_day as $d): ?>
                                                        <option value="<?= $d['operational_day_id'] ?>" <?= (isset($row['operational_day_id']) && $row['operational_day_id'] == $d['operational_day_id']) ? 'selected' : '' ?>>
                                                            <?= $d['start_day'] . ' - ' . $d['end_day'] ?>
                                                        </option>
                                                    <?php endforeach; endif; ?>
                                                </select>

                                            <?php else: ?>
                                                <input type="text" class="form-control" value="<?= isset($row['operational_day_full']) ? $row['operational_day_full'] : '' ?>" readonly>
                                                <input type="hidden" name="operational_day_id" value="<?= isset($row['operational_day_id']) ? $row['operational_day_id'] : '' ?>">
                                            <?php endif; ?>
                                        </div>
                                    </div>


                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Operational Hour <span class="text-danger">*</span></label>

                                            <?php if($rid == 6 && !$is_readonly): ?>
                                                <select name="operational_hour_id" class="form-control select2" required>
                                                    <option value="">-- Select Hour --</option>

                                                    <?php if(!empty($opt_hour)): foreach($opt_hour as $h): ?>
                                                        <option value="<?= $h['operational_hour_id'] ?>" <?= (isset($row['operational_hour_id']) && $row['operational_hour_id'] == $h['operational_hour_id']) ? 'selected' : '' ?>>
                                                            <?= $h['start_time'] . ' - ' . $h['end_time'] ?>
                                                        </option>
                                                    <?php endforeach; endif; ?>
                                                </select>

                                            <?php else: ?>
                                                <input type="text" class="form-control" value="<?= isset($row['operational_hour_full']) ? $row['operational_hour_full'] : '' ?>" readonly>
                                                <input type="hidden" name="operational_hour_id" value="<?= isset($row['operational_hour_id']) ? $row['operational_hour_id'] : '' ?>">
                                            <?php endif; ?>

                                        </div>
                                    </div>
                                </div>

                            <?php else: ?>
                                <div class="row">
                                    <div class="col-md-6"> 
                                        <div class="form-group">
                                            <label>Application Name <span class="text-danger">*</span></label>
                                            <input type="text" name="application_name" class="form-control" required value="<?= isset($row['application_name']) ? $row['application_name'] : '' ?>" <?= $is_readonly ? 'readonly' : '' ?>>
                                        </div>
                                    </div>
                                    <div class="col-md-6"> 
                                        <div class="form-group">
                                            <label>Short Name</label>
                                            <input type="text" name="short_name" class="form-control" value="<?= isset($row['short_name']) ? $row['short_name'] : '' ?>" <?= $is_readonly ? 'readonly' : '' ?>>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Category <span class="text-danger">*</span></label>

                                            <?php if($is_readonly): ?>
                                                <input type="text" class="form-control" value="<?= isset($row['category_name']) ? $row['category_name'] : '' ?>" readonly>

                                            <?php else: ?>
                                                <select class="form-control select2" name="category_id" data-placeholder="-- Select Category --" required>
                                                    <option>
                                                        
                                                    </option>
                                                    <?php if(!empty($opt_category)): foreach($opt_category as $cat): ?>
                                                        <option value="<?= $cat['category_id'] ?>" <?= (isset($row['category_id']) && $row['category_id'] == $cat['category_id']) ? 'selected' : '' ?>><?= $cat['category_name'] ?></option>
                                                    <?php endforeach; endif; ?>
                                                </select>
                                            <?php endif; ?>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group"><label>Application Type</label>
                                            <?php if($is_readonly): ?>
                                                <input type="text" class="form-control" value="<?= isset($row['application_type']) ? $row['application_type'] : '' ?>" readonly>
                                            <?php else: ?>
                                                <select name="application_type" class="form-control select2" data-placeholder="-- Select App Type --" required>
                                                <option></option>
                                            <?php foreach(['Custom-built','Off the shelf', 'Off the shelf with customization'] as $type): ?>
                                                <option value="<?= $type ?>" <?= (isset($row['application_type']) && $row['application_type'] == $type) ? 'selected' : '' ?>><?= $type ?>
                                                </option><?php endforeach; ?>
                                                </select><?php endif; ?>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Live Year</label>
                                            <input type="number" name="live_year" class="form-control" value="<?= isset($row['live_year']) ? $row['live_year'] : '' ?>" <?= $is_readonly ? 'readonly' : '' ?>>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Decommission Year</label>
                                            <input type="number" name="decommission_year" class="form-control" value="<?= isset($row['decommission_year']) ? $row['decommission_year'] : '' ?>" <?= $is_readonly ? 'readonly' : '' ?>>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>Description</label>
                                            <textarea name="apps_description" class="form-control" rows="2" <?= $is_readonly ? 'readonly' : '' ?>><?= isset($row['apps_description']) ? $row['apps_description'] : '' ?></textarea>
                                        </div>
                                    </div>
                                </div>

                                <h6 class="infra-header"></h6>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Deployment Model</label>
                                            <?php if($is_readonly): ?>
                                                <input type="text" class="form-control" value="<?= isset($row['deployment_info_full']) ? $row['deployment_info_full'] : '' ?>" readonly>
                                            <?php else: ?>
                                                <select class="form-control select2" name="deployment_id" data-placeholder="-- Select Deployment Model --">
                                                    <option></option>
                                                    <?php if(!empty($opt_deploy)): ?>
                                                        <?php foreach($opt_deploy as $dep): ?>
                                                            <option value="<?= $dep['deployment_id'] ?>" <?= (isset($row['deployment_id']) && $row['deployment_id'] == $dep['deployment_id']) ? 'selected' : '' ?>>
                                                                <?= $dep['deployment_model'] . ' - ' . $dep['deployment_provider'] . ' - ' . $dep['main_deployment_site'] ?>
                                                            </option>
                                                        <?php endforeach; ?>
                                                    <?php endif; ?>
                                                </select>
                                            <?php endif; ?>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Network</label>
                                            <?php if($is_readonly): ?>
                                                <input type="text" class="form-control" value="<?= isset($row['network_name']) ? $row['network_name'] : '' ?>" readonly>
                                            <?php else: ?>
                                                <select class="form-control select2" name="network_id" data-placeholder="-- Select Network --">
                                                <option></option>
                                                <?php if(!empty($opt_network)): ?>
                                                    <?php foreach($opt_network as $net): ?>
                                                        <option value="<?= $net['network_id'] ?>" <?= (isset($row['network_id']) && $row['network_id'] == $net['network_id']) ? 'selected' : '' ?>> <?= $net['network_name'] ?>
                                                        </option>
                                                    <?php endforeach; ?>
                                                <?php endif; ?>
                                                </select>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Database </label>
                                            <?php if($is_readonly): ?>
                                                <div class="p-2 border rounded bg-light" style="min-height: 38px;">
                                                    <?= isset($row['database_names_str']) ? $row['database_names_str'] : '-' ?>
                                                </div>
                                            <?php else: ?>
                                                <div class="dropdown">
                                                    <button class="btn btn-default dropdown-toggle w-100 d-flex justify-content-between align-items-center" type="button" id="dropdownDB" data-toggle="dropdown">
                                                        <span id="labelDB" class="text-truncate">-- Select Databases --</span>
                                                    </button>
                                                    <div class="dropdown-menu w-100 p-2 keep-open">
                                                        <?php if(!empty($opt_database)): ?>
                                                            <?php foreach($opt_database as $db): ?>
                                                                <div class="form-check mb-1">
                                                                    <input class="form-check-input db-checkbox" type="checkbox" name="database_ids[]" value="<?= $db['database_id'] ?>" id="db_<?= $db['database_id'] ?>" data-label="<?= $db['database_name'] ?>" <?= (in_array($db['database_id'], $selected_db_ids)) ? 'checked' : '' ?>>
                                                                    <label class="form-check-label w-100" for="db_<?= $db['database_id'] ?>">
                                                                        <?= $db['database_name'] ?>
                                                                    </label>
                                                                </div>
                                                            <?php endforeach; ?>
                                                        <?php endif; ?>
                                                    </div>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Operating Software </label>
                                            <?php if($is_readonly): ?>
                                                <div class="p-2 border rounded bg-light" style="min-height: 38px;">
                                                    <?= isset($row['os_names_str']) ? $row['os_names_str'] : '-' ?>
                                                </div>
                                            <?php else: ?>
                                                <div class="dropdown">
                                                    <button class="btn btn-default dropdown-toggle w-100 d-flex justify-content-between align-items-center" type="button" id="dropdownOS" data-toggle="dropdown">
                                                        <span id="labelOS" class="text-truncate">-- Select OS --</span>
                                                    </button>
                                                    <div class="dropdown-menu w-100 p-2 keep-open">
                                                        <?php if(!empty($opt_os)): ?>
                                                            <?php foreach($opt_os as $os): ?>
                                                                <div class="form-check mb-1">
                                                                    <input class="form-check-input os-checkbox" type="checkbox" name="os_ids[]" value="<?= $os['operating_software_id'] ?>" id="os_<?= $os['operating_software_id'] ?>" data-label="<?= $os['operating_software_name'] ?>" <?= (in_array($os['operating_software_id'], $selected_os_ids)) ? 'checked' : '' ?>>
                                                                    <label class="form-check-label w-100" for="os_<?= $os['operating_software_id'] ?>">
                                                                        <?= $os['operating_software_name'] ?>
                                                                    </label>
                                                                </div>
                                                            <?php endforeach; ?>
                                                        <?php endif; ?>
                                                    </div>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Resilience</label>

                                            <?php if($is_readonly): ?>
                                                <input type="text" class="form-control" value="<?= isset($row['resilience_category']) ? $row['resilience_category'] : '' ?>" readonly>

                                            <?php else: ?>
                                                <select class="form-control select2" name="resilience_id" id="resilience_id" data-placeholder="-- Select Resilience --">
                                                    <option>

                                                    </option>
                                                    <?php if(!empty($opt_resilience)): foreach($opt_resilience as $res): ?>
                                                        <option value="<?= $res['resilience_id'] ?>" data-dr="<?= $res['dr'] ?>" data-ha="<?= $res['ha'] ?>" <?= (isset($row['resilience_id']) && $row['resilience_id'] == $res['resilience_id']) ? 'selected' : '' ?>><?= $res['resilience_category'] ?></option>
                                                    <?php endforeach; endif; ?>
                                                </select>
                                            <?php endif; ?>
                                        </div>
                                    </div>

                                    <div class="col-md-1">
                                        <div class="form-group">
                                            <label>DR</label>
                                            <input type="text" id="dr_view" class="form-control" readonly placeholder="-" value="<?= isset($row['dr_availability']) ? $row['dr_availability'] : '' ?>">
                                        </div>
                                    </div>

                                    <div class="col-md-1">
                                        <div class="form-group">
                                            <label>HA</label>
                                            <input type="text" id="ha_view" class="form-control" readonly placeholder="-" value="<?= isset($row['ha']) ? $row['ha'] : '' ?>">
                                        </div>
                                    </div>

                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>Flash Copy</label>

                                            <?php if($is_readonly): ?>
                                                <input type="text" class="form-control" value="<?= isset($row['flash_copy']) ? $row['flash_copy'] : '' ?>" readonly>

                                            <?php else: ?>
                                                <select class="form-control select2" name="flash_copy" data-placeholder="-- Select Flash Copy--">
                                                    <option>

                                                    </option>
                                                    <option value="Y" <?= (isset($row['flash_copy']) && $row['flash_copy'] == 'Y') ? 'selected' : '' ?>>Y</option>
                                                    <option value="N" <?= (isset($row['flash_copy']) && $row['flash_copy'] == 'N') ? 'selected' : '' ?>>N</option>
                                                </select>
                                            <?php endif; ?>
                                        </div>
                                    </div>

                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>End of Day</label>

                                            <?php if($is_readonly): ?>
                                                <input type="text" class="form-control" value="<?= isset($row['end_of_day']) ? $row['end_of_day'] : '' ?>" readonly>

                                            <?php else: ?>
                                                <select class="form-control select2" name="end_of_day" data-placeholder="-- Select End of Day --">
                                                    <option>

                                                    </option>
                                                    <option value="Y" <?= (isset($row['end_of_day']) && $row['end_of_day'] == 'Y') ? 'selected' : '' ?>>Y</option>
                                                    <option value="N" <?= (isset($row['end_of_day']) && $row['end_of_day'] == 'N') ? 'selected' : '' ?>>N</option>
                                                </select>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>

                                <h6 class="infra-header"></h6>

                                <div class="row">   
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Principle Name</label>
                                            <input type="text" name="principle_name" class="form-control" value="<?= isset($row['principle_name']) ? $row['principle_name'] : '' ?>" <?= $is_readonly ? 'readonly' : '' ?>>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Principle Solution Name</label>
                                            <input type="text" name="principle_solution_name" class="form-control" value="<?= isset($row['principle_solution_name']) ? $row['principle_solution_name'] : '' ?>" <?= $is_readonly ? 'readonly' : '' ?>>
                                        </div>
                                    </div>
                                </div>

                                <h6 class="infra-header"></h6>

                                <div class="row">    
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>IT Group</label>
                                            <input type="text" name="it_group_name" class="form-control" value="<?= isset($row['it_group_name']) ? $row['it_group_name'] : '' ?>" <?= $is_readonly ? 'readonly' : '' ?>>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>IT Division</label>
                                            <input type="text" name="it_division_name" class="form-control" value="<?= isset($row['it_division_name']) ? $row['it_division_name'] : '' ?>" <?= $is_readonly ? 'readonly' : '' ?>>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">   
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label> Owner Directorate</label>
                                            <input type="text" name="owner_directorate" class="form-control" value="<?= isset($row['owner_directorate']) ? $row['owner_directorate'] : '' ?>" <?= $is_readonly ? 'readonly' : '' ?>>
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label> Owner Sub-Directorate</label>
                                            <input type="text" name="owner_subdirectorate" class="form-control" value="<?= isset($row['owner_subdirectorate']) ? $row['owner_subdirectorate'] : '' ?>" <?= $is_readonly ? 'readonly' : '' ?>>
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Owner Title</label>
                                            <input type="text" name="owner_title" class="form-control" value="<?= isset($row['owner_title']) ? $row['owner_title'] : '' ?>" <?= $is_readonly ? 'readonly' : '' ?>>
                                        </div>
                                    </div>
                                </div>

                            <?php endif; ?>

                        </div>

                        <div class="card-footer bg-white text-right border-top">
                            <input type="hidden" name="remarks" id="inputRemarks">
                            <input type="hidden" name="target_role_id" id="inputTargetRole">

                            <?php if($mode == 'add' || $mode == 'edit'): ?>
                                <button type="button" class="btn btn-secondary mr-2" onclick="submitForm('draft')">Save Draft</button>
                                <button type="button" class="btn btn-save-custom" onclick="submitForm('submit')">Submit</button>

                            <?php elseif($mode == 'review'): ?>
                                <?php if(in_array($rid, [3, 5, 7])): ?>
                                    <button type="button" class="btn btn-danger mr-2" onclick="submitForm('reject')">Reject</button>
                                    <button type="button" class="btn btn-success" onclick="submitForm('approve')">Approve</button>
                                <?php endif; ?>

                                <?php if($rid == 8): ?>
                                    <button type="button" class="btn btn-success" onclick="submitForm('approve')">Acknowledge</button>
                                <?php endif; ?>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card card-tabs shadow-sm" style="border-top: 3px solid #ffc107; height: 750px; max-height: 750px;">
                        <div class="card-header p-0 pt-1 border-bottom-0">
                             <ul class="nav nav-tabs" id="rightTabs" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link active" id="tab-timeline" data-toggle="pill" href="#content-timeline">Timeline</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="tab-audit" data-toggle="pill" href="#content-audit">Audit</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="tab-docs" data-toggle="pill" href="#content-docs">Documents</a>
                                </li>
                            </ul>
                        </div>
                        <div class="card-body">
                             <div class="tab-content">
                                 <div class="tab-pane fade show active" id="content-timeline">
                                     <div class="timeline-wrapper">
                                     <?php 
                                        $stages = [2 => 'EA Apps Inputter', 3 => 'EA Apps Approval', 4 => 'EA Infra Inputter', 5 => 'EA Infra Approval', 6 => 'BU Inputter', 7 => 'BU Approval', 8 => 'IT Dev'];
                                        $reject_signatures = [];
                                        if(!empty($timeline)) {
                                            foreach($timeline as $t) {
                                                if(!empty($t['modified_at']) && !empty($t['modified_by'])) {
                                                    $sig = $t['modified_at'] . '|' . $t['modified_by'];
                                                    if(!isset($reject_signatures[$sig])) $reject_signatures[$sig] = 0;
                                                    $reject_signatures[$sig]++;
                                                }
                                            }
                                        }

                                        foreach($stages as $rid_key => $label):
                                            $myData = null;
                                            if(!empty($timeline)) {
                                                foreach($timeline as $t) { if($t['user_role_id'] == $rid_key) { $myData = $t; break; } }
                                            }
                                            $containerClass = ''; $markerClass = ''; $textClass = ''; $badgeLabel = ''; 
                                            $showDetails = false; $displayDate = ''; $displayRemarks = '';

                                            if($myData) {
                                                $mySig = (!empty($myData['modified_at']) && !empty($myData['modified_by'])) ? $myData['modified_at'].'|'.$myData['modified_by'] : '';
                                                $isRejectEvent = ($mySig && isset($reject_signatures[$mySig]) && $reject_signatures[$mySig] > 1);

                                                if($myData['status'] == 1) {
                                                    $containerClass = 'passed'; $markerClass = 'bg-success'; 
                                                    $rawDate = !empty($myData['submit_date']) ? $myData['submit_date'] : $myData['modified_at'];
                                                    $displayDate = date('d M Y H:i', strtotime($rawDate));
                                                    $displayRemarks = $myData['remarks'];
                                                    $showDetails = true;
                                                } elseif($myData['status'] == 0 && $isRejectEvent) {
                                                    $markerClass = 'bg-danger'; $containerClass = 'rejected';
                                                    if(in_array($rid_key, [3, 5, 7, 8])) { $displayRemarks = $myData['remarks']; $showDetails = true; } 
                                                    else { $containerClass = 'active'; $markerClass = 'bg-warning'; }
                                                    $displayDate = date('d M Y H:i', strtotime($myData['modified_at']));
                                                } elseif($myData['current'] == 1) {
                                                    $containerClass = 'active'; $markerClass = 'bg-warning'; 
                                                }
                                            }
                                     ?>
                                     <div class="timeline-item <?= $containerClass ?>">
                                         <div class="timeline-marker  <?= $markerClass ?>"></div>
                                         <div class="timeline-content">
                                              <span class="timeline-title <?= $textClass ?>"><?= $label ?> <?= $badgeLabel ?>
                                              </span>
                                              <?php if($showDetails): ?>
                                                  <span class="timeline-date <?= $textClass ?>"><?= $displayDate ?></span>
                                                  <?php if(!empty($displayRemarks)): ?><div class="text-muted small mt-1 <?= $textClass ?>"><i class="fas fa-comment-dots mr-1"></i> "<?= $displayRemarks ?>"</div><?php endif; ?>
                                              <?php endif; ?>
                                         </div>
                                     </div>
                                        <?php endforeach; ?>
                                     </div>
                                 </div>

                                 <div class="tab-pane fade" id="content-audit">
                                     <table class="table table-sm text-sm">
                                         <thead>
                                            <tr>
                                                <th>Date</th>
                                                <th>User Role</th>
                                                <th>Action</th>
                                                <th>Remarks</th>
                                            </tr>
                                        </thead>
                                         
                                        <tbody>
                                            <?php if(!empty($audit_trail)): foreach($audit_trail as $aud): ?>
                                            <tr>
                                                <td><?= date('d/m/y H:i', strtotime($aud['submit_date'])) ?></td>
                                                <td><?= $aud['role_name'] ?></td>
                                                <td><?php if($aud['status'] == 1): ?>
                                                    <span class="badge badge-success">Approved/Submitted</span>
                                                    <?php else: ?>
                                                    <span class="badge badge-danger">Rejected/Revision</span><?php endif; ?>
                                                </td>
                                                <td><?= $aud['remarks'] ?></td>
                                            </tr>
                                             <?php endforeach; else: ?>
                                            <tr>
                                                <td colspan="4" class="text-center text-muted">No history yet.</td>
                                            </tr><?php endif; ?>
                                        </tbody>
                                     </table>
                                 </div>

                                 <div class="tab-pane fade" id="content-docs">
                                     <p class="text-muted text-center py-4">No documents uploaded.</p>
                                 </div>
                             </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>

      </div>
    </section>
  </div>
  
  <?php $this->load->view('layout/footer'); ?>
</div>

<?php $this->load->view('layout/foot_links'); ?>

<script>
    var timelineData = <?= !empty($timeline) ? json_encode($timeline) : '[]' ?>;
    var infraMapping = <?= isset($infra_mapping) ? $infra_mapping : '[]' ?>;
    var serverOptions = <?= isset($opt_server) ? json_encode($opt_server) : '[]' ?>;
    var resilienceOptions = <?= isset($opt_resilience) ? json_encode($opt_resilience) : '[]' ?>;
    var savedInfraData = <?= isset($existing_infra_list) ? json_encode($existing_infra_list) : '[]' ?>;
    var isReadonly = <?= $is_readonly ? 'true' : 'false' ?>;
    var targetServiceId = <?= isset($service_id_param) ? $service_id_param : 0 ?>;

    $(document).ready(function() {
        $('.select2').select2({ 
            theme: 'bootstrap4', 
            width: '100%',
            minimumResultsForSearch: Infinity,
            placeholder: function(){ 
                return $(this).data('placeholder'); } 
        });

        // 2. Handler untuk Update Label Database & OS (Multiselect Checkbox)
        function updateMultiSelectLabels() {
            // Database Label Update
            let dbSelected = [];
            $('.db-checkbox:checked').each(function() { 
                dbSelected.push($(this).data('label')); 
            });
            $('#labelDB').text(dbSelected.length > 0 ? dbSelected.join(', ') : '-- Select Databases --');

            // OS Label Update
            let osSelected = [];
            $('.os-checkbox:checked').each(function() { 
                osSelected.push($(this).data('label')); 
            });
            $('#labelOS').text(osSelected.length > 0 ? osSelected.join(', ') : '-- Select OS --');
        }

        // Listener untuk perubahan checkbox
        $(document).on('change', '.db-checkbox, .os-checkbox', function() {
            updateMultiSelectLabels();
        });

        // Mencegah dropdown tertutup saat mengklik checkbox/label di dalamnya
        $(document).on('click', '.keep-open', function (e) {
            e.stopPropagation();
        });

        // Jalankan update label saat halaman pertama kali dimuat
        updateMultiSelectLabels();

        // TAMBAHAN JAVASCRIPT BUAT FIELD DR DAN HA
        $('#resilience_id').on('change', function() {
            // Ambil data dari atribut data-dr dan data-ha yang ada di option
            const selectedOption = $(this).find(':selected');
            const dr = selectedOption.data('dr');
            const ha = selectedOption.data('ha');

            // Isi ke input field view (ID sesuai dengan HTML yang Anda berikan sebelumnya)
            $('#dr_view').val(dr ? dr : '-');
            $('#ha_view').val(ha ? ha : '-');
        });
        
        function renderServiceRow(serviceId, serviceName, data = null) {
            let slaStandard = $('#global_sla_standard').val();
            let uniqueId = serviceId; 

            let v_server_id = data ? data.server_id : '';
            let v_pw = data ? data.server_web_prod_count : 0;
            let v_pa = data ? data.server_app_prod_count : 0;
            let v_pd = data ? data.server_db_prod_count : 0;
            let v_dw = data ? data.server_web_dr_count : 0;
            let v_da = data ? data.server_app_dr_count : 0;
            let v_dd = data ? data.server_db_dr_count : 0;
            let v_resilience_id = data ? data.resilience_id : ''; 
            let v_resilience_cat_text = data ? (data.resilience_category || '-') : '-';

            let serverOptsHtml = '<option></option>'; 
            serverOptions.forEach(function(srv){
                let selected = (v_server_id == srv.server_id) ? 'selected' : '';
                serverOptsHtml += `<option value="${srv.server_id}" data-sla="${srv.server_sla}" ${selected}>${srv.server_name}</option>`;
            });

            let resilienceOptsHtml = '<option></option>';
            resilienceOptions.forEach(function(res){
                let selected = (v_resilience_id == res.resilience_id) ? 'selected' : '';
                resilienceOptsHtml += `<option value="${res.resilience_id}" data-cat="${res.resilience_category}" ${selected}>${res.resilience_category}</option>`;
            });

            let serverInput = isReadonly 
                ? `<input type="hidden" name="infra[${uniqueId}][server_id]" value="${v_server_id}"><input type="text" class="form-control form-control-sm" value="${data ? (data.server_type_name || '-') : '-'}" readonly>` 
                : `<select name="infra[${uniqueId}][server_id]" class="form-control form-control-sm server-select calc-trigger select2-dynamic" required>${serverOptsHtml}</select>`;

            let resilienceInput = isReadonly
                ? `<input type="hidden" name="infra[${uniqueId}][resilience_id]" value="${v_resilience_id}"><input type="text" class="form-control form-control-sm resilience-select" value="${v_resilience_cat_text}" readonly>`
                : `<select name="infra[${uniqueId}][resilience_id]" class="form-control form-control-sm resilience-select calc-trigger select2-dynamic" required>${resilienceOptsHtml}</select>`;
                    let name_to_use = name === 'dr_dd' ? 'dr_db' : name; 

            const mkInp = (name, val) => isReadonly 
                ? `<input type="hidden" name="infra[${uniqueId}][${name === 'dr_dd' ? 'dr_db' : name}]" value="${val}"><input type="number" class="form-control form-control-sm" value="${val}" readonly>`
                : `<input type="number" name="infra[${uniqueId}][${name === 'dr_dd' ? 'dr_db' : name}]" class="form-control form-control-sm calc-trigger ${name}-inp" min="0" value="${val}">`;

            let approveCbHtml = '';
            let isApprover = <?= ($rid == 5 && $mode == 'review') ? 'true' : 'false' ?>;
            if (isApprover) {
                approveCbHtml = `
                    <div class="mt-3 border-top pt-3 text-right">
                        <div class="custom-control custom-checkbox d-inline-block">
                            <input type="checkbox" class="custom-control-input approve-svc-cb" id="approve_svc_${uniqueId}">
                            <label class="custom-control-label text-success font-weight-bold" style="cursor:pointer; font-size:1.1em;" for="approve_svc_${uniqueId}">Saya menyetujui (Approve) Service ini</label>
                        </div>
                    </div>
                `;
            }

            let html = `
            <div class="card border-secondary mb-3 shadow-sm service-row" data-id="${uniqueId}">
                <div class="card-header bg-light py-2" style="border-top: 3px solid var(--theme-yellow-primary);">
                    <h6 class="m-0 font-weight-bold"><i class="fas fa-server mr-1"></i> Service: ${serviceName}</h6>
                    <input type="hidden" name="infra[${uniqueId}][service_id]" value="${serviceId}">
                </div>
                <div class="card-body p-3">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="mb-1 small font-weight-bold">Server Type <span class="text-danger">*</span></label>
                            ${serverInput}
                        </div>
                        <div class="col-md-6">
                            <label class="mb-1 small font-weight-bold">Resilience <span class="text-danger">*</span></label>
                            ${resilienceInput}
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-3"><div class="form-group mb-1"><label class="small text-muted">Prod Web</label>${mkInp('prod_web', v_pw)}</div></div>
                        <div class="col-md-3"><div class="form-group mb-1"><label class="small text-muted">Prod Apps</label>${mkInp('prod_apps', v_pa)}</div></div>
                        <div class="col-md-3"><div class="form-group mb-1"><label class="small text-muted">Prod DB</label>${mkInp('prod_db', v_pd)}</div></div>
                        <div class="col-md-3"><div class="form-group mb-1"><label class="small text-muted font-weight-bold">SLA SVR PROD</label>
                            <input type="text" class="form-control form-control-sm sla-prod-disp calc-field" readonly value="0.00%"></div>
                        </div>
                    </div>

                    <div class="row border-bottom pb-3 mb-2">
                        <div class="col-md-3"><div class="form-group mb-1"><label class="small text-muted">DR Web</label>${mkInp('dr_web', v_dw)}</div></div>
                        <div class="col-md-3"><div class="form-group mb-1"><label class="small text-muted">DR Apps</label>${mkInp('dr_apps', v_da)}</div></div>
                        <div class="col-md-3"><div class="form-group mb-1"><label class="small text-muted">DR DB</label>${mkInp('dr_dd', v_dd)}</div></div>
                        <div class="col-md-3"><div class="form-group mb-1"><label class="small text-muted font-weight-bold">SLA SVR DR</label>
                            <input type="text" class="form-control form-control-sm sla-dr-disp calc-field" readonly value="0.00%"></div>
                        </div>
                    </div>

                    <div class="row bg-light rounded p-2 align-items-center">
                        <div class="col-md-4">
                            <div class="form-group mb-1">
                                <label class="small font-weight-bold mb-1">SLA SCCA Standard</label>
                                <input type="text" class="form-control form-control-sm sla-std-disp font-weight-bold" value="${parseFloat(slaStandard).toFixed(2)}%" readonly>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group mb-1">
                                <label class="small font-weight-bold mb-1">SLA Actual</label>
                                <input type="text" class="form-control form-control-sm sla-actual-disp font-weight-bold" readonly value="0.00%">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group mb-1">
                                <label class="small font-weight-bold mb-1">Readiness</label>
                                <input type="text" class="form-control form-control-sm readiness-disp font-weight-bold" readonly value="-">
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group mb-1">
                                <label class="small font-weight-bold mb-1">Suggestion</label>
                                <input type="text" class="form-control form-control-sm suggestion-disp" readonly value="-">
                            </div>
                        </div>
                    </div>
                    ${approveCbHtml}
                </div>
            </div>`;

            $('#dynamic_infra_container').append(html);

            // Inisialisasi Select2 pada elemen yang baru saja di-append
            if (!isReadonly) {
                // Inisialisasi khusus dengan Placeholder
                $(`.service-row[data-id="${uniqueId}"] .server-select`).select2({
                    theme: 'bootstrap4',
                    width: '100%',
                    placeholder: "-- Select Server Type --",
                    allowClear: true,
                    minimumResultsForSearch: Infinity
                }).on('change', function() { $(this).trigger('keyup'); }); // Pemicu kalkulasi

                $(`.service-row[data-id="${uniqueId}"] .resilience-select`).select2({
                    theme: 'bootstrap4',
                    width: '100%',
                    placeholder: "-- Select Resilience --",
                    allowClear: true,
                    minimumResultsForSearch: Infinity
                }).on('change', function() { $(this).trigger('keyup'); }); // Pemicu kalkulasi
            }
        }

        $('#module_id_select').change(function() {
            if (targetServiceId > 0) return; 

            let modId = $(this).val();
            $('#dynamic_infra_container').empty(); 

            if(modId) {
                let services = [];
                let seen = new Set();
                
                infraMapping.forEach(function(item){
                    if(item.module_id == modId && !seen.has(item.service_id)) {
                        services.push({id: item.service_id, name: item.service_name});
                        seen.add(item.service_id);
                    }
                });

                if(services.length > 0) {
                    services.forEach(function(svc){
                        renderServiceRow(svc.id, svc.name);
                    });
                    recalculateAll();
                } else {
                    $('#dynamic_infra_container').html('<div class="alert alert-warning">No services found for this module.</div>');
                }
            }
        });

        let initModId = $('#module_id_select').val() || $('#module_id_hidden').val();
        if(initModId) {
            if(savedInfraData && savedInfraData.length > 0) {
                 $('#dynamic_infra_container').empty();
                 savedInfraData.forEach(function(data){
                     renderServiceRow(data.service_id, data.service_name, data);
                 });
                 recalculateAll(); 
            } else if (!isReadonly && targetServiceId == 0) {
                $('#module_id_select').trigger('change');
            }
        }

        $(document).on('change keyup', '.calc-trigger', function() {
            let row = $(this).closest('.service-row');
            // Ambil value semua field wajib
            let srvType = row.find('.server-select').val();
            let resId   = row.find('.resilience-select').val();
            let pw = row.find('.prod_web-inp').val();
            let pa = row.find('.prod_apps-inp').val();
            let pd = row.find('.prod_db-inp').val();
            let dw = row.find('.dr_web-inp').val();
            let da = row.find('.dr_apps-inp').val();
            let dd = row.find('.dr_dd-inp').val();

            // Cek apakah semua field sudah diisi
            if (srvType && resId && pw !== "" && pa !== "" && pd !== "" && dw !== "" && da !== "" && dd !== "") {
                calculateRowSLA(row);
            } else {
                // Reset ke default jika data belum lengkap
                row.find('.sla-prod-disp, .sla-dr-disp, .sla-actual-disp').val('0.00%');
                row.find('.readiness-disp').val('-').css('color', '#6c757d'); // Warna abu-abu standar
            }
        });

        function recalculateAll() {
            $('.service-row').each(function() { calculateRowSLA($(this)); });
        }

        function calculateRowSLA(row) {
            let select = row.find('.server-select option:selected');
            let slaBase = 0;

            if(select.length) {
                slaBase = parseFloat(select.data('sla')) || 0;
            } else {
                let sId = row.data('id');
                let found = savedInfraData.find(d => d.service_id == sId);
                if(found) slaBase = parseFloat(found.server_sla) || 0;
            }

            if (slaBase === 0) {
                row.find('.sla-prod-disp, .sla-dr-disp, .sla-actual-disp').val('0.00%');
                row.find('.readiness-disp').val('-').css('color', '#6c757d');
                return; 
            }

            slaBase = slaBase / 100.0;

            const getVal = (cls) => parseInt(row.find('input.'+cls).val()) || parseInt(row.find('input[name*="['+cls.split('-')[0]+']"]').val()) || 0;
            let pw = getVal('prod_web-inp');
            let pa = getVal('prod_apps-inp');
            let pd = getVal('prod_db-inp');
            let dw = getVal('dr_web-inp');
            let da = getVal('dr_apps-inp');
            let dd = getVal('dr_dd-inp');

            const calcComp = (cnt, sla) => (cnt <= 0) ? 0 : 1 - Math.pow((1 - sla), cnt);
            const mulNonZero = (arr) => {
                let f = arr.filter(v => v > 0);
                return (f.length === 0) ? 0 : f.reduce((a,b)=>a*b, 1);
            };

            let sla_prod = mulNonZero([calcComp(pw, slaBase), calcComp(pa, slaBase), calcComp(pd, slaBase)]);
            let sla_dr   = mulNonZero([calcComp(dw, slaBase), calcComp(da, slaBase), calcComp(dd, slaBase)]);

            row.find('.sla-prod-disp').val((sla_prod * 100).toFixed(2) + '%');
            row.find('.sla-dr-disp').val((sla_dr * 100).toFixed(2) + '%');

            let resCat = '';
            if(!isReadonly) {
                let resSelected = row.find('.resilience-select option:selected');
                resCat = resSelected.data('cat') || 'L0'; 
            } else {
                resCat = row.find('input.resilience-select').val() || 'L0';
            }

            let N = (resCat.trim() === 'L0') ? 1 : 2;
            let diff = sla_prod - sla_dr;
            if(diff < 0) diff = 0; 
            
            let sla_actual = 1 - Math.pow(diff, N);
            row.find('.sla-actual-disp').val((sla_actual * 100).toFixed(2) + '%');

            let stdStr = row.find('.sla-std-disp').val() || '0';
            let stdVal = parseFloat(stdStr.replace('%','')) / 100.0;
            
            if(stdVal > 0) {
                let status = (sla_actual < stdVal) ? 'Not Comply' : 'Comply';
                let color = (status === 'Not Comply') ? '#dc3545' : '#28a745';
                row.find('.readiness-disp').val(status).css('color', color);

                // LOGIKA SUGGESTION SESUAI CONTROLLER
                if (status === 'Not Comply') {
                    row.find('.suggestion-disp').val('Assesment kembali konfigurasi infra atau kategori kualitas aplikasi').css('color', '#dc3545');
                } else {
                    row.find('.suggestion-disp').val('-').css('color', '#6c757d');
                }
            } else {
                // Reset jika standard tidak ada
                row.find('.readiness-disp').val('-').css('color', '#6c757d');
                row.find('.suggestion-disp').val('-').css('color', '#6c757d');
            }
        }

        window.submitForm = function(type) {
            $('#saveType').val(type);
            var isInfra = <?= $is_infra ? 'true' : 'false' ?>;
            var isBu    = <?= $is_bu ? 'true' : 'false' ?>;

            if(type === 'draft') {
                if(isInfra) {
                    if($('.service-row').length === 0) {
                        Swal.fire({ icon: 'warning', title: 'Data Belum Lengkap', text: 'Pilih Module Name terlebih dahulu.' });
                        return;
                    }

                    let serverTypeEmpty = false;
                    $('.server-select').each(function() {
                        if(!$(this).val()) {
                            serverTypeEmpty = true;
                            $(this).addClass('is-invalid');
                        } else {
                            $(this).removeClass('is-invalid');
                        }
                    });

                    if(serverTypeEmpty) {
                        Swal.fire({ icon: 'warning', title: 'Data Belum Lengkap', text: 'Untuk menyimpan Draft, semua dropdown Server Type pada service harus dipilih.' });
                        return;
                    }
                } 
                else if (!isBu) { 
                    let appName = $('input[name="application_name"]').val();
                    let catId   = $('select[name="category_id"]').val();
                    if(!appName || !catId) {
                        Swal.fire({ icon: 'warning', title: 'Data Belum Lengkap', text: 'Application Name dan Category wajib diisi.' });
                        return;
                    }
                }
                
                $('#formDetail :input[required]').prop('required', false);
                
                Swal.fire({
                    icon: 'success', title: 'Success', text: 'Draft saved successfully.',
                    confirmButtonText: 'OK', confirmButtonColor: '#28a745', allowOutsideClick: false
                }).then((result) => { if (result.isConfirmed) document.getElementById('formDetail').submit(); });
                return;
            }

            if(type !== 'reject') { 
                let isValid = true;
                
                $('#formDetail').find('input:visible, select, textarea').not('input[type=hidden]').not('input[type=file]').each(function() {
                    if($(this).attr('type') === 'checkbox') return;
                    if($(this).prop('required') && (!$(this).val() || $(this).val().toString().trim() === '')) {
                        isValid = false; 
                        $(this).addClass('is-invalid');
                    } else { 
                        $(this).removeClass('is-invalid'); 
                    }
                });

                if(isInfra && type === 'submit') {
                    $('.server-select, .resilience-select').each(function() {
                        if(!$(this).val()) { isValid = false; $(this).addClass('is-invalid'); }
                    });

                    let hasAllZeroCounts = false;
                    $('.service-row').each(function() {
                        let pw = parseInt($(this).find('.prod_web-inp').val()) || 0;
                        let pa = parseInt($(this).find('.prod_apps-inp').val()) || 0;
                        let pd = parseInt($(this).find('.prod_db-inp').val()) || 0;
                        let dw = parseInt($(this).find('.dr_web-inp').val()) || 0;
                        let da = parseInt($(this).find('.dr_apps-inp').val()) || 0;
                        let dd = parseInt($(this).find('.dr_dd-inp').val()) || 0;

                        if (pw === 0 && pa === 0 && pd === 0 && dw === 0 && da === 0 && dd === 0) {
                            hasAllZeroCounts = true;
                            $(this).find('.prod_web-inp, .prod_apps-inp, .prod_db-inp, .dr_web-inp, .dr_apps-inp, .dr_dd-inp').addClass('is-invalid');
                        } else {
                            $(this).find('.prod_web-inp, .prod_apps-inp, .prod_db-inp, .dr_web-inp, .dr_apps-inp, .dr_dd-inp').removeClass('is-invalid');
                        }
                    });

                    if (hasAllZeroCounts) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Data Tidak Valid',
                            text: 'Saat melakukan Submit, data jumlah Server (Prod/DR) tidak boleh semuanya bernilai 0 pada masing-masing service.'
                        });
                        return; 
                    }
                }

                if(!isValid) {
                     Swal.fire({ icon: 'error', title: 'Data Belum Lengkap', text: 'Mohon lengkapi semua field yang wajib diisi.' });
                     return; 
                }
            }

            if (type === 'approve') {
                let isApprover = <?= ($rid == 5 && $mode == 'review') ? 'true' : 'false' ?>;
                if(isApprover) {
                    let totalSvcs = $('.approve-svc-cb').length;
                    let checkedSvcs = $('.approve-svc-cb:checked').length;
                    
                    if(totalSvcs > 0 && checkedSvcs < totalSvcs) {
                        Swal.fire({
                            icon: 'warning',
                            title: 'Approval Belum Lengkap',
                            text: 'Anda wajib melakukan persetujuan (centang) ke seluruh service yang ada. (' + checkedSvcs + ' dari ' + totalSvcs + ' dicentang).'
                        });
                        return;
                    }
                }
            }
            
            let title = type.charAt(0).toUpperCase() + type.slice(1);
            let confirmBtnColor = (type === 'reject') ? '#d33' : '#28a745';
            if(type === 'submit') confirmBtnColor = '#007bff';
            let iconType = (type === 'reject') ? 'warning' : 'question';

            let htmlContent = '<div class="text-left mb-2"><label class="font-weight-normal">Remarks <span class="text-danger">*</span></label>' +
                              '<textarea id="swal-remarks" class="form-control" rows="3" placeholder="Enter remarks..."></textarea></div>';
            
            if (type === 'reject') {
                let currentRid = parseInt("<?= isset($rid) ? $rid : 0 ?>"); 
                let targets = [];
                const isPassed = (targetRoleId) => {
                    let node = timelineData.find(t => t.user_role_id == targetRoleId);
                    return node && node.status == 1;
                };
                if (currentRid > 6 && isPassed(6)) { targets.push({id: 6, name: 'BU Inputter'}); }
                if (currentRid > 4 && isPassed(4)) { targets.push({id: 4, name: 'EA Infra Inputter'}); }
                if (currentRid > 2 && isPassed(2)) { targets.push({id: 2, name: 'EA Apps Inputter'}); }
                
                if (targets.length > 0) {
                    let dropdownHtml = '<div class="text-left mb-3"><label class="font-weight-normal">Reject To <span class="text-danger">*</span></label>';
                    dropdownHtml += '<select id="swal-target" class="form-control"><option value="">-- Select Inputter --</option>';
                    targets.forEach(function(t) { dropdownHtml += '<option value="'+t.id+'">'+t.name+'</option>'; });
                    dropdownHtml += '</select></div>';
                    htmlContent = dropdownHtml + htmlContent;
                } else {
                    htmlContent = '<div class="alert alert-warning text-sm">Cannot reject: No previous inputter found.</div>' + htmlContent;
                }
            }

            Swal.fire({
                title: title + ' Confirmation', html: htmlContent, icon: iconType, 
                showCancelButton: true, confirmButtonColor: confirmBtnColor, confirmButtonText: 'Yes, ' + type, reverseButtons: true,
                preConfirm: () => {
                    const remarks = Swal.getPopup().querySelector('#swal-remarks').value;
                    const targetEl = Swal.getPopup().querySelector('#swal-target');
                    const target = targetEl ? targetEl.value : null;
                    
                    if (!remarks) { Swal.showValidationMessage('Remarks is required!'); return false; }
                    if (type === 'reject' && document.getElementById('swal-target') && !target) { 
                        Swal.showValidationMessage('Please select a target.'); return false; 
                    }
                    return { remarks: remarks, target: target }
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    $('#inputRemarks').val(result.value.remarks);
                    if(result.value.target) $('#inputTargetRole').val(result.value.target);

                    // [PERUBAHAN]: Popup Sukses menampilkan tombol OK
                    Swal.fire({
                        icon: 'success', 
                        title: 'Success', 
                        text: 'Data berhasil disimpan.', 
                        confirmButtonText: 'OK', 
                        confirmButtonColor: '#28a745', 
                        allowOutsideClick: false 
                    }).then((res) => { 
                        if (res.isConfirmed) {
                            document.getElementById('formDetail').submit(); 
                        }
                    });
                }
            });
        }
    });
</script>
</body>
</html>