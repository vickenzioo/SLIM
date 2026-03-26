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

<div id="loadingOverlay">
    <div class="spinner-border" role="status"></div>
    <div class="mt-2 font-weight-bold" style="color: #333;">Processing...</div>
</div>

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
                    <div class="card shadow-sm" style="border-top: 3px solid var(--theme-yellow-primary); height: 650px; display: flex; flex-direction: column;">
                        
                        <div class="card-header bg-white border-bottom">
                            <div class="d-flex align-items-center">
                                <a href="<?= base_url('home') ?>" class="btn btn-secondary btn-sm mr-3" title="Back" onclick="sessionStorage.removeItem('portfolioTableScrollLeft');">
                                    <i class="fas fa-arrow-left"></i> Back
                                </a>
                                <h5 class="mb-0 font-weight-bold">
                                    <?= ($mode == 'add') ? 'Add New Portofolio' : 'Application Data' ?>
                                </h5>
                            </div>
                        </div>

                        <div class="card-body custom-scroll-kiri" style="flex: 1; overflow-y: auto; overflow-x: hidden; padding-right: 15px;">
                            
                            <h6 class="infra-header">General Information</h6>
							
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Application Name <span class="text-danger">*</span></label>
                                        <?php
                                            $keep_name = $this->input->get('keep_name');
                                            $flash_name = $this->session->flashdata('saved_app_name');
                                            
                                            $val_app_name = '';
                                            if ($keep_name == 1 && !empty($flash_name)) {
                                                $val_app_name = $flash_name;
                                            } 
                                            elseif (isset($row['application_name'])) {
                                                $val_app_name = $row['application_name'];
                                            }
                                        ?>
                                        <input type="text" name="application_name" class="form-control" required value="<?= $val_app_name ?>" <?= $is_readonly ? 'readonly' : '' ?>>
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
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>Module Name <span class="text-danger">*</span></label>
                                        <input type="text" name="module" class="form-control" required value="<?= isset($row['module']) ? $row['module'] : '' ?>" <?= $is_readonly ? 'readonly' : '' ?>>
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
							
							<div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Category</label>
                                        <?php if($is_readonly): ?>
                                            <input type="text" class="form-control" value="<?= isset($row['category_name']) ? $row['category_name'] : '' ?>" readonly style="background-color: #e9ecef; pointer-events: none;">
                                        <?php else: ?>
                                            <select class="form-control select2" name="category_id" data-placeholder="-- Select Category --" required>
                                                <option></option>
                                                <?php if(!empty($opt_category)): foreach($opt_category as $cat): ?>
                                                    <option value="<?= $cat['category_id'] ?>" <?= (isset($row['category_id']) && $row['category_id'] == $cat['category_id']) ? 'selected' : '' ?>><?= $cat['category_name'] ?></option>
                                                <?php endforeach; endif; ?>
                                            </select>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                
								<div class="col-md-6">
                                    <div class="form-group">
                                        <label>Standard Category (%)</label>
                                        <input type="number" step="0.01" name="standard_category" class="form-control" 
                                               value="<?= isset($row['standard_category']) ? $row['standard_category'] : '' ?>" 
                                               <?= ($is_readonly || $this->session->userdata('role_id') != 1) ? 'readonly' : '' ?>>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Application Type</label>
                                        <?php if($is_readonly): ?>
                                            <input type="text" class="form-control" 
                                                   value="<?= isset($row['app_type_name']) ? $row['app_type_name'] : '' ?>" 
                                                   readonly style="background-color: #e9ecef; pointer-events: none;">
                                        <?php else: ?>
                                            <select name="app_type_id" class="form-control select2" data-placeholder="-- Select App Type --">
                                                <option></option>
                                                <?php if(!empty($opt_app_type)): foreach($opt_app_type as $typ): ?>
                                                    <option value="<?= $typ['app_type_id'] ?>" <?= (isset($row['app_type_id']) && $row['app_type_id'] == $typ['app_type_id']) ? 'selected' : '' ?>>
                                                        <?= $typ['app_type_name'] ?>
                                                    </option>
                                                <?php endforeach; endif; ?>
                                            </select>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Server Type (Multiple)</label>
                                        <?php if($is_readonly): ?>
                                            <div class="p-2 border rounded bg-light" style="min-height: 38px;">
                                                <?= isset($row['server_names_str']) && !empty($row['server_names_str']) ? $row['server_names_str'] : '-' ?>
                                            </div>
                                        <?php else: ?>
                                            <div class="dropdown">
                                                <button class="btn btn-default dropdown-toggle w-100 d-flex justify-content-between align-items-center" type="button" id="dropdownSrv" data-toggle="dropdown">
                                                    <span id="labelSrv" class="text-truncate">-- Select Server --</span>
                                                </button>
                                                <div class="dropdown-menu w-100 p-2 keep-open" style="max-height: 250px; overflow-y: auto;">
                                                    <?php if(!empty($opt_server)): ?>
                                                        <?php foreach($opt_server as $srv): ?>
                                                            <div class="form-check mb-1">
                                                                <input class="form-check-input srv-checkbox" type="checkbox" name="server_ids[]" value="<?= $srv['server_id'] ?>" id="srv_<?= $srv['server_id'] ?>" data-label="<?= $srv['server_name'] ?>" <?= (in_array($srv['server_id'], $selected_srv_ids)) ? 'checked' : '' ?>>
                                                                <label class="form-check-label w-100" for="srv_<?= $srv['server_id'] ?>">
                                                                    <?= $srv['server_name'] ?>
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
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Solution Vendor</label>
                                        <input type="text" name="solution_vendor" class="form-control" value="<?= isset($row['solution_vendor']) ? $row['solution_vendor'] : '' ?>" <?= $is_readonly ? 'readonly' : '' ?>>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Services Vendor</label>
                                        <input type="text" name="services_vendor" class="form-control" value="<?= isset($row['services_vendor']) ? $row['services_vendor'] : '' ?>" <?= $is_readonly ? 'readonly' : '' ?>>
                                    </div>
                                </div>
                            </div>
							
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>Live Year</label>
                                        <input type="number" name="live_year" class="form-control" value="<?= isset($row['live_year']) ? $row['live_year'] : '' ?>" <?= $is_readonly ? 'readonly' : '' ?>>
                                    </div>
                                </div>
                            </div>
							
                            <h6 class="infra-header">Deployment Information</h6>

                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Deployment Model</label>
                                        <?php if($is_readonly): ?>
                                            <input type="text" class="form-control" value="<?= isset($row['deployment_model']) ? $row['deployment_model'] : '' ?>" readonly>
                                        <?php else: ?>
                                            <select class="form-control select2" name="deployment_id" data-placeholder="-- Select Model --">
                                                <option></option>
                                                <?php if(!empty($opt_deploy)): foreach($opt_deploy as $dep): ?>
                                                    <option value="<?= $dep['deployment_id'] ?>" <?= (isset($row['deployment_id']) && $row['deployment_id'] == $dep['deployment_id']) ? 'selected' : '' ?>><?= $dep['deployment_model'] ?></option>
                                                <?php endforeach; endif; ?>
                                            </select>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Deployment Provider</label>
                                        <?php if($is_readonly): ?>
                                            <input type="text" class="form-control" value="<?= isset($row['provider_name']) ? $row['provider_name'] : '' ?>" readonly>
                                        <?php else: ?>
                                            <select class="form-control select2" name="deployment_provider_id" data-placeholder="-- Select Provider --">
                                                <option></option>
                                                <?php if(!empty($opt_provider)): foreach($opt_provider as $prov): ?>
                                                    <option value="<?= $prov['deployment_provider_id'] ?>" <?= (isset($row['deployment_provider_id']) && $row['deployment_provider_id'] == $prov['deployment_provider_id']) ? 'selected' : '' ?>><?= $prov['deployment_provider_name'] ?></option>
                                                <?php endforeach; endif; ?>
                                            </select>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Deployment Site</label>
                                        <?php if($is_readonly): ?>
                                            <input type="text" class="form-control" value="<?= isset($row['site_name']) ? $row['site_name'] : '' ?>" readonly>
                                        <?php else: ?>
                                            <select class="form-control select2" name="deployment_site_id" data-placeholder="-- Select Site --">
                                                <option></option>
                                                <?php if(!empty($opt_site)): foreach($opt_site as $site): ?>
                                                    <option value="<?= $site['deployment_site_id'] ?>" <?= (isset($row['deployment_site_id']) && $row['deployment_site_id'] == $site['deployment_site_id']) ? 'selected' : '' ?>><?= $site['deployment_site_name'] ?></option>
                                                <?php endforeach; endif; ?>
                                            </select>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                            
                            <h6 class="infra-header">Ownership Information</h6>

                            <div class="row">   
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>LOB Directorate</label>
                                        <input type="text" name="lob_directorate" class="form-control" value="<?= isset($row['lob_directorate']) ? $row['lob_directorate'] : '' ?>" <?= $is_readonly ? 'readonly' : '' ?>>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>LOB Sub-Directorate</label>
                                        <input type="text" name="lob_subdirectorate" class="form-control" value="<?= isset($row['lob_subdirectorate']) ? $row['lob_subdirectorate'] : '' ?>" <?= $is_readonly ? 'readonly' : '' ?>>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
								<div class="col-md-4">
                                    <div class="form-group">
                                        <label>LOB Department Head</label>
                                        <input type="text" name="lob_department_head" class="form-control" value="<?= isset($row['lob_department_head']) ? $row['lob_department_head'] : '' ?>" <?= $is_readonly ? 'readonly' : '' ?>>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>LOB Group</label>
                                        <input type="text" name="lob_group" class="form-control" value="<?= isset($row['lob_group']) ? $row['lob_group'] : '' ?>" <?= $is_readonly ? 'readonly' : '' ?>>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>LOB Group Head</label>
                                        <input type="text" name="lob_group_head" class="form-control" value="<?= isset($row['lob_group_head']) ? $row['lob_group_head'] : '' ?>" <?= $is_readonly ? 'readonly' : '' ?>>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>IT Sub-Directorate</label>
                                        <input type="text" name="it_subdirectorate" class="form-control" value="<?= isset($row['it_subdirectorate']) ? $row['it_subdirectorate'] : '' ?>" <?= $is_readonly ? 'readonly' : '' ?>>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>IT Department Head</label>
                                        <input type="text" name="it_department_head" class="form-control" value="<?= isset($row['it_department_head']) ? $row['it_department_head'] : '' ?>" <?= $is_readonly ? 'readonly' : '' ?>>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>IT Support Group</label>
                                        <input type="text" name="it_support_group" class="form-control" value="<?= isset($row['it_support_group']) ? $row['it_support_group'] : '' ?>" <?= $is_readonly ? 'readonly' : '' ?>>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>IT Group Head</label>
                                        <input type="text" name="it_group_head" class="form-control" value="<?= isset($row['it_group_head']) ? $row['it_group_head'] : '' ?>" <?= $is_readonly ? 'readonly' : '' ?>>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>IT Support Division</label>
                                        <input type="text" name="it_support_divison" class="form-control" value="<?= isset($row['it_support_divison']) ? $row['it_support_divison'] : '' ?>" <?= $is_readonly ? 'readonly' : '' ?>>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>IT Division Head</label>
                                        <input type="text" name="it_division_head" class="form-control" value="<?= isset($row['it_division_head']) ? $row['it_division_head'] : '' ?>" <?= $is_readonly ? 'readonly' : '' ?>>
                                    </div>
                                </div>
                            </div>
                            
                            <h6 class="infra-header">Technical Information</h6>

                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>App Version</label>
                                        <input type="text" name="application_version" class="form-control" value="<?= isset($row['application_version']) ? $row['application_version'] : '' ?>" <?= $is_readonly ? 'readonly' : '' ?>>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Dev Language</label>
                                        <input type="text" name="development_language" class="form-control" value="<?= isset($row['development_language']) ? $row['development_language'] : '' ?>" <?= $is_readonly ? 'readonly' : '' ?>>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>App Developer</label>
                                        <input type="text" name="application_developer" class="form-control" value="<?= isset($row['application_developer']) ? $row['application_developer'] : '' ?>" <?= $is_readonly ? 'readonly' : '' ?>>
                                    </div>
                                </div>
                            </div>
                            
							<div class="row">
								<div class="col-md-6">
                                    <div class="form-group">
                                        <label>Operating Software (Multiple)</label>
                                        <?php if($is_readonly): ?>
                                            <div class="p-2 border rounded bg-light" style="min-height: 38px;">
                                                <?= isset($row['os_names_str']) && !empty($row['os_names_str']) ? $row['os_names_str'] : '-' ?>
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
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Database (Multiple)</label>
                                        <?php if($is_readonly): ?>
                                            <div class="p-2 border rounded bg-light" style="min-height: 38px;">
                                                <?= isset($row['database_names_str']) && !empty($row['database_names_str']) ? $row['database_names_str'] : '-' ?>
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
                            </div>
							
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Supporting Web Server</label>
                                        <input type="text" name="supporting_web_server" class="form-control" value="<?= isset($row['supporting_web_server']) ? $row['supporting_web_server'] : '' ?>" <?= $is_readonly ? 'readonly' : '' ?>>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Supporting App Server</label>
                                        <input type="text" name="supporting_application_server" class="form-control" value="<?= isset($row['supporting_application_server']) ? $row['supporting_application_server'] : '' ?>" <?= $is_readonly ? 'readonly' : '' ?>>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Supporting Others</label>
                                        <input type="text" name="supporting_others" class="form-control" value="<?= isset($row['supporting_others']) ? $row['supporting_others'] : '' ?>" <?= $is_readonly ? 'readonly' : '' ?>>
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
                                                <option></option>
                                                <?php if(!empty($opt_resilience)): foreach($opt_resilience as $res): ?>
                                                    <option value="<?= $res['resilience_id'] ?>" data-dr="<?= $res['dr'] ?>" data-ha="<?= $res['ha'] ?>" <?= (isset($row['resilience_id']) && $row['resilience_id'] == $res['resilience_id']) ? 'selected' : '' ?>><?= $res['resilience_category'] ?></option>
                                                <?php endforeach; endif; ?>
                                            </select>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>DR Availability</label>
                                        <input type="text" id="dr_view" class="form-control" readonly placeholder="-" value="<?= isset($row['dr_availability']) ? $row['dr_availability'] : '' ?>">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>HA</label>
                                        <input type="text" id="ha_view" class="form-control" readonly placeholder="-" value="<?= isset($row['ha']) ? $row['ha'] : '' ?>">
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Network</label>
                                        <?php if($is_readonly): ?>
                                            <input type="text" class="form-control" value="<?= isset($row['network_name']) ? $row['network_name'] : '' ?>" readonly>
                                        <?php else: ?>
                                            <select class="form-control select2" name="network_id" data-placeholder="-- Select Network --">
                                                <option></option>
                                                <?php if(!empty($opt_network)): foreach($opt_network as $net): ?>
                                                    <option value="<?= $net['network_id'] ?>" <?= (isset($row['network_id']) && $row['network_id'] == $net['network_id']) ? 'selected' : '' ?>><?= $net['network_name'] ?></option>
                                                <?php endforeach; endif; ?>
                                            </select>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                
                                <?php 
                                    $is_op_readonly = $is_readonly || ($rid != 1); 
                                ?>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Operational Day</label>
                                        <?php if($is_op_readonly): ?>
                                            <input type="text" class="form-control" value="<?= isset($row['operational_day_full']) ? $row['operational_day_full'] : '-' ?>" readonly>
                                            <input type="hidden" name="operational_day_id" value="<?= isset($row['operational_day_id']) ? $row['operational_day_id'] : '' ?>">
                                        <?php else: ?>
                                            <select name="operational_day_id" class="form-control select2" data-placeholder="-- Select Operational Day --">
                                                <option></option>
                                                <?php if(!empty($opt_day)): foreach($opt_day as $d): ?>
                                                    <option value="<?= $d['operational_day_id'] ?>" <?= (isset($row['operational_day_id']) && $row['operational_day_id'] == $d['operational_day_id']) ? 'selected' : '' ?>><?= $d['start_day'] . ' - ' . $d['end_day'] ?></option>
                                                <?php endforeach; endif; ?>
                                            </select>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Operational Hour</label>
                                        <?php if($is_op_readonly): ?>
                                            <input type="text" class="form-control" value="<?= isset($row['operational_hour_full']) ? $row['operational_hour_full'] : '-' ?>" readonly>
                                            <input type="hidden" name="operational_hour_id" value="<?= isset($row['operational_hour_id']) ? $row['operational_hour_id'] : '' ?>">
                                        <?php else: ?>
                                            <select name="operational_hour_id" class="form-control select2" data-placeholder="-- Select Operational Hour --">
                                                <option></option>
                                                <?php if(!empty($opt_hour)): foreach($opt_hour as $h): ?>
                                                    <option value="<?= $h['operational_hour_id'] ?>" <?= (isset($row['operational_hour_id']) && $row['operational_hour_id'] == $h['operational_hour_id']) ? 'selected' : '' ?>><?= $h['start_time'] . ' - ' . $h['end_time'] ?></option>
                                                <?php endforeach; endif; ?>
                                            </select>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Source Code Owned</label>
                                        <?php if($is_readonly): ?>
                                            <input type="text" class="form-control" value="<?= isset($row['source_code_owned']) ? $row['source_code_owned'] : '' ?>" readonly>
                                        <?php else: ?>
                                            <select name="source_code_owned" class="form-control select2" data-placeholder="-- Select Yes/No --">
                                                <option></option>
                                                <option value="Yes" <?= (isset($row['source_code_owned']) && $row['source_code_owned'] == 'Yes') ? 'selected' : '' ?>>Yes</option>
                                                <option value="No" <?= (isset($row['source_code_owned']) && $row['source_code_owned'] == 'No') ? 'selected' : '' ?>>No</option>
                                            </select>
                                        <?php endif; ?>
                                    </div>
                                </div>
								<div class="col-md-8">
									<div class="form-group">
										<label>URL</label>
										<input type="text" name="Url" id="input_url" class="form-control" 
											   value="<?= isset($row['Url']) ? $row['Url'] : '' ?>" 
											   placeholder="contoh: www.domain.com" 
											   <?= $is_readonly ? 'readonly' : '' ?>>
											   
										<small id="url_error_msg" class="text-danger" style="display: none;">Format URL tidak valid.</small>
									</div>
								</div>
                            </div>
                        </div>

                        <div class="custom-card-footer bg-white text-right border-top">
                            <input type="hidden" name="remarks" id="inputRemarks">

                            <?php if($mode == 'add' || $mode == 'edit' || $mode == 'review'): ?>
                                <?php if($rid == 2): ?>
                                    <button type="button" class="btn btn-secondary" onclick="submitForm('save_stay')">Save</button>
                                <?php else: ?>
                                    <button type="button" class="btn btn-secondary mr-2" onclick="submitForm('draft')">Save Draft</button>
                                    <button type="button" class="btn btn-save-custom" onclick="submitForm('submit')">Submit</button>
                                <?php endif; ?>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                     <?php 
                        // Logic UPDATED: Cek apakah dokumen masih di posisi EA (rid 2) atau masih draft
                        $current_pos = 0;
                        if(!empty($timeline)) {
                            foreach($timeline as $t) {
                                if($t['current'] == 1) {
                                    $current_pos = $t['user_role_id'];
                                    break;
                                }
                            }
                        }
                        
                        // Kondisi tampilkan tab List: Role user adalah EA (2) DAN posisi dokumen juga di EA (2)
                        $show_list_for_ea = ($rid == 2 && ($current_pos == 2 || empty($timeline))); 
                        
                        // Logic UPDATED: Tentukan tab mana yang harus active secara default
                        // Jika tidak menampilkan List, maka Workflow harus active
                        $workflow_active = (!$show_list_for_ea) ? 'active' : ''; 
                    ?>


                    <div class="card card-tabs shadow-sm" style="border-top: 3px solid #ffc107; height: 650px; min-height: 650px; display: flex; flex-direction: column;">
                        
                        <div class="card-header p-0 pt-1 border-bottom-0" style="flex: 0 0 auto;">
                            <ul class="nav nav-tabs custom-scroll-tabs" id="rightTabs" role="tablist" style="flex-wrap: nowrap; overflow-x: auto; overflow-y: hidden; white-space: nowrap;">
                                
                                <?php if($show_list_for_ea): // UPDATED ?>
                                <li class="nav-item" style="flex: 0 0 auto;">
                                    <a class="nav-link active text-dark font-weight-bold" id="tab-list" data-toggle="pill" href="#content-list" title="List" style="display: flex; align-items: center; gap: 8px; padding: 12px 20px;">
                                        <i class="fas fa-clipboard-list" style="font-size: 1.1rem; color: #2c3e50;"></i>
                                        <span>List</span>
                                    </a>
                                </li>
                                <?php endif; ?>

                                <li class="nav-item" style="flex: 0 0 auto;">
                                    <a class="nav-link <?= (!$show_list_for_ea) ? 'active' : '' ?> text-dark font-weight-bold" id="tab-timeline" data-toggle="pill" href="#content-timeline" title="Workflow" style="display: flex; align-items: center; gap: 8px; padding: 12px 20px;">
                                        <i class="fas fa-stream" style="font-size: 1.1rem; color: #2c3e50;"></i>
                                        <span>Workflow</span>
                                    </a>
                                </li>

                                <li class="nav-item" style="flex: 0 0 auto;">
                                    <a class="nav-link text-dark font-weight-bold" id="tab-audit" data-toggle="pill" href="#content-audit" title="Audit" style="display: flex; align-items: center; gap: 8px; padding: 12px 20px;">
                                        <i class="fas fa-history" style="font-size: 1.1rem; color: #2c3e50;"></i>
                                        <span>Audit</span>
                                    </a>
                                </li>

                                <li class="nav-item" style="flex: 0 0 auto;">
                                    <a class="nav-link text-dark font-weight-bold" id="tab-docs" data-toggle="pill" href="#content-docs" title="SLA Doc" style="display: flex; align-items: center; gap: 8px; padding: 12px 20px;">
                                        <i class="fas fa-folder-open" style="font-size: 1.1rem; color: #2c3e50;"></i>
                                        <span>SLA Doc</span>
                                    </a>
                                </li>

                            </ul>
                        </div>
                        
                        <div class="card-body p-0" style="flex: 1 1 auto; overflow: hidden;">
                             <div class="tab-content h-100">
                                 
                                 <?php if($show_list_for_ea): // UPDATED ?>
                                 <div class="tab-pane fade show active h-100" id="content-list">
                                     <div class="d-flex flex-column h-100">
                                         
                                         <div class="table-responsive scrollable-card-body p-0" style="flex: 1 1 auto; overflow-y: auto; min-height: 0;">
                                             <table class="table table-bordered table-hover text-nowrap text-sm m-0 w-100">
                                                 <thead class="bg-light" style="position: sticky; top: 0; z-index: 1; font-size: 11px;">
                                                     <tr class= "bg-info">
                                                         <th style="width: 50px;" class="border-top-0 p-0 align-middle">
                                                             <div class="d-flex justify-content-center align-items-center" style="height: 100%; min-height: 0px;">
                                                                 <input type="checkbox" id="checkAllDrafts" title="Select All">
                                                             </div>
                                                         </th>
                                                         <th class="text-center align-middle border-top-0">App Name</th>
                                                         <th class="text-center align-middle border-top-0">Module</th>
                                                         <th style="width: 80px;" class="border-top-0 p-0 align-middle">
                                                             <div class="d-flex justify-content-center align-items-center" style="height: 100%; min-height: 0px;">
                                                                 Action
                                                             </div>
                                                         </th>
                                                     </tr>
                                                 </thead>
                                                 <tbody style="font-size: 11px;">
                                                    <?php if(!empty($draft_list)): foreach($draft_list as $d): ?>
                                                        <?php 
                                                            // Cek apakah ID di baris ini sama dengan ID yang ada di URL
                                                            // Properti 'table-active' secara default menggunakan background-color: rgba(0, 0, 0, 0.075)
                                                            $is_active = ($this->uri->segment(3) == $d['apps_id']) ? 'table-active-list' : ''; 
                                                        ?>
                                                        <tr class="<?= $is_active ?>">
                                                            <td class="align-middle p-0">
                                                                <div class="d-flex justify-content-center align-items-center" style="height: 100%; min-height: 0px;">
                                                                    <input type="checkbox" class="check-draft" value="<?= $d['apps_id'] ?>">
                                                                </div>
                                                            </td>
                                                            <td class="text-center align-middle"><?= $d['application_name'] ?: $d['short_name'] ?></td>
                                                            <td class="text-center align-middle"><?= $d['module_name'] ?></td>
                                                            <td class="align-middle p-0">
                                                                <div class="d-flex justify-content-center align-items-center" style="height: 100%; min-height: 0px;">
                                                                    <a href="<?= base_url('home/detail/'.$d['apps_id']) ?>" class="btn btn-xs btn-info mr-1" title="Edit Data" style="margin-bottom: 0;">
                                                                        <i class="fas fa-edit"></i>
                                                                    </a>
                                                                    <button type="button" class="btn btn-xs btn-danger" title="Delete Draft" onclick="deleteDraft(<?= $d['apps_id'] ?>)" style="margin-bottom: 0;">
                                                                        <i class="fas fa-trash"></i>
                                                                    </button>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                    <?php endforeach; else: ?>
                                                        <tr><td colspan="4" class="text-center text-muted py-4">Belum ada draft yang tersimpan.</td></tr>
                                                    <?php endif; ?>
                                                </tbody>
                                             </table>
                                         </div>
                                         <div class="custom-card-footer">
                                             <?php if(!empty($draft_list)): ?>
                                                 <div class=" custom-card-footer bg-white text-right border-top" style="flex: 0 0 auto; padding: 0.75rem 1.25rem;">
                                                     <button type="button" class="btn btn-save-custom w-100" onclick="bulkSubmit()">
                                                         <i class="fas fa-paper-plane mr-1"></i> Submit
                                                     </button>
                                                 </div>
                                             <?php endif; ?>
                                         </div>
                                     </div>
                                 </div>
                                 <?php endif; ?>

                                 <div class="tab-pane fade <?= $workflow_active ? 'show active' : '' ?> h-100" id="content-timeline">
                                    <div class="d-flex flex-column h-100">
                                         <div class="scrollable-card-body p-3 h-100" style="overflow-y: auto;">
                                             <div class="timeline-wrapper">
                                             <?php 
                                                $stages = [2 => 'EA', 3 => 'IT Dev', 1 => 'IT SLM'];
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
                                                            if(in_array($rid_key, [1, 3])) { $displayRemarks = $myData['remarks']; $showDetails = true; } 
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
                                                    <span class="timeline-title <?= $textClass ?>" style="font-size: 14px;"><?= $label ?> <?= $badgeLabel ?></span>
                                                    
                                                    <?php if($showDetails): ?>
                                                        <span class="timeline-date <?= $textClass ?>" style="font-size: 14px;"><?= $displayDate ?></span>
                                                        <?php if(!empty($displayRemarks)): ?>
                                                            <div class="text-muted small mt-1 <?= $textClass ?>" style="font-size: 14px;">
                                                                <i class="fas fa-comment-dots mr-1"></i> "<?= $displayRemarks ?>"
                                                            </div>
                                                        <?php endif; ?>
                                                    <?php endif; ?>
                                                </div>
                                             </div>
                                                <?php endforeach; ?>
                                             </div>
                                         </div>
                                        <div class="custom-card-footer"></div>
                                    </div>
                                </div>

                                <div class="tab-pane fade h-100" id="content-audit">
                                    <div class="d-flex flex-column h-100">
                                        <div class="table-responsive scrollable-card-body p-0" style="flex: 1 1 auto; overflow-y: auto; min-height: 0px;">
                                            <table class="table table-striped table-bordered table-hover text-sm m-0 w-100 text-center">
                                                <thead class="bg-light" style="position: sticky; top: 0; z-index: 1; font-size: 11px;">
                                                    <tr class = "bg-info">
                                                        <th class="text-center align-middle border-top-0">Timestamp</th>
                                                        <th class="text-center align-middle border-top-0">User Role</th>
                                                        <th class="text-center align-middle border-top-0">Action</th>
                                                        <th class="text-center align-middle border-top-0">Remarks</th>
                                                    </tr>
                                                </thead>
                                                <tbody style="font-size: 11px;">
                                                    <?php if(!empty($audit_trail)): ?>
                                                        <?php foreach($audit_trail as $aud): ?>
                                                            <tr>
                                                                <td class="align-middle">
                                                                    <?= !empty($aud['created_at']) ? date('d/m/y H:i', strtotime($aud['created_at'])) : '-' ?>
                                                                </td>
                                                                <td class="align-middle">
                                                                    <b><?= $aud['role_name'] ?></b>
                                                                </td>
                                                                <td class="align-middle text-center">
                                                                    <?php 
                                                                        // PERBAIKAN: Gunakan $aud bukan $row
                                                                        $action = strtoupper(trim($aud['action'])); 
                                                                        $bg = '#e9ecef'; $color = '#495057'; // Default Abu-abu

                                                                        switch ($action) {
                                                                            case 'SUBMIT':
                                                                                $bg = '#e8f5e9'; $color = '#2e7d32'; // Hijau
                                                                                break;
                                                                            case 'RENEWAL':
                                                                                $bg = '#e3f2fd'; $color = '#1565c0'; // Biru
                                                                                break;
                                                                            case 'DEACTIVATE':
                                                                                $bg = '#ffebee'; $color = '#c62828'; 
                                                                                break;
                                                                            case 'ACTIVATE':
                                                                                $bg = '#e0f2f1'; $color = '#00695c'; // Toska
                                                                                break;
                                                                            case 'DRAFT':
                                                                                $bg = '#fff3e0'; $color = '#ef6c00'; // Oranye
                                                                                break;
                                                                        }
                                                                    ?>
                                                                    <span class="badge px-3 py-2" style="background-color: <?= $bg ?>; color: <?= $color ?>; border-radius: 6px; font-size: 0.75rem; font-weight: 700; display: inline-block; min-width: 110px;">
                                                                        <?= $action ?>
                                                                    </span>
                                                                </td>
                                                                <td class="align-middle">
                                                                    <?= !empty($aud['remarks']) ? nl2br($aud['remarks']) : '-' ?>
                                                                </td>
                                                            </tr>
                                                        <?php endforeach; ?>
                                                    <?php else: ?>
                                                        <tr>
                                                            <td colspan="4" class="text-center text-muted py-4">
                                                                No history yet.
                                                            </td>
                                                        </tr>
                                                    <?php endif; ?>
                                                </tbody>
                                            </table>
                                        </div>
                                        <div class="custom-card-footer"></div>
                                    </div>
                                </div>

                                 <div class="tab-pane fade h-100" id="content-docs">
                                    <div class="d-flex flex-column h-100">
                                        <div class="table-responsive scrollable-card-body p-0" style="flex: 1 1 auto; overflow-y: auto; min-height: 0;">
                                            <table class="table table-striped table-bordered table-hover text-sm m-0 w-100 text-center">
                                                <thead class="bg-light" style="position: sticky; top: 0; z-index: 1; font-size: 11px;">
                                                    <tr class= "bg-info">
                                                        <th class="text-center align-middle border-top-0">Version</th>
                                                        <th class="text-center align-middle border-top-0">Date Uploaded</th>
                                                        <th class="border-top-0 p-0 align-middle">
                                                            <div class="d-flex justify-content-center align-items-center" style="height: 100%; min-height: 0px;">
                                                                Action
                                                            </div>
                                                        </th>
                                                    </tr>
                                                </thead>
                                                <tbody style="font-size: 11px;">
                                                    <?php if (!empty($sla_history)): ?>
                                                        <?php foreach ($sla_history as $history): ?>
                                                            <tr>
                                                                <td class="align-middle"><b>V<?= $history['version']; ?></b></td>
                                                                <td class="align-middle"><?= date('d M Y, H:i', strtotime($history['created_at'])); ?></td>
                                                                <td class="align-middle p-0">
                                                                    <div class="d-flex justify-content-center align-items-center" style="height: 100%; min-height: 0px;">
                                                                        <a href="javascript:void(0)" onclick="downloadSlaDoc('<?= base_url('home/download_sla_version/' . $history['file_name']); ?>')" class="btn btn-xs btn-outline-warning" title="Download Document" style="margin-bottom: 0;">
                                                                            <i class="fas fa-download"></i>
                                                                        </a>
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                        <?php endforeach; ?>
                                                    <?php else: ?>
                                                        <tr>
                                                            <td colspan="3" class="text-center text-muted py-4">
                                                                Belum ada riwayat dokumen SLA.
                                                            </td>
                                                        </tr>
                                                    <?php endif; ?>
                                                </tbody>
                                            </table>
                                        </div>
                                        <div class="custom-card-footer"></div>
                                    </div>

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
    var isReadonly = <?= $is_readonly ? 'true' : 'false' ?>;

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
    });
    
    $(document).ready(function() {
        // --- TAMBAHAN VALIDASI URL (LIVE CHECK) ---
        var urlPattern = /^(https?:\/\/)?([\w\d-]+\.)+[\w\d]{2,}(\/.*)?$/i;
        $('#input_url').on('blur keyup', function() {
            var urlValue = $(this).val().trim();
            if (urlValue !== '' && !urlPattern.test(urlValue)) {
                $('#url_error_msg').show();
                $(this).addClass('is-invalid');
            } else {
                $('#url_error_msg').hide();
                $(this).removeClass('is-invalid');
            }
        });
        // -------------------------------------------

        $('.select2').select2({ 
            theme: 'bootstrap4', 
            width: '100%',
            minimumResultsForSearch: 0,
            placeholder: function(){ 
                return $(this).data('placeholder'); } 
        });

        function updateMultiSelectLabels() {
            let dbSelected = [];
            $('.db-checkbox:checked').each(function() { 
                dbSelected.push($(this).data('label')); 
            });
            $('#labelDB').text(dbSelected.length > 0 ? dbSelected.join(', ') : '-- Select Databases --');

            let osSelected = [];
            $('.os-checkbox:checked').each(function() { 
                osSelected.push($(this).data('label')); 
            });
            $('#labelOS').text(osSelected.length > 0 ? osSelected.join(', ') : '-- Select OS --');
			
			let srvSelected = [];
            $('.srv-checkbox:checked').each(function() { 
                srvSelected.push($(this).data('label')); 
            });
            $('#labelSrv').text(srvSelected.length > 0 ? srvSelected.join(', ') : '-- Select Server --');
        }

        $(document).on('change', '.db-checkbox, .os-checkbox, .srv-checkbox', function() {
            updateMultiSelectLabels();
        });

        $(document).on('click', '.keep-open', function (e) {
            e.stopPropagation();
        });

        updateMultiSelectLabels();

        $('#formDetail').find('input, select, textarea').not('input[type=hidden], input[type=file]').each(function() {
            let el = $(this);
            let name = el.attr('name');
            if(name) {
                el.data('original', el.val() || '');
                if (el.hasClass('select2') || el.is('select')) {
                    el.data('original-text', el.find('option:selected').text().trim() || '');
                }
            }
        });
        $('#labelDB').data('original-text', $('#labelDB').text().trim());
        $('#labelOS').data('original-text', $('#labelOS').text().trim());
        $('#labelSrv').data('original-text', $('#labelSrv').text().trim());
        // ==========================================

        $('#resilience_id').on('change', function() {
            const selectedOption = $(this).find(':selected');
            const dr = selectedOption.data('dr');
            const ha = selectedOption.data('ha');

            $('#dr_view').val(dr ? dr : '-');
            $('#ha_view').val(ha ? ha : '-');
        });

        $(document).on('change', '#checkAllDrafts', function() {
            $('.check-draft').prop('checked', $(this).prop('checked'));
        });

        $(document).on('change', '.check-draft', function() {
            if ($('.check-draft:checked').length === $('.check-draft').length) {
                $('#checkAllDrafts').prop('checked', true);
            } else {
                $('#checkAllDrafts').prop('checked', false);
            }
        });
        
        window.submitForm = function(type) {
            $('#saveType').val(type);
            
            let liveYear = $('input[name="live_year"]').val();
            let decomYear = $('input[name="decommission_year"]').val();
            
            if (liveYear && decomYear) {
                if (parseInt(decomYear) <= parseInt(liveYear)) {
                    Swal.fire({ 
                        icon: 'error', 
                        title: 'Tahun Tidak Valid', 
                        text: 'Decommission Year harus lebih besar dari Live Year!' 
                    });
                    return; 
                }
            }

            // --- VALIDASI URL ---
            let urlInput = $('#input_url');
            if (urlInput.length > 0) {
                let urlValue = urlInput.val().trim();
                if (urlValue !== '' && !urlPattern.test(urlValue)) {
                    Swal.fire({ icon: 'error', title: 'Format URL Tidak Valid', text: 'Pastikan URL mengandung ekstensi yang benar seperti .com, .id, dll.' });
                    $('#url_error_msg').show();
                    urlInput.addClass('is-invalid').focus();
                    return; 
                }
            }

            let appName = $('input[name="application_name"]').val();
            let modName = $('input[name="module"]').val(); 

            if((type === 'draft' || type === 'save_stay') && (!appName || !modName)) {
                Swal.fire({ icon: 'warning', title: 'Data Belum Lengkap', text: 'Application Name dan Module Name wajib diisi untuk menyimpan data.' });
                return;
            }

            // =========================================================
            // AJAX PENGECEKAN DUPLIKAT SEBELUM PROSES SUBMIT
            // =========================================================
            if (appName && modName) {
                $('#loadingOverlay').css('display', 'flex');
                $.ajax({
                    url: '<?= base_url("home/check_duplicate_ajax") ?>',
                    type: 'POST',
                    dataType: 'json',
                    data: { 
                        application_name: appName, 
                        module: modName, 
                        apps_id: <?= isset($apps_id) ? $apps_id : 0 ?> 
                    },
                    success: function(res) {
                        $('#loadingOverlay').css('display', 'none');
                        if(res.is_duplicate) {
                            // JIKA DUPLIKAT: Muncul popup dan hentikan proses (Form tidak akan hilang)
                            Swal.fire({ 
                                icon: 'error', 
                                title: 'Data Duplikat!', 
                                html: 'Gagal! Aplikasi dengan nama <b>"'+appName+'"</b> dan modul <b>"'+modName+'"</b> sudah ada di database.', 
                                confirmButtonText: 'OK', 
                                buttonsStyling: false, 
                                customClass: { confirmButton: 'btn btn-danger px-4' } 
                            });
                        } else {
                            // JIKA AMAN: Lanjut ke validasi sisa dan submit
                            continueSubmission(type); 
                        }
                    },
                    error: function() {
                        $('#loadingOverlay').css('display', 'none');
                        Swal.fire('Error', 'Gagal mengecek data ke server.', 'error');
                    }
                });
            } else {
                continueSubmission(type); 
            }
        };

        function continueSubmission(type) {
            let autoRemark = "";
            
            <?php 
            $user_role_id = $this->session->userdata('role_id');
            $is_after_renewal = false;
            $current_apps_id = isset($row['apps_id']) ? $row['apps_id'] : 0;

            $ci =& get_instance();
            $cek_renewal = $ci->db->where('apps_id', $current_apps_id)
                                  ->where('action', 'RENEWAL')
                                  ->get('tbl_apps_audit_trail')
                                  ->num_rows();

            if ($cek_renewal > 0) {
                $is_after_renewal = true;
            }

            if (in_array($user_role_id, [1, 3]) || ($user_role_id == 2 && $is_after_renewal)): 
            ?>
            let changes = [];
            
            $('#formDetail').find('input, select, textarea').not('input[type=hidden], input[type=file]').each(function() {
                let fieldName = $(this).attr('name');
                if(!fieldName || $(this).attr('type') === 'checkbox') return;
                if(fieldName === 'operational_day_id' || fieldName === 'operational_hour_id') return;

                let currentVal = $(this).val() || '';
                let originalVal = $(this).data('original') || '';
                
                let currentText = currentVal;
                let originalText = originalVal;

                if ($(this).hasClass('select2') || $(this).is('select')) {
                    currentText = $(this).find('option:selected').text().trim();
                    originalText = $(this).data('original-text') || '';
                }

                if(currentVal !== originalVal) {
                    let label = $(this).closest('.form-group').find('label').text().replace('*', '').trim();
                    if(label) {
                        if(originalText === '' && currentText === '') return;
                        changes.push("- " + label + " : '" + (originalText || '(kosong)') + "' -> '" + (currentText || '(kosong)') + "'");
                    }
                }
            });

            // Pengecekan OS
            let oldOs = $('#labelOS').data('original-text') || '';
            let currOs = $('#labelOS').text().trim() || '';
            if (oldOs === '-- Select OS --') oldOs = '';
            if (currOs === '-- Select OS --') currOs = '';
            if(oldOs !== currOs) { changes.push("- Operating Software : '" + (oldOs || '(kosong)') + "' -> '" + (currOs || '(kosong)') + "'"); }

            // Pengecekan Database
            let oldDb = $('#labelDB').data('original-text') || '';
            let currDb = $('#labelDB').text().trim() || '';
            if (oldDb === '-- Select Databases --') oldDb = '';
            if (currDb === '-- Select Databases --') currDb = '';
            if(oldDb !== currDb) { changes.push("- Database : '" + (oldDb || '(kosong)') + "' -> '" + (currDb || '(kosong)') + "'"); }

            // Pengecekan Server
            let oldSrv = $('#labelSrv').data('original-text') || '';
            let currSrv = $('#labelSrv').text().trim() || '';
            if (oldSrv === '-- Select Server --') oldSrv = '';
            if (currSrv === '-- Select Server --') currSrv = '';
            if(oldSrv !== currSrv) { changes.push("- Server Type : '" + (oldSrv || '(kosong)') + "' -> '" + (currSrv || '(kosong)') + "'"); }

            if(changes.length > 0) {
                autoRemark = changes.join("\n");
            }
            <?php endif; ?>


            // -----------------------------------------------------------------
            // 2. LOGIKA JIKA TOMBOL SAVE DITEKAN ('draft' atau 'save_stay')
            // -----------------------------------------------------------------
            if (type !== 'submit') {
                // Sisipkan remarks secara otomatis KHUSUS untuk Role 2 setelah Renewal
                <?php if ($user_role_id == 2 && $is_after_renewal): ?>
                    if (autoRemark !== "") {
                        $('#inputRemarks').val(autoRemark); 
                    }
                <?php endif; ?>
                
                $('#loadingOverlay').css('display', 'flex');
                document.getElementById('formDetail').submit(); 
            }
            
            // -----------------------------------------------------------------
            // 3. LOGIKA JIKA TOMBOL SUBMIT DITEKAN ('submit')
            // -----------------------------------------------------------------
            else if (type === 'submit') {
                
                // Daftar Form yang SECARA GLOBAL BOLEH KOSONG (Untuk Semua Role)
                // (Catatan: decommission_year dibiarkan opsional agar aplikasi aktif tidak error)
                let optionalFields = [
                    'decommission_year', 
                    'application_version', 
                    'development_language', 
                    'application_developer', 
                    'supporting_web_server', 
                    'supporting_application_server', 
                    'supporting_others',
                    'ha', 'ha_view'
                ];

                // ATURAN KHUSUS ROLE 1 vs ROLE LAINNYA
                <?php if($this->session->userdata('role_id') != 1): ?>
                    // Jika BUKAN Role 1 (contoh: EA), ketiga form ini BOLEH KOSONG.
                    // Jika Role 1, kode ini dilewati sehingga ketiganya WAJIB diisi.
                    optionalFields.push('operational_day_id', 'operational_hour_id', 'standard_category');
                <?php endif; ?>
                
                let isValid = true;
                
                // Loop semua form. Jika name form tersebut tidak ada di optionalFields, maka WAJIB diisi.
                $('#formDetail').find('input:visible, select, textarea').not('input[type=hidden]').not('input[type=file]').each(function() {
                    let fieldName = $(this).attr('name');
                    if(!fieldName || $(this).attr('type') === 'checkbox') return;

                    let isRequired = !optionalFields.includes(fieldName);

                    if(isRequired && (!$(this).val() || $(this).val().toString().trim() === '')) {
                        isValid = false; 
                        $(this).addClass('is-invalid'); 
                    } else { 
                        $(this).removeClass('is-invalid'); 
                    }
                });

                // Validasi Checkbox (DB, OS, Server)
                let dbSelected = $('.db-checkbox:checked').length;
                let osSelected = $('.os-checkbox:checked').length;
                let srvSelected = $('.srv-checkbox:checked').length;

                if (dbSelected === 0 || osSelected === 0 || srvSelected === 0) {
                    isValid = false;
                    if(dbSelected === 0) $('#labelDB').css('color', '#dc3545');
                    if(osSelected === 0) $('#labelOS').css('color', '#dc3545');
                    if(srvSelected === 0) $('#labelSrv').css('color', '#dc3545');
                }

                if(!isValid) {
                     Swal.fire({ 
                        icon: 'error', 
                        title: 'Data Belum Lengkap', 
                        text: 'Mohon lengkapi semua form yang di-highlight merah sebelum melakukan Submit (Kecuali form opsional).' 
                     });
                     return; 
                } else {
                    $('#labelDB').css('color', ''); 
                    $('#labelOS').css('color', '');
                    $('#labelSrv').css('color', '');
                }

                // Popup Swal Submit
                Swal.fire({
                    title: 'Submit Confirmation', 
                    html: '<div class="text-left mb-2"><label class="font-weight-normal">Remarks (Optional)</label>' +
                          '<textarea id="swal-remarks" class="form-control" rows="3" placeholder="Enter remarks..."></textarea></div>', 
                    icon: 'question', 
                    showCancelButton: true, 
                    confirmButtonColor: '#007bff', 
                    confirmButtonText: 'Yes, submit', 
                    reverseButtons: true,
                    preConfirm: () => {
                        let remarks = Swal.getPopup().querySelector('#swal-remarks').value;
                        if(autoRemark !== "") {
                            remarks = remarks.trim() !== "" ? remarks.trim() + "\n\n" + autoRemark : autoRemark;
                        } else {
                            remarks = remarks.trim();
                        }
                        return { remarks: remarks }
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        $('#inputRemarks').val(result.value.remarks);
                        $('#loadingOverlay').css('display', 'flex');
                        document.getElementById('formDetail').submit(); 
                    }
                });
            }
        }
		
        function downloadSlaDoc(downloadUrl) {
            Swal.fire({
                title: 'Download SLA Document?',
                text: "Dokumen ini akan diunduh ke perangkat Anda.",
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Yes, download!',
                cancelButtonText: 'Cancel',
                buttonsStyling: false,
                customClass: {
                    confirmButton: 'btn btn-save-custom px-4 mx-2',
                    cancelButton: 'btn btn-secondary px-4 mx-2'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    // Memunculkan animasi Toast loading di pojok kanan atas
                    const Toast = Swal.mixin({
                        toast: true, 
                        position: 'top-end', 
                        showConfirmButton: false, 
                        timer: 3000, 
                        timerProgressBar: true
                    });
                    
                    Toast.fire({ icon: 'success', title: 'Downloading file...' });
                    
                    // Eksekusi URL download setelah animasi muncul
                    window.location.href = downloadUrl;
                }
            });
        }
	
		window.bulkSubmit = function() {
			let selectedApps = [];
			$('.check-draft:checked').each(function() {
				selectedApps.push($(this).val());
			});

			if(selectedApps.length === 0) {
                Swal.fire({ icon: 'warning', title: 'Pilih Data', text: 'Silakan centang aplikasi dari tabel untuk disubmit.' });
                return;
            }

			Swal.fire({
				title: 'Submit ' + selectedApps.length + ' Aplikasi?',
				text: "Aplikasi yang dicentang akan dikirim ke tahap selanjutnya.",
				html: '<div class="text-left mt-3 mb-2"><label class="font-weight-normal">Remarks (Optional)</label>' +
					  '<textarea id="swal-remarks-bulk" class="form-control" rows="3" placeholder="Enter remarks..."></textarea></div>',
				icon: 'question',
				showCancelButton: true,
				confirmButtonColor: '#007bff',
				confirmButtonText: 'Yes, Submit',
				reverseButtons: true,
				preConfirm: () => {
					return document.getElementById('swal-remarks-bulk').value;
				}
			}).then((result) => {
				if(result.isConfirmed) {
					$('#loadingOverlay').css('display', 'flex');
					let form = document.createElement('form');
					form.method = 'POST';
					form.action = '<?= base_url("home/bulk_submit") ?>';
					
					let inputRemarks = document.createElement('input');
					inputRemarks.type = 'hidden';
					inputRemarks.name = 'remarks';
					inputRemarks.value = result.value;
					form.appendChild(inputRemarks);
					
					selectedApps.forEach(function(id) {
						let inputId = document.createElement('input');
						inputId.type = 'hidden';
						inputId.name = 'selected_apps[]';
						inputId.value = id;
						form.appendChild(inputId);
					});
					
					document.body.appendChild(form);
					form.submit();
				}
			});
		}

        <?php $msg_success = $this->session->flashdata('success'); ?>
        <?php if(!empty($msg_success)): ?>
            Swal.fire({ icon: 'success', title: 'Success', text: '<?= $msg_success ?>', confirmButtonText: 'OK', confirmButtonColor: '#28a745' });
            <?php $this->session->unset_userdata('success'); ?>
        <?php endif; ?>

        <?php $msg_error = $this->session->flashdata('error'); ?>
        <?php if(!empty($msg_error)): ?>
            <?php if(strpos($msg_error, 'Akses Ditolak') === false): ?>
                Swal.fire({ icon: 'error', title: 'Gagal!', html: '<?= $msg_error ?>', confirmButtonText: 'OK', buttonsStyling: false, customClass: { confirmButton: 'btn btn-danger px-4' } });
            <?php endif; ?>
            <?php $this->session->unset_userdata('error'); ?>
        <?php endif; ?>

        <?php if($this->session->flashdata('duplicate_error')): ?>
            Swal.fire({ icon: 'error', title: 'Data Duplikat!', html: '<?= $this->session->flashdata('duplicate_error') ?>', confirmButtonText: 'OK', buttonsStyling: false, customClass: { confirmButton: 'btn btn-danger px-4' } });
            <?php $this->session->unset_userdata('duplicate_error'); ?>
        <?php endif; ?>
    });

    window.deleteDraft = function(apps_id) {
        Swal.fire({
            title: 'Apakah Anda yakin?', text: "Data draft ini tidak dapat dikembalikan!",
            icon: 'warning', showCancelButton: true, confirmButtonColor: '#d33', cancelButtonColor: '#6c757d',
            confirmButtonText: 'Ya, hapus!', cancelButtonText: 'Batal', reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                $('#loadingOverlay').css('display', 'flex');
                window.location.href = '<?= base_url("home/delete_draft/") ?>' + apps_id;
            }
        });
    }
</script>
</body>
</html>