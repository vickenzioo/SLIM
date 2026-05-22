<?php
// Mencegah spasi kosong terkirim
ob_clean();

// Set header agar dibaca sebagai file Excel (.xls)
header("Content-type: application/vnd-ms-excel; charset=utf-8");
header("Content-Disposition: attachment; filename=Portofolio_".date('Y-m-d').".xls");
header("Pragma: no-cache");
header("Expires: 0");

// 1. Definisikan mapping 43 kolom dengan Index aslinya.
// Index 0 sengaja dilewati karena itu adalah kolom 'Action' yang tidak ikut di-export.
$columns_def = [
    1 => ['label' => 'Status', 'key' => 'status_custom', 'center' => true],
    2 => ['label' => 'Workflow Status', 'key' => 'workflow_custom', 'center' => true],
    3 => ['label' => 'Category', 'key' => 'category_name', 'center' => true],
    4 => ['label' => 'Application Name', 'key' => 'application_name'],
    5 => ['label' => 'Short Name', 'key' => 'short_name'],
    6 => ['label' => 'Module', 'key' => 'module'],
    7 => ['label' => 'Database', 'key' => 'database_names', 'format' => 'comma_space'],
    8 => ['label' => 'Operating Software', 'key' => 'os_names', 'format' => 'comma_space'],
    9 => ['label' => 'Application Type', 'key' => 'application_type_name'],
    10 => ['label' => 'Server Type', 'key' => 'server_name', 'format' => 'comma_space'],
    11 => ['label' => 'Standard Category (%)', 'key' => 'standard_category', 'format' => 'percent', 'center' => true],
    12 => ['label' => 'Description', 'key' => 'apps_description'],
    13 => ['label' => 'Live Year', 'key' => 'live_year', 'center' => true],
    14 => ['label' => 'Decommission Year', 'key' => 'decommission_year', 'center' => true],
    15 => ['label' => 'Resilience', 'key' => 'resilience', 'center' => true],
    16 => ['label' => 'DR Availability', 'key' => 'dr_availability', 'center' => true],
    17 => ['label' => 'HA', 'key' => 'ha', 'center' => true],
    18 => ['label' => 'Network', 'key' => 'network_name'],
    19 => ['label' => 'Deployment Model', 'key' => 'deployment_model'],
    20 => ['label' => 'Deployment Provider', 'key' => 'provider_name'],
    21 => ['label' => 'Deployment Site', 'key' => 'site_name'],
    22 => ['label' => 'Operational Hour', 'key' => 'operational_hour'],
    23 => ['label' => 'Operational Day', 'key' => 'operational_day'],
    24 => ['label' => 'Solution Vendor', 'key' => 'solution_vendor'],
    25 => ['label' => 'Services Vendor', 'key' => 'services_vendor'],
    26 => ['label' => 'LOB Directorate', 'key' => 'lob_directorate'],
    27 => ['label' => 'LOB Sub-Directorate', 'key' => 'lob_subdirectorate'],
    28 => ['label' => 'LOB Group', 'key' => 'lob_group'],
    29 => ['label' => 'LOB Group Head', 'key' => 'lob_group_head'],
    30 => ['label' => 'IT Sub-Directorate', 'key' => 'it_subdirectorate'],
    31 => ['label' => 'IT Dept Head', 'key' => 'it_department_head'],
    32 => ['label' => 'IT Support Group', 'key' => 'it_support_group'],
    33 => ['label' => 'IT Group Head', 'key' => 'it_group_head'],
    34 => ['label' => 'IT Support Division', 'key' => 'it_support_divison'],
    35 => ['label' => 'IT Division Head', 'key' => 'it_division_head'],
    36 => ['label' => 'App Version', 'key' => 'application_version'],
    37 => ['label' => 'Dev Language', 'key' => 'development_language'],
    38 => ['label' => 'App Developer', 'key' => 'application_developer'],
    39 => ['label' => 'Supporting Web Server', 'key' => 'supporting_web_server'],
    40 => ['label' => 'Supporting App Server', 'key' => 'supporting_application_server'],
    41 => ['label' => 'Supporting Others', 'key' => 'supporting_others'],
    42 => ['label' => 'Source Code Owned', 'key' => 'source_code_owned', 'center' => true],
    43 => ['label' => 'URL', 'key' => 'Url']
];

