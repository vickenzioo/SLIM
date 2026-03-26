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
        table { border-collapse: collapse; width: 100%; font-family: sans-serif; font-size: 11pt; }
        th { background-color: #f2f2f2; font-weight: bold; border: 1px solid #000; padding: 5px; text-align: center; }
        td { border: 1px solid #000; padding: 5px; vertical-align: top; }
        .text-center { text-align: center; }
    </style>
</head>
<body>
	<h3>My Portofolio</h3>
    <table>
        <thead>
            <tr>
                <th>Status</th>
                <th>Category</th>

                <?php if($is_infra): ?>
                    <th>Application Name</th>
                    <th>Module Name</th>
                    <th>Service Name</th>
                    <th>Database Name</th>
                    <th>Operating Software Name</th>
                    
                    <th>Resilience</th>
                    <th>Server Type</th>
                    
                    <th>Prod Web</th>
                    <th>Prod Apps</th>
                    <th>Prod DB</th>
                    <th>SLA SVR PROD</th>
                    
                    <th>DR Web</th>
                    <th>DR Apps</th>
                    <th>DR DB</th>
                    <th>SLA SVR DR</th>
                    
                    <th>SLA SCCA Standard</th>
                    <th>SLA Actual</th>
                    <th>Readyness</th>
                    <th>Suggestion</th>
                    
                <?php elseif($is_bu): ?>
                    <th>Application Name</th>
                    <th>Operational Day</th>
                    <th>Operational Hour</th>
                    
                <?php else: ?>
                    <th>Application Name</th>
                    <th>Short Name</th>
                    <th>Database</th>
                    <th>Operating Software</th>
                    <th>Application Type</th>
                    <th>Description</th> 
                    <th>Live Year</th>
                    <th>Decommission Year</th>
                    <th>Resilience</th>
                    <th>DR Availability</th>
                    <th>HA</th>
                    <th>Flash Copy</th>
                    <th>End of Day</th>
                    <th>Network</th>
                    <th>Deployment</th>
                    <?php if(!in_array($rid, [2, 3])): ?>
                        <th>Operational Hour</th>
                        <th>Operational Day</th>
                    <?php endif; ?>
                    <th>Principle</th>
                    <th>Principle Solution</th>
                    <th>IT Group</th>
                    <th>IT Division</th>
                    <th>Directorate</th>
                    <th>Sub-Directorate</th>
                    <th>Owner Title</th>
                    <th>Head Owner</th>
                    <th>Owner</th>
                    <th>IT Department</th>
                <?php endif; ?>
            </tr>
        </thead>
        <tbody>
            <?php if(!empty($export_data)): foreach($export_data as $row): ?>
            <tr>
                <td class="text-center"><?= $row['calculated_status_label'] ?></td>
                <td class="text-center"><?= isset($row['category_name']) ? $row['category_name'] : '-' ?></td>
                
                <?php if($is_infra): ?>
                    <td><?= isset($row['application_name']) ? $row['application_name'] : '-' ?></td>
                    <td><?= isset($row['module_name']) ? $row['module_name'] : '-' ?></td>
                    <td><?= isset($row['service_name']) ? $row['service_name'] : '-' ?></td>
                    <td><?= isset($row['database_names']) ? $row['database_names'] : '-' ?></td>
                    <td><?= isset($row['os_names']) ? $row['os_names'] : '-' ?></td>
                    
                    <td class="text-center"><?= isset($row['resilience_category']) && $row['resilience_category'] != '' ? $row['resilience_category'] : '-' ?></td>
                    <td class="text-center"><?= isset($row['server_type_name']) ? $row['server_type_name'] : '-' ?></td>

                    <td class="text-center"><?= (int)$row['server_web_prod_count'] ?></td>
                    <td class="text-center"><?= (int)$row['server_app_prod_count'] ?></td>
                    <td class="text-center"><?= (int)$row['server_db_prod_count'] ?></td>
                    <td class="text-center"><?= number_format(((float)$row['sla_svr_prod'])*100, 2) ?>%</td>
                    
                    <td class="text-center"><?= (int)$row['server_web_dr_count'] ?></td>
                    <td class="text-center"><?= (int)$row['server_app_dr_count'] ?></td>
                    <td class="text-center"><?= (int)$row['server_db_dr_count'] ?></td>
                    <td class="text-center"><?= number_format(((float)$row['sla_svr_dr'])*100, 2) ?>%</td>
                    
                    <td class="text-center"><?= number_format((float)$row['sla_standard'], 2) ?>%</td>
                    <td class="text-center"><?= number_format(((float)$row['sla_actual'])*100, 2) ?>%</td>
                    <td class="text-center"><?= $row['readyness'] ?></td>
                    <td><?= $row['suggestion'] ?></td>
                
                <?php elseif($is_bu): ?>
                    <td><?= isset($row['application_name']) ? $row['application_name'] : '-' ?></td>
                    <td><?= isset($row['operational_day']) ? $row['operational_day'] : '-' ?></td>
                    <td><?= isset($row['operational_hour']) ? $row['operational_hour'] : '-' ?></td>
                
                <?php else: ?>
                    <td><?= isset($row['application_name']) ? $row['application_name'] : '-' ?></td>
                    <td><?= isset($row['short_name']) ? $row['short_name'] : '-' ?></td>
                    <td><?= isset($row['database_names']) ? $row['database_names'] : '-' ?></td>
                    <td><?= isset($row['os_names']) ? $row['os_names'] : '-' ?></td>
                    <td><?= isset($row['application_type']) ? $row['application_type'] : '-' ?></td>
                    <td><?= isset($row['apps_description']) ? $row['apps_description'] : '-' ?></td>
                    <td class="text-center"><?= isset($row['live_year']) ? $row['live_year'] : '-' ?></td>
                    <td class="text-center"><?= isset($row['decommission_year']) ? $row['decommission_year'] : '-' ?></td>
                    <td class="text-center"><?= isset($row['resilience']) ? $row['resilience'] : '-' ?></td>
                    <td class="text-center"><?= isset($row['dr_availability']) ? $row['dr_availability'] : '-' ?></td>
                    <td class="text-center"><?= isset($row['ha']) ? $row['ha'] : '-' ?></td>
                    <td class="text-center"><?= isset($row['flash_copy']) ? $row['flash_copy'] : '-' ?></td>
                    <td class="text-center"><?= isset($row['end_of_day']) ? $row['end_of_day'] : '-' ?></td>
                    <td><?= isset($row['network_name']) ? $row['network_name'] : '-' ?></td>
                    <td><?= isset($row['deployment_info']) ? $row['deployment_info'] : '-' ?></td>
                    <?php if(!in_array($rid, [2, 3])): ?>
                        <td><?= isset($row['operational_hour']) ? $row['operational_hour'] : '-' ?></td>
                        <td><?= isset($row['operational_day']) ? $row['operational_day'] : '-' ?></td>
                    <?php endif; ?>
                    <td><?= isset($row['principle_name']) ? $row['principle_name'] : '-' ?></td>
                    <td><?= isset($row['principle_solution_name']) ? $row['principle_solution_name'] : '-' ?></td>
                    <td><?= isset($row['it_group_name']) ? $row['it_group_name'] : '-' ?></td>
                    <td><?= isset($row['it_division_name']) ? $row['it_division_name'] : '-' ?></td>
                    <td><?= isset($row['owner_directorate']) ? $row['owner_directorate'] : '-' ?></td>
                    <td><?= isset($row['owner_subdirectorate']) ? $row['owner_subdirectorate'] : '-' ?></td>
                    <td><?= isset($row['owner_title']) ? $row['owner_title'] : '-' ?></td>
                    <td><?= isset($row['nik_owner_head']) ? $row['nik_owner_head'] : '-' ?></td>
                    <td><?= isset($row['nik_owner']) ? $row['nik_owner'] : '-' ?></td>
                    <td><?= isset($row['nik_it_department']) ? $row['nik_it_department'] : '-' ?></td>
                <?php endif; ?>
            </tr>
            <?php endforeach; else: ?>
            <tr>
                <td colspan="40" style="text-align: center;">No Data Found</td>
            </tr>
            <?php endif; ?>
        </tbody>
    </table>
</body>
</html>