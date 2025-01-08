<?php
session_start();
if (!isset($_SESSION['user']) || $_SESSION['user']['kode_role'] != 'MHS') {
    header('Location: ../login.php');
    exit();
}

include '../config/db.php';

// Validasi parameter 'id'
if (!isset($_GET['id']) || empty($_GET['id']) || !is_numeric($_GET['id'])) {
    die("ID proposal tidak valid.");
}

$proposal_id = intval($_GET['id']);

// Ambil data proposal untuk ditampilkan di form edit
$query = "SELECT * FROM proposal WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $proposal_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    die("Proposal tidak ditemukan untuk ID $proposal_id.");
}

$proposal = $result->fetch_assoc();

// Proses update jika data diterima melalui POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $proposal_id = intval($_POST['id']);
    $title = trim($_POST['title']);
    $description = trim($_POST['description']);

    // Validasi input
    if (empty($title) || empty($description)) {
        die("Title dan Description tidak boleh kosong.");
    }

    // Query untuk update proposal
    $query = "UPDATE proposal SET title = ?, description = ?, updated_at = NOW(), kaprodi = 'Pending', koordinator_hima = 'Pending', fakultas = 'Pending', bkal = 'Pending' WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ssi", $title, $description, $proposal_id);

    if ($stmt->execute()) {
        echo "Proposal berhasil diperbarui";
    } else {
        echo "Error: " . $stmt->error;
    }
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Proposal</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f9f9f9;
            color: #333;
        }

        h1 {
            text-align: center;
            color: #4CAF50;
            margin-top: 20px;
            font-size: 24px;
        }

        form {
            max-width: 500px;
            margin: 20px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        label {
            font-weight: bold;
            display: block;
            margin-bottom: 5px;
        }

        input[type="text"], textarea {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 4px;
            font-size: 14px;
            box-sizing: border-box; 
        }

        button[type="submit"] {
            background-color: #4CAF50;
            color: #fff;
            padding: 10px 15px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 14px;
            transition: background-color 0.3s ease;
        }

        button[type="submit"]:hover {
            background-color: #45a049;
        }

        .alert {
            padding: 10px;
            margin-bottom: 15px;
            border-radius: 4px;
            color: #fff;
            text-align: center;
        }

        .alert.success {
            background-color: #4CAF50;
        }

        .alert.error {
            background-color: #f44336;
        }

        @media (max-width: 600px) {
            form {
                padding: 15px;
            }

            h1 {
                font-size: 20px;
            }

            input[type="text"], textarea {
                font-size: 16px; 
            }

            button[type="submit"] {
                padding: 12px 18px; 
                font-size: 16px;
            }
        }

    </style>
</head>
<body>
    <h1>Edit Proposal</h1>

    <div id="alertMessage" class="alert" style="display: none;"></div>

    <form method="POST" id="editForm">
        <input type="hidden" name="id" value="<?= htmlspecialchars($proposal['id']); ?>">

        <label>Title:</label><br>
        <input type="text" name="title" value="<?= htmlspecialchars($proposal['title']); ?>" required><br>

        <label>Description:</label><br>
        <textarea name="description" required><?= htmlspecialchars($proposal['description']); ?></textarea><br><br>

        <button type="submit">Update</button>
    </form>

    <script>
        document.getElementById('editForm').addEventListener('submit', function (e) {
            e.preventDefault();
            var formData = new FormData(this); 

            var xhr = new XMLHttpRequest();
            xhr.open('POST', '', true);
            xhr.onload = function () {
                console.log(xhr.status, xhr.responseText); 
                if (xhr.status === 200 && xhr.responseText.trim() === "Proposal berhasil diperbarui") {
                    // Tampilkan pesan sukses
                    document.getElementById('alertMessage').innerText = 'Proposal berhasil diperbarui!';
                    document.getElementById('alertMessage').style.display = 'block';
                    document.getElementById('alertMessage').style.backgroundColor = '#dff0d8'; 
                    setTimeout(function () {
                        window.location.href = '../dashboard.php'; 
                    }, 2000); 
                } else {
                    console.error('Error:', xhr.status, xhr.statusText); 
                    document.getElementById('alertMessage').innerText = 'Gagal memperbarui proposal.';
                    document.getElementById('alertMessage').style.display = 'block';
                    document.getElementById('alertMessage').style.backgroundColor = '#f2dede'; 
                }
            };
            xhr.onerror = function() {
                console.error('Request failed'); 
                document.getElementById('alertMessage').innerText = 'Terjadi kesalahan pada server.';
                document.getElementById('alertMessage').style.display = 'block';
                document.getElementById('alertMessage').style.backgroundColor = '#f2dede'; 
            };
            xhr.send(formData); 
        });
    </script>
</body>
</html>