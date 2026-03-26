<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <title>SLIM | Deployment Site</title>
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
            <h1 class="m-0" style="color: var(--text-dark); font-weight: 700;">Deployment Site Management</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="<?= base_url('portofolio')?>" class="breadcrumb-home">Home</a></li>
              <li class="breadcrumb-item active">Deployment Site</li>
            </ol>
          </div>
        </div>
      </div>
    </section>

    <section class="content">
      <div class="container-fluid">
        <div class="card" style="border-top: 3px solid var(--theme-yellow-primary);">
            <form id="mainFilterForm" action="<?= base_url('deployment_site') ?>" method="get">
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
                            <button type="button" class="btn btn-add-custom btn-sm" onclick="clearForm()">
                                <i class="fas fa-plus mr-1"></i> Add
                            </button>
                            <button type="button" class="btn btn-export-custom btn-sm ml-2" onclick="confirmExport()">
                                <i class="fas fa-file-export mr-1"></i> Export
                            </button>
                        </div>
                        <div class="col-md-4">
                            <div class="input-group">
                                <input type="text" name="keyword" class="form-control" placeholder="Search Site Name..." value="<?= isset($keyword) ? $keyword : '' ?>">
                                <div class="input-group-append">
                                    <button class="btn btn-default"><i class="fas fa-search"></i></button>
                                    <a href="<?= base_url('deployment_site') ?>" class="btn btn-secondary d-flex align-items-center">
                                        <i class="fas fa-sync-alt"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            
                <div class="card-body">
                    <table class="table table-striped table-bordered table-hover text-nowrap">
                        <thead>
                            <tr class="bg-info">
                                <th class="text-center" style="width: 200px;">Action</th>
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
                                ?>
                                <?= render_th('Deployment Site Name', 'deployment_site_name', $opt_deployment_site_name, $selected_filters) ?>
                                <th style="width: 200px; text-align: center; color: var(--text-dark);">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if(!empty($deployment_sites)): ?>
                                <?php 
                                    $start = $this->input->get('per_page');
                                    $no = $start ? $start + 1 : 1; 
                                ?>
                                <?php foreach($deployment_sites as $site): ?>
                                <tr>
                                    <td class="text-center align-middle">
                                        <div class="dropdown">
                                            <button class="btn btn-sm btn-secondary dropdown-toggle btn-operation" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                <i class="fas fa-cog mr-2"></i> Operation
                                            </button>
                                            <div class="dropdown-menu dropdown-menu-right shadow dropdown-operation-menu">
                                                <button class="dropdown-item" type="button" onclick="window.location.href='<?= base_url('deployment_site/audit/'.$site['deployment_site_id']) ?>'">
                                                    <i class="fas fa-clipboard-list fa-fw text-primary mr-2"></i> Audit Trail
                                                </button>
                                                <?php if($site['status'] == 1): ?>
                                                    <button class="dropdown-item" type="button" onclick="editData(<?= $site['deployment_site_id'] ?>, '<?= $site['deployment_site_name'] ?>')">
                                                        <i class="fas fa-edit fa-fw text-warning mr-2"></i> Edit Data
                                                    </button>
                                                    <button class="dropdown-item" type="button" onclick="confirmDelete(<?= $site['deployment_site_id'] ?>)">
                                                        <i class="fas fa-power-off fa-fw text-danger mr-2"></i> Deactivate
                                                    </button>
                                                <?php else: ?>
                                                    <button class="dropdown-item" type="button" onclick="confirmRestore(<?= $site['deployment_site_id'] ?>)">
                                                        <i class="fas fa-undo-alt fa-fw text-success mr-2"></i> Activate
                                                    </button>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="align-middle"><?= $site['deployment_site_name'] ?></td>
                                    <td class="text-center align-middle">
                                        <?php 
                                            $status_bg = ($site['status'] == 1) ? '#e8f5e9' : '#ffebee';
                                            $status_color = ($site['status'] == 1) ? '#2e7d32' : '#c62828';
                                            $status_label = ($site['status'] == 1) ? 'Active' : 'Non Active';
                                        ?>
                                        <span class="badge px-3 py-2" style="background-color: <?= $status_bg ?>; color: <?= $status_color ?>; border-radius: 6px; font-size: 0.75rem; font-weight: 700; min-width: 85px; display: inline-block; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
                                            <?= $status_label ?>
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
            </form>
            <div class="card-footer bg-white clearfix" style="border-top: 1px solid #dee2e6;">
                <div class="float-right"><?= $pagination ?></div>
                <div class="float-left"><small class="text-muted">Total Data: <?= isset($total_rows) ? $total_rows : 0 ?></small></div>
            </div>
        </div>
      </div>
    </section>
  </div>
  <?php $this->load->view('layout/footer'); ?>
</div>

