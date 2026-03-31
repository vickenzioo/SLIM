<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>SLIM | Audit Trail</title>

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
                        <h1 class="m-0" style="color: var(--text-dark); font-weight: 700;">Audit Trail</h1>
                        <p class="text-muted">History of changes for: <span class="font-weight-bold" style="color: #3f51b5;"><?= $target_name ?></span></p>
                    </div>

                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item">
                                <a href="<?= base_url('home') ?>" class="breadcrumb-home">Home</a>
                            </li>
                            
                            <li class="breadcrumb-item">
                                <a href="<?= base_url($back_url) ?>" class="breadcrumb-home">
                                    <?= isset($menu_label) ? $menu_label : 'Menu' ?> "<?= isset($target_name) ? $target_name : '' ?>"
                                </a>
                            </li>

                            <li class="breadcrumb-item active">Audit</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>

        <section class="content">
            <div class="container-fluid">
                
                <div class="card" style="border-top: 3px solid var(--theme-yellow-primary);">
                    <div class="card-header" style="background-color: #fff;">
                        <div class="row align-items-center">
                            <div class="col-md-8">
                                <a href="<?= base_url($back_url) ?>" class="btn btn-secondary btn-sm">
                                    <i class="fas fa-arrow-left mr-1"></i> Back
                                </a>

                                <button class="btn btn-export-custom btn-sm ml-1" onclick="confirmExport()">
                                    <i class="fas fa-file-export mr-1"></i> Export
                                </button>
                            </div>

                            <div class="col-md-4">
                                <form action="<?= current_url() ?>" method="GET">
                                    <div class="input-group">
                                        <input type="text" name="keyword" class="form-control" placeholder="Search Audit Logs..." value="<?= isset($keyword) ? $keyword : '' ?>">
                                        <div class="input-group-append">
                                            <button type="submit" class="btn btn-default">
                                                <i class="fas fa-search"></i>
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    
                    <div class="card-body">
                        <table class="table table-striped table-bordered table-hover">
                            <thead>
                                <tr class="bg-info text-center">
                                    <th style="border-bottom: none;" class="px-4">Timestamp</th>
                                    <th style="border-bottom: none;">User</th>
                                    <th style="border-bottom: none;">Action</th>
                                    <th style="border-bottom: none;">Field</th>
                                    <th style="border-bottom: none;">Old Value</th>
                                    <th style="border-bottom: none;">New Value</th>
                                    <th style="border-bottom: none;">Reason</th>
                                </tr>
                            </thead>

                            <tbody>
                                <?php if(!empty($audit_data)): ?>
                                    <?php 
                                        $start = $this->input->get('per_page');
                                        $no = $start ? $start + 1 : 1; 
                                    ?>

                                    <?php foreach($audit_data as $log): 
                                        $l = (array) $log; 
                                    ?>

                                    <tr>
                                        <td class="px-4 align-middle" style="font-size: 0.9rem;">
                                            <?= $l['timestamp'] ?>
                                        </td>

                                        <td class="text-center align-middle font-weight-bold" style="font-size: 0.9rem;">
                                            <?= !empty($l['email']) ? $l['email'] : $l['username'] ?>
                                        </td>

                                        <td class="text-center align-middle">
                                            <?php 
                                                $bg = '#e9ecef'; $color = '#495057';
                                                if($l['action'] == 'ADD') { $bg = '#e8f5e9'; $color = '#2e7d32'; }
                                                elseif($l['action'] == 'EDIT') { $bg = '#e3f2fd'; $color = '#1565c0'; }
                                                elseif($l['action'] == 'DEACTIVATE' || $l['action'] == 'DELETE') { $bg = '#ffebee'; $color = '#c62828'; }
                                                elseif($l['action'] == 'ACTIVATE') { $bg = '#e0f2f1'; $color = '#00695c'; }
                                            ?>
                                            
                                            <span class="badge px-3 py-2" style="background-color: <?= $bg ?>; color: <?= $color ?>; border-radius: 6px; font-size: 0.75rem; font-weight: 700;">
                                                <?= $l['action'] ?>
                                            </span>
                                        </td>
                                        
                                        <td class="align-middle text-muted" style="font-size: 0.9rem;">
                                            <?php 
                                                if (!empty($l['field_name'])) {
                                                    // MAPPING NAMA FIELD LENGKAP (Termasuk Network Provider & Product)
                                                    $display_names = [
                                                        'database_name'   => 'Database Name',
                                                        'connection_type' => 'Connection Type',
                                                        'host'            => 'Host/Server',
                                                        'username'        => 'DB Username',
                                                        'app_type_name'  => 'Application Type',
                                                        'network_name'    => 'Network Name',
                                                        'provider_name'   => 'Provider Name',
                                                        'product_name'    => 'Product Name',
                                                        'deployment_model'     => 'Deployment Model',
                                                        'deployment_provider'  => 'Deployment Provider',
                                                        'main_deployment_site' => 'Main Deployment Site',
                                                        'operating_software_name' => 'Operating Software Name',
                                                        'status'          => 'Status',
                                                        'reason'          => 'Reason',
                                                        'start_day'       => 'Start Day',
                                                        'end_day'         => 'End Day'
                                                    ];

                                                    echo isset($display_names[$l['field_name']]) ? $display_names[$l['field_name']] : ucwords(str_replace('_', ' ', $l['field_name']));
                                                } else {
                                                    echo '-';
                                                }
                                            ?>
                                        </td>

                                        <td class="align-middle" style="font-size: 0.9rem;">
                                            <strike class="text-danger">
                                                <?php 
                                                    if ($l['field_name'] == 'status' && $l['old_value'] !== null) {
                                                        echo ($l['old_value'] == '1') ? 'Active' : 'Non Active';
                                                    } else {
                                                        echo ($l['old_value'] !== null && $l['old_value'] !== '') ? $l['old_value'] : '-';
                                                    }
                                                ?>
                                            </strike>
                                        </td>

                                        <td class="align-middle" style="font-size: 0.9rem;">
                                            <span class="text-success font-weight-bold">
                                                <?php 
                                                    if ($l['field_name'] == 'status' && $l['new_value'] !== null) {
                                                        echo ($l['new_value'] == '1') ? 'Active' : 'Non Active';
                                                    } else {
                                                        echo ($l['new_value'] !== null && $l['new_value'] !== '') ? $l['new_value'] : '-';
                                                    }
                                                ?>
                                            </span>
                                        </td>

                                        <td class="align-middle text-muted" style="font-size: 0.85rem; font-style: italic;">
                                            <?= !empty($l['reason']) ? '"'.$l['reason'].'"' : '-' ?>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>

                                <?php else: ?>
                                    <tr>
                                        <td colspan="8" class="text-center py-5 text-muted">
                                            <div class="mb-3">
                                                <img src="<?= base_url('assets/img/no_change_icon.svg') ?>" width="80" style="opacity: 0.5;">
                                            </div>
                                            No change history found for this item.
                                        </td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>

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

