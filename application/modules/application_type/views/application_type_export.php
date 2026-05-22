<!DOCTYPE html>
<html>
<head>
    <title>Export Data</title>
    <style>
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid black; padding: 5px; }
        th { background-color: #f2f2f2; text-align: center; }
    </style>
</head>
<body>
    <h3>Data Application Type List</h3>
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Application Type Name</th>
                <th>Status</th> 
                <th>Created By</th>
                <th>Created At</th>
                <th>Modified By</th>
                <th>Modified At</th>
            </tr>
        </thead>
        <tbody>
            <?php $no = 1; foreach($app_types as $row): ?>
            <tr>
                <td style="text-align: center;"><?= $no++ ?></td>
                <td><?= $row['app_type_name'] ?></td>
                
                <td style="text-align: center;">
                    <?= (isset($row['status']) && $row['status'] == 1) ? 'Active' : 'Non Active' ?>
                </td>

                <td><?= isset($row['created_by']) ? $row['created_by'] : '-' ?></td>
                <td><?= isset($row['created_at']) ? $row['created_at'] : '-' ?></td>
                <td><?= isset($row['modified_by']) ? $row['modified_by'] : '-' ?></td>
                <td><?= isset($row['modified_at']) ? $row['modified_at'] : '-' ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</body>
</html>