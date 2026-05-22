<?php
// Mencegah spasi kosong terkirim
ob_clean();

// Bersihkan nama file dari karakter aneh
$clean_name = preg_replace('/[^A-Za-z0-9\-]/', '_', $app_name);
$filename = "Audit_Trail_" . $clean_name . "_" . date('Ymd_His') . ".xls";

// Header untuk memaksa download sebagai file Excel
header("Content-type: application/vnd-ms-excel; charset=utf-8");
header("Content-Disposition: attachment; filename=\"$filename\"");
header("Pragma: no-cache");
header("Expires: 0");
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid black; padding: 5px; vertical-align: middle; }
        th { background-color: #f2f2f2; text-align: center; font-weight: bold; }
        .text-center { text-align: center; }
    </style>
</head>
<body>
    <h3>Audit Trail Log - <?= $app_name ?></h3>
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Timestamp</th>
                <th>User Role</th>
                <th>Action</th>
                <th>Remarks</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($audit_trail)): ?>
                <?php $no = 1; foreach ($audit_trail as $row): ?>
                    <tr>
                        <td class="text-center"><?= $no++ ?></td>
                        <td class="text-center"><?= isset($row['created_at']) ? $row['created_at'] : '-' ?></td>
                        <td class="text-center">
                            <b><?= isset($row['role_name']) ? $row['role_name'] : '-' ?></b>
                        </td>
                        <td class="text-center"><?= isset($row['action']) ? $row['action'] : '-' ?></td>
                        <td>
                            <?php 
                                $remarks = isset($row['remarks']) ? $row['remarks'] : '-';
                                // Mengubah format teks menjadi merah coret dan hijau tebal agar terbaca oleh Excel
                                $remarks = preg_replace(
                                    "/'(.*?)'\s*->\s*'(.*?)'/", 
                                    "<span style=\"color: #dc3545; text-decoration: line-through;\">'$1'</span> -> <span style=\"color: #28a745; font-weight: bold;\">'$2'</span>", 
                                    $remarks
                                );
                                echo nl2br($remarks);
                            ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="5" class="text-center">No Audit Data Found</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</body>
</html>