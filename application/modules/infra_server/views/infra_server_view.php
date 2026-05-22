<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>SLIM | Server</title>
  
  <?php $this->load->view('layout/head_links'); ?>

</head>

<body class="hold-transition sidebar-mini layout-fixed sidebar-collapse">
<script>
  if (localStorage.getItem('theme') === 'dark') document.body.classList.add('dark-mode');
</script>

<div class="wrapper">
  <?php $this->load->view('layout/header'); ?>
  <?php $this->load->view('layout/sidebar'); ?>

  <div class="content-wrapper">
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
              <h1 class="m-0" style="color: var(--text-dark); font-weight: 700;">Server Management</h1>
          </div>

          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item">
                <a href="<?= base_url('portofolio')?>" class="breadcrumb-home">Home</a>
              </li>

              <li class="breadcrumb-item active">Server</li>
            </ol>
          </div>
        </div>
      </div>
    </div>

    <section class="content">
      <div class="container-fluid">
        <div class="card" style="border-top: 3px solid var(--theme-yellow-primary); border-radius: 8px; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">
          <div class="card-header" style="background-color: #fff;">
            <div class="row align-items-center">
              <div class="col-md-6">
               
                <button class="btn btn-export-custom btn-sm" onclick="confirmExport()">
                    <i class="fas fa-file-export mr-1"></i> Export
                </button>
              </div>

              <div class="col-md-6">
                <form action="<?= base_url('server') ?>" method="get">
                  <div class="input-group">
                    <input type="text" name="keyword" class="form-control" placeholder="Search..." value="<?= isset($keyword) ? $keyword : '' ?>">
                    <div class="input-group-append">
                      <button type="submit" class="btn btn-default">
                        <i class="fas fa-search"></i>
                      </button>
                      <a href="<?= base_url('server') ?>" class="btn btn-secondary d-flex align-items-center">
                        <i class="fas fa-sync-alt"></i>
                      </a>
                    </div>
                  </div>
                </form>
              </div>
            </div>
          </div>

          <div class="card-body">
            <div style="overflow-x: auto; width: 100%;">
              <table class="table table-striped table-bordered table-hover text-nowrap table-custom-fixed mb-0">
                  <thead>
                    <tr class="bg-info text-center">
                        <th class="text-center align-middle" style="color: var(--text-dark);">Action</th>
                                
                        <?php 
                          // Helper Render Header disamakan persis dengan Portofolio
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
                        ?>

                        <?= render_th('Category', 'category', $opt_category, $selected_filters) ?>
                        <?= render_th('Application Name', 'apps_name', $opt_apps_name, $selected_filters) ?>
                        <?= render_th('Module', 'module', $opt_module, $selected_filters) ?>
                        <?= render_th('Service Name', 'service_name', $opt_service_name, $selected_filters) ?>
                        <?= render_th('Database', 'database', $opt_database, $selected_filters) ?>
                        <?= render_th('Operating Software', 'operating_sw', $opt_os, $selected_filters) ?>
                        <th style="color: var(--text-dark); vertical-align: middle;">Resilience</th>
                        <?= render_th('Server Type', 'server_type', $opt_server_type, $selected_filters) ?>
                                
                        <th style="color: var(--text-dark); vertical-align: middle;">Prod Web</th>
                        <th style="color: var(--text-dark); vertical-align: middle;">Prod Apps</th>
                        <th style="color: var(--text-dark); vertical-align: middle;">Prod DB</th>
                        <th style="color: var(--text-dark); vertical-align: middle;">SLA SVR PROD</th>
                        <th style="color: var(--text-dark); vertical-align: middle;">DR Web</th>
                        <th style="color: var(--text-dark); vertical-align: middle;">DR Apps</th>
                        <th style="color: var(--text-dark); vertical-align: middle;">DR DB</th>
                        <th style="color: var(--text-dark); vertical-align: middle;">SLA SVR DR</th>
                        <th style="color: var(--text-dark); vertical-align: middle;">SLA SCCA Standard</th>
                        <th style="color: var(--text-dark); vertical-align: middle;">SLA Actual</th>
                                
                        <?= render_th('Readyness', 'readyness', $opt_readyness, $selected_filters) ?>
                                
                        <th style="color: var(--text-dark); vertical-align: middle;">Suggestion</th>
                      </tr>
                  </thead>
                  <tbody>
                      <?php if(!empty($rows)): foreach($rows as $r): ?>
                      <tr>
                          <td class="text-center align-middle">
                              <div class="dropdown">
                                  <button class="btn btn-sm btn-secondary dropdown-toggle btn-operation" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <i class="fas fa-cog mr-2"></i> Operation
                                  </button>
                                  <div class="dropdown-menu dropdown-menu-right shadow dropdown-operation-menu">
                                      <button class="dropdown-item" type="button" onclick="window.location.href='<?= base_url('server/audit/'.$r['apps_id'].'/'.$r['infra_id']) ?>'">
                                        <i class="fas fa-clipboard-list fa-fw text-primary mr-2"></i> Audit Trail
                                      </button>       
                                  </div>
                              </div>
                          </td>
                          <td><?= !empty($r['category']) ? $r['category'] : '-' ?></td>
                          <td><?= !empty($r['apps_name']) ? $r['apps_name'] : '-' ?></td>
                          <td><?= !empty($r['module']) ? $r['module'] : '-' ?></td>
                          <td><?= !empty($r['service_name']) ? $r['service_name'] : '-' ?></td>
                          <td><?= !empty($r['db_name']) ? $r['db_name'] : '-' ?></td>
                          <td><?= !empty($r['os_name']) ? $r['os_name'] : '-' ?></td>
                          <td class="text-center"><?= !empty($r['dr']) ? $r['dr'] : '-' ?></td>
                          <td><?= !empty($r['server_type']) ? $r['server_type'] : '-' ?></td>
                          <td class="text-center"><?= (int)$r['svr_web_prod'] ?></td>
                          <td class="text-center"><?= (int)$r['svr_apps_prod'] ?></td>
                          <td class="text-center"><?= (int)$r['svr_db_prod'] ?></td>
                          <td class="text-center"><?= number_format(((float)$r['sla_svr_prod'])*100, 2) ?>%</td>
                          <td class="text-center"><?= (int)$r['svr_web_dr'] ?></td>
                          <td class="text-center"><?= (int)$r['svr_apps_dr'] ?></td>
                          <td class="text-center"><?= (int)$r['svr_db_dr'] ?></td>
                          <td class="text-center"><?= number_format(((float)$r['sla_svr_dr'])*100, 2) ?>%</td>
                          <td class="text-center"><?= number_format((float)$r['sla_standard'], 2) ?>%</td>
                          <td class="text-center font-weight-bold"><?= number_format(((float)$r['sla_actual'])*100, 2) ?>%</td>
                          <td class="text-center font-weight-bold" style="color: <?= (strtolower($r['readyness']) == 'not comply') ? '#dc3545' : '#28a745' ?>;">
                                    <?= !empty($r['readyness']) ? $r['readyness'] : '-' ?></td>
                          <td class="small"><?= !empty($r['suggestion']) ? $r['suggestion'] : '-' ?></td>
                      </tr>
                        <?php endforeach; else: ?>
                            <tr><td colspan="20" class="text-center">No Data Found</td></tr>
                        <?php endif; ?>
                  </tbody>
              </table>
            </div>
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

