<!DOCTYPE html>
<html>
<head>
    <title>Export Audit Trail</title>
    <style>
        table { width: 100%; border-collapse: collapse; font-family: sans-serif; }
        th, td { border: 1px solid black; padding: 8px; font-size: 12px; }
        th { background-color: #f2f2f2; text-align: center; }
        .text-center { text-align: center; }
        .date-text { mso-number-format:"\@"; }
    </style>
</head>
<body>
    <h3>Audit Trail Report</h3>
    <p>History of changes for: <strong><?= isset($audit_target) ? $audit_target : 'All Data' ?></strong></p>
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Timestamp</th>
                <th>User</th>
                <th>Action</th>
                <th>Field</th>
                <th>Old Value</th>
                <th>New Value</th>
                <th>Reason</th>
            </tr>
        </thead>
        <tbody>
            <?php $no = 1; foreach($audit_logs as $log): ?>
            <tr>
                <td class="text-center"><?= $no++ ?></td>
                <td class="date-text"><?= $log['timestamp'] ?></td>
                <td><?= $log['username'] ?></td> 
                <td class="text-center"><?= strtoupper($log['action']) ?></td>
                
                <td>
                    <?php 
                        $display_names = [
                            'deployment_model'     => 'Deployment Model',
                            'deployment_provider'  => 'Deployment Provider',
                            'main_deployment_site' => 'Main Deployment Site'
                        ];
                        echo isset($display_names[$log['field_name']]) ? $display_names[$log['field_name']] : ucwords(str_replace('_', ' ', $log['field_name']));
                    ?>
                </td> 
                
                <td style="color: #dc3545;"><?= ($log['old_value'] == null || $log['old_value'] == '-') ? '-' : $log['old_value'] ?></td>
                <td style="color: #28a745;"><?= $log['new_value'] ?></td>
                <td><em><?= $log['reason'] ?></em></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</body>
</html>