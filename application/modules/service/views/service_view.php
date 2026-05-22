<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <title>SLIM | Service</title>
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
            <h1 class="m-0" style="color: var(--text-dark); font-weight: 700;">Service Management</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="<?= base_url('portofolio')?>" class="breadcrumb-home">Home</a></li>
              <li class="breadcrumb-item active">Service</li>
            </ol>
          </div>
        </div>
      </div>
    </section>

    <section class="content">
      <div class="container-fluid">
        <div class="card" style="border-top: 3px solid var(--theme-yellow-primary);">
          <div class="card-body">
                <form id="mainFilterForm" action="<?= base_url('service') ?>" method="get">
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

                    <div class="row align-items-center mb-3">
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
                                <input type="text" name="keyword" class="form-control" placeholder="Search Service or Module..." value="<?= isset($keyword) ? $keyword : '' ?>">
                                <div class="input-group-append">
                                    <button class="btn btn-default"><i class="fas fa-search"></i></button>
                                    <a href="<?= base_url('service') ?>" class="btn btn-secondary d-flex align-items-center">
                                        <i class="fas fa-sync-alt"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <table class="table table-striped table-bordered table-hover text-nowrap">
                        <thead>
                            <tr class="bg-info">
                                <th class="text-center" style="width: 180px;">Action</th>
                                <?php 
                                if(!function_exists('render_th_service')){
                                    function render_th_service($label, $key, $options, $selected) {
                                        $isActive = isset($selected[$key]) && !empty($selected[$key]);
                                        $iconClass = $isActive ? 'filter-active' : ''; 
                                        echo '<th style="color: var(--text-dark); vertical-align: middle;">';
                                        echo '<div class="d-inline-flex align-items-center">';
                                        echo '<span>' . $label . '</span>';
                                        echo '<div class="btn-group ml-2 filter-icon-wrapper '.$iconClass.'" style="position: static; transform: none; padding: 0;">';
                                        echo '<i class="fas fa-filter fa-sm" data-toggle="dropdown" style="cursor: pointer;"></i>';
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
                                        echo '</div></div></div></div></th>';
                                    }
                                }
                                ?>
                                <?= render_th_service('Module Name', 'module_name', $opt_module, $selected_filters) ?>
                                <?= render_th_service('Service Name', 'service_name', $opt_service, $selected_filters) ?>
                                <th style="width: 150px; text-align: center; color: var(--text-dark);">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if(!empty($services)): ?>
                                <?php foreach($services as $s): ?>
                                <tr>
                                    <td class="text-center align-middle">
                                        <div class="dropdown">
                                            <button class="btn btn-sm btn-secondary dropdown-toggle btn-operation" type="button" data-toggle="dropdown">
                                                <i class="fas fa-cog mr-2"></i> Operation
                                            </button>
                                            <div class="dropdown-menu dropdown-menu-right shadow dropdown-operation-menu">
                                                <button class="dropdown-item" type="button" onclick="window.location.href='<?= base_url('service/audit/'.$s['service_id']) ?>'">
                                                    <i class="fas fa-clipboard-list fa-fw text-primary mr-2"></i> Audit Trail
                                                </button>
                                                <?php if($s['status'] == 1): ?>
                                                    <button class="dropdown-item" type="button" onclick="editData(<?= $s['service_id'] ?>, <?= $s['module_id'] ?>, '<?= $s['service_name'] ?>')">
                                                        <i class="fas fa-edit fa-fw text-warning mr-2"></i> Edit Data
                                                    </button>
                                                    <button class="dropdown-item" type="button" onclick="confirmDelete(<?= $s['service_id'] ?>)">
                                                        <i class="fas fa-power-off fa-fw text-danger mr-2"></i> Deactivate
                                                    </button>
                                                <?php else: ?>
                                                    <button class="dropdown-item" type="button" onclick="confirmRestore(<?= $s['service_id'] ?>)">
                                                        <i class="fas fa-undo-alt fa-fw text-success mr-2"></i> Activate
                                                    </button>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="align-middle"><?= $s['module_name'] ?></td>
                                    <td class="align-middle"><?= $s['service_name'] ?></td>
                                    <td class="text-center align-middle">
                                        <?php 
                                            if (isset($s['status']) && $s['status'] == 1) { 
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
                                <tr><td colspan="4" class="text-center">No Data Found</td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
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
      </div>
    </section>
  </div>
  <?php $this->load->view('layout/footer'); ?>
</div>

<div class="modal fade" id="modalService" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header" style="background: linear-gradient(135deg, var(--theme-bg-yellow-light) 0%, var(--theme-bg-yellow-dark) 100%); color: var(--text-dark);">
        <h5 class="modal-title" id="modalTitle">Add Service</h5>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <form action="<?= base_url('service/save') ?>" method="post">
          <div class="modal-body">
            <input type="hidden" name="service_id" id="service_id">
            <div class="form-group">
                <label>Module</label>
                <select name="module_id" id="module_id" class="form-control select2" required style="width: 100%;">
                    <option value="">-- Select Module --</option>
                    <?php foreach($modules as $m): ?>
                        <option value="<?= $m['module_id'] ?>"><?= $m['module_name'] ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label>Service Name</label>
                <input type="text" name="service_name" id="service_name" class="form-control" required placeholder="Enter Service Name">
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
        // Inisialisasi Select2 untuk dropdown Module
        $('.select2').select2({
            theme: 'bootstrap4',
            placeholder: "-- Select Module --",
            allowClear: true,
            dropdownParent: $('#modalService'), // Agar dropdown tampil di atas modal
            minimumResultsForSearch: Infinity  // Menghilangkan field search
        });
        
        $('#loadingOverlay').fadeOut();
    });


    function filterList(input) {
        var filter = input.value.toUpperCase();
        var div = input.parentNode.nextElementSibling;
        var labels = div.getElementsByTagName("label");
        for (var i = 0; i < labels.length; i++) {
            var txtValue = labels[i].textContent || labels[i].innerText;
            labels[i].style.display = (txtValue.toUpperCase().indexOf(filter) > -1) ? "block" : "none";
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
        $('.filter-applied-' + key).remove();
        document.getElementById('mainFilterForm').submit();
    }

    function confirmExport() {
        Swal.fire({
            title: 'Export to Excel?',
            text: "File data service akan diunduh.",
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Yes, Export',
            buttonsStyling: false,
            customClass: { confirmButton: 'btn btn-save-custom px-4 mx-2', cancelButton: 'btn btn-secondary px-4 mx-2' }
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = "<?= base_url('service/export') ?>" + window.location.search;
            }
        })
    }

    function clearForm() {
        $('#modalTitle').text('Add Service');
        $('#service_id').val('');
        $('#module_id').val('').trigger('change'); // Trigger Select2 reset
        $('#service_name').val('');
        $('#reason_container').hide(); 
        $('#modalService').modal('show'); 
    }

    function editData(id, moduleId, name) {
        $('#modalTitle').text('Edit Service');
        $('#service_id').val(id);
        $('#module_id').val(moduleId).trigger('change'); // Trigger Select2 set value
        $('#service_name').val(name);
        $('#reason_container').show(); 
        $('#modalService').modal('show'); 
    }

    function confirmDelete(id) {
        Swal.fire({
            title: 'Apakah Anda yakin?',
            text: "Service akan dinonaktifkan.",
            icon: 'warning',
            input: 'text',
            inputLabel: 'Alasan:',
            showCancelButton: true,
            confirmButtonText: 'Ya, Nonaktifkan!',
            buttonsStyling: false,
            customClass: { confirmButton: 'btn btn-danger px-4 mx-2', cancelButton: 'btn btn-secondary px-4 mx-2' },
            inputValidator: (value) => { if (!value) return 'Anda harus menuliskan alasan!'; }
        }).then((result) => {
            if (result.isConfirmed) {
                updateStatus(id, 0, result.value);
            }
        })
    }

    function confirmRestore(id) {
        Swal.fire({
            title: 'Aktifkan Kembali?',
            text: "Service ini akan dikembalikan ke daftar aktif.",
            icon: 'info',
            input: 'text',
            inputLabel: 'Alasan Pengaktifan:',
            showCancelButton: true,
            confirmButtonText: 'Ya, Aktifkan!',
            buttonsStyling: false,
            customClass: { confirmButton: 'btn btn-success px-4 mx-2', cancelButton: 'btn btn-secondary px-4 mx-2' },
            inputValidator: (value) => { if (!value) return 'Anda harus menuliskan alasan!'; }
        }).then((result) => {
            if (result.isConfirmed) {
                updateStatus(id, 1, result.value);
            }
        })
    }

    function updateStatus(id, status, reason) {
        $.ajax({
            url: "<?= base_url('service/update_status') ?>",
            type: "POST",
            dataType: "JSON",
            data: { id: id, status: status, reason: reason },
            success: function(response) {
                if (response.success) {
                    Swal.fire({ icon: 'success', title: 'Berhasil', text: 'Status service berhasil diupdate', customClass: { confirmButton: 'btn btn-theme-gradient px-4' } }).then(() => { location.reload(); });
                } else {
                    Swal.fire({ icon: 'error', title: 'Gagal', text: response.message });
                }
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