<div id="loadingOverlay" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(255,255,255,0.8); z-index:9999; justify-content:center; align-items:center; flex-direction:column;">
    <div class="spinner-border text-warning" role="status"></div>
    <div class="loading-text mt-2 font-weight-bold">Processing...</div>
</div>

<?php $this->load->view('layout/foot_links'); ?>

<script>
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
                // Notifikasi kecil
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

                window.location.href = "<?= $export_url ?>";
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
	
	    // Target: Search Bar Utama dan Filter Dropdown
    var auditSelectors = 'input[name="keyword"], .filter-search-input';

    // Regex khusus Audit: Izinkan Huruf, Angka, Spasi, dan simbol log (. , _ - : | > ( ) )
    var auditForbiddenChars = /[^a-zA-Z0-9\s.,_\-:|>()]/g;

    // 1. Validasi saat KETIK (Input)
    $(document).on('input', auditSelectors, function() {
        var el = $(this);
        var currentValue = el.val();

        if (auditForbiddenChars.test(currentValue)) {
            // Hapus karakter terlarang
            el.val(currentValue.replace(auditForbiddenChars, ''));
            
            // Efek visual border merah berkedip
            el.css({
                'border-color': '#dc3545',
                'box-shadow': '0 0 0 0.2rem rgba(220, 53, 69, 0.25)'
            });
            setTimeout(function() {
                el.css({ 'border-color': '', 'box-shadow': '' });
            }, 400);
            
            // Jika ini input filter, panggil ulang fungsi pencarian
            if (el.hasClass('filter-search-input')) {
                if (typeof filterList === "function") {
                    filterList(this);
                }
            }
        }
    });

    // 2. Validasi saat TEMPEL (Paste)
    $(document).on('paste', auditSelectors, function(e) {
        var el = $(this);
        var pasteData = (e.originalEvent || e).clipboardData.getData('text');

        if (auditForbiddenChars.test(pasteData)) {
            // Batalkan paste jika mengandung karakter ilegal
            e.preventDefault();
            
            // Feedback visual merah
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