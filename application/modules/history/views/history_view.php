<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <title>SLIM | Audit Trail</title>
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
            <h1 class="m-0" style="color: var(--text-dark); font-weight: 700;">Audit Trail</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item">
                <a href="<?= base_url('portofolio')?>" class="breadcrumb-home">Home</a></li>
              <li class="breadcrumb-item active">Audit Trail</li>
            </ol>
          </div>
        </div>
      </div>
    </section>

    <section class="content">
      <div class="container-fluid">
        
        <div class="card" style="border-top: 3px solid var(--theme-yellow-primary);">
            
            <form id="mainFilterForm" action="<?= base_url('history') ?>" method="get">
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
                                <input type="text" name="keyword" class="form-control" placeholder="Search History..." value="<?= isset($keyword) ? $keyword : '' ?>">
                                <div class="input-group-append">
                                    <button class="btn btn-default">
                                        <i class="fas fa-search"></i>
                                    </button>
                                    <a href="<?= base_url('history') ?>" class="btn btn-secondary d-flex align-items-center">
                                        <i class="fas fa-sync-alt"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            
                <div class="card-body">
                    <table class="table table-striped table-bordered table-hover">
                        <thead>
                            <tr class="bg-info text-center">
                                
                                <?php 
									// Helper Render Header with Filter
									function render_th($label, $key, $options, $selected) {
										$isActive = isset($selected[$key]) && !empty($selected[$key]);
										// Class ini mengatur warna jika filter sedang aktif
										$iconClass = $isActive ? 'filter-active' : ''; 
										
										// Sorting options
										if(!empty($options) && is_array($options)) {
											$options = array_unique($options);
											sort($options);
										}
										
										echo '<th style="color: var(--text-dark); vertical-align: middle;">';
										
										// [WRAPPER UTAMA]: d-inline-flex membuat kotak ini hanya selebar isinya (Teks + Icon)
										// align-items-center mensejajarkan teks dan icon secara vertikal
										echo '<div class="d-inline-flex align-items-center">';
										
											// 1. Teks Label
											echo '<span>' . $label . '</span>';
											
											// 2. Icon Wrapper
											// - Tetap pakai class 'filter-icon-wrapper' agar dapat efek hover/warna lama.
											// - Tambah 'ml-2' untuk jarak spasi.
											// - STYLE INLINE PENTING: 'position: static' membatalkan 'absolute' yang bikin dia lari ke pojok.
											// - 'transform: none' mencegah icon loncat vertikal karena style lama.
											echo '<div class="btn-group ml-2 filter-icon-wrapper '.$iconClass.'" style="position: static; transform: none; padding: 0;">';
											
												echo '<i class="fas fa-filter fa-sm" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="cursor: pointer;"></i>';
												
												// Dropdown Menu
												echo '<div class="dropdown-menu dropdown-menu-right custom-filter-dropdown" onclick="event.stopPropagation()">';
													
													// Search
													echo '<div class="filter-header">';
													echo '<input type="text" class="filter-search-input" placeholder="Find..." onkeyup="filterList(this)">';
													echo '</div>';
													
													// List
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
													
													// Footer
													echo '<div class="filter-footer">';
													echo '<button type="button" class="btn btn-xs btn-default" onclick="clearFilter(\''.$key.'\')">Clear</button>';
													echo '<button type="button" class="btn btn-xs btn-primary btn-theme-gradient" onclick="applyFilter(\''.$key.'\')">Apply</button>';
													echo '</div>';
													
												echo '</div>'; // End Dropdown
											echo '</div>'; // End Btn Group

										echo '</div>'; // End Flex
										echo '</th>';
									}
								?>

                                <?= render_th('Timestamp', 'timestamp', $opt_timestamp, $selected_filters) ?>
                                <?= render_th('User', 'username', $opt_username, $selected_filters) ?>
                                <?= render_th('Action', 'action', $opt_action, $selected_filters) ?>
                                <?= render_th('Page Name', 'table_name', $opt_table_name, $selected_filters) ?>
                                <?= render_th('Field Name', 'field_name', $opt_field_name, $selected_filters) ?>
                                <?= render_th('Old Value', 'old_value', $opt_old_value, $selected_filters) ?>
                                <?= render_th('New Value', 'new_value', $opt_new_value, $selected_filters) ?>
                                <?= render_th('Reason', 'reason', $opt_reason, $selected_filters) ?>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if(!empty($historys)): ?>
                                <?php 
                                    $start = $this->input->get('per_page');
                                    $no = $start ? $start + 1 : 1; 
                                ?>
                                <?php foreach($historys as $row): ?>
                                
                                <?php 
                                    $raw_table = $row['table_name']; 
                                    $display_table = $raw_table; 
                                    $target_controller = '#';

                                    $table_map = [
                                        'tbl_server'        => 'Server',
                                        'tbl_operating_software' => 'Operating Software',
                                        'tbl_apps_operational_hour'    => 'Operational Hour',
                                        'tbl_apps_deployment'         => 'Deployment',
                                        'tbl_apps_category'            => 'Category',
                                        'tbl_apps_network'             => 'Network',
                                        'tbl_network_product'    => 'Network Product',
                                        'tbl_network_provider'   => 'Network Provider',
                                        'tbl_day'                => 'Operational Day',
                                        'tbl_database_master'            => 'Database',
                                        'tbl_audit_trail'        => 'History',
                                        'tbl_history'            => 'History'
                                    ];
                                    
                                    $controller_map = [
                                        'tbl_server'        => 'server',          
                                        'tbl_operating_software' => 'operating_software',
                                        'tbl_apps_operational_hour'    => 'operational_hour',
                                        'tbl_apps_deployment'         => 'deployment',
                                        'tbl_apps_category'            => 'category',
                                        'tbl_apps_network'             => 'network',
                                        'tbl_network_product'    => 'network_product',
                                        'tbl_network_provider'   => 'network_provider',
                                        'tbl_day'                => 'operational_day', 
                                        'tbl_database_master'            => 'database',
                                        'tbl_audit_trail'        => 'history',
                                        'tbl_history'            => 'history'
                                    ];
                                    
                                    if(isset($table_map[$raw_table])) {
                                        $display_table = $table_map[$raw_table];
                                    } else {
                                        $display_table = ucwords(str_replace('_', ' ', str_replace('tbl_', '', $raw_table)));
                                    }

                                    if(isset($controller_map[$raw_table])) {
                                        $target_controller = $controller_map[$raw_table];
                                    } else {
                                        $target_controller = str_replace('tbl_', '', $raw_table);
                                    }
                                ?>

                                <tr>
                                    
                                    <td class="align-middle" style="font-size: 0.9rem;">
                                        <?= date('Y-m-d H:i:s', strtotime($row['timestamp'])) ?>
                                    </td>
                                    
                                    <td class="text-center align-middle font-weight-bold" style="font-size: 0.9rem;">
                                        <?= $row['username'] ? htmlspecialchars($row['username']) : '<i class="text-muted">Unknown</i>' ?>
                                    </td>
                                    
                                    <td class="align-middle text-center">
                                        <?php 
                                            $action = strtoupper(trim($row['action'])); 
                                            
                                            // Default (Secondary/Grey)
                                            $bg = '#e9ecef'; 
                                            $color = '#495057';

                                            // Logika warna disamakan dengan Audit Trail per sub-menu
                                            if($action == 'ADD' || $action == 'INSERT') { 
                                                $bg = '#e8f5e9'; 
                                                $color = '#2e7d32'; 
                                            } elseif($action == 'EDIT' || $action == 'UPDATE') { 
                                                $bg = '#e3f2fd'; 
                                                $color = '#1565c0'; 
                                            } elseif($action == 'DEACTIVATE') { 
                                                $bg = '#ffebee'; 
                                                $color = '#c62828'; 
                                            } elseif($action == 'ACTIVATE') { 
                                                $bg = '#e0f2f1'; 
                                                $color = '#00695c';
                                            } elseif($action == 'EXPORT') { 
                                                $bg = '#fff8e1'; 
                                                $color = '#f57c00';
                                            }
                                        ?>
                                        
                                        <span class="badge px-3 py-2" style="background-color: <?= $bg ?>; color: <?= $color ?>; border-radius: 6px; font-size: 0.75rem; font-weight: 700;">
                                            <?= $action ?>
                                        </span>
                                    </td>
                                    
                                    <td class="align-middle text-muted" style="font-size: 0.9rem;">
                                        <a href="<?= base_url($target_controller) ?>" class="link-table-dynamic" style="color: #007bff; text-decoration: none;">
                                            <?= $display_table ?> 
                                            <i class="fas fa-external-link-alt ml-1" style="font-size: 0.8em; opacity: 0.7;"></i>
                                        </a>
                                    </td>

                                    <td class="align-middle text-muted" style="font-size: 0.9rem;">
                                        <?php if($row['field_name'] && $row['field_name'] != '-'): ?>
                                            <?php 
                                                // Mapping Field Name dari Database ke Label yang user-friendly
                                                $raw_field = $row['field_name'];
                                                $field_map = [
                                                    'product_name'   => 'Product Name',
                                                    'product_sla'    => 'Product SLA',
                                                    'provider_name'  => 'Provider Name',
                                                    'network_id'     => 'Network Name',
                                                    'status'         => 'Status',
                                                    'app_type_name'  => 'Application Type Name',
                                                    // Tambahkan mapping lain di sini jika diperlukan
                                                ];

                                                if(isset($field_map[$raw_field])) {
                                                    echo $field_map[$raw_field];
                                                } else {
                                                    // Jika tidak ada di map, bersihkan underscore dan jadikan Proper Case
                                                    echo ucwords(str_replace('_', ' ', $raw_field));
                                                }
                                            ?>
                                        <?php else: ?>
                                                -
                                        <?php endif; ?>
                                    </td>
                                    
                                    <td class="align-middle" style="font-size: 0.9rem;">
										<?php if($row['old_value'] != '-'): ?>
											<strike class="text-danger">
												<?php 
													if ($row['old_value'] == '0') {
														echo 'DEACTIVATE';
													} elseif ($row['old_value'] == '1') {
														echo 'ACTIVATE';
													} else {
														echo htmlspecialchars($row['old_value']);
													}
												?>
											</strike>
										<?php else: ?>
											-
										<?php endif; ?>
									</td>

									<td class="align-middle" style="font-size: 0.9rem;">
										<?php if($row['new_value'] != '-'): ?>
											<span class="text-success font-weight-bold">
												<?php 
													if ($row['new_value'] == '0') {
														echo 'DEACTIVATE';
													} elseif ($row['new_value'] == '1') {
														echo 'ACTIVATE';
													} else {
														echo htmlspecialchars($row['new_value']);
													}
												?>
											</span>
										<?php else: ?>
											-
										<?php endif; ?>
									</td>
                                    
                                    <td class="align-middle text-muted" style="font-size: 0.85rem; font-style: italic;">
                                        <?= $row['reason'] ? htmlspecialchars($row['reason']) : '-' ?>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="9" class="text-center py-4 text-muted">
                                        <i class="fas fa-search-minus fa-3x mb-3"></i><br>
                                        No History Logs Found
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </form>
            
            <div class="card-footer bg-white clearfix" style="border-top: 1px solid #dee2e6;">
                <div class="float-right">
                    <?= $pagination ?>
                </div>
                <div class="float-left">
                    <small class="text-muted">
                        Total Data: <?= isset($total_rows) ? $total_rows : 0 ?>
                    </small>
                </div>
            </div>
        </div>
      </div>
    </section>
  </div>
  
  <?php $this->load->view('layout/footer'); ?>
</div>

<div id="loadingOverlay">
    <div class="spinner-border" role="status"></div>
    <div class="mt-2 font-weight-bold" style="color: #333;">Processing...</div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/js/adminlte.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.26.17/dist/sweetalert2.all.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

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
                
                window.location.href = "<?= base_url('history/export') ?>" + window.location.search;
            }
        })
    }
    
    $(document).ready(function() {
        $('#loadingOverlay').fadeOut();
    });

     // Flashdata Success
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

    // Flashdata Error
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
    
    // --- Script Dark Mode & Logout ---
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