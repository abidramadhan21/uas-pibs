<?php
session_start();
if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit();
}

$user = $_SESSION['user'];
include 'config/db.php';

$query = "
    SELECT *, 
        CASE 
            WHEN kaprodi = 'Setuju' AND koordinator_hima = 'Setuju' AND fakultas = 'Setuju' AND bkal = 'Setuju' THEN 'Verified'
            WHEN kaprodi = 'Tidak Setuju' OR koordinator_hima = 'Tidak Setuju' OR fakultas = 'Tidak Setuju' OR bkal = 'Tidak Setuju' THEN 'Declined'
            WHEN kaprodi = 'Pending' OR koordinator_hima = 'Pending' OR fakultas = 'Pending' OR bkal = 'Pending' THEN 
                CASE 
                    WHEN updated_at IS NOT NULL AND TIMESTAMPDIFF(MINUTE, updated_at, NOW()) < 5 THEN 'Updated'
                    WHEN updated_at IS NOT NULL THEN 'Pending'
                    ELSE 'Ongoing'
                END
            WHEN created_at IS NOT NULL AND updated_at IS NULL THEN 'Ongoing'
            ELSE 'Unknown'
        END AS status 
    FROM proposal
";

$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Proposal</title>

    <style>
        .table-container {
            display: flex;
            justify-content: center; 
            align-items: center; 
            min-height: 100vh; 
            padding: 10px;
        }

        table {
            width: 100%; 
            max-width: 1200px; 
            margin-bottom: 20px;
            border-collapse: collapse; 
            box-shadow: 0 2px 5px rgba(1, 1, 1, 0.1);
        }

        table th, table td {
            padding: 10px;
            text-align: center;
            font-size: 14px;
            border: 1px solid #ddd; 
        }

        table th {
            background-color: #34495e; 
            color: white; 
            font-weight: bold;
        }

        table tr:nth-child(even) {
            background-color: #f9f9f9; 
        }

        table tr:hover {
            background-color: #f1f1f1; 
        }

        .btn-view, .btn-setuju, .btn-tolak, .btn-edit, .btn-delete {
            font-size: 14px;
            padding: 8px 16px;
            border: none;
            border-radius: 5px; 
            cursor: pointer;
            transition: background-color 0.3s ease;
            text-align: center;
            text-decoration: none;
        }

        .btn-view {
            background-color: #4CAF50; 
            color: white;
        }

        .btn-view:hover {
            background-color: #38813b;
        }

        .btn-setuju {
            background-color: #28a745;
            color: white;
        }

        .btn-setuju:hover {
            background-color: #218838;
        }

        .btn-tolak {
            background-color: #dc3545; 
            color: white;
        }

        .btn-tolak:hover {
            background-color: #c82333;
        }

        .btn-edit {
            background-color: #2196F3; 
            color: white;
        }

        .btn-edit:hover {
            background-color: #1a6aac;
        }

        .btn-delete {
            background-color: #f44336; 
            color: white;
        }

        .btn-delete:hover {
            background-color: #b83229;
        }

        .btn-setuju:active, .btn-tolak:active, .btn-edit:active, .btn-delete:active {
            transform: scale(0.98); 
        }

        @media (max-width: 768px) {
            .table-container {
                padding: 5px;
            }

            table {
                font-size: 12px; 
            }

            table th, table td {
                padding: 8px; 
            }

            .btn-view, .btn-setuju, .btn-tolak, .btn-edit, .btn-delete {
                font-size: 12px; 
                padding: 6px 12px; 
            }
        }

        @media (max-width: 480px) {
            .table-container {
                flex-direction: column; 
            }

            table {
                width: 100%; 
                overflow-x: auto; 
            }

            .btn-view, .btn-setuju, .btn-tolak, .btn-edit, .btn-delete {
                font-size: 10px; 
                padding: 5px 10px; 
            }
        }
    </style>

    <script>
        window.onload = function() {
            <?php if (isset($_SESSION['flash_message'])): ?>
            alert("<?= $_SESSION['flash_message']; ?>");
            <?php unset($_SESSION['flash_message']); ?>
            <?php endif; ?>
        }
    </script>
