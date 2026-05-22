<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Export Server Management</title>
    <style>
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid black; padding: 5px; font-family: sans-serif; font-size: 12px; }
        th { background-color: #f2f2f2; text-align: center; vertical-align: middle; }
        .text-center { text-align: center; }
        .text-bold { font-weight: bold; }
        .not-comply { color: #dc3545; font-weight: bold; }
        .comply { color: #28a745; font-weight: bold; }
    </style>
</head>
<body>

    <h3>Data Server Management List</h3>

    <table>
        <thead>
            <tr>
                <th rowspan="2">No</th>
                <th rowspan="2">Module</th>
                <th rowspan="2">Server Type</th>
                <th rowspan="2">Application Name</th>
                <th rowspan="2">DR</th>
                <th colspan="3">Prod</th>
                <th rowspan="2">SLA SVR PROD</th>
                <th colspan="3">DR</th>
                <th rowspan="2">SLA SVR DR</th>
                <th rowspan="2">SLA SCCA Standard</th>
                <th rowspan="2">SLA Actual</th>
                <th rowspan="2">Readyness</th>
                <th rowspan="2">Suggestion</th>
            </tr>
            <tr>
                <th>Web</th>
                <th>Apps</th>
                <th>DB</th>
                <th>Web</th>
                <th>Apps</th>
                <th>DB</th>
            </tr>
        </thead>
        <tbody>
            <?php if(!empty($rows)): ?>
                <?php $no = 1; foreach($rows as $r): ?>
                <tr>
                    <td class="text-center"><?= $no++ ?></td>
                    <td><?= !empty($r['module']) ? $r['module'] : '-' ?></td>
                    <td><?= !empty($r['server_type']) ? $r['server_type'] : '-' ?></td>
                    <td><?= !empty($r['apps_name']) ? $r['apps_name'] : '-' ?></td>
                    <td class="text-center"><?= !empty($r['dr']) ? $r['dr'] : '-' ?></td>

                    <td class="text-center"><?= (int)$r['svr_web_prod'] ?></td>
                    <td class="text-center"><?= (int)$r['svr_apps_prod'] ?></td>
                    <td class="text-center"><?= (int)$r['svr_db_prod'] ?></td>
                    <td class="text-center"><?= number_format(((float)$r['sla_svr_prod'])*100, 2) ?>%</td>

                    <td class="text-center"><?= (int)$r['svr_web_dr'] ?></td>
                    <td class="text-center"><?= (int)$r['svr_apps_dr'] ?></td>
                    <td class="text-center"><?= (int)$r['svr_db_dr'] ?></td>
                    <td class="text-center"><?= number_format(((float)$r['sla_svr_dr'])*100, 2) ?>%</td>

                    <td class="text-center"><?= number_format((float)$r['sla_standard'], 2) ?>%</td>
                    <td class="text-center text-bold"><?= number_format(((float)$r['sla_actual'])*100, 2) ?>%</td>
                    
                    <td class="text-center <?= (strtolower($r['readyness']) == 'not comply') ? 'not-comply' : 'comply' ?>">
                        <?= !empty($r['readyness']) ? $r['readyness'] : '-' ?>
                    </td>
                    <td><small><?= !empty($r['suggestion']) ? $r['suggestion'] : '-' ?></small></td>
                </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="17" class="text-center">Data tidak ditemukan</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>

</body>
</html>