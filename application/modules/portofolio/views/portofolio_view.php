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
                            <a href="<?= base_url('portofolio') ?>" class="btn btn-reset btn-sm ml-1"><i class="fas fa-sync"></i> Reset</a>
                        </div>
                        <div class="col-md-4">
                            <div class="input-group">
                                <input type="text" name="keyword" class="form-control" placeholder="Search Global..." value="<?= isset($keyword) ? $keyword : '' ?>">
                                <div class="input-group-append">
                                    <button class="btn btn-default"><i class="fas fa-search"></i></button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card-body table-responsive">
                    <table class="table table-striped table-bordered table-hover text-nowrap table-custom-fixed">
                        <thead>
                            <tr class="bg-info text-center">
								<th>Action</th>
                                
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
                                <?= render_th('Flash Copy', 'flash_copy', $opt_yn, $selected_filters) ?>
                                <?= render_th('End of Day', 'eod', $opt_yn, $selected_filters) ?>
                                
                                <?= render_th('Network', 'network', $opt_network, $selected_filters) ?>
                                <?= render_th('Deployment', 'deployment', $opt_deploy, $selected_filters) ?>
                                <?= render_th('Operational Hour', 'op_hour', $opt_op_hour, $selected_filters) ?>
                                <?= render_th('Operational Day', 'op_day', $opt_op_day, $selected_filters) ?>
                                
                                <?= render_th('Principle', 'principle', $opt_principle, $selected_filters) ?>
                                <?= render_th('Principle Solution', 'principle_sol', $opt_principle_sol, $selected_filters) ?>
                                
                                <?= render_th('IT Group', 'it_group', $opt_it_group, $selected_filters) ?>
                                <?= render_th('IT Division', 'it_division', $opt_it_div, $selected_filters) ?>
                                <?= render_th('Directorate', 'directorate', $opt_directorate, $selected_filters) ?>
                                <?= render_th('Sub-Directorate', 'sub_directorate', $opt_sub_dir, $selected_filters) ?>
                                <?= render_th('Owner Title', 'owner_title', $opt_owner_title, $selected_filters) ?>
                                <?= render_th('Head Owner', 'nik_head', $opt_nik_head, $selected_filters) ?>
                                <?= render_th('Owner', 'nik_owner', $opt_nik_owner, $selected_filters) ?>
                                <?= render_th('IT Department', 'nik_dept', $opt_nik_dept, $selected_filters) ?>
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
                                <td><?= $row['module_name'] ?></td>
								<td><?= str_replace(',', ',<br>', $row['database_names']) ?></td>
								<td><?= str_replace(',', ',<br>', $row['os_names']) ?></td>
                                <td><?= $row['application_type'] ?></td>
                                <td title="<?= $row['apps_description'] ?>"><?= substr($row['apps_description'],0,20) ?></td>
                                <td class="text-center"><?= $row['live_year'] ?></td>
                                <td class="text-center"><?= $row['decommission_year'] ?></td>
                                <td class="text-center"><?= $row['resilience'] ?></td>
                                <td class="text-center"><?= $row['dr_availability'] ?></td>
                                <td class="text-center"><?= $row['ha'] ?></td>
                                <td class="text-center"><?= $row['flash_copy'] ?></td>
                                <td class="text-center"><?= $row['end_of_day'] ?></td>
                                <td><?= $row['network_name'] ?></td>
                                <td><?= $row['deployment_info'] ?></td>
                                <td><?= $row['operational_hour'] ?></td>
                                <td><?= $row['operational_day'] ?></td>
                                <td><?= $row['principle_name'] ?></td>
                                <td><?= $row['principle_solution_name'] ?></td>
                                <td><?= $row['it_group_name'] ?></td>
                                <td><?= $row['it_division_name'] ?></td>
                                <td><?= $row['owner_directorate'] ?></td>
                                <td><?= $row['owner_subdirectorate'] ?></td>
                                <td><?= $row['owner_title'] ?></td>
                                <td><?= $row['nik_owner_head'] ?></td>
                                <td><?= $row['nik_owner'] ?></td>
                                <td><?= $row['nik_it_department'] ?></td>
                            </tr>
                            <?php endforeach; else: ?>
                                <tr><td colspan="40" class="text-center">No Data Found</td></tr>
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
        // No Loading Spinner here
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
        $('#loadingOverlay').css('display', 'flex'); // Show loading on clear (optional)
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
    
    function showAddModal() { $('#modalForm').modal('show'); }
    
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

    // 2. Flashdata Error (Jika ada duplikat nama database) -> INI YANG DITAMBAHKAN
    <?php if($this->session->flashdata('error')): ?>
        Swal.fire({
            icon: 'error',
            title: 'Gagal!',
            text: '<?= $this->session->flashdata('error') ?>',
            confirmButtonText: 'OK',
            buttonsStyling: false,
            // Tombol warna merah
            customClass: { confirmButton: 'btn btn-danger px-4' } 
        });
        <?php unset($_SESSION['error']); ?>
    <?php endif; ?>
    
    // --- 1. Script Dark Mode (Versi Universal) ---
    const toggleBtn = document.getElementById('darkModeBtn');
    const body = document.body;
    // Cek dulu apakah tombol ada (untuk menghindari error di halaman tanpa navbar)
    const icon = toggleBtn ? toggleBtn.querySelector('i') : null;

    // Fungsi ganti icon
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

    // Cek status saat load
    if (localStorage.getItem('theme') === 'dark') {
        if(!body.classList.contains('dark-mode')){
            body.classList.add('dark-mode');
        }
        updateIcon(true);
    }

    // Event Listener Klik
    if(toggleBtn) {
        toggleBtn.addEventListener('click', function(e) {
            e.preventDefault();
            
            body.classList.toggle('dark-mode');
            const isDark = body.classList.contains('dark-mode');
            
            // Simpan & Update Icon
            if (isDark) {
                localStorage.setItem('theme', 'dark');
                updateIcon(true);
            } else {
                localStorage.setItem('theme', 'light');
                updateIcon(false);
            }

            // --- PERUBAHAN PENTING DISINI ---
            // Cek dulu: Apakah fungsi updateChartTheme SUDAH DIBUAT di halaman ini?
            // Jika halaman ini punya grafik (Portofolio), maka jalankan.
            // Jika halaman ini tidak punya grafik (Database), maka LEWATI agar tidak error.
            if (typeof updateChartTheme === 'function') {
                updateChartTheme(isDark);
            }
        });
    }
    
    // --- Script Logout ---
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