</head>
<body>
    <h2 style="font-size: 50px; margin-bottom: 20px;">Daftar Proposal</h2>
    <h3 style="font-size: 20px; margin-bottom: 20px;">Proposal yang ada</h3>
   
    <table>
        <tr>
            <th>ID</th>
            <th>Title</th>
            <th>Description</th>
            <th>File</th>
            <th>Status Kaprodi</th>
            <th>Status Koordinator HIMA</th>
            <th>Status Fakultas</th>
            <th>Status BKAL</th>
            <th>Status</th>
            <th>Actions</th>
        </tr>
        <?php while ($proposal = mysqli_fetch_assoc($result)): ?>
        <tr>
            <td><?= htmlspecialchars($proposal['id']); ?></td>
            <td><?= htmlspecialchars($proposal['title']); ?></td>
            <td><?= htmlspecialchars($proposal['description']); ?></td>
            <td>
            <?php if ($proposal['file_path']): ?>
            <a href="crud/<?= htmlspecialchars($proposal['file_path']); ?>" target="_blank">
            <button style="background-color: #4CAF50; color: white; padding: 5px 5px; border: none; border-radius: 5px; cursor: pointer;">
            View File
             </button></a>
                <?php else: ?>
                    No file uploaded
                <?php endif; ?>
            </td>
            <td><?= htmlspecialchars($proposal['kaprodi']); ?></td>
            <td><?= htmlspecialchars($proposal['koordinator_hima']) ?? 'Pending'; ?></td>
            <td><?= htmlspecialchars($proposal['fakultas']) ?? 'Pending'; ?></td>
            <td><?= htmlspecialchars($proposal['bkal']) ?? 'Pending'; ?></td>
            <td>
                <?= htmlspecialchars($proposal['status']); ?>
            </td>
            <td>
                <?php if ($user['kode_role'] == 'PRD' && $proposal['kaprodi'] == 'Pending'): ?>
                     <a href="validate_proposal.php?id=<?= htmlspecialchars($proposal['id']); ?>&action=approve&role=PRD" style="text-decoration: none; color: white;">
                        <button class="btn-setuju">Setuju</button>
                     </a>
                    <a href="validate_proposal.php?id=<?= htmlspecialchars($proposal['id']); ?>&action=decline&role=PRD" style="text-decoration: none; color: white;">
                        <button class="btn-tolak">Tidak Setuju</button>
                    </a>
                <?php elseif ($user['kode_role'] == 'KRD' && $proposal['kaprodi'] == 'Setuju' && $proposal['koordinator_hima'] == 'Pending'): ?>
                    <a href="validate_proposal.php?id=<?= htmlspecialchars($proposal['id']); ?>&action=approve&role=KRD" style="text-decoration: none; color: white;">
                        <button class="btn-setuju">Setuju</button>
                    </a>
                    <a href="validate_proposal.php?id=<?= htmlspecialchars($proposal['id']); ?>&action=decline&role=KRD" style="text-decoration: none; color: white;">
                        <button class="btn-tolak">Tidak Setuju</button>
                    </a>
                <?php elseif ($user['kode_role'] == 'FKT' && $proposal['kaprodi'] == 'Setuju' && $proposal['koordinator_hima'] == 'Setuju' && $proposal['fakultas'] == 'Pending'): ?>
                     <a href="validate_proposal.php?id=<?= htmlspecialchars($proposal['id']); ?>&action=approve&role=FKT" style="text-decoration: none; color: white;">
                        <button class="btn-setuju">Setuju</button>
                     </a>
                    <a href="validate_proposal.php?id=<?= htmlspecialchars($proposal['id']); ?>&action=decline&role=FKT" style="text-decoration: none; color: white;">
                        <button class="btn-tolak">Tidak Setuju</button>
                    </a>
                <?php elseif ($user['kode_role'] == 'BKL' && $proposal['kaprodi'] == 'Setuju' && $proposal['koordinator_hima'] == 'Setuju' && $proposal['fakultas'] == 'Setuju' && $proposal['bkal'] == 'Pending'): ?>
                    <a href="validate_proposal.php?id=<?= htmlspecialchars($proposal['id']); ?>&action=approve&role=BKL" style="text-decoration: none; color: white;">
                        <button class="btn-setuju">Setuju</button>
                    </a>
                    <a href="validate_proposal.php?id=<?= htmlspecialchars($proposal['id']); ?>&action=decline&role=BKL" style="text-decoration: none; color: white;">
                        <button class="btn-tolak">Tidak Setuju</button>
                    </a>
                <?php elseif ($user['kode_role'] != 'PRD' && $user['kode_role'] != 'KRD' && $user['kode_role'] != 'FKT' && $user['kode_role'] != 'BKL'): ?>
                    <div style="display: flex; justify-content: space-between;">
                        <a href="crud/edit_proposal.php?id=<?= htmlspecialchars($proposal['id']); ?>" class="btn-edit">Edit</a>
                        <a href="crud/delete_proposal.php?id=<?= htmlspecialchars($proposal['id']); ?>" class="btn-delete" onclick="return confirm('Yakin ingin menghapus proposal ini?')">Delete</a>
                    </div>
                <?php endif; ?>
            </td>
        </tr>
        <?php endwhile; ?>
    </table>
</body>
</html>
