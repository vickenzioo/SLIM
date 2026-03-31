<!DOCTYPE html>
<html>
<head>
    <title>Export Audit Trail</title>
    <style>
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid black; padding: 5px; }
        th { background-color: #f2f2f2; text-align: center; }
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
                <td style="text-align: center;"><?= $no++ ?></td>
                <td class="date-text" style="text-align: center;"><?= $log['timestamp'] ?></td>
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
                
                <td style="color: #dc3545; text-align: left;">
                    <?php 
                        $val_old = ($log['old_value'] == null || $log['old_value'] == '-') ? '-' : $log['old_value'];
                        if ($log['field_name'] == 'status') {
                            echo ($val_old == '1') ? 'Active' : (($val_old == '0') ? 'Non Active' : $val_old);
                        } else {
                            echo $val_old;
                        }
                    ?>
                </td>

                <td style="color: #28a745; text-align: left;">
                    <?php 
                        $val_new = $log['new_value'];
                        if ($log['field_name'] == 'status') {
                            echo ($val_new == '1') ? 'Active' : (($val_new == '0') ? 'Non Active' : $val_new);
                        } else {
                            echo $val_new;
                        }
                    ?>
                </td>
                <td><em><?= $log['reason'] ?></em></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</body>
</html>