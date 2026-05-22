<!DOCTYPE html>
<html>
<head>
    <title>Export Holiday Calendar</title>
    <style>
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid black; padding: 5px; }
        th { background-color: #f2f2f2; text-align: center; }
        /* Style khusus agar Excel membaca format dd-mm-yyyy sebagai text/tanggal yang konsisten */
        .date-format {
            mso-number-format: "dd-mm-yyyy";
            text-align: center;
        }
    </style>
</head>
<body>
    <h3>Holiday Calendar List (<?= date('Y') ?>)</h3>
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Holiday Name</th>
                <th>Holiday Date</th>
                <th>Type</th>
                <th>Description</th>
            </tr>
        </thead>
        <tbody>
            <?php $no = 1; foreach($holidays as $h): ?>
            <tr>
                <td style="text-align: center;"><?= $no++ ?></td>
                <td><?= $h['name'] ?></td>
                <td class="date-format"><?= date('d-m-Y', strtotime($h['date'])) ?></td>
                <td style="text-align: center;"><?= $h['type'] ?></td>
                <td><?= $h['desc'] ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</body>
</html>