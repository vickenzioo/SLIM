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
    <h3>Data Network Product List</h3>
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Product Name</th>
                <th>Product SLA</th>
                <th>Network Name</th>
				<th>Status</th> 
                <th>Created By</th>
                <th>Created At</th>
                <th>Modified By</th>
                <th>Modified At</th>
            </tr>
        </thead>
        <tbody>
            <?php $no = 1; foreach($products as $row): ?>
            <tr>
                <td style="text-align: center;"><?= $no++ ?></td>
                <td><?= $row['product_name'] ?></td>
                <td><?= $row['product_sla'] . '%' ?></td>
                <td><?= $row['network_name'] ?></td>
				<td style="text-align: center;">
                    <?= (isset($db['status']) && $db['status'] == 1) ? 'Active' : 'Non Active' ?>
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