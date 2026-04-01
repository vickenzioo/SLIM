<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <title>SLIM | User Role</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <?php $this->load->view('layout/head_links'); ?>
  
</head>

<body class="hold-transition sidebar-mini layout-fixed sidebar-collapse">

<script>
    if (localStorage.getItem('theme') === 'dark') { document.body.classList.add('dark-mode'); }
</script>

<div id="loadingOverlay">
    <div class="spinner-border text-warning" role="status"></div>
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
            <h1 class="m-0" style="color: var(--text-dark); font-weight: 700;">User Management</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="<?= base_url('portofolio')?>" class="breadcrumb-home">Home</a></li>
              <li class="breadcrumb-item active">User Role</li>
            </ol>
          </div>
        </div>
      </div>
    </section>

    <section class="content">
      <div class="container-fluid">
        
        <div class="card" style="border-top: 3px solid var(--theme-yellow-primary);">
            
            <form id="mainFilterForm" action="<?= base_url('user_role') ?>" method="get">
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

                            <div class="btn-group">
                                <button type="button" class="btn btn-add-custom btn-sm dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <i class="fas fa-plus mr-1"></i> Add
                                </button>
                                <div class="dropdown-menu shadow dropdown-operation-menu">
                                    <button class="dropdown-item" type="button" data-toggle="modal" data-target="#modalAddUser">
                                        <i class="fas fa-user-plus fa-fw text-primary mr-2"></i> Add New User
                                    </button>
                                    <button class="dropdown-item" type="button" onclick="openAssignModal()">
                                        <i class="fas fa-user-tag fa-fw text-warning mr-2"></i> Assign Role
                                    </button>
                                </div>
                            </div>
                           
							<button type="button" class="btn btn-export-custom btn-sm ml-2" onclick="confirmExport()">
								<i class="fas fa-file-export mr-1"></i> Export
							</button>
                        </div>
                        <div class="col-md-4">
                            <div class="input-group">
                                <input type="text" name="keyword" class="form-control" placeholder="Search User..." value="<?= isset($keyword) ? $keyword : '' ?>">
                                <div class="input-group-append">
                                    <button class="btn btn-default">
                                        <i class="fas fa-search"></i>
                                    </button>
                                    <a href="<?= base_url('user_role') ?>" class="btn btn-secondary d-flex align-items-center">
                                        <i class="fas fa-sync-alt"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            
                <div class="card-body">
                    <table class="table table-striped table-bordered table-hover mb-0">
                        <thead>
                            <tr class="bg-info text-center">
                                <th class="text-center" style="width: 200px;">Action</th>
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

                                <?= render_th('Username', 'username', $opt_username, $selected_filters) ?>
                                <?= render_th('Email', 'email', $opt_email, $selected_filters) ?>
                                <th class="text-center" style="width: 200px;">Password</th>
                                <?= render_th('Role Assignment', 'Role Assignment', $opt_role_name, $selected_filters) ?>
                                <th style="width: 200px; text-align: center; color: var(--text-dark);">Status</th>

                            </tr>
                        </thead>
                        <tbody>
                            <?php if(!empty($user_roles)): ?>
                                <?php 
                                    $no = $this->input->get('per_page') + 1; 
                                    foreach($user_roles as $ur): 
                                ?>
                                <tr>
                                    <td class="text-center align-middle">
                                        <div class="dropdown">
                                            <button class="btn btn-sm btn-secondary dropdown-toggle btn-operation" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                <i class="fas fa-cog mr-2"></i> Operation
                                            </button>
                                            
                                            <div class="dropdown-menu dropdown-menu-right shadow dropdown-operation-menu">
                                                <a href="<?= base_url('user_role/audit/' . $ur['user_role_id']); ?>" class="dropdown-item">
                                                    <i class="fas fa-clipboard-list fa-fw text-primary mr-2"></i> Audit Trail
                                                </a>

                                                <?php if($ur['status'] == 1): ?>
                                                    <button class="dropdown-item" type="button" 
                                                        onclick="openEditModal(
                                                            '<?= $ur['user_role_id']; ?>', 
                                                            '<?= $ur['id']; ?>', 
                                                            '<?= $ur['role_id']; ?>', 
                                                            '<?= htmlspecialchars($ur['username'], ENT_QUOTES); ?>', 
                                                            '<?= htmlspecialchars($ur['email'], ENT_QUOTES); ?>'
                                                        )">
                                                        <i class="fas fa-edit fa-fw text-warning mr-2"></i> Edit Data
                                                    </button>
                                                    
                                                    <button class="dropdown-item" type="button" onclick="confirmDelete(<?= $ur['user_role_id'] ?>)">
                                                        <i class="fas fa-power-off fa-fw text-danger mr-2"></i> Deactivate
                                                    </button>
                                                <?php else: ?>
                                                    <button class="dropdown-item" type="button" onclick="confirmRestore(<?= $ur['user_role_id'] ?>)">
                                                        <i class="fas fa-undo-alt fa-fw text-success mr-2"></i> Activate
                                                    </button>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </td>
                                    <td><strong><?= $ur['username'] ?></strong></td>
                                    <td><?= $ur['email'] ?></td>
                                    <td>********</td>
                                    <td>
                                        <?php 
                                            // Badge Color Logic
                                            $bg = '#f8f9fa'; $color = '#6c757d'; 
                                            $role_name = strtoupper(trim($ur['role_name']));

                                            // Penentuan warna spesifik untuk 3 Role Utama
                                            switch ($role_name) {
                                                case 'IT SLM':
                                                    $bg = 'rgba(0, 210, 211, 0.15)'; $color = '#008a8a';
                                                    break;
                                                case 'EA':
                                                    $bg = 'rgba(162, 155, 254, 0.2)'; $color = '#6c5ce7'; // Ungu Muda
                                                    break;
                                                case 'IT DEV':
                                                    $bg = 'rgba(232, 67, 147, 0.15)'; $color = '#d63031'; 
                                                    break;
                                                default:
                                                    $bg = '#f8f9fa'; $color = '#6c757d';
                                                    break;
                                            }
                                        ?>
                                        <span class="badge px-3 py-2" style="background-color: <?= $bg ?>; color: <?= $color ?>; border-radius: 6px; font-size: 0.75rem; font-weight: 800; letter-spacing: 0.5px;">
                                            <?= $role_name ?>
                                        </span>
                                    </td>
                                    <td class="text-center align-middle">
                                        <?php 
                                            // Menggunakan variabel $ur sesuai loop yang Anda gunakan
                                            // Pastikan mengecek $ur['status'] == 1 untuk Active
                                            if ($ur['status'] == 1) {
                                                $status_bg = '#e8f5e9';    
                                                $status_color = '#2e7d32'; 
                                                $status_label = 'Active';
                                            } else {
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
                                <tr><td colspan="6" class="text-center py-4 text-muted">No Role Assignments Found</td></tr>
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
                <small class="text-muted">Total Data: <?= isset($total_rows) ? $total_rows : 0 ?></small>
            </div>
        </div>
      </div>
    </section>
  </div>

  <?php $this->load->view('layout/footer'); ?>
</div>

<div class="modal fade" id="modalAddUser" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form action="<?= base_url('user_role/add_user') ?>" method="post">
                <div class="modal-header" style="background: linear-gradient(135deg, var(--theme-bg-yellow-light) 0%, var(--theme-bg-yellow-dark) 100%); color: var(--text-dark);">
                    <h5 class="modal-title font-weight-bold">Create New User Account</h5>
                    <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>Username</label>
                        <input type="text" name="username" class="form-control" required placeholder="Enter username...">
                    </div>
                    <div class="form-group">
                        <label>Email Address</label>
                        <input type="email" name="email" class="form-control" required placeholder="example@mail.com">
                    </div>
                    <div class="form-group">
                        <label>Password</label>
                        <div class="input-group">
                            <input type="password" name="password" id="passwordInputAdd" class="form-control" required placeholder="******">
                            <div class="input-group-append">
                                <button class="btn btn-outline-secondary" type="button" id="togglePasswordAdd">
                                    <i class="fas fa-eye" id="eyeIconAdd"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-save-custom">Save Changes</button>
                </div>
            </form>
        </div>
    </div>
</div>


<div class="modal fade" id="modalAssignRole" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form action="<?= base_url('user_role/save') ?>" method="post">
                <div class="modal-header" style="background: linear-gradient(135deg, var(--theme-bg-yellow-light) 0%, var(--theme-bg-yellow-dark) 100%); color: var(--text-dark);">
                    <h5 class="modal-title font-weight-bold">Assign User Role</h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>Username - Email</label>
                        <select name="user_id" class="form-control select2" data-placeholder="-- Pilih User --" style="width: 100%;" required>
                            <option value=""></option> 
                            <?php foreach($users_no_role as $u): ?>
                                <option value="<?= $u['id'] ?>"><?= $u['username'] ?> (<?= $u['email'] ?>)</option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Role Assignment</label>
                        <select name="role_id" class="form-control select2" data-placeholder="-- Select Role --" style="width: 100%;" required>
                            <option value=""></option> <?php foreach($roles as $r): ?>
                                <option value="<?= $r['role_id'] ?>"><?= $r['role_name'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-save-custom">Save Changes</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="modalEditData" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form action="<?= base_url('user_role/save') ?>" method="post">
                <div class="modal-header" style="background: linear-gradient(135deg, var(--theme-bg-yellow-light) 0%, var(--theme-bg-yellow-dark) 100%); color: var(--text-dark);">
                    <h5 class="modal-title font-weight-bold">Edit Assignment</h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="id" id="edit_user_role_id">
                    <input type="hidden" name="user_id" id="edit_user_id">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Username</label>
                                <input type="text" name="username" id="edit_username" class="form-control" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Email Address</label>
                                <input type="email" name="email" id="edit_email" class="form-control" required>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Role Assignment</label>
                        <select name="role_id" id="edit_role_id" class="form-control select2" data-placeholder="-- Pilih Role --" style="width: 100%;" required>
                            <option value=""></option>
                            <?php foreach($roles as $r): ?>
                                <option value="<?= $r['role_id'] ?>"><?= $r['role_name'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                   <div class="form-group" id="reason_container" style="display: none;">
                        <label>Reason</label>
                        <textarea name="reason" id="reason" class="form-control" rows="2" placeholder="Masukkan alasan..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-save-custom">Save Changes</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php $this->load->view('layout/foot_links'); ?>

<script>
    $(document).ready(function() {
        // Inisialisasi semua Select2 di dalam modal
        $('.select2').each(function() {
            var $this = $(this);
            $this.select2({
                theme: 'bootstrap4',
                placeholder: $this.data('placeholder'),
                allowClear: false,
                width: '100%',
                dropdownParent: $this.closest('.modal'),
                minimumResultsForSearch: Infinity
            });
        });

        // Menghilangkan highlight biru default Select2 saat dibuka
        $('.select2').on('select2:open', function() {
            document.querySelector('.select2-search__field').focus();
        });
        
        $('#loadingOverlay').fadeOut();
    });


    $(document).ready(function() {
        // Fungsi Show/Hide Password
        $('#togglePasswordAdd').on('click', function() {
            const passwordField = $('#passwordInputAdd');
            const eyeIcon = $('#eyeIconAdd');
            
            // Cek tipe input
            const type = passwordField.attr('type') === 'password' ? 'text' : 'password';
            passwordField.attr('type', type);
            
            // Ganti icon
            eyeIcon.toggleClass('fa-eye fa-eye-slash');
        });
    });



    // --- FILTER LOGIC ---
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

    function openAssignModal() {
        $('#modalAssignRole form')[0].reset();
        $('#modalAssignRole .select2').val(null).trigger('change');
        $('#modalAssignRole').modal('show');
    }

    function openEditModal(id, userId, roleId, username, email) {
        $('#edit_user_role_id').val(id);
        $('#edit_user_id').val(userId);
        $('#edit_username').val(username);
        $('#edit_email').val(email);
        $('#edit_role_id').val(roleId).trigger('change');
        
        // Tampilkan field reason
        $('#reason_container').show(); 
        // Reset isi reason (sesuai id="reason" di HTML)
        $('#reason').val(''); 
        
        $('#modalEditData').modal('show');
    }

    // Fungsi saat klik tombol "Assign Role"
    function openAddRoleModal() {
        $('#userRoleModalLabel').text('Assign Role');
        $('#userRoleForm')[0].reset();
        
        // Reset select2 jika digunakan
        $('#user_id_select').val('').trigger('change');
        $('#role_id_select').val('').trigger('change');
        
        $('#user_role_id').val(''); // KOSONGKAN ID (Agar masuk ke blok ELSE di controller)
        $('#reason_field').hide();  // SEMBUNYIKAN REASON
        $('#userRoleModal').modal('show');
    }

    // Fungsi saat klik tombol "Edit" di tabel
    function openEditRoleModal(id, user_id, role_id) {
        $('#userRoleModalLabel').text('Edit User Role');
        
        $('#user_role_id').val(id); // ISI ID (Agar masuk ke blok IF di controller)
        $('#user_id_select').val(user_id).trigger('change');
        $('#role_id_select').val(role_id).trigger('change');
        
        $('#reason_field').show();  // TAMPILKAN REASON
        $('#reason_input').val(''); // Reset isi reason sebelumnya
        $('#userRoleModal').modal('show');
    }

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
                window.location.href = "<?= base_url('user_role/export') ?>" + window.location.search;
            }
        })
    }

    // Fungsi untuk Menonaktifkan (Status 0)
    function confirmDelete(id) {
        Swal.fire({
            title: 'Apakah Anda yakin?',
            text: "User akan dinonaktifkan.",
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
                $.ajax({
                    url: "<?= base_url('user_role/update_status') ?>",
                    type: "POST",
                    dataType: "JSON",
                    data: {
                        id: id,
                        status: 0, // Set Non-Active
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
                                buttonsStyling: false,
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
                                buttonsStyling: false,
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
        });
    }

    // Fungsi untuk Mengaktifkan Kembali (Status 1)
    function confirmRestore(id) {
        Swal.fire({
            title: 'Aktifkan User Kembali?',
            text: "User akan mendapatkan aksesnya kembali.",
            icon: 'info',
            input: 'text',
            inputLabel: 'Alasan:',
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
                    url: "<?= base_url('user_role/update_status') ?>",
                    type: "POST",
                    dataType: "JSON",
                    data: {
                        id: id,
                        status: 1, // Set Active
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
                                buttonsStyling: false,
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
                                buttonsStyling: false,
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
        });
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

    // 1. Validasi Global (Search, Filter, Reason, Username Edit, dan Username Add)
    $(document).on('input', '#edit_username, #edit_reason, input[name="username"], #reason, input[name="keyword"], .filter-search-input, .swal2-popup input[type="text"], .swal2-popup textarea', function() {
        var el = $(this);
        var currentValue = el.val();
        var forbiddenChars;
        var isEmailFilter = false;

        // Cek jika ini adalah filter pencarian untuk kolom email
        if (el.hasClass('filter-search-input')) {
            var columnKey = el.closest('.custom-filter-dropdown').find('input[type="checkbox"]').first().data('key');
            if (columnKey === 'email') {
                isEmailFilter = true;
            }
        }

        if (isEmailFilter) {
            // Izinkan format email (@, titik, strip, underscore)
            forbiddenChars = /[^a-zA-Z0-9@.\-_]/g;
        } else {
            // Standar nama dan alasan
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

    // 2. Validasi KHUSUS untuk form Email (Edit dan Add New User)
    $(document).on('input', '#edit_email, input[name="email"]', function() {
        var forbiddenChars = /[^a-zA-Z0-9@.\-_]/g;
        var currentValue = $(this).val();

        if (forbiddenChars.test(currentValue)) {
            $(this).val(currentValue.replace(forbiddenChars, ''));
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

    // 1. Paste Validation - Global (Search, Filter, Reason, Username)
    $(document).on('paste', '#edit_username, #edit_reason, input[name="username"], #reason, input[name="keyword"], .filter-search-input, .swal2-popup input[type="text"], .swal2-popup textarea', function(e) {
        var el = $(this);
        var pasteData = (e.originalEvent || e).clipboardData.getData('text');
        var forbiddenChars;
        var isEmailFilter = false;

        // Cek jika ini adalah filter pencarian untuk kolom email
        if (el.hasClass('filter-search-input')) {
            var columnKey = el.closest('.custom-filter-dropdown').find('input[type="checkbox"]').first().data('key');
            if (columnKey === 'email') {
                isEmailFilter = true;
            }
        }

        // Tentukan Regex: Email filter boleh pakai @ . - _, sisanya standar
        if (isEmailFilter) {
            forbiddenChars = /[^a-zA-Z0-9@.\-_]/g;
        } else {
            forbiddenChars = /[^a-zA-Z0-9\s.,_\-]/g; 
        }

        if (forbiddenChars.test(pasteData)) {
            // Gagalkan proses paste secara total jika mengandung karakter terlarang
            e.preventDefault();
            
            // Efek visual border merah berkedip
            el.css({
                'border-color': '#dc3545',
                'box-shadow': '0 0 0 0.2rem rgba(220, 53, 69, 0.25)'
            });
            setTimeout(function() {
                el.css({ 'border-color': '', 'box-shadow': '' });
            }, 400);
        }
    });

    // 2. Paste Validation - KHUSUS Email (Form Add & Edit)
    $(document).on('paste', '#edit_email, input[name="email"]', function(e) {
        var el = $(this);
        var pasteData = (e.originalEvent || e).clipboardData.getData('text');
        var forbiddenChars = /[^a-zA-Z0-9@.\-_]/g;

        if (forbiddenChars.test(pasteData)) {
            e.preventDefault();
            
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