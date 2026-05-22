<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Export Portofolio</title>
    <style>
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid black; padding: 5px; }
        th { background-color: #f2f2f2; text-align: center; }
        .text-center { text-align: center; }
    </style>
</head>
<body>

    <h3>Data Portofoflio Application List</h3>

    <table>
        <thead>
            <tr>
                <th>No</th>
                
                <th>Category</th>
                <th>Application Name</th>
                <th>Short Name</th>
                <th>Module</th>
                
                <th>Database</th>
                <th>Operating Software</th>
                
                <th>App Type</th>
                <th>Description</th>
                <th>Live Year</th>
                <th>Decom. Year</th>
                
                <th>Resilience</th>
                <th>DR Avail</th>
                <th>HA</th>
                <th>Flash Copy</th>
                <th>End of Day</th>
                
                <th>Network</th>
                <th>Deployment Model</th>
                <th>Operational Hour</th>
                <th>Operational Day</th>
                
                <th>Principle</th>
                <th>Principle Solution</th>
                
                <th>IT Group</th>
                <th>IT Division</th>
                <th>Directorate</th>
                <th>Sub-Directorate</th>
                <th>Owner Title</th>
                <th>Head Owner (NIK)</th>
                <th>Owner (NIK)</th>
                <th>IT Dept (NIK)</th>
            </tr>
        </thead>
        <tbody>
            <?php if(!empty($list)): ?>
                <?php $no = 1; foreach($list as $row): ?>
                <tr>
                    <td class="text-center"><?= $no++ ?></td>
                    
                    <td><?= isset($row['category_name']) ? $row['category_name'] : '-' ?></td>
                    <td><?= $row['application_name'] ?></td>
                    <td><?= $row['short_name'] ?></td>
                    <td><?= isset($row['module_name']) ? $row['module_name'] : '-' ?></td>
                    
                    <td><?= isset($row['database_names']) ? $row['database_names'] : '-' ?></td>
                    <td><?= isset($row['os_names']) ? $row['os_names'] : '-' ?></td>
                    
                    <td><?= $row['application_type'] ?></td>
                    <td><?= $row['apps_description'] ?></td>
                    <td class="text-center"><?= $row['live_year'] ?></td>
                    <td class="text-center"><?= $row['decommission_year'] ?></td>
                    
                    <td class="text-center"><?= $row['resilience'] ?></td>
                    <td class="text-center"><?= $row['dr_availability'] ?></td>
                    <td class="text-center"><?= $row['ha'] ?></td>
                    <td class="text-center"><?= $row['flash_copy'] ?></td>
                    <td class="text-center"><?= $row['end_of_day'] ?></td>
                    
                    <td><?= isset($row['network_name']) ? $row['network_name'] : '-' ?></td>
                    <td><?= isset($row['deployment_info']) ? $row['deployment_info'] : '-' ?></td>
                    <td><?= isset($row['operational_hour']) ? $row['operational_hour'] : '-' ?></td>
                    <td><?= isset($row['operational_day']) ? $row['operational_day'] : '-' ?></td>
                    
                    <td><?= $row['principle_name'] ?></td>
                    <td><?= $row['principle_solution_name'] ?></td>
                    
                    <td><?= $row['it_group_name'] ?></td>
                    <td><?= $row['it_division_name'] ?></td>
                    <td><?= $row['owner_directorate'] ?></td>
                    <td><?= $row['owner_subdirectorate'] ?></td>
                    <td><?= $row['owner_title'] ?></td>
                    <td><?= $row['nik_owner_head'] ?></td>
                    <td><?= $row['nik_owner'] ?></td>
                    <td><?= $row['nik_it_department'] ?></td>
                </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="30" class="text-center">Data tidak ditemukan</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>

</body>
</html>