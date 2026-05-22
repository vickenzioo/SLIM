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
    <h3>Data Category List</h3>
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Category Name</th>
                <th>Standard Category (%)</th>
                <th>Status</th> 
                <th>Created By</th>
                <th>Created At</th>
                <th>Modified By</th>
                <th>Modified At</th>
            </tr>
        </thead>
        <tbody>
            <?php $no = 1; foreach($categorys as $db): ?>
            <tr>
                <td style="text-align: center;"><?= $no++ ?></td>
                <td><?= $db['category_name'] ?></td>
                <td style="text-align: center;">
                    <?= ($db['standard_category'] !== null && $db['standard_category'] !== '') ? $db['standard_category'] . '%' : '-' ?>
                </td>
                
                <td style="text-align: center;">
                    <?= (isset($db['status']) && $db['status'] == 1) ? 'Active' : 'Non Active' ?>
                </td>

                <td><?= isset($db['created_by']) ? $db['created_by'] : '-' ?></td>
                <td><?= isset($db['created_at']) ? $db['created_at'] : '-' ?></td>
                <td><?= isset($db['modified_by']) ? $db['modified_by'] : '-' ?></td>
                <td><?= isset($db['modified_at']) ? $db['modified_at'] : '-' ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</body>
</html>