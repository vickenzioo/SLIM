<!DOCTYPE html>
<html>
<head>
    <title>Export Data History</title>
    <style>
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid black; padding: 5px; vertical-align: middle; }
        th { background-color: #f2f2f2; text-align: center; font-weight: bold; }
        .text-center { text-align: center; }
    </style>
</head>
<body>
    <h3>Data History Logs</h3>
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Timestamp</th>
                <th>User</th>
                <th>Action</th>
                <th>Page Name</th>
                <th>Field Name</th>
				<th>Old Value</th>  
				<th>New Value</th>  
				<th>Reason</th>
            </tr>
        </thead>
        <tbody>
            <?php $no = 1; 

            foreach($historys as $row): 
                $raw_table = !empty($row['table_name']) ? $row['table_name'] : '-';
                $display_name = isset($table_map[$raw_table]) ? $table_map[$raw_table] : $raw_table;
            ?>
            <tr>
                <td class="text-center"><?= $no++ ?></td>
                <td class="text-center"><?= !empty($row['timestamp']) ? date('Y-m-d H:i:s', strtotime($row['timestamp'])) : '-' ?></td>
                <td class="text-center"><?= !empty($row['username']) ? $row['username'] : '-' ?></td>
                <td class="text-center"><?= !empty($row['action']) ? $row['action'] : '-' ?></td>
                <td><?= $display_name ?></td>
                <td><?= !empty($row['field_name']) ? $row['field_name'] : '-' ?></td>
                <td><?= !empty($row['old_value']) ? $row['old_value'] : '-' ?></td>
                <td><?= !empty($row['new_value']) ? $row['new_value'] : '-' ?></td>   
                <td><?= !empty($row['reason']) ? $row['reason'] : '-' ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</body>
</html>