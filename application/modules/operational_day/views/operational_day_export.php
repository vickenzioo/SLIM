<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Export Data</title>
    <style>
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid black; padding: 5px; }
        th { background-color: #f2f2f2; text-align: center; }
    </style>
</head>
<body>
    <h3>Data Operational Day List</h3>
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Start Day</th>
                <th>End Day</th>
                <th>Total Day</th>
                <th>Status</th> 
                <th>Created By</th>
                <th>Created At</th>
                <th>Modified By</th>
                <th>Modified At</th>
            </tr>
        </thead>
        <tbody>
            <?php $no = 1; foreach($operational_days as $db): ?>
            <tr>
                <td style="text-align: center;"><?= $no++ ?></td>
                <td style="text-align: center;"><?= $db['start_day'] ?></td>
                <td style="text-align: center;"><?= $db['end_day'] ?></td>
                <td style="text-align: center;"><?= $db['total_day'] ?> Days</td>
                
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