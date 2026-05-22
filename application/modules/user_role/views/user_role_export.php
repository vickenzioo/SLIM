<!DOCTYPE html>
<html>
<head>
    <title>Export User Role Data</title>
    <style>
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid black; padding: 5px; }
        th { background-color: #f2f2f2; text-align: center; }
        .text-center { text-align: center; }
    </style>
</head>
<body>
    <h3>Data User List</h3>
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Username</th>
                <th>Email</th>
                <th>Password</th>
                <th>Role Assignment</th>
                <th>Status</th>
                <th>Created By</th>
                <th>Created At</th>
                <th>Modified By</th>
                <th>Modified At</th>
            </tr>
        </thead>
        <tbody>
            <?php $no = 1; foreach($user_roles as $role): ?>
            <tr>
                <td style="text-align: center;"><?= $no++ ?></td>
                <td><?= isset($role['username']) ? $role['username'] : '-' ?></td>
                <td><?= isset($role['email']) ? $role['email'] : '-' ?></td>
                <td style="text-align: center;">********</td>
                <td style="text-align: center;">
                    <?= isset($role['role_name']) ? strtoupper($role['role_name']) : '-' ?>
                </td>
                <td class="text-center">
                    <?php 
                        if (isset($role['status'])) {
                            echo ($role['status'] == 1) ? 'Active' : 'Non-Active';
                        } else {
                            echo '-';
                        }
                    ?>
                </td>
                <td><?= isset($role['creator_name']) ? $role['creator_name'] : '-' ?></td>
                <td class="text-center"><?= isset($role['created_at']) ? $role['created_at'] : '-' ?></td>
                <td><?= isset($role['modified_by']) ? $role['modified_by'] : '-' ?></td>
                <td class="text-center"><?= isset($role['modified_at']) ? $role['modified_at'] : '-' ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</body>
</html>