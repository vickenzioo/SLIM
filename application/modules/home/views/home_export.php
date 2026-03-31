<?php
// Mencegah spasi kosong terkirim
ob_clean();

// Set header agar dibaca sebagai file Excel (.xls)
header("Content-type: application/vnd-ms-excel; charset=utf-8");
header("Content-Disposition: attachment; filename=Portofolio_".date('Y-m-d').".xls");
header("Pragma: no-cache");
header("Expires: 0");
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid black; padding: 5px; vertical-align: middle; }
        th { background-color: #f2f2f2; text-align: center; font-weight: bold; }
        .text-center { text-align: center; }
</style>
</head>
<body>
	<h3>My Portofolio</h3>
    <table>
        <thead>
            <tr>
                <th>Status</th>
                <th>Workflow Status</th>
                <th>Category</th>
                <th>Application Name</th>
                <th>Short Name</th>
                <th>Module</th>
                <th>Database</th>
                <th>Operating Software</th>
                <th>Application Type</th>
                <th>Server Type</th>
                <th>Standard Category (%)</th>
                <th>Description</th>
                <th>Live Year</th>
                <th>Decommission Year</th>
                <th>Resilience</th>
                <th>DR Availability</th>
                <th>HA</th>
                <th>Network</th>
                <th>Deployment Model</th>
                <th>Deployment Provider</th>
                <th>Deployment Site</th>
                <th>Operational Hour</th>
                <th>Operational Day</th>
                <th>Solution Vendor</th>
                <th>Services Vendor</th>
                <th>LOB Directorate</th>
                <th>LOB Sub-Directorate</th>
                <th>LOB Group</th>
                <th>LOB Group Head</th>
                <th>IT Sub-Directorate</th>
                <th>IT Dept Head</th>
                <th>IT Support Group</th>
                <th>IT Group Head</th>
                <th>IT Support Division</th>
                <th>IT Division Head</th>
                <th>App Version</th>
                <th>Dev Language</th>
                <th>App Developer</th>
                <th>Supporting Web Server</th>
                <th>Supporting App Server</th>
                <th>Supporting Others</th>
                <th>Source Code Owned</th>
                <th>URL</th>
            </tr>
        </thead>
        <tbody>
            <?php if(!empty($export_data)): foreach($export_data as $row): ?>
            <?php 
                // Menentukan Label Status Aktif / Tidak Aktif
                $status_label = (isset($row['status']) && $row['status'] == 1) ? 'Active' : 'Non Active';
                
                // Menentukan Label Workflow & Logika NEED RENEWAL
                $workflow_status = isset($row['status_name']) ? $row['status_name'] : '-';
                
                // Jika aplikasi berstatus DONE dan Active, lakukan pengecekan umur submit
                if ($workflow_status === 'DONE' && $status_label === 'Active') {
                    // Cari baris persetujuan Role 1 terakhir (IT SLM) untuk aplikasi ini
                    $ci =& get_instance();
                    $r1_approval = $ci->db->where(['apps_id' => $row['apps_id'], 'user_role_id' => 1, 'status' => 1])
                                          ->order_by('modified_at', 'DESC') // <--- UBAH 'id' MENJADI 'modified_at'
                                          ->limit(1)
                                          ->get('tbl_apps_approval')
                                          ->row_array();
                    
                    $waktu_submit = !empty($r1_approval['submit_date']) ? $r1_approval['submit_date'] : (!empty($r1_approval['modified_at']) ? $r1_approval['modified_at'] : null);
                    
                    if (!empty($waktu_submit)) {
                        $tanggal_submit = strtotime($waktu_submit);
                        $batas_satu_tahun = strtotime('-1 year');
                        
                        // Jika umurnya sudah lewat atau pas 1 tahun
                        if ($tanggal_submit <= $batas_satu_tahun) {
                            $workflow_status = 'NEED RENEWAL';
                        }
                    }
                }
            ?>
            <tr>
                <td class="text-center"><?= $status_label ?></td>
                <td class="text-center"><?= $workflow_status ?></td>
                <td class="text-center"><?= isset($row['category_name']) ? $row['category_name'] : '-' ?></td>
                
                <td><?= isset($row['application_name']) ? $row['application_name'] : '-' ?></td>
                <td><?= isset($row['short_name']) ? $row['short_name'] : '-' ?></td>
                <td><?= isset($row['module']) ? $row['module'] : '-' ?></td>
                <td><?= isset($row['database_names']) ? str_replace(',', ', ', $row['database_names']) : '-' ?></td>
                <td><?= isset($row['os_names']) ? str_replace(',', ', ', $row['os_names']) : '-' ?></td>
                <td><?= isset($row['application_type_name']) ? $row['application_type_name'] : '-' ?></td>
                <td><?= isset($row['server_name']) ? str_replace(',', ', ', $row['server_name']) : '-' ?></td>
                <td class="text-center"><?= !empty($row['standard_category']) ? $row['standard_category'] . '%' : '-' ?></td>
                <td><?= isset($row['apps_description']) ? $row['apps_description'] : '-' ?></td>
                
                <td class="text-center"><?= isset($row['live_year']) ? $row['live_year'] : '-' ?></td>
                <td class="text-center"><?= isset($row['decommission_year']) ? $row['decommission_year'] : '-' ?></td>
                <td class="text-center"><?= isset($row['resilience']) ? $row['resilience'] : '-' ?></td>
                <td class="text-center"><?= isset($row['dr_availability']) ? $row['dr_availability'] : '-' ?></td>
                <td class="text-center"><?= isset($row['ha']) ? $row['ha'] : '-' ?></td>
                
                <td><?= isset($row['network_name']) ? $row['network_name'] : '-' ?></td>
                <td><?= isset($row['deployment_model']) ? $row['deployment_model'] : '-' ?></td>
                <td><?= isset($row['provider_name']) ? $row['provider_name'] : '-' ?></td>
                <td><?= isset($row['site_name']) ? $row['site_name'] : '-' ?></td>
                
                <td><?= isset($row['operational_hour']) ? $row['operational_hour'] : '-' ?></td>
                <td><?= isset($row['operational_day']) ? $row['operational_day'] : '-' ?></td>
                
                <td><?= isset($row['solution_vendor']) ? $row['solution_vendor'] : '-' ?></td>
                <td><?= isset($row['services_vendor']) ? $row['services_vendor'] : '-' ?></td>
                
                <td><?= isset($row['lob_directorate']) ? $row['lob_directorate'] : '-' ?></td>
                <td><?= isset($row['lob_subdirectorate']) ? $row['lob_subdirectorate'] : '-' ?></td>
                <td><?= isset($row['lob_group']) ? $row['lob_group'] : '-' ?></td>
                <td><?= isset($row['lob_group_head']) ? $row['lob_group_head'] : '-' ?></td>
                
                <td><?= isset($row['it_subdirectorate']) ? $row['it_subdirectorate'] : '-' ?></td>
                <td><?= isset($row['it_department_head']) ? $row['it_department_head'] : '-' ?></td>
                <td><?= isset($row['it_support_group']) ? $row['it_support_group'] : '-' ?></td>
                <td><?= isset($row['it_group_head']) ? $row['it_group_head'] : '-' ?></td>
                <td><?= isset($row['it_support_divison']) ? $row['it_support_divison'] : '-' ?></td>
                <td><?= isset($row['it_division_head']) ? $row['it_division_head'] : '-' ?></td>
                
                <td><?= isset($row['application_version']) ? $row['application_version'] : '-' ?></td>
                <td><?= isset($row['development_language']) ? $row['development_language'] : '-' ?></td>
                <td><?= isset($row['application_developer']) ? $row['application_developer'] : '-' ?></td>
                <td><?= isset($row['supporting_web_server']) ? $row['supporting_web_server'] : '-' ?></td>
                <td><?= isset($row['supporting_application_server']) ? $row['supporting_application_server'] : '-' ?></td>
                <td><?= isset($row['supporting_others']) ? $row['supporting_others'] : '-' ?></td>
                <td class="text-center"><?= isset($row['source_code_owned']) ? $row['source_code_owned'] : '-' ?></td>
                <td><?= isset($row['Url']) ? $row['Url'] : '-' ?></td>
            </tr>
            <?php endforeach; else: ?>
            <tr>
                <td colspan="43" style="text-align: center;">No Data Found</td>
            </tr>
            <?php endif; ?>
        </tbody>
    </table>
</body>
</html>