<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>SLIM | Operational Day</title>
  
  <?php $this->load->view('layout/head_links'); ?>

</head>

<body class="hold-transition sidebar-mini layout-fixed sidebar-collapse">

<script>
    if (localStorage.getItem('theme') === 'dark') {
        document.body.classList.add('dark-mode');
    }
</script>

<div class="wrapper">

  <?php $this->load->view('layout/header'); ?>
  <?php $this->load->view('layout/sidebar'); ?>

  <div class="content-wrapper">
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0" style="color: var(--text-dark); font-weight: 700;">Operational Day Management</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item">
                <a href="<?= base_url('portofolio')?>" class="breadcrumb-home">Home</a></li>
              <li class="breadcrumb-item active">Operational Day</li>
            </ol>
          </div>
        </div>
      </div>
    </div>

    <section class="content">
      <div class="container-fluid">
        
        <div class="card card-submenu-compact" style="border-top: 3px solid var(--theme-yellow-primary);">
            
            <form id="mainFilterForm" action="<?= base_url('operational_day') ?>" method="get">
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

                <div class="card-header" style="background-color: #fff;">
                    <div class="row align-items-center">
                        <div class="col-md-8">
							<button type="button" class="btn btn-add-custom btn-sm" onclick="clearForm()">
								<i class="fas fa-plus mr-1"></i> Add
							</button>
							<button type="button" class="btn btn-export-custom btn-sm ml-2" onclick="confirmExport()">
								<i class="fas fa-file-export mr-1"></i> Export
							</button>
						</div>
                        <div class="col-md-4">
                            <div class="input-group">
                                <input type="text" name="keyword" class="form-control" placeholder="Search Operational Day..." value="<?= isset($keyword) ? $keyword : '' ?>">
                                <div class="input-group-append">
                                    <button class="btn btn-default">
                                        <i class="fas fa-search"></i>
                                    </button>
                                    <a href="<?= base_url('operational_day') ?>" class="btn btn-secondary d-flex align-items-center">
                                        <i class="fas fa-sync-alt"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            
                <div class="card-body">
                    <div class="table-responsive" style="flex: 1; overflow: auto;">
                        <table class="table table-striped table-bordered table-hover text-nowrap" style="width: 100%;">
                            <thead>
                                <tr class="bg-info">
    								<th style="width: 200px; text-align: center; color: var(--text-dark);">Action</th>
                                    
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

                                    <?= render_th('Start Day', 'start_day', $opt_start_day, $selected_filters) ?>
                                    <?= render_th('End Day', 'end_day', $opt_end_day, $selected_filters) ?>
                                    <?= render_th('Total Day', 'total_day', $opt_total_day, $selected_filters) ?> 
                                    <th style="width: 200px; text-align: center; color: var(--text-dark);">Status</th>
                                </tr>
    							
                            </thead>
                            <tbody>
                                <?php if(!empty($operational_days)): ?>
                                    <?php 
                                        $start = $this->input->get('per_page');
                                        $no = $start ? $start + 1 : 1; 
                                    ?>
                                    <?php foreach($operational_days as $db): ?>
                                    <tr>
    									<td class="text-center align-middle">
    										<div class="dropdown">
    											<button class="btn btn-sm btn-secondary dropdown-toggle btn-operation" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
    												<i class="fas fa-cog mr-2"></i> Actions
    											</button>
    											
    											<div class="dropdown-menu dropdown-menu-right shadow dropdown-operation-menu">
    												<button class="dropdown-item" type="button" onclick="window.location.href='<?= base_url('operational_day/audit/'.$db['operational_day_id']) ?>'">
    													<i class="fas fa-clipboard-list fa-fw text-primary mr-2"></i> Audit Trail
    												</button>

    												<?php if($db['status'] == 1): ?>
    													<button class="dropdown-item" type="button" onclick="editOpD(<?= $db['operational_day_id'] ?>, '<?= $db['start_day'] ?>', '<?= $db['end_day'] ?>', '<?= $db['total_day'] ?>')">
    														<i class="fas fa-edit fa-fw text-warning mr-2"></i> Edit Data
    													</button>
    												
    													<button class="dropdown-item" type="button" onclick="confirmDelete(<?= $db['operational_day_id'] ?>)">
    														<i class="fas fa-power-off fa-fw text-danger mr-2"></i> Deactivate
    													</button>
    												<?php else: ?>
    													
    													<button class="dropdown-item" type="button" onclick="confirmRestore(<?= $db['operational_day_id'] ?>)">
    														<i class="fas fa-undo-alt fa-fw text-success mr-2"></i> Activate
    													</button>
    												<?php endif; ?>
    											</div>
    										</div>
    									</td>
                                        <td class="align-middle"><?= $db['start_day'] ?></td>
                                        <td class="align-middle"><?= $db['end_day'] ?></td>
                                        <td class="align-middle"><?= $db['total_day'] ?> Days</td>
    									<td class="text-center align-middle">
    										<?php 
    											if (isset($db['status']) && $db['status'] == 1) { 
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
                                    </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="5" class="text-center">No Data Found</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </form>
            
            <div class="custom-card-footer bg-white d-flex align-items-center" style="border-top: 1px solid #dee2e6;">
                <div style="flex: 1; text-align: left; font-size: 9px !important;" class="text-muted">
                            Total Data: <b class="text-dark"><?= isset($total_rows) ? $total_rows : 0 ?></b>
                </div>
                 <div class="float-right">
                    <?= $pagination ?>
                </div>
            </div>
        </div>
      </div>
    </section>
  </div>

  <?php $this->load->view('layout/footer'); ?>
  
</div>

<div class="modal fade modal-submenu-compact" id="modalOperational_Day" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header" style="background: linear-gradient(135deg, var(--theme-bg-yellow-light) 0%, var(--theme-bg-yellow-dark) 100%); color: var(--text-dark);">
        <h5 class="modal-title" id="modalTitle">Add Operational Day</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form action="<?= base_url('operational_day/save') ?>" method="post">
            <div class="modal-body">
                <input type="hidden" name="operational_day_id" id="operational_day_id">
                
               <div class="form-group" style="font-size: 9px;">
                    <label>Start Day</label>
                    <select name="start_day" id="start_day" class="form-control select2" style="width: 100%;" required>
                        <option value=""></option> <option value="Monday">Monday</option>
                        <option value="Tuesday">Tuesday</option>
                        <option value="Wednesday">Wednesday</option>
                        <option value="Thursday">Thursday</option>
                        <option value="Friday">Friday</option>
                        <option value="Saturday">Saturday</option>
                        <option value="Sunday">Sunday</option>
                    </select>
                </div>

                <div class="form-group " style="font-size: 9px;">
                    <label>End Day</label>
                    <select name="end_day" id="end_day" class="form-control select2" style="width: 100%;" required>
                        <option value=""></option> <option value="Monday">Monday</option>
                        <option value="Tuesday">Tuesday</option>
                        <option value="Wednesday">Wednesday</option>
                        <option value="Thursday">Thursday</option>
                        <option value="Friday">Friday</option>
                        <option value="Saturday">Saturday</option>
                        <option value="Sunday">Sunday</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Total Day</label>
                    <input type="text" id="total_day" class="form-control" readonly style="background-color: #e9ecef; cursor: not-allowed;" placeholder="0 Days">
                </div>

                <div class="form-group" id="reason_container" style="display: none;">
                    <label>Reason</label>
                    <textarea name="reason" id="reason" class="form-control" rows="2" placeholder="Masukkan alasan..."></textarea>
                </div>
            </div>
            
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-save-custom">Submit</button>
            </div>
      </form>
    </div>
  </div>
</div>

<div id="loadingOverlay">
    <svg class="spinner-svg" fill="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
        <defs>
            <filter id="spinner-gF00">
                <feGaussianBlur in="SourceGraphic" stdDeviation="1.5" result="y"/>
                <feColorMatrix in="y" mode="matrix" values="1 0 0 0 0 0 1 0 0 0 0 0 1 0 0 0 0 0 18 -7" result="z"/>
                <feBlend in="SourceGraphic" in2="z"/>
            </filter>
        </defs>
        <g filter="url(#spinner-gF00)">
            <circle cx="4" cy="12" r="3">
                <animate attributeName="cx" calcMode="spline" dur="0.75s" values="4;9;4" keySplines=".56,.52,.17,.98;.56,.52,.17,.98" repeatCount="indefinite"/>
                <animate attributeName="r" calcMode="spline" dur="0.75s" values="3;8;3" keySplines=".56,.52,.17,.98;.56,.52,.17,.98" repeatCount="indefinite"/>
            </circle>
            <circle cx="15" cy="12" r="8">
                <animate attributeName="cx" calcMode="spline" dur="0.75s" values="15;20;15" keySplines=".56,.52,.17,.98;.56,.52,.17,.98" repeatCount="indefinite"/>
                <animate attributeName="r" calcMode="spline" dur="0.75s" values="8;3;8" keySplines=".56,.52,.17,.98;.56,.52,.17,.98" repeatCount="indefinite"/>
            </circle>
        </g>
    </svg>
    <div class="loading-text">Processing...</div>
</div>

<?php $this->load->view('layout/foot_links'); ?>

<script>
    $(document).ready(function() {
        // Inisialisasi untuk Start Day
        $('#start_day').select2({
            theme: 'bootstrap4',
            placeholder: "Select Start Day",
            allowClear: false,
                        width: '100%',
            dropdownParent: $('#modalOperational_Day'),
            minimumResultsForSearch: Infinity
        });

        // Inisialisasi untuk End Day
        $('#end_day').select2({
            theme: 'bootstrap4',
            placeholder: "Select End Day",
            allowClear: false,
                        width: '100%',
            dropdownParent: $('#modalOperational_Day'),
            minimumResultsForSearch: Infinity
        });
        
        $('#loadingOverlay').fadeOut();
    });

    // --- FILTER LOGIC (NO LOADING ON APPLY) ---
    function filterList(input) {
        var filter = input.value.toUpperCase();
        var div = input.parentNode.nextElementSibling;
        var labels = div.getElementsByTagName("label");
        for (var i = 0; i < labels.length; i++) {
            var txtValue = labels[i].textContent || labels[i].innerText;
            if (txtValue.toUpperCase().indexOf(filter) > -1) labels[i].style.display = "block";
            else labels[i].style.display = "none";
        }
    }

    function applyFilter(key) {
        // NO LOADING HERE
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
        $('#loadingOverlay').css('display', 'flex'); // Optional on clear
        var checkboxes = document.querySelectorAll('input[type="checkbox"][data-key="' + key + '"]');
        checkboxes.forEach(cb => cb.checked = false);
        $('.filter-applied-' + key).remove();
        document.getElementById('mainFilterForm').submit();
    }

    // --- STANDARD SCRIPTS ---
    function clearForm() {
        $('#modalTitle').text('Add Operational Day');
        $('#operational_day_id').val('');
        
        // PERBAIKAN: Tambahkan .trigger('change') agar Select2 ikut kereset
        $('#start_day').val('').trigger('change');
        $('#end_day').val('').trigger('change');
        $('#total_day').val(''); 
        
        $('#reason').val(''); 
        $('#reason_container').hide(); 

        $('#modalOperational_Day').modal('show'); 
    }

    function editOpD(id, start_day, end_day, total_day) {
        $('#modalTitle').text('Edit Operational Day');
        $('#operational_day_id').val(id);
        
        // PERBAIKAN: Tambahkan .trigger('change') agar Select2 merender nilai yang dipilih
        $('#start_day').val(start_day).trigger('change');
        $('#end_day').val(end_day).trigger('change');
        
        if(total_day) {
            $('#total_day').val(total_day + " Days");
        } else {
            $('#total_day').val('');
        }
        
        $('#reason').val(''); 
        $('#reason_container').show(); 
        
        $('#modalOperational_Day').modal('show'); 
    }

    $(document).ready(function() {
        const days = ["Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday", "Sunday"];

        $('#start_day, #end_day').on('change', function() {
            var startVal = $('#start_day').val();
            var endVal = $('#end_day').val();

            if (startVal && endVal) {
                var startIdx = days.indexOf(startVal);
                var endIdx = days.indexOf(endVal);

                if (startIdx !== -1 && endIdx !== -1) {
                    var diff = (endIdx - startIdx + 7) % 7;
                    var totalDays = diff + 1;
                    $('#total_day').val(totalDays + " Days");
                }
            } else {
                $('#total_day').val('');
            }
        });
        
        // Hide Loading on Load
        $('#loadingOverlay').fadeOut();
    });
    
    function confirmExport() {
        Swal.fire({
            title: 'Export to Excel?',
            text: "File akan otomatis diunduh.",
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
                // Append filter params
                window.location.href = "<?= base_url('operational_day/export') ?>" + window.location.search;
            }
        })
    }
    
    function confirmDelete(id) {
        $('#loadingOverlay').css('display', 'flex');

        $.ajax({
            url: '<?= base_url("home/api_check_master_usage") ?>',
            type: 'POST',
            dataType: 'json',
            data: {
                table_name: 'tbl_apps_operational_day',
                id_value: id
            },
            success: function(response) {
                $('#loadingOverlay').css('display', 'none');

                if (response.is_used) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Deactivate Ditolak!',
                        text: 'Data sedang digunakan aplikasi. ',
                        confirmButtonText: 'OK',
                        customClass: {
                            confirmButton: 'btn btn-theme-gradient px-4 mx-2'
                        }
                    });
                } else {
                    Swal.fire({
                        title: 'Apakah Anda yakin?',
                        text: "Data akan dinonaktifkan.",
                        icon: 'warning',
                        input: 'text',
                        inputLabel: 'Alasan:',
                        inputPlaceholder: 'Masukkan Alasan...',
                        showCancelButton: true,
                        confirmButtonText: 'Yes, Deactivate',
                        cancelButtonText: 'Cancel',
                        reverseButtons: true,
                        buttonsStyling: false, 
                        customClass: {
                            confirmButton: 'btn btn-deactivate px-4 mx-2',  
                            cancelButton: 'btn btn-secondary px-4 mx-2'
                        },
                        inputAttributes: {
                            style: 'width: 95%; margin: 10px auto; display: block; border: 1px solid #ced4da; padding: 8px; border-radius: 4px;'
                        },
                        inputValidator: (value) => {
                            if (!value) {
                                return 'Anda harus menuliskan alasan!';
                            }
                        }
                    }).then((result) => {
                        if (result.isConfirmed) {
                            $('#loadingOverlay').css('display', 'flex');
                            
                            $.ajax({
                                url: "<?= base_url('operational_day/update_status') ?>",
                                type: "POST",
                                dataType: "JSON",
                                data: { 
                                    id: id, 
                                    status: 0, 
                                    reason: result.value 
                                },
                                success: function(response) {
                                    $('#loadingOverlay').css('display', 'none');


                                    if (response.success) {
                                        Swal.fire({
                                            icon: 'success',
                                            title: 'Berhasil',
                                            text: response.message,
                                            confirmButtonText: 'OK',
                                            customClass: {
                                                confirmButton: 'btn btn-theme-gradient px-4 mx-2'
                                            }
                                        }).then(() => {
                                            location.reload(); 
                                        });
                                    } else {
                                        Swal.fire({
                                            icon: 'error',
                                            title: 'Gagal',
                                            text: response.message,
                                            confirmButtonText: 'OK',
                                            customClass: {
                                                confirmButton: 'btn btn-theme-gradient px-4 mx-2'
                                            }
                                        });
                                        $('#loadingOverlay').css('display', 'none');
                                    }
                                },
                                error: function() {
                                    $('#loadingOverlay').css('display', 'none');
                                    Swal.fire({
                                        icon: 'error',
                                        title: 'Error',
                                        text: 'Gagal memproses data ke server',
                                        confirmButtonText: 'OK',
                                        customClass: {
                                            confirmButton: 'btn btn-theme-gradient px-4 mx-2'
                                        }
                                    });
                                }
                            });
                        }
                    });
                }
            },
            error: function() {
                $('#loadingOverlay').css('display', 'none');
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Gagal terhubung ke server validasi.',
                    confirmButtonText: 'OK',
                    customClass: {
                        confirmButton: 'btn btn-theme-gradient px-4 mx-2'
                    }
                });
            }
        });
    }

    function confirmRestore(id) {
        Swal.fire({
            title: 'Aktifkan Kembali?',
            text: "Data ini akan dikembalikan ke daftar aktif.",
            icon: 'info',
            input: 'text',
            inputLabel: 'Alasan Pengaktifan:',
            inputPlaceholder: 'Masukkan Alasan...',
            showCancelButton: true,
            confirmButtonText: 'Yes, Activate',
            cancelButtonText: 'Cancel',
            reverseButtons: true,
            buttonsStyling: false,
            customClass: {
                confirmButton: 'btn btn-activate px-4 mx-2',
                cancelButton: 'btn btn-secondary px-4 mx-2'
            },
            inputAttributes: {
                style: 'width: 95%; margin: 10px auto; display: block; border: 1px solid #ced4da; padding: 8px; border-radius: 4px;'
            },
            inputValidator: (value) => {
                if (!value) {
                    return 'Anda harus menuliskan alasan!';
                }
            }
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: "<?= base_url('operational_day/update_status') ?>",
                    type: "POST",
                    dataType: "JSON",
                    data: { 
                        id: id, 
                        status: 1, // Balikkan ke Active
                        reason: result.value 
                    },
                    success: function(response) {
                        $('#loadingOverlay').css('display', 'none');
                        
                        if (response.success) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Berhasil',
                                text: response.message,
                                confirmButtonText: 'OK',
                                customClass: {
                                    confirmButton: 'btn btn-theme-gradient px-4 mx-2'
                                }
                            }).then(() => {
                                location.reload(); 
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Gagal',
                                text: response.message, // Menampilkan pesan gagal dari controller
                                confirmButtonText: 'OK',
                                customClass: {
                                    confirmButton: 'btn btn-theme-gradient px-4 mx-2'
                                }
                            });
                        }
                    },
                    error: function() {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Gagal memproses data ke server',
                            confirmButtonText: 'OK',
                            customClass: {
                                confirmButton: 'btn btn-theme-gradient px-4 mx-2'
                            }
                        });
                    }
                });
            }
        })
    }

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

    $(document).on('input', '#reason, input[name="keyword"], .filter-search-input, .swal2-popup input[type="text"], .swal2-popup textarea', function() {
        var el = $(this);
        var currentValue = el.val();
        var forbiddenChars;
        var isTotalDayFilter = false;

        if (el.hasClass('filter-search-input')) {
            var columnKey = el.closest('.custom-filter-dropdown').find('input[type="checkbox"]').first().data('key');
            if (columnKey === 'total_day') {
                isTotalDayFilter = true;
            }
        }

        if (isTotalDayFilter) {
            forbiddenChars = /[^0-9]/g;
        } else {
            forbiddenChars = /[^a-zA-Z0-9\s.,_\-]/g; 
        }

        if (forbiddenChars.test(currentValue)) {
            el.val(currentValue.replace(forbiddenChars, ''));
            
            el.css({
                'border-color': '#dc3545',
                'box-shadow': '0 0 0 0.2rem rgba(220, 53, 69, 0.25)'
            });
            setTimeout(function() {
                el.css({ 'border-color': '', 'box-shadow': '' });
            }, 400);
            
            if (el.hasClass('filter-search-input')) {
                filterList(this);
            }
        }
    });

    $(document).on('paste', '#reason, input[name="keyword"], .filter-search-input, .swal2-popup input[type="text"], .swal2-popup textarea', function(e) {
        var el = $(this);
        // Ambil data teks dari clipboard (apa yang sedang di-copas)
        var pasteData = (e.originalEvent || e).clipboardData.getData('text');
        var forbiddenChars;
        var isTotalDayFilter = false;

        // 1. CEK: Apakah ini kotak pencarian di filter Total Day?
        if (el.hasClass('filter-search-input')) {
            var columnKey = el.closest('.custom-filter-dropdown').find('input[type="checkbox"]').first().data('key');
            if (columnKey === 'total_day') {
                isTotalDayFilter = true;
            }
        }

        // 2. TENTUKAN ATURAN REGEX (Sesuai logika input kamu)
        if (isTotalDayFilter) {
            // Hanya boleh Angka (0-9)
            forbiddenChars = /[^0-9]/g;
        } else {
            // Umum: Huruf, Angka, Spasi, Titik, Koma, Strip, Underscore
            forbiddenChars = /[^a-zA-Z0-9\s.,_\-]/g; 
        }

        // 3. EKSEKUSI BLOKIR JIKA MENGANDUNG KARAKTER TERLARANG
        if (forbiddenChars.test(pasteData)) {
            // Batalkan proses paste secara total
            e.preventDefault();
            
            // Beri feedback visual border merah berkedip
            el.css({
                'border-color': '#dc3545',
                'box-shadow': '0 0 0 0.2rem rgba(220, 53, 69, 0.25)'
            });
            setTimeout(function() {
                el.css({ 'border-color': '', 'box-shadow': '' });
            }, 400);
        }
    });
</script>
</body>
</html>