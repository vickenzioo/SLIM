<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <title>SLIM | Deployment Provider</title>
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
            <h1 class="m-0" style="color: var(--text-dark); font-weight: 700;">Deployment Provider Management</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="<?= base_url('portofolio')?>" class="breadcrumb-home">Home</a></li>
              <li class="breadcrumb-item active">Deployment Provider</li>
            </ol>
          </div>
        </div>
      </div>
    </section>

    <section class="content">
      <div class="container-fluid">
        <div class="card card-submenu-compact" style="border-top: 3px solid var(--theme-yellow-primary);">
            <form id="mainFilterForm" action="<?= base_url('deployment_model') ?>" method="get">
                <div id="activeFiltersContainer">
                    <?php if(!empty($selected_filters)): ?>
                        <?php foreach($selected_filters as $key => $values): ?>
                            <?php foreach($values as $val): ?>
                                <input type="hidden" name="filter[<?= $key ?>][]" value="<?= htmlspecialchars($val) ?>" class="filter-applied-<?= $key ?>">
                            <?php endforeach; ?>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>

                <div class="card-header bg-white">
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
                                <input type="text" name="keyword" class="form-control" placeholder="Search Provider Name..." value="<?= isset($keyword) ? $keyword : '' ?>">
                                <div class="input-group-append">
                                    <button class="btn btn-default"><i class="fas fa-search"></i></button>
                                    <a href="<?= base_url('deployment_model') ?>" class="btn btn-secondary d-flex align-items-center"><i class="fas fa-sync-alt"></i></a>
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
                                    <th class="text-center" style="width: 200px;">Action</th>
                                    <?php 
                                        function render_th($label, $key, $options, $selected) {
                                            $isActive = isset($selected[$key]) && !empty($selected[$key]);
                                            $iconClass = $isActive ? 'filter-active' : ''; 
                                            if(!empty($options)) sort($options);
                                            echo '<th style="color: var(--text-dark); vertical-align: middle;">';
                                            echo '<div class="d-inline-flex align-items-center"><span>' . $label . '</span>';
                                            echo '<div class="btn-group ml-2 filter-icon-wrapper '.$iconClass.'" style="position: static; transform: none; padding: 0;">';
                                            echo '<i class="fas fa-filter fa-sm" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="cursor: pointer;"></i>';
                                            echo '<div class="dropdown-menu dropdown-menu-right custom-filter-dropdown" onclick="event.stopPropagation()">';
                                            echo '<div class="filter-header"><input type="text" class="filter-search-input" placeholder="Find..." onkeyup="filterList(this)"></div>';
                                            echo '<div class="filter-body">';
                                            if(!empty($options)) {
                                                foreach($options as $opt) {
                                                    $checked = ($isActive && in_array($opt, $selected[$key])) ? 'checked' : '';
                                                    echo '<label class="filter-item"><input type="checkbox" value="'.htmlspecialchars($opt).'" '.$checked.' data-key="'.$key.'"> '.htmlspecialchars($opt).'</label>';
                                                }
                                            } else { echo '<div class="p-2 text-muted text-center small">No Options</div>'; }
                                            echo '</div><div class="filter-footer">';
                                            echo '<button type="button" class="btn btn-xs btn-default" onclick="clearFilter(\''.$key.'\')">Clear</button>';
                                            echo '<button type="button" class="btn btn-xs btn-primary btn-theme-gradient" onclick="applyFilter(\''.$key.'\')">Apply</button>';
                                            echo '</div></div></div></div></th>';
                                        }
                                        render_th('Deployment Provider Name', 'deployment_provider_name', $opt_deployment_provider_name, $selected_filters);
                                    ?>
                                    <th style="width: 200px; text-align: center; color: var(--text-dark);">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if(!empty($deployments)): ?>
                                    <?php foreach($deployments as $row): ?>
                                    <tr>
                                        <td class="text-center align-middle">
                                            <div class="dropdown">
                                                <button class="btn btn-sm btn-secondary dropdown-toggle btn-operation" type="button" data-toggle="dropdown">
                                                    <i class="fas fa-cog mr-2"></i> Actions
                                                </button>
                                                <div class="dropdown-menu dropdown-menu-right shadow dropdown-operation-menu">
                                                    <button class="dropdown-item" type="button" onclick="window.location.href='<?= base_url('deployment_model/audit/'.$row['deployment_provider_id']) ?>'">
                                                        <i class="fas fa-clipboard-list fa-fw text-primary mr-2"></i> Audit Trail
                                                    </button>
                                                    <?php if($row['status'] == 1): ?>
                                                        <button class="dropdown-item" type="button" onclick="editData(<?= $row['deployment_provider_id'] ?>, '<?= $row['deployment_provider_name'] ?>')">
                                                            <i class="fas fa-edit fa-fw text-warning mr-2"></i> Edit Data
                                                        </button>
                                                        <button class="dropdown-item" type="button" onclick="confirmDelete(<?= $row['deployment_provider_id'] ?>)">
                                                            <i class="fas fa-power-off fa-fw text-danger mr-2"></i> Deactivate
                                                        </button>
                                                    <?php else: ?>
                                                        <button class="dropdown-item" type="button" onclick="confirmRestore(<?= $row['deployment_provider_id'] ?>)">
                                                            <i class="fas fa-undo-alt fa-fw text-success mr-2"></i> Activate
                                                        </button>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="align-middle"><?= $row['deployment_provider_name'] ?></td>
                                        <td class="text-center align-middle">
                                            <?php 
                                                $active = ($row['status'] == 1);
                                                $bg = $active ? '#e8f5e9' : '#ffebee';
                                                $color = $active ? '#2e7d32' : '#c62828';
                                                $lbl = $active ? 'Active' : 'Non Active';
                                            ?>
                                            <span class="badge px-3 py-2" style="background-color: <?= $bg ?>; color: <?= $color ?>; border-radius: 6px; font-size: 0.75rem; font-weight: 700; min-width: 85px; display: inline-block;">
                                                <?= $lbl ?>
                                            </span>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr><td colspan="3" class="text-center">No Data Found</td></tr>
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

