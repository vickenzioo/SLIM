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
    $is_change_owner = ($this->input->get('mode') == 'change_owner');

    if ($is_change_owner) {
        $is_readonly = true;
        $is_owner_readonly = false; 
        $mode = 'change_owner'; 
    } else {
        $is_owner_readonly = $is_readonly;
    }
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
                <div class="col-md-9">
                    <div class="card shadow-sm" style="border-top: 3px solid var(--theme-yellow-primary); height: 650px; display: flex; flex-direction: column;">
                        
                        <div class="card-header bg-white border-bottom" style="height: 45px; display: flex; align-items: center;">
                            <div class="d-flex align-items-center">
                               <?php 
                                    // 0. Cek apakah IT Dev (Role 3) sudah submit (untuk logic tombol Back)
                                    $it_dev_done_header = false;
                                    if (!empty($timeline)) {
                                        foreach ($timeline as $t) {
                                            if ($t['user_role_id'] == 3 && $t['status'] == 1) {
                                                $it_dev_done_header = true;
                                                break;
                                            }
                                        }
                                    }

                                    // 1. Pindahkan pengecekan status renewal ke bagian atas
                                    $header_title = 'Application Data';
                                    $is_renewal_mode = false;
                                    
                                    if (isset($apps_id) && $apps_id > 0) {
                                        $ci =& get_instance();
                                        $cek_renewal_header = $ci->db->where('apps_id', $apps_id)->where('action', 'RENEWAL')->count_all_results('tbl_apps_audit_trail');
                                        
                                        if ($cek_renewal_header > 0) {
                                            // CEK STATUS: Apakah status app Active dan workflow Done?
                                            $is_active = (isset($row['app_status_label']) && strtolower($row['app_status_label']) == 'active') || (isset($row['status']) && $row['status'] == 1);

                                            if ($is_active && $is_done) {
                                                // Jika sudah Active & Done, kembalikan ke mode normal
                                                $header_title = 'Application Data';
                                                $is_renewal_mode = false;
                                            } else {
                                                // Jika masih dalam proses (belum Done), tetap mode renewal
                                                $header_title = 'Renewal Application Data';
                                                $is_renewal_mode = true; 
                                            }
                                        }
                                    }

                                    // 2. Logic Tampilkan Tombol Back
                                    $show_back_btn = false;
                                    if (!$is_renewal_mode) {
                                        $show_back_btn = true;
                                    } else {
                                        // JIKA RENEWAL: Munculkan HANYA untuk Role 3, ATAU Role 1 (Jika Role 3 sudah submit)
                                        if ($rid == 3 || ($rid == 1 && $it_dev_done_header) || ($rid == 1 && $is_readonly) || ($rid == 2 && $is_readonly)) {
                                            $show_back_btn = true;
                                        }
                                    }
                                ?>

                                <?php if ($show_back_btn): ?>
                                <a href="<?= base_url('home') ?>" class="btn btn-secondary btn-sm mr-3" title="Back" onclick="sessionStorage.removeItem('portfolioTableScrollLeft');">
                                    <i class="fas fa-arrow-left"></i> Back
                                </a>
                                <?php endif; ?>

                                <h5 class="mb-0 font-weight-bold">
                                    <?= ($mode == 'add') ? 'Add New Portofolio' : $header_title ?>
                                </h5>
                            </div>
                        </div>

                        <div class="card-body custom-scroll-kiri" style="flex: 1; overflow-y: auto; overflow-x: hidden; padding-right: 15px;">
                            
                            <h6 class="infra-header">General Information</h6>
							
                            <div class="row">
                                <div class="col-md-4">
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

								<div class="col-md-4"> 
                                    <div class="form-group">
                                        <label>Short Name <span class="text-danger">*</span></label>
                                        <input type="text" name="short_name" class="form-control" value="<?= isset($row['short_name']) ? $row['short_name'] : '' ?>" <?= $is_readonly ? 'readonly' : '' ?>>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Module Name <span class="text-danger">*</span></label>
                                        <input type="text" name="module" class="form-control" required value="<?= isset($row['module']) ? $row['module'] : '' ?>" <?= $is_readonly ? 'readonly' : '' ?>>
                                    </div>
                                </div>

                            </div>
							
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>Description <span class="text-danger">*</span></label>
                                        <textarea name="apps_description" class="form-control" rows="2" <?= $is_readonly ? 'readonly' : '' ?>><?= isset($row['apps_description']) ? $row['apps_description'] : '' ?></textarea>
                                    </div>
                                </div>
                            </div>
							
							<div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Live Year <span class="text-danger">*</span></label>
                                        <input type="number" name="live_year" class="form-control" value="<?= isset($row['live_year']) ? $row['live_year'] : '' ?>" <?= $is_readonly ? 'readonly' : '' ?>>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Category <span class="text-danger">*</span></label>
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
                                
								<div class="col-md-4">
                                    <div class="form-group">
                                        <label>Standard Category (%) <span class="text-danger">*</span></label>
                                        <input type="number" step="0.01" name="standard_category" class="form-control" 
                                               value="<?= isset($row['standard_category']) ? $row['standard_category'] : '' ?>" 
                                               <?= ($is_readonly || $this->session->userdata('role_id') != 1) ? 'readonly' : '' ?>>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Application Type <span class="text-danger">*</span></label>
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
                                        <label>Server Type (Multiple) <span class="text-danger">*</span></label>
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
                                        <label>Solution Vendor <span class="text-danger">*</span></label>
                                        <input type="text" name="solution_vendor" class="form-control" value="<?= isset($row['solution_vendor']) ? $row['solution_vendor'] : '' ?>" <?= $is_readonly ? 'readonly' : '' ?>>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Services Vendor <span class="text-danger">*</span></label>
                                        <input type="text" name="services_vendor" class="form-control" value="<?= isset($row['services_vendor']) ? $row['services_vendor'] : '' ?>" <?= $is_readonly ? 'readonly' : '' ?>>
                                    </div>
                                </div>
                            </div>
							
                            <h6 class="infra-header">Deployment Information</h6>

                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Deployment Model <span class="text-danger">*</span></label>
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
                                        <label>Deployment Provider <span class="text-danger">*</span></label>
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
                                        <label>Deployment Site <span class="text-danger">*</span></label>
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
                                        <label>LOB Directorate <span class="text-danger">*</span></label>
                                        <input type="text" name="lob_directorate" class="form-control" value="<?= isset($row['lob_directorate']) ? $row['lob_directorate'] : '' ?>" <?= $is_owner_readonly ? 'readonly' : '' ?>>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>LOB Sub-Directorate <span class="text-danger">*</span></label>
                                        <input type="text" name="lob_subdirectorate" class="form-control" value="<?= isset($row['lob_subdirectorate']) ? $row['lob_subdirectorate'] : '' ?>" <?= $is_owner_readonly ? 'readonly' : '' ?>>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
								<div class="col-md-4">
                                    <div class="form-group">
                                        <label>LOB Department Head <span class="text-danger">*</span></label>
                                        <input type="text" name="lob_department_head" class="form-control" value="<?= isset($row['lob_department_head']) ? $row['lob_department_head'] : '' ?>" <?= $is_owner_readonly ? 'readonly' : '' ?>>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>LOB Group <span class="text-danger">*</span></label>
                                        <input type="text" name="lob_group" class="form-control" value="<?= isset($row['lob_group']) ? $row['lob_group'] : '' ?>" <?= $is_owner_readonly ? 'readonly' : '' ?>>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>LOB Group Head <span class="text-danger">*</span></label>
                                        <input type="text" name="lob_group_head" class="form-control" value="<?= isset($row['lob_group_head']) ? $row['lob_group_head'] : '' ?>" <?= $is_owner_readonly ? 'readonly' : '' ?>>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>IT Sub-Directorate <span class="text-danger">*</span></label>
                                        <input type="text" name="it_subdirectorate" class="form-control" value="<?= isset($row['it_subdirectorate']) ? $row['it_subdirectorate'] : '' ?>" <?= $is_owner_readonly ? 'readonly' : '' ?>>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>IT Department Head <span class="text-danger">*</span></label>
                                        <input type="text" name="it_department_head" class="form-control" value="<?= isset($row['it_department_head']) ? $row['it_department_head'] : '' ?>" <?= $is_owner_readonly ? 'readonly' : '' ?>>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>IT Support Group <span class="text-danger">*</span></label>
                                        <input type="text" name="it_support_group" class="form-control" value="<?= isset($row['it_support_group']) ? $row['it_support_group'] : '' ?>" <?= $is_owner_readonly ? 'readonly' : '' ?>>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>IT Group Head <span class="text-danger">*</span></label>
                                        <input type="text" name="it_group_head" class="form-control" value="<?= isset($row['it_group_head']) ? $row['it_group_head'] : '' ?>" <?= $is_owner_readonly ? 'readonly' : '' ?>>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>IT Support Division <span class="text-danger">*</span></label>
                                        <input type="text" name="it_support_divison" class="form-control" value="<?= isset($row['it_support_divison']) ? $row['it_support_divison'] : '' ?>" <?= $is_owner_readonly ? 'readonly' : '' ?>>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>IT Division Head <span class="text-danger">*</span></label>
                                        <input type="text" name="it_division_head" class="form-control" value="<?= isset($row['it_division_head']) ? $row['it_division_head'] : '' ?>" <?= $is_owner_readonly ? 'readonly' : '' ?>>
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
                                        <label>Operating Software (Multiple) <span class="text-danger">*</span></label>
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
                                        <label>Database (Multiple) <span class="text-danger">*</span></label>
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
                                        <label>Resilience <span class="text-danger">*</span></label>
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
                                        <label>Network <span class="text-danger">*</span></label>
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
                                        <label>Operational Day <span class="text-danger">*</span></label>
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
                                        <label>Operational Hour <span class="text-danger">*</span></label>
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
                                        <label>Source Code Owned <span class="text-danger">*</span></label>
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
                                        <label>URL <span class="text-danger">*</span></label>
                                        <input type="text" name="Url" id="input_url" class="form-control" 
                                               value="<?= isset($row['Url']) ? $row['Url'] : '' ?>" 
                                               placeholder="contoh: www.domain.com" 
                                               required <?= $is_readonly ? 'readonly' : '' ?>> <small id="url_error_msg" class="text-danger" style="display: none;">Format URL tidak valid.</small>
                                    </div>
								</div>
                            </div>
                        </div>

                        <div class="custom-card-footer bg-white text-right border-top">
                            <input type="hidden" name="remarks" id="inputRemarks">

                            <?php if($mode == 'change_owner'): ?>
                                <button type="button" class="btn btn-save-custom" onclick="submitForm('change_owner')">Submit</button>

                            <?php elseif($mode == 'add' || $mode == 'edit' || $mode == 'review'): ?>
                                <?php
                                    // 1. KITA EKSTRAK CEK IT_DEV_DONE KESINI AGAR BISA DIGUNAKAN DI KEDUA CARD
                                    $it_dev_done = false;
                                    if (!empty($timeline)) {
                                        foreach ($timeline as $t) {
                                            if ($t['user_role_id'] == 3 && $t['status'] == 1) {
                                                $it_dev_done = true;
                                                break;
                                            }
                                        }
                                    }
                                ?>
                                <?php if(in_array($rid, [1, 2])): ?>

                                    <?php
                                        $is_renewal_draft = false;
                                        $is_awal_renewal = false;
                                        
                                        if ($apps_id > 0 && $mode == 'edit') {
                                            $ci =& get_instance();
                                            $cek = $ci->db->where('apps_id', $apps_id)->where('action', 'RENEWAL')->count_all_results('tbl_apps_audit_trail');
                                            
                                            if ($cek > 0) {
                                                $is_renewal_draft = true;
                                                $curr_stage = $ci->Home_model->get_current_approval_stage($apps_id);
                                                $role_turn = isset($curr_stage['user_role_id']) ? $curr_stage['user_role_id'] : 0;

                                                // Logika validasi tombol berdasarkan giliran dan progress timeline
                                                if ($role_turn == 1) {
                                                    if (!$it_dev_done) {
                                                        $is_awal_renewal = true; // Belum ke IT DEV -> Tahap awal (muncul Cancel)
                                                    } else {
                                                        $is_awal_renewal = false; // Sudah lewat IT DEV -> Tahap akhir (HANYA SUBMIT)
                                                    }
                                                } elseif ($role_turn == 2) {
                                                    $is_awal_renewal = true; // Di tahap EA masih bisa cancel
                                                }
                                            }
                                        }
                                    ?>
                                    
                                    <?php if($is_renewal_draft): ?>
                                        <?php if($is_awal_renewal): ?>
                                        <button type="button" class="btn btn-deactivate mr-2" onclick="cancelRenewal(<?= $apps_id ?>)">Cancel</button>
                                        <?php endif; ?>
                                         <button type="button" class="btn btn-save-custom" onclick="submitForm('submit')">Submit</button>
                                    <?php else: ?>
                                        <!-- LOGIC BARU: JIKA IT DEV SUDAH SUBMIT (status = 1), GANTI BUTTON SAVE JADI SUBMIT -->
                                        <?php if ($rid == 1 && $it_dev_done): ?>
                                            <button type="button" class="btn btn-save-custom" onclick="submitForm('submit')">Submit</button>
                                        <?php else: ?>
                                            <button type="button" class="btn btn-secondary" onclick="submitForm('save_stay')">Save</button>
                                        <?php endif; ?>
                                    <?php endif; ?>
                                <?php else: ?>
                                    <button type="button" class="btn btn-save-custom" onclick="submitForm('submit')">Submit</button>
                                <?php endif; ?>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <div class="col-md-3">
                     <?php
                        // Logic UPDATED: Cek apakah dokumen masih di posisi EA (rid 2) atau masih draft
                        $current_pos = 0;
                        if(!empty($timeline)) {
                            foreach($timeline as $t) {
                                if($t['current'] == 1) {
                                $current_pos = $t['user_role_id']; break;
                                }
                            }
                        }

                        // Cek apakah sedang dalam proses RENEWAL
                        $is_renewal_tab = false;
                        if (isset($apps_id) && $apps_id > 0) {
                            $ci =& get_instance();
                            $cek_ren = $ci->db->where('apps_id', $apps_id)->where('action', 'RENEWAL')->count_all_results('tbl_apps_audit_trail');

                            if ($cek_ren > 0) {
                                // FIX: Samakan logic pengecekan dengan header di atas
                                $is_active_tab = (isset($row['app_status_label']) && strtolower($row['app_status_label']) == 'active') || (isset($row['status']) && $row['status'] == 1);

                                // Jika BELUM Active dan Done, berarti masih murni mode renewal
                                if (!($is_active_tab && $is_done)) {
                                $is_renewal_tab = true;
                                }
                            }
                        }

                        // 1. Definisikan status draft dengan mengecek berbagai kemungkinan variabel draft
                        $is_pure_draft = (
                            (isset($row['status']) && $row['status'] == 0) || 
                            (isset($row['app_status_label']) && strtolower($row['app_status_label']) == 'drafting') || 
                            (isset($is_draft_status) && $is_draft_status)
                        );

                        // 2. Sisipkan variabel $is_pure_draft ke logic di bawah agar tab list paksa dimunculkan
                        $show_list_tab = (in_array($rid, [1, 2]) && ($current_pos == $rid || empty($timeline) || $is_pure_draft) && !$is_renewal_tab);
                        
                        if ($rid == 1 && isset($it_dev_done) && $it_dev_done) {
                            $show_list_tab = false;
                        }

                        $workflow_active = (!$show_list_tab) ? 'active' : '';

                        // 3. $is_pure_draft sudah didefinisikan di atas, tinggal dipakai
                        $is_create_or_draft_mode = (($mode == 'add' && empty($apps_id)) || $is_pure_draft) && !$is_renewal_tab;
                    ?>

                    <div class="card card-tabs shadow-sm" style="border-top: 3px solid #ffc107; height: 650px; min-height: 650px; display: flex; flex-direction: column;">
                        
                        <?php if ($is_create_or_draft_mode): // TAMPILAN HEADER BARU (GAMBAR 2) KHUSUS CREATE PORTOFOLIO ?>
                        <div class="card-header" style="height: 45px; padding: 0 15px; flex: 0 0 auto; display: flex; align-items: center; border-bottom: 1px solid #dee2e6;">
                            <div class="d-flex justify-content-between align-items-center w-100">
                                <div class="d-flex align-items-center">
                                    <div class="d-flex justify-content-center align-items-center rounded-circle" style="width: 32px; height: 32px; background-color: #fefae0; color: #2f3542;">
                                        <i class="fas fa-clipboard-list" style="font-size: 14px;"></i>
                                    </div>
                                    <div class="ml-2 d-flex flex-column justify-content-center">
                                        <span style="font-size: 12px; font-weight: 800; color: #212529; line-height: 1.2; letter-spacing: -0.2px;" class="text-dark">Selected Portfolio</span>
                                        <span style="font-size: 9px; color: #6c757d; margin-top: 2px;">Manage added application modules</span>
                                    </div>
                                </div>
                                <div>
                                    <!-- Tambahkan id="selectedItemCount" dan set default text ke "0 Item" -->
                                    <span class="badge" id="selectedItemCount" style="background-color: #fefae0; color: #2f3542; font-size: 9px; font-weight: 700; padding: 4px 8px; border-radius: 4px;">
                                        0 Item
                                    </span>
                                </div>
                            </div>
                        </div>
                        
                        <?php else: // TAMPILAN TAB DEFAULT (GAMBAR 1) UNTUK DETAIL/EDIT ?>
                        <div class="card-header p-0 border-bottom-0" style="height: 45px; flex: 0 0 auto; display: flex; align-items: flex-end;">
                            <ul class="nav nav-tabs custom-scroll-tabs mb-0" id="rightTabs" role="tablist" style="flex-wrap: nowrap; overflow-x: auto; overflow-y: hidden; white-space: nowrap; margin-bottom: 0;">
                                
                                <?php if($show_list_tab): // UPDATED ?>
                                <li class="nav-item" style="flex: 0 0 auto;">
                                    <a class="nav-link active text-dark font-weight-bold" id="tab-list" data-toggle="pill" href="#content-list" title="List" style="height: 45px; display: flex; align-items: center; gap: 8px; padding: 0 15px;">
                                        <i class="fas fa-clipboard-list" style="font-size: 12px; color: #2c3e50;"></i>
                                        <span style="font-size: 11px;">List</span>
                                    </a>
                                </li>
                                <?php endif; ?>

                                <?php if (!$is_create_or_draft_mode): // Sembunyikan tab jika dalam mode Create atau edit draft untuk Role 1 & 2 ?>
                                <li class="nav-item" style="flex: 0 0 auto;">
                                    <a class="nav-link <?= (!$show_list_tab) ? 'active' : '' ?> text-dark font-weight-bold" id="tab-timeline" data-toggle="pill" href="#content-timeline" title="Workflow" style="height: 45px; display: flex; align-items: center; gap: 8px; padding: 0 15px;">
                                        <i class="fas fa-stream" style="font-size: 12px; color: #2c3e50;"></i>
                                        <span style="font-size: 11px;">Workflow</span>
                                    </a>
                                </li>

                                <li class="nav-item" style="flex: 0 0 auto;">
                                    <a class="nav-link text-dark font-weight-bold" id="tab-audit" data-toggle="pill" href="#content-audit" title="Audit" style="height: 45px; display: flex; align-items: center; gap: 8px; padding: 0 15px;">
                                        <i class="fas fa-history" style="font-size: 12px; color: #2c3e50;"></i>
                                        <span style="font-size: 11px;">Audit</span>
                                    </a>
                                </li>

                                <li class="nav-item" style="flex: 0 0 auto;">
                                    <a class="nav-link text-dark font-weight-bold" id="tab-docs" data-toggle="pill" href="#content-docs" title="SLA Doc" style="height: 45px; display: flex; align-items: center; gap: 8px; padding: 0 15px;">
                                        <i class="fas fa-folder-open" style="font-size: 12px; color: #2c3e50;"></i>
                                        <span style="font-size: 11px;">SLA Doc</span>
                                    </a>
                                </li>
                                <?php endif; ?>

                            </ul>
                        </div>
                        <?php endif; ?>
                        
                        <div class="card-body p-0" style="flex: 1 1 auto; overflow: hidden;">
                             <div class="tab-content h-100">
                                 
                                 <?php if(((isset($is_draft_status) && $is_draft_status) || $is_pure_draft) && $show_list_tab): // <--- BAGIAN YANG DIPERBAIKI ?>
                                    <div class="tab-pane fade show active h-100" id="content-list">
                                     <div class="d-flex flex-column h-100">
                                         
                                         <div class="table-responsive custom-scroll-tabs custom-scroll-kiri p-0" style="flex: 1 1 auto; overflow-y: auto; min-height: 0;">
                                             <table class="table table-bordered table-hover text-sm m-0 w-100" style="table-layout: fixed;">
                                                 <thead class="bg-light" style="position: sticky; top: 0; z-index: 1; font-size: 11px;">
                                                    <tr class="bg-info" style="height: 30px;">
                                                        <th style="width: 10%;" class="border-top-0 p-0 align-middle">
                                                            <div class="d-flex justify-content-center align-items-center" style="height: 100%; min-height: 0px;">
                                                                <input type="checkbox" id="checkAllDrafts" title="Select All">
                                                            </div>
                                                        </th>
                                                        <th style="width: 40%;" class="text-center align-middle border-top-0">App Name</th>
                                                        <th style="width: 25%;" class="text-center align-middle border-top-0">Module</th>
                                                        <th style="width: 25%;" class="text-center align-middle border-top-0">
                                                            <div class="d-flex justify-content-center align-items-center" style="height: 100%;">
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
                                                            <td class="text-center align-middle" style="white-space: normal; word-wrap: break-word;">
                                                                <?= $d['application_name'] ?: $d['short_name'] ?>
                                                            </td>
                                                            <td class="text-center align-middle" style="white-space: normal; word-wrap: break-word;">
                                                                <?= $d['module_name'] ?>
                                                            </td>
                                                            <td class="align-middle p-0">
                                                                <div class="d-flex justify-content-center align-items-center" style="height: 100%; min-height: 0px; min-width: 70px;">
                                                                    <a href="<?= base_url('home/detail/'.$d['apps_id']) ?>" class="btn btn-xs btn-info mr-1 d-inline-flex align-items-center justify-content-center" title="Edit Data" style="margin-bottom: 0;">
                                                                        <i class="fas fa-edit"></i>
                                                                    </a>
                                                                    <button type="button" class="btn btn-xs btn-danger d-inline-flex align-items-center justify-content-center" title="Delete Draft" onclick="deleteDraft(<?= $d['apps_id'] ?>)" style="margin-bottom: 0;">
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
                                                <div class="d-flex justify-content-center w-100" style="flex: 0 0 auto; padding: 0.75rem 1.25rem;">
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
                                         <div class="custom-scroll-tabs custom-scroll-kiri p-3 h-100" style="overflow-y: auto; overflow-x: auto;">
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

                                                        if ($current_pos == 2 && $myData['status'] == 0 && in_array($rid_key, [1, 3])) {
                                                            $isRejectEvent = false;
                                                        }

                                                        // --- UBAH URUTAN DI SINI ---
                                                        
                                                        // 1. Cek Posisi Saat Ini (KUNING) -> Prioritas utama jika sedang di role tsb
                                                        if($myData['current'] == 1) {
                                                            $containerClass = 'active'; 
                                                            $markerClass = 'bg-warning'; // Ini warna kuning
                                                            $showDetails = false; 
                                                        }
                                                        // 2. Cek Jika Sudah Selesai (HIJAU)
                                                        elseif($myData['status'] == 1) {
                                                            $containerClass = 'passed'; 
                                                            $markerClass = 'bg-success'; 
                                                            $rawDate = !empty($myData['submit_date']) ? $myData['submit_date'] : $myData['modified_at'];
                                                            $displayDate = date('d M Y H:i', strtotime($rawDate));
                                                            $displayRemarks = $myData['remarks'];
                                                            $showDetails = true;
                                                        }
                                                        // 3. Cek Jika Rejected (MERAH)
                                                        elseif($myData['status'] == 0 && $isRejectEvent) {
                                                            $markerClass = 'bg-danger'; 
                                                            $containerClass = 'rejected';
                                                            if(in_array($rid_key, [1, 3])) { 
                                                                $displayRemarks = $myData['remarks']; 
                                                                $showDetails = true; 
                                                            } else { 
                                                                $containerClass = 'active'; 
                                                                $markerClass = 'bg-warning'; 
                                                            }
                                                            $displayDate = date('d M Y H:i', strtotime($myData['modified_at']));
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
                                                            <?php 
                                                                // Terapkan regex format merah-hijau yang sama seperti di tab Audit
                                                                $formatted_workflow_remarks = preg_replace(
                                                                    "/'(.*?)'\s*->\s*'(.*?)'/", 
                                                                    "<span class=\"text-danger\" style=\"text-decoration: line-through;\">'$1'</span> -> <span class=\"text-success font-weight-bold\">'$2'</span>", 
                                                                    $displayRemarks
                                                                );
                                                            ?>
                                                            <div class="text-muted small mt-1 <?= $textClass ?>" style="font-size: 14px;">
                                                                <i class="fas fa-comment-dots mr-1"></i> "<?= nl2br($formatted_workflow_remarks) ?>"
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
                                        <div class="table-responsive custom-scroll-tabs custom-scroll-kiri p-0" style="flex: 1 1 auto; overflow-y: auto; min-height: 0;">
                                            <table class="table table-striped table-bordered table-hover text-sm m-0 w-100 text-center">
                                                <thead class="bg-light" style="position: sticky; top: 0; z-index: 1; font-size: 11px;">
                                                    <tr class= "bg-info" style="height: 30px;">
                                                        <th class="text-center align-middle border-top-0" style="width: 15%;">Timestamp</th>
                                                        <th class="text-center align-middle border-top-0" style="width: 20%;">User Role</th>
                                                        <th class="text-center align-middle border-top-0" style="width: 20%;">Action</th>
                                                        <th class="text-center align-middle border-top-0" style="width: 35%;">Remarks</th>
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
                                                                            case 'CANCEL':
                                                                                $bg = '#fff3e0'; $color = '#e65100'; // Toska
                                                                                break;
                                                                            case 'CHANGE OWNERSHIP':
                                                                                $bg = '#fff3e0'; $color = '#ef6c00'; // Oranye
                                                                                break;
                                                                            case 'IMPORT':
                                                                                $bg = '#f3e5f5'; $color = '#6a1b9a'; // Ungu
                                                                                break;
                                                                        }
                                                                    ?>
                                                                    <span class="badge px-3 py-2" style="background-color: <?= $bg ?>; color: <?= $color ?>; border-radius: 6px; font-size: 0.75rem; font-weight: 700; display: inline-block; min-width: 110px;">
                                                                        <?= $action ?>
                                                                    </span>
                                                                </td>
                                                                    <td class="align-middle">
                                                                    <?php 
                                                                        $audit_remarks = '-';
                                                                        if (!empty($aud['remarks'])) {
                                                                            $audit_remarks = preg_replace(
                                                                                "/'(.*?)'\s*->\s*'(.*?)'/", 
                                                                                "<span class=\"text-danger\" style=\"text-decoration: line-through;\">'$1'</span> -> <span class=\"text-success font-weight-bold\">'$2'</span>", 
                                                                                $aud['remarks']
                                                                            );
                                                                            $audit_remarks = nl2br($audit_remarks);
                                                                        }
                                                                    ?>
                                                                    <?= $audit_remarks ?>
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
                                        <div class="custom-card-footer">
                                            <?php if (!empty($audit_trail)): // Tombol hanya muncul jika ada data ?>
                                                <div class="d-flex justify-content-center w-100" style="flex: 0 0 auto; padding: 0.75rem 1.25rem;">
                                                    <button type="button" class="btn btn-export-custom w-100" onclick="confirmExportAudit('<?= base_url('home/export_audit/' . $apps_id) ?>')">
                                                        <i class="fas fa-file-export mr-1"></i> Export
                                                    </button>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>

                                <div class="tab-pane fade h-100" id="content-docs">
                                    <div class="d-flex flex-column h-100">
                                        <div class="table-responsive scrollable-card-body p-0" style="flex: 1 1 auto; overflow-y: auto; min-height: 0;">
                                            <table class="table table-striped table-bordered table-hover text-sm m-0 w-100 text-center">
                                                <thead class="bg-light" style="position: sticky; top: 0; z-index: 1; font-size: 11px;">
                                                    <tr class= "bg-info" style="height: 30px;">
                                                        <th class="text-center align-middle border-top-0">Version</th>
                                                        <th class="text-center align-middle border-top-0">Date Uploaded</th>
                                                        <th class="border-top-0 p-0 align-middle">
                                                            <div class="d-flex justify-content-center align-items-center" style="height: 100%; min-height: 0px; min-width: 50px;">
                                                                Action
                                                            </div>
                                                        </th>
                                                    </tr>
                                                </thead>
                                                <tbody style="font-size: 10px;">
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
										
                                        <div class="custom-card-footer">
                                            <div class="d-flex justify-content-center w-100" style="flex: 0 0 auto; padding: 0.75rem 1.25rem;">
                                                <?php 
                                                    $ci =& get_instance();
                                                    $current_apps_id = $ci->uri->segment(3); 
                                                    
                                                    $is_imported = $ci->db->where('apps_id', $current_apps_id)->where('action', 'IMPORT')->count_all_results('tbl_apps_audit_trail') > 0;
                                                    $has_sla = $ci->db->where('apps_id', $current_apps_id)->count_all_results('tbl_apps_sla_history') > 0;
                                                    $is_new_imported = ($is_imported && !$has_sla) ? 1 : 0;
                                                ?>

                                                <?php if ($this->session->userdata('role_id') == 1 && $is_new_imported == 1 && !empty($current_apps_id)): ?>
                                                    <button type="button" class="btn btn-primary w-100" onclick="confirmUploadSLA('<?= $current_apps_id; ?>')">
                                                        <i class="fas fa-file-pdf mr-1"></i> Upload Dokumen SLA
                                                    </button>
                                                <?php endif; ?>
                                            </div>
                                        </div>
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

    function showNotification(title, message, icon = 'success') {
        Swal.fire({
            title: title,
            html: message,
            icon: icon,
            confirmButtonText: 'OK',
            buttonsStyling: false,
            customClass: {
                confirmButton: 'btn btn-theme-gradient px-4'
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
        
        $('#formDetail').on('input', 'input[type="text"], textarea', function() {
            var el = $(this);
            var id = el.attr('id');
            var name = el.attr('name');
            var currentValue = el.val();
            var forbiddenChars;

            // 1. Pengecualian untuk URL (biarkan divalidasi oleh Pattern URL di atas)
            if (id === 'input_url' || name === 'Url') {
                return; 
            }

            // 2. Validasi Live Year (Hanya boleh ANGKA)
            if (id === 'live_year' || name === 'Live_Year') {
                forbiddenChars = /[^0-9]/g;
            } 
            // 3. Validasi Standard Category (Angka, Titik, Koma)
            else if (id === 'standard_category' || name === 'Standard_Category') {
                forbiddenChars = /[^0-9.,]/g;
            } 
            // 4. Validasi Umum (Huruf, Angka, Spasi, Titik, Koma, Strip, Underscore)
            else {
                forbiddenChars = /[^a-zA-Z0-9\s.,_\-]/g;
            }

            // Eksekusi pembersihan karakter jika melanggar regex
            if (forbiddenChars.test(currentValue)) {
                el.val(currentValue.replace(forbiddenChars, ''));
                
                // Efek visual kedip merah
                el.addClass('is-invalid');
                setTimeout(function() {
                    el.removeClass('is-invalid');
                }, 400);
            }
        });
            
        $(document).on('keydown', '#live_year, #standard_category, input[name="Live_Year"], input[name="Standard_Category"]', function(e) {
            // Blokir simbol -, +, dan huruf e / E
            if (['-', '+', 'e', 'E'].includes(e.key)) {
                e.preventDefault();
            }
        });
        
        $('#formDetail').on('input', 'input[type="text"], textarea', function() {
            // Pengecualian untuk field URL (karena URL wajib pakai titik dua, garis miring, dll)
            if ($(this).attr('id') === 'input_url' || $(this).attr('name') === 'Url') {
                return; 
            }

            // Regex: HANYA izinkan huruf, angka, spasi, titik, koma, strip, dan underscore
            var forbiddenChars = /[^a-zA-Z0-9\s.,_\-]/g; 
            var currentValue = $(this).val();

            if (forbiddenChars.test(currentValue)) {
                // Langsung hapus karakter terlarang yang baru saja diketik
                $(this).val(currentValue.replace(forbiddenChars, ''));
                
                // Beri efek visual berkedip merah agar user sadar karakternya ditolak
                var el = $(this);
                el.addClass('is-invalid');
                setTimeout(function() {
                    el.removeClass('is-invalid');
                }, 400); // Kedip selama 400ms
            }
        });

        $(document).on('input', '.swal2-popup textarea', function() {
            var forbiddenChars = /[^a-zA-Z0-9\s.,_\-]/g; 
            var currentValue = $(this).val();

            if (forbiddenChars.test(currentValue)) {
                // Langsung hapus karakter terlarang
                $(this).val(currentValue.replace(forbiddenChars, ''));
                
                // Beri efek visual border merah berkedip pada form Swal
                var el = $(this);
                el.css({
                    'border-color': '#dc3545',
                    'box-shadow': '0 0 0 0.2rem rgba(220, 53, 69, 0.25)'
                });
                
                setTimeout(function() {
                    el.css({
                        'border-color': '',
                        'box-shadow': ''
                    });
                }, 400);
            }
        });

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
                Swal.fire({ 
                    icon: 'warning', 
                    title: 'Data Belum Lengkap', 
                    text: 'Application Name dan Module Name wajib diisi untuk menyimpan data.',
                    confirmButtonText: 'OK',
                    buttonsStyling: false,
                    customClass: {
                        confirmButton: 'btn btn-theme-gradient px-4'
                    }
                });
                return;
            }

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
                                customClass: { 
                                    confirmButton: 'btn btn-theme-gradient px-4' 
                                } 
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

            if (type === 'change_owner') {
                if (autoRemark === "") {
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal!',
                        text: 'Silakan ubah data sebelum Submit.',
                        confirmButtonText: 'OK',
                        buttonsStyling: false,
                        customClass: {
                            confirmButton: 'btn btn-danger px-4'
                        }
                    });
                    return;
                }

                Swal.fire({
                    title: 'Edit Ownership?',
                    text: 'Perubahan data ini akan direview oleh EA',
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonText: 'Yes, Submit',
                    cancelButtonText: 'Cancel',
                    reverseButtons: true,
                    buttonsStyling: false,
                    customClass: {
                        confirmButton: 'btn btn-save-custom px-4 ml-2',
                        cancelButton: 'btn btn-secondary px-4'
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        $('#loadingOverlay').css('display', 'flex');
                        document.getElementById('formDetail').submit(); 
                    }
                });
            }
            
            else if (type !== 'submit') {
                // Sisipkan remarks secara otomatis KHUSUS untuk Role 2 setelah Renewal
                <?php if ($user_role_id == 2 && $is_after_renewal): ?>
                    if (autoRemark !== "") {
                        $('#inputRemarks').val(autoRemark); 
                    }
                <?php endif; ?>
                
                $('#loadingOverlay').css('display', 'flex');
                document.getElementById('formDetail').submit(); 
            }
            
            else if (type === 'submit') {
                
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
                        text: 'Mohon lengkapi semua form sebelum Submit.',
                        confirmButtonText: 'OK',
                        buttonsStyling: false,
                        customClass: {
                            confirmButton: 'btn btn-theme-gradient px-4'
                        }
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
                    confirmButtonText: 'Yes, Submit', 
                    cancelButtonText: 'Cancel',
                    reverseButtons: true,
                    buttonsStyling: false,
                    customClass: {
                        confirmButton: 'btn btn-theme-gradient px-4 ml-2',
                        cancelButton: 'btn btn-secondary px-4'
                    },
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
        
        window.downloadSlaDoc = function(downloadUrl) {
            Swal.fire({
                title: 'Download SLA Document?',
                text: "Dokumen ini akan diunduh ke perangkat Anda.",
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Yes, download',
                cancelButtonText: 'Cancel',
                reverseButtons: true,
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
                confirmButtonText: 'Yes, Submit',
                cancelButtonText: 'Cancel', // Tambahkan teks cancel
                reverseButtons: true,
                buttonsStyling: false, // Wajib false agar class custom bisa jalan
                customClass: {
                    confirmButton: 'btn btn-theme-gradient px-4 ml-2', // Menggunakan gradient agar seragam
                    cancelButton: 'btn btn-secondary px-4' // Sesuai permintaan Anda
                },
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

        <?php if($this->session->flashdata('success')): ?>
            showNotification('Success', '<?= $this->session->flashdata('success') ?>', 'success');
            <?php $this->session->unset_userdata('success'); ?>
        <?php endif; ?>

        // 2. Cek Notifikasi Error Umum
        <?php if($this->session->flashdata('error')): ?>
            <?php if(strpos($this->session->flashdata('error'), 'Akses Ditolak') === false): ?>
                showNotification('Gagal!', '<?= $this->session->flashdata('error') ?>', 'error');
            <?php endif; ?>
            <?php $this->session->unset_userdata('error'); ?>
        <?php endif; ?>

        // 3. Cek Notifikasi Duplicate Error
        <?php if($this->session->flashdata('duplicate_error')): ?>
            showNotification('Data Duplikat!', '<?= $this->session->flashdata('duplicate_error') ?>', 'error');
            <?php $this->session->unset_userdata('duplicate_error'); ?>
        <?php endif; ?>
    });

    window.deleteDraft = function(apps_id) {
        Swal.fire({
            title: 'Apakah Anda yakin?', 
            text: "Data draft ini tidak dapat dikembalikan!",
            icon: 'warning', 
            showCancelButton: true, 
            confirmButtonText: 'Yes, Delete', 
            cancelButtonText: 'Cancel',
            reverseButtons: true,
            buttonsStyling: false,
            customClass: {
                confirmButton: 'btn btn-deactivate px-4 mx-2', 
                cancelButton: 'btn btn-secondary px-4 mx-2'
            },
        }).then((result) => {
            if (result.isConfirmed) {
                $('#loadingOverlay').css('display', 'flex');
                window.location.href = '<?= base_url("home/delete_draft/") ?>' + apps_id;
            }
        });
    }
    
    window.cancelRenewal = function(apps_id) {
        Swal.fire({
            title: 'Batalkan Renewal?',
            text: "Status aplikasi akan dikembalikan menjadi DONE (selesai) seperti sebelum Renewal.",
            html: '<div class="text-left mt-3 mb-2"><label class="font-weight-normal">Remarks / Alasan (Opsional)</label>' +
                  '<textarea id="swal-remarks-cancel" class="form-control" rows="3" placeholder="Contoh: Salah tekan tombol renewal..."></textarea></div>',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes, Cancel',
            cancelButtonText: 'No',
            reverseButtons: true,
            buttonsStyling: false,
            customClass: {
                confirmButton: 'btn btn-deactivate px-4 mx-2', 
                cancelButton: 'btn btn-secondary px-4 mx-2'
            },
            preConfirm: () => {
                return document.getElementById('swal-remarks-cancel').value;
            }
        }).then((result) => {
            if (result.isConfirmed) {
                $('#loadingOverlay').css('display', 'flex');
                
                let form = document.createElement('form');
                form.method = 'POST';
                form.action = '<?= base_url("home/cancel_renewal/") ?>' + apps_id;
                
                let inputRemarks = document.createElement('input');
                inputRemarks.type = 'hidden';
                inputRemarks.name = 'remarks';
                inputRemarks.value = result.value;
                form.appendChild(inputRemarks);
                
                document.body.appendChild(form);
                form.submit();
            }
        });
    }
    
    window.confirmExportAudit = function(exportUrl) {
        Swal.fire({
            title: 'Export to Excel?',
            text: "Data audit trail ini akan otomatis diunduh.",
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
                // Munculkan toast loading
                const Toast = Swal.mixin({
                    toast: true, position: 'top-end', showConfirmButton: false, timer: 3000, timerProgressBar: true
                });
                Toast.fire({ icon: 'success', title: 'Downloading file...' });
                
                // Redirect ke URL untuk men-download file Excel
                window.location.href = exportUrl;
            }
        });
    }

    // Tambahkan parameter appId untuk menangkap $current_apps_id dari PHP
    function confirmUploadSLA(appId) {
        Swal.fire({
            title: 'Upload Dokumen SLA',
            // Note: name diubah menjadi sla_file agar sesuai dengan backend aslimu
            html: "<p style='margin-bottom: 15px; font-size: 14px;'>Pastikan dokumen SLA yang diupload berformat PDF.</p>" +
                  "<div class='text-left px-2'>" +
                  "<label class='font-weight-normal' style='font-size: 14px;'>Pilih Dokumen (.pdf) <span class='text-danger'>*</span></label>" +
                  "<input type='file' id='swal-upload-sla' name='sla_file' class='form-control' accept='.pdf, .doc, .docx' style='padding: 3px;'>" +
                  "</div>",
            icon: 'info',
            showCancelButton: true,
            confirmButtonText: 'Yes, Upload',
            cancelButtonText: 'Cancel',
            reverseButtons: true,
            buttonsStyling: false,
            customClass: {
                confirmButton: 'btn btn-save-custom px-4 mx-2',  // Menyesuaikan warna tombol primary aslimu
                cancelButton: 'btn btn-secondary px-4 mx-2'
            },
            preConfirm: () => {
                let fileInput = document.getElementById('swal-upload-sla');
                
                // Validasi harus ada file yang diupload
                if (fileInput.files.length === 0) {
                    Swal.showValidationMessage('Dokumen SLA wajib dipilih!');
                    return false;
                }

                // Validasi ekstensi
                let fileName = fileInput.files[0].name;
                let ext = fileName.split('.').pop().toLowerCase();
                let allowedExt = ['pdf', 'doc', 'docx'];
                
                if (!allowedExt.includes(ext)) {
                    Swal.showValidationMessage('Format file tidak didukung! Harus .pdf, .doc, atau .docx');
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
                
                // Menggabungkan base_url dengan appId dinamis
                form.action = '<?= base_url("home/upload_imported_sla/") ?>' + appId; 
                form.enctype = 'multipart/form-data'; 
                
                form.appendChild(safeFileInput);
                document.body.appendChild(form);
                
                // Submit data
                form.submit();
            } else {
                // Bersihkan DOM jika user batal
                let orphanedInput = document.getElementById('swal-upload-sla');
                if (orphanedInput) orphanedInput.remove();
            }
        });
    }

    $(document).ready(function() {
        // Fungsi untuk menghitung jumlah checkbox yang dicentang
        function updateSelectedCount() {
            // Asumsi class checkbox di tabel kamu adalah '.check-draft'
            var selectedCount = $('.check-draft:checked').length;
            
            // Ubah teks berdasarkan jumlah (tambah 's' jika lebih dari 1)
            var itemText = selectedCount <= 1 ? ' Item' : ' Items';
            
            // Update angka di dalam badge
            $('#selectedItemCount').text(selectedCount + itemText);
        }

        // Jalankan fungsi saat checkbox satuan dicentang/dihapus centangnya
        $(document).on('change', '.check-draft', function() {
            updateSelectedCount();
        });

        // Jalankan fungsi saat checkbox "Select All" di header tabel diklik
        // Asumsi ID checkbox select all kamu adalah '#checkAllDrafts'
        $(document).on('change', '#checkAllDrafts', function() {
            // Beri sedikit delay agar script select all bawaan selesai mengeksekusi centang ke semua baris
            setTimeout(function() {
                updateSelectedCount();
            }, 50);
        });

        // Jalankan sekali saat halaman pertama kali dimuat (in case ada baris yang sudah dicentang dari awal)
        updateSelectedCount();
    });
</script>
</body>
</html>