<div class="modal fade" id="modalSite" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header" style="background: linear-gradient(135deg, var(--theme-bg-yellow-light) 0%, var(--theme-bg-yellow-dark) 100%); color: var(--text-dark);">
        <h5 class="modal-title" id="modalTitle">Add Deployment Site</h5>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <form action="<?= base_url('deployment_site/save') ?>" method="post">
          <div class="modal-body">
            <input type="hidden" name="deployment_site_id" id="deployment_site_id">
            <div class="form-group">
                <label>Deployment Site Name</label>
                <input type="text" name="deployment_site_name" id="deployment_site_name" class="form-control" required placeholder="Enter Deployment Site Name">
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
    // Copy identical JS functions from database_view
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
            input.type = 'hidden'; input.name = 'filter[' + key + '][]';
            input.value = cb.value; input.className = 'filter-applied-' + key;
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
            title: 'Export to Excel?', text: "File akan otomatis diunduh.", icon: 'question',
            showCancelButton: true, confirmButtonText: 'Yes, export!', cancelButtonText: 'Cancel',
            buttonsStyling: false, customClass: { confirmButton: 'btn btn-save-custom px-4 mx-2', cancelButton: 'btn btn-secondary px-4 mx-2' }
        }).then((result) => {
            if (result.isConfirmed) {
                const Toast = Swal.mixin({ toast: true, position: 'top-end', showConfirmButton: false, timer: 3000, timerProgressBar: true });
                Toast.fire({ icon: 'success', title: 'Downloading file...' });
                window.location.href = "<?= base_url('deployment_site/export') ?>" + window.location.search;
            }
        })
    }

    function clearForm() {
        $('#modalTitle').text('Add Deployment Site');
        $('#deployment_site_id').val('');
        $('#deployment_site_name').val('');
        $('#reason').val(''); 
        $('#reason_container').hide(); 
        $('#modalSite').modal('show'); 
    }

    function editData(id, name) {
        $('#modalTitle').text('Edit Deployment Site');
        $('#deployment_site_id').val(id);
        $('#deployment_site_name').val(name);
        $('#reason').val(''); 
        $('#reason_container').show(); 
        $('#modalSite').modal('show'); 
    }

    function confirmDelete(id) {
        Swal.fire({
            title: 'Apakah Anda yakin?', text: "Data akan dinonaktifkan.", icon: 'warning',
            input: 'text', inputLabel: 'Alasan:', inputPlaceholder: 'Masukkan Alasan...',
            showCancelButton: true, confirmButtonText: 'Ya, Nonaktifkan!', cancelButtonText: 'Batal',
            buttonsStyling: false, customClass: { confirmButton: 'btn btn-danger px-4 mx-2', cancelButton: 'btn btn-secondary px-4 mx-2' },
            inputValidator: (value) => { if (!value) return 'Anda harus menuliskan alasan!'; }
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: "<?= base_url('deployment_site/update_status') ?>",
                    type: "POST", dataType: "JSON",
                    data: { id: id, status: 0, reason: result.value },
                    success: function(response) {
                        if (response.success) {
                            Swal.fire({ icon: 'success', title: 'Berhasil', text: response.message, confirmButtonText: 'OK', customClass: { confirmButton: 'btn btn-theme-gradient px-4 mx-2' } }).then(() => { location.reload(); });
                        } else {
                            Swal.fire({ icon: 'error', title: 'Gagal', text: response.message, confirmButtonText: 'OK', customClass: { confirmButton: 'btn btn-theme-gradient px-4 mx-2' } });
                        }
                    }
                });
            }
        })
    }

    function confirmRestore(id) {
        Swal.fire({
            title: 'Aktifkan Kembali?', text: "Data ini akan dikembalikan ke daftar aktif.", icon: 'info',
            input: 'text', inputLabel: 'Alasan Pengaktifan:', inputPlaceholder: 'Masukkan Alasan...',
            showCancelButton: true, confirmButtonText: 'Ya, Aktifkan!', cancelButtonText: 'Batal',
            buttonsStyling: false, customClass: { confirmButton: 'btn btn-success px-4 mx-2', cancelButton: 'btn btn-secondary px-4 mx-2' },
            inputValidator: (value) => { if (!value) return 'Anda harus menuliskan alasan!'; }
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: "<?= base_url('deployment_site/update_status') ?>",
                    type: "POST", dataType: "JSON",
                    data: { id: id, status: 1, reason: result.value },
                    success: function(response) {
                        if (response.success) {
                            Swal.fire({ icon: 'success', title: 'Berhasil', text: response.message, confirmButtonText: 'OK', customClass: { confirmButton: 'btn btn-theme-gradient px-4 mx-2' } }).then(() => { location.reload(); });
                        } else {
                            Swal.fire({ icon: 'error', title: 'Gagal', text: response.message });
                        }
                    }
                });
            }
        })
    }

    <?php if($this->session->flashdata('success')): ?>
        Swal.fire({ icon: 'success', title: 'Success', text: '<?= $this->session->flashdata('success') ?>', confirmButtonText: 'OK', buttonsStyling: false, customClass: { confirmButton: 'btn btn-theme-gradient px-4' } });
    <?php endif; ?>

    <?php if($this->session->flashdata('error')): ?>
        Swal.fire({ icon: 'error', title: 'Gagal!', text: '<?= $this->session->flashdata('error') ?>', confirmButtonText: 'OK', buttonsStyling: false, customClass: { confirmButton: 'btn btn-danger px-4' } });
    <?php endif; ?>
</script>
</body>
</html>