<div class="modal fade modal-submenu-compact" id="modalDeployment" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header" style="background: linear-gradient(135deg, var(--theme-bg-yellow-light) 0%, var(--theme-bg-yellow-dark) 100%); color: var(--text-dark);">
        <h5 class="modal-title" id="modalTitle">Add Deployment Provider</h5>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <form action="<?= base_url('deployment_model/save') ?>" method="post">
          <div class="modal-body">
            <input type="hidden" name="deployment_provider_id" id="deployment_provider_id">
            <div class="form-group">
                <label>Deployment Provider Name</label>
                <input type="text" name="deployment_provider_name" id="deployment_provider_name" class="form-control" required placeholder="Enter Deployment Provider Name">
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

<?php $this->load->view('layout/foot_links'); ?>

<script>
    // Copy seluruh script dari database_view, ganti variabel yang relevan:
    // database/update_status -> deployment_model/update_status
    // #modalDatabase -> #modalDeployment
    // #database_id -> #deployment_provider_id
    // #database_name -> #deployment_provider_name
    
    function filterList(input) {
        var filter = input.value.toUpperCase();
        var labels = input.parentNode.nextElementSibling.getElementsByTagName("label");
        for (var i = 0; i < labels.length; i++) {
            labels[i].style.display = (labels[i].innerText.toUpperCase().indexOf(filter) > -1) ? "block" : "none";
        }
    }

    function applyFilter(key) {
        $('.filter-applied-' + key).remove();
        document.querySelectorAll('input[type="checkbox"][data-key="' + key + '"]:checked').forEach(cb => {
            $('<input>').attr({type: 'hidden', name: 'filter['+key+'][]', value: cb.value, class: 'filter-applied-'+key}).appendTo('#activeFiltersContainer');
        });
        $('#mainFilterForm').submit();
    }

    function clearFilter(key) {
        $('#loadingOverlay').css('display', 'flex'); 
        $('.filter-applied-' + key).remove();
        $('#mainFilterForm').submit();
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
                
                window.location.href = "<?= base_url('deployment_model/export') ?>" + window.location.search;
            }
        })
    }

    function clearForm() {
        $('#modalTitle').text('Add Deployment Provider');
        $('#deployment_provider_id').val('');
        $('#deployment_provider_name').val('');
        $('#reason_container').hide();
        $('#modalDeployment').modal('show');
    }

    function editData(id, name) {
        $('#modalTitle').text('Edit Deployment Provider');
        $('#deployment_provider_id').val(id);
        $('#deployment_provider_name').val(name);
        $('#reason_container').show();
        $('#modalDeployment').modal('show');
    }

    function confirmDelete(id) {
        $('#loadingOverlay').css('display', 'flex');

        $.ajax({
            url: '<?= base_url("home/api_check_master_usage") ?>',
            type: 'POST',
            dataType: 'json',
            data: {
                table_name: 'tbl_apps_deployment_model',
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
                                url: "<?= base_url('deployment_model/update_status') ?>",
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
                    url: "<?= base_url('deployment_model/update_status') ?>",
                    type: "POST",
                    dataType: "JSON", // Tambahkan tipe data JSON
                    data: { 
                        id: id, 
                        status: 1, // Balikkan ke Active
                        reason: result.value // Gunakan nilai dari input pop-up
                    },
                    success: function(response) {
                        $('#loadingOverlay').css('display', 'none');
                        
                        if (response.success) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Berhasil',
                                text: response.message, // Menampilkan pesan sukses dari controller
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

    $(document).on('input', '#deployment_provider_name, #reason, input[name="keyword"], .filter-search-input, .swal2-popup input[type="text"], .swal2-popup textarea', function() {
        
        var forbiddenChars = /[^a-zA-Z0-9\s.,_\-]/g; 
        var currentValue = $(this).val();

        if (forbiddenChars.test(currentValue)) {
            $(this).val(currentValue.replace(forbiddenChars, ''));
            
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
            
            if (el.hasClass('filter-search-input')) {
                filterList(this);
            }
        }
    });
    
    $(document).on('paste', '#deployment_provider_name, #reason, input[name="keyword"], .filter-search-input, .swal2-popup input[type="text"], .swal2-popup textarea', function(e) {
        
        // Regex: HANYA izinkan huruf, angka, spasi, titik, koma, strip, dan underscore
        var forbiddenChars = /[^a-zA-Z0-9\s.,_\-]/g; 
        
        // Ambil data teks dari clipboard
        var pasteData = (e.originalEvent || e).clipboardData.getData('text');

        if (forbiddenChars.test(pasteData)) {
            // Jika mengandung karakter terlarang, batalkan proses paste
            e.preventDefault();
            
            // Beri feedback visual border merah berkedip
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
</script>
</body>
</html>