// 2. Olah urutan dari parameter column_order
$final_order = [];
if (!empty($column_order)) {
    // Karena dikirim via form GET, datanya berbentuk string "[0,4,1,2,3...]"
    $order_arr = json_decode($column_order, true);
    if (is_array($order_arr)) {
        foreach ($order_arr as $idx) {
            $idx = (int)$idx;
            if ($idx == 0) continue; // Abaikan index 0 (Action)
            if (isset($columns_def[$idx])) {
                $final_order[] = $idx;
            }
        }
    }
}

// 3. Jika proses gagal/kosong, kembalikan ke urutan default 1 sampai 43
if (empty($final_order) || count($final_order) < count($columns_def)) {
    $final_order = range(1, 43);
}
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
                <?php foreach ($final_order as $idx): ?>
                    <th><?= $columns_def[$idx]['label'] ?></th>
                <?php endforeach; ?>
            </tr>
        </thead>
        <tbody>
            <?php 
            if(!empty($export_data)): 
                $ci =& get_instance(); // Load instance sekali saja di luar loop data
                
                foreach($export_data as $row): 
                    // [LOGIKA STATUS & WORKFLOW DARI KODE ANDA SEBELUMNYA]
                    if (isset($row['app_status_label'])) {
                        $status_label = $row['app_status_label'];
                    } else {
                        $status_label = (isset($row['status']) && $row['status'] == 1) ? 'Active' : 'Not Active';
                    }
                    
                    $workflow_status = isset($row['status_name']) ? $row['status_name'] : '-';
                    
                    if ($workflow_status === 'DONE' && $status_label === 'Active') {
                        $r1_approval = $ci->db->where(['apps_id' => $row['apps_id'], 'user_role_id' => 1, 'status' => 1])
                                              ->order_by('modified_at', 'DESC')
                                              ->limit(1)
                                              ->get('tbl_apps_approval')
                                              ->row_array();
                        
                        $waktu_submit = !empty($r1_approval['submit_date']) ? $r1_approval['submit_date'] : (!empty($r1_approval['modified_at']) ? $r1_approval['modified_at'] : null);
                        
                        if (!empty($waktu_submit) && strtotime($waktu_submit) <= strtotime('-1 year')) {
                            $workflow_status = 'NEED RENEWAL';
                        }
                    }

                    // Masukkan kembali ke dalam row agar terbaca oleh pemetaan array
                    $row['status_custom'] = $status_label;
                    $row['workflow_custom'] = $workflow_status;
            ?>
            <tr>
                <?php 
                foreach ($final_order as $idx): 
                    $col = $columns_def[$idx];
                    $val = isset($row[$col['key']]) ? (string)$row[$col['key']] : '';
                    
                    // Formatting Data Cepat
                    if (isset($col['format'])) {
                        if ($col['format'] == 'comma_space') {
                            $val = str_replace(',', ', ', $val);
                        } elseif ($col['format'] == 'percent') {
                            $val = ($val !== '') ? $val . '%' : '';
                        }
                    }

                    // Tampilkan "-" jika kosong
                    if ($val === '') {
                        $val = '-';
                    }
                    
                    $class = isset($col['center']) ? 'class="text-center"' : '';
                ?>
                <td <?= $class ?>><?= htmlspecialchars($val) ?></td>
                <?php endforeach; ?>
            </tr>
            <?php endforeach; else: ?>
            <tr>
                <td colspan="<?= count($final_order) ?>" style="text-align: center;">No Data Found</td>
            </tr>
            <?php endif; ?>
        </tbody>
    </table>
</body>
</html>