<?php $this->load->view('layout/foot_links'); ?>

<script>
  $(document).ready(function() {    
  });

  function applyFilter(key) {
    const url = new URL(window.location.href);
    
    // Ambil semua checkbox yang dicheck berdasarkan atribut data-key
    const selectedValues = [];
    $(`input[type="checkbox"][data-key="${key}"]:checked`).each(function() {
        selectedValues.push($(this).val());
    });

    // Hapus filter lama untuk key ini
    url.searchParams.delete(`filter[${key}][]`);
    
    // Tambahkan kembali filter yang baru dipilih
    selectedValues.forEach(val => {
        url.searchParams.append(`filter[${key}][]`, val);
    });

    url.searchParams.delete('per_page'); // Reset ke halaman 1
    window.location.href = url.toString();
}

  function clearFilter(key) {
      const url = new URL(window.location.href);
      url.searchParams.delete(`filter[${key}][]`);
      window.location.href = url.toString();
  }

  function confirmExport() {
      Swal.fire({
          title: 'Export to Excel?',
          icon: 'question',
          showCancelButton: true,
          confirmButtonText: 'Yes, Export',
          customClass: { confirmButton: 'btn btn-save-custom px-4 mx-2', cancelButton: 'btn btn-secondary px-4 mx-2' },
          buttonsStyling: false
      }).then((result) => {
          if (result.isConfirmed) { window.location.href = "<?= base_url('server/export') ?>"; }
      })
  }
</script>
</body>
</html>