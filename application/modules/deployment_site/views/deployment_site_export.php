<!DOCTYPE html>
<html>
<head>
    <title>Export Data Deployment Site</title>
    <style>
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid black; padding: 5px; }
        th { background-color: #f2f2f2; text-align: center; }
    </style>
</head>
<body>
    <h3>Data Deployment Site List</h3>
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Deployment Site Name</th>
                <th>Status</th> 
                <th>Created By</th>
                <th>Created At</th>
                <th>Modified By</th>
                <th>Modified At</th>
            </tr>
        </thead>
        <tbody>
            <?php $no = 1; if(!empty($deployment_sites)): foreach($deployment_sites as $site): ?>
            <tr>
                <td style="text-align: center;"><?= $no++ ?></td>
                <td><?= $site['deployment_site_name'] ?></td>
                
                <td style="text-align: center;">
                    <?= (isset($site['status']) && $site['status'] == 1) ? 'Active' : 'Non Active' ?>
                </td>

                <td><?= isset($site['created_by']) ? $site['created_by'] : '-' ?></td>
                <td><?= isset($site['created_at']) ? $site['created_at'] : '-' ?></td>
                <td><?= isset($site['modified_by']) ? $site['modified_by'] : '-' ?></td>
                <td><?= isset($site['modified_at']) ? $site['modified_at'] : '-' ?></td>
            </tr>
            <?php endforeach; else: ?>
            <tr>
                <td colspan="7" style="text-align: center;">No Data Found</td>
            </tr>
            <?php endif; ?>
        </tbody>
    </table>
</body>
</html>