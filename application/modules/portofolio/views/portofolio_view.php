<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <title>SLIM | Portofolio</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  
  <?php $this->load->view('layout/head_links'); ?>

</head>

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
            <h1 class="m-0" style="color: var(--text-dark); font-weight: 700;">Portofolio Management</h1>
          </div>
        </div>
      </div>
    </section>

    <section class="content">
      <div class="container-fluid">
        <div class="card" style="border-top: 3px solid #ffc107;">
            
            <form id="mainFilterForm" action="<?= base_url('portofolio') ?>" method="get">
                <input type="hidden" name="keyword" value="<?= isset($keyword) ? $keyword : '' ?>">
                
                <div id="activeFiltersContainer">
                    <?php if(!empty($selected_filters) && is_array($selected_filters)): ?>
                        <?php foreach($selected_filters as $key => $values): ?>
                            <?php if(is_array($values)): foreach($values as $val): ?>
                                <input type="hidden" name="filter[<?= $key ?>][]" value="<?= htmlspecialchars($val) ?>" class="filter-applied-<?= $key ?>">
                            <?php endforeach; endif; ?>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>

                <div class="card-header bg-white">
                    <div class="row align-items-center">
                        <div class="col-md-8">
                            <button type="button" class="btn btn-export-custom btn-sm" onclick="confirmExport()">
                                <i class="fas fa-file-export mr-1"></i> Export
                            </button>
                        </div>
                        <div class="col-md-4">
                            <div class="input-group">
                                <input type="text" name="keyword" class="form-control" placeholder="Search..." value="<?= isset($keyword) ? $keyword : '' ?>">
                                <div class="input-group-append">
                                    <button class="btn btn-default">
                                        <i class="fas fa-search"></i>
                                    </button>
                                    <a href="<?= base_url('portofolio') ?>" class="btn btn-secondary d-flex align-items-center">
                                        <i class="fas fa-sync-alt"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card-body">
                    <div style="overflow-x: auto; width: 100%;">
                    <table class="table table-striped table-bordered table-hover text-nowrap">
                        <thead>
                            <tr class="bg-info text-center">
                                <th>Action</th>
                                
                                <?php 
                                    function render_th($label, $key, $options, $selected) {
                                        $isActive = isset($selected[$key]) && !empty($selected[$key]);
                                        $iconClass = $isActive ? 'filter-active' : ''; 
                                        
                                        if(!empty($options) && is_array($options)) {
                                            $options = array_unique($options);
                                            sort($options);
                                        }
                                        
                                        echo '<th style="color: var(--text-dark); vertical-align: middle;">';
                                        echo '<div class="d-inline-flex align-items-center">';
                                            echo '<span>' . $label . '</span>';
                                            echo '<div class="btn-group ml-2 filter-icon-wrapper '.$iconClass.'" style="position: static; transform: none; padding: 0;">';
                                                echo '<i class="fas fa-filter fa-sm" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="cursor: pointer;"></i>';
                                                
                                                echo '<div class="dropdown-menu dropdown-menu-right custom-filter-dropdown" onclick="event.stopPropagation()">';
                                                    
                                                    echo '<div class="filter-header">';
                                                    echo '<input type="text" class="filter-search-input" placeholder="Find..." onkeyup="filterList(this)">';
                                                    echo '</div>';
                                                    
                                                    echo '<div class="filter-body">';
                                                    if(!empty($options)) {
                                                        foreach($options as $opt) {
                                                            if(trim($opt) === '') continue;
                                                            $checked = ($isActive && in_array($opt, $selected[$key])) ? 'checked' : '';
                                                            echo '<label class="filter-item">';
                                                            echo '<input type="checkbox" value="'.htmlspecialchars($opt).'" '.$checked.' data-key="'.$key.'"> ';
                                                            echo htmlspecialchars($opt);
                                                            echo '</label>';
                                                        }
                                                    } else {
                                                        echo '<div class="p-2 text-muted text-center small">No Options</div>';
                                                    }
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
                                    
                                    $opt_category = isset($opt_category) ? $opt_category : [];
                                    $opt_app_name = isset($opt_app_name) ? $opt_app_name : [];
                                    $opt_short_name = isset($opt_short_name) ? $opt_short_name : [];
                                    $opt_module = isset($opt_module) ? $opt_module : [];
                                    $opt_db_name = isset($opt_db_name) ? $opt_db_name : [];
                                    $opt_os_name = isset($opt_os_name) ? $opt_os_name : [];
                                    $opt_app_type = isset($opt_app_type) ? $opt_app_type : [];
                                    $opt_live_year = isset($opt_live_year) ? $opt_live_year : [];
                                    $opt_decom_year = isset($opt_decom_year) ? $opt_decom_year : [];
                                    $opt_resilience = isset($opt_resilience) ? $opt_resilience : [];
                                    $opt_network = isset($opt_network) ? $opt_network : [];
                                    $opt_deploy = isset($opt_deploy) ? $opt_deploy : [];
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
                                ?>

                                <?= render_th('Category', 'category', $opt_category, $selected_filters) ?>
                                <?= render_th('Application Name', 'app_name', $opt_app_name, $selected_filters) ?>
                                <?= render_th('Short Name', 'short_name', $opt_short_name, $selected_filters) ?>
                                <?= render_th('Module', 'module', $opt_module, $selected_filters) ?>
                                <?= render_th('Database', 'db_name', $opt_db_name, $selected_filters) ?>
                                <?= render_th('Operating Software', 'os_name', $opt_os_name, $selected_filters) ?>
                                <?= render_th('Application Type', 'app_type', $opt_app_type, $selected_filters) ?>
                                <th>Description</th> 
                                
                                <?= render_th('Live Year', 'live_year', $opt_live_year, $selected_filters) ?>
                                <?= render_th('Decommission Year', 'decom_year', $opt_decom_year, $selected_filters) ?>
                                <?= render_th('Resilience', 'resilience', $opt_resilience, $selected_filters) ?>
                                <?= render_th('DR Availability', 'dr_avail', $opt_yn, $selected_filters) ?>
                                <?= render_th('HA', 'ha', $opt_yn, $selected_filters) ?>
                                
                                <?= render_th('Network', 'network', $opt_network, $selected_filters) ?>
                                <?= render_th('Deployment', 'deployment', $opt_deploy, $selected_filters) ?>
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
                            <?php if(!empty($list)): $no=$this->input->get('per_page')+1; foreach($list as $row): ?>
                            <tr>
                                <td class="text-center align-middle">
                                    <div class="dropdown">
                                        <button class="btn btn-sm btn-secondary dropdown-toggle btn-operation" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            <i class="fas fa-cog mr-2"></i> Operation
                                        </button>
                                        
                                        <div class="dropdown-menu dropdown-menu-right shadow dropdown-operation-menu">
                                            <button class="dropdown-item" type="button" onclick="window.location.href='<?= base_url('portofolio/audit/'.$row['apps_id']) ?>'">
                                                <i class="fas fa-clipboard-list fa-fw text-primary mr-2"></i> Audit Trail
                                            </button>        
                                        </div>
                                    </div>
                                </td>
                                <td><?= $row['category_name'] ?></td>
                                <td><?= $row['application_name'] ?></td>
                                <td><?= $row['short_name'] ?></td>
                                <td><?= $row['module'] ?></td>
                                <td><?= str_replace(',', ',<br>', $row['database_names']) ?></td>
                                <td><?= str_replace(',', ',<br>', $row['os_names']) ?></td>
                                <td><?= $row['application_type'] ?></td>
                                <td title="<?= $row['apps_description'] ?>"><?= substr($row['apps_description'],0,20) ?></td>
                                <td class="text-center"><?= $row['live_year'] ?></td>
                                <td class="text-center"><?= $row['decommission_year'] ?></td>
                                <td class="text-center"><?= $row['resilience'] ?></td>
                                <td class="text-center"><?= $row['dr_availability'] ?></td>
                                <td class="text-center"><?= $row['ha'] ?></td>
                                <td><?= $row['network_name'] ?></td>
                                <td><?= $row['deployment_info'] ?></td>
                                <td><?= $row['operational_hour'] ?></td>
                                <td><?= $row['operational_day'] ?></td>
                                <td><?= $row['solution_vendor'] ?></td>
                                <td><?= $row['services_vendor'] ?></td>
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
                                <td><?= $row['source_code_owned'] ?></td>
                                <td>
									<?php if (!empty($row['Url']) && $row['Url'] !== '-'): ?>
										<?php 
											// Memastikan URL memiliki http:// atau https:// agar link tidak error
											$valid_url = (strpos($row['Url'], 'http') === 0) ? $row['Url'] : 'http://' . $row['Url']; 
										?>
										<a href="<?= $valid_url ?>" target="_blank" style="color: black; text-decoration: underline;">
											<?= $row['Url'] ?>
										</a>
									<?php else: ?>
										-
									<?php endif; ?>
								</td>
                            </tr>
                            <?php endforeach; else: ?>
                                <tr><td colspan="45" class="text-center">No Data Found</td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </form>
            
            <div class="card-footer bg-white clearfix">
                <div class="float-right">
                    <?= $pagination ?>        
                </div>
                <div class="float-left small text-muted">Total Data: <?= isset($total_rows) ? $total_rows : 0 ?>
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
    function filterList(input) {
        var filter = input.value.toUpperCase();
        var div = input.parentNode.nextElementSibling;
        var labels = div.getElementsByTagName("label");
        for (var i = 0; i < labels.length; i++) {
            var txtValue = labels[i].textContent || labels[i].innerText;
            if (txtValue.toUpperCase().indexOf(filter) > -1) {
                labels[i].style.display = "block";
            } else {
                labels[i].style.display = "none";
            }
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
        $('#loadingOverlay').css('display', 'flex'); 
        var checkboxes = document.querySelectorAll('input[type="checkbox"][data-key="' + key + '"]');
        checkboxes.forEach(cb => cb.checked = false);
        $('.filter-applied-' + key).remove();
        document.getElementById('mainFilterForm').submit();
    }

    function confirmExport() {
        Swal.fire({
            title: 'Export to Excel?',
            text: "File akan otomatis diunduh.",
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Yes, Export',
            cancelButtonText: 'Cancel',
            buttonsStyling: false,
            customClass: {
                confirmButton: 'btn btn-theme-gradient px-4 mx-2', 
                cancelButton: 'btn btn-secondary px-4 mx-2'
            }
        }).then((result) => {
            if (result.isConfirmed) {
                const Toast = Swal.mixin({
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 3000,
                    timerProgressBar: true
                });

                Toast.fire({
                    icon: 'success',
                    title: 'Downloading file...'
                });

                window.location.href = "<?= base_url('portofolio/export') ?>" + window.location.search;
            }
        })
    }

    $(document).ready(function() {
        $('.select2').select2({theme: 'bootstrap4', dropdownParent: $('#modalForm')});
        $('#loadingOverlay').fadeOut();
    });
    
    <?php if($this->session->flashdata('success')): ?>
        Swal.fire({
            icon: 'success',
            title: 'Success',
            text: '<?= $this->session->flashdata('success') ?>',
            confirmButtonText: 'OK',
            buttonsStyling: false,
            customClass: { confirmButton: 'btn btn-theme-gradient px-4' }
        });
        <?php unset($_SESSION['success']); ?>
    <?php endif; ?>

    <?php if($this->session->flashdata('error')): ?>
        Swal.fire({
            icon: 'error',
            title: 'Gagal!',
            text: '<?= $this->session->flashdata('error') ?>',
            confirmButtonText: 'OK',
            buttonsStyling: false,
            customClass: { confirmButton: 'btn btn-danger px-4' } 
        });
        <?php unset($_SESSION['error']); ?>
    <?php endif; ?>
    
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

    if (localStorage.getItem('theme') === 'dark') {
        if(!body.classList.contains('dark-mode')){
            body.classList.add('dark-mode');
        }
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
                    overlay.style.display = 'flex';
                    window.location.href = urlLogout;
                }
            });
        });
    }
</script>
</body>
</html>