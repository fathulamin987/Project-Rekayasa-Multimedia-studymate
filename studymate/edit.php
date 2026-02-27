<?php
include 'koneksi.php';

// Cek login
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

// Cek apakah ada ID tugas yang dikirim
if (!isset($_GET['id'])) {
    header("Location: index.php");
    exit;
}

$id_task = $_GET['id'];
$id_user = $_SESSION['user_id'];

// Ambil data tugas lama dari database
$query = "SELECT * FROM tasks WHERE id = '$id_task' AND id_user = '$id_user'";
$result = mysqli_query($conn, $query);
$task = mysqli_fetch_assoc($result);

// Jika tugas tidak ditemukan (misal user iseng ganti ID di URL)
if (!$task) {
    echo "<script>alert('Tugas tidak ditemukan!'); window.location='index.php';</script>";
    exit;
}

// Proses Update Data
if (isset($_POST['update'])) {
    $judul   = mysqli_real_escape_string($conn, $_POST['judul']);
    $tenggat = mysqli_real_escape_string($conn, $_POST['tenggat']);
    $catatan = mysqli_real_escape_string($conn, $_POST['catatan']);

    $updateQuery = "UPDATE tasks SET judul='$judul', tenggat='$tenggat', catatan='$catatan' WHERE id='$id_task' AND id_user='$id_user'";
    
    if (mysqli_query($conn, $updateQuery)) {
        header("Location: index.php");
    } else {
        echo "<script>alert('Gagal mengupdate tugas.');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Tugas - StudyMate</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        /* --- GLOBAL STYLE (SAMA DENGAN TAMBAH.PHP) --- */
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #C9EFF7; /* Background Biru Muda */
            margin: 0;
            padding: 20px;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            color: #333;
            box-sizing: border-box;
        }

        .main-wrapper {
            width: 100%;
            max-width: 500px;
            display: flex;
            flex-direction: column;
            gap: 10px; 
        }

        /* 1. JUDUL DI LUAR CONTAINER (ATAS) */
        h2.page-title {
            font-size: 26px;
            font-weight: 700;
            margin: 0 0 20px 0;
            color: #000;
            text-align: center;
        }

        /* 2. CONTAINER PUTIH (HANYA FORM INPUT) */
        .form-card {
            background-color: #FFFFFF;
            width: 100%;
            padding: 30px;
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
            box-sizing: border-box;
        }

        /* --- FORM STYLES --- */
        .form-group {
            margin-bottom: 20px;
        }

        .form-group:last-child {
            margin-bottom: 0;
        }

        .form-group label {
            display: block;
            font-weight: 600;
            font-size: 14px;
            color: #333;
            margin-bottom: 8px;
            padding-left: 5px;
        }

        /* Input Styles */
        input[type="text"],
        input[type="date"],
        textarea {
            width: 100%;
            padding: 14px 20px;
            border: 1px solid #E0E0E0;
            border-radius: 15px;
            background-color: #F9F9F9;
            font-family: 'Poppins', sans-serif;
            font-size: 14px;
            color: #333;
            box-sizing: border-box;
            outline: none;
            transition: all 0.3s;
        }

        textarea {
            resize: vertical;
            min-height: 100px;
        }

        input:focus, textarea:focus {
            border-color: #0983FE;
            background-color: #FFFFFF;
            box-shadow: 0 0 0 4px rgba(9, 131, 254, 0.1);
        }

        /* WRAPPER TOMBOL (BAWAH) */
        .action-buttons {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0 10px;
            margin-top: 30px; 
        }

        /* 3. TOMBOL KEMBALI (BAWAH KIRI) */
        .btn-back {
            text-decoration: none;
            color: #333;
            font-weight: 600;
            font-size: 16px;
            display: flex;
            align-items: center;
            gap: 8px;
            transition: 0.3s;
        }
        .btn-back:hover { color: #0983FE; }

        /* 4. TOMBOL UPDATE (BAWAH KANAN) */
        .btn-update {
            background-color: #0983FE; /* Warna Biru */
            color: black;              /* Teks Hitam */
            border: none;
            padding: 12px 40px;
            border-radius: 50px;
            font-size: 16px;
            font-weight: 700;
            cursor: pointer;
            box-shadow: 0 4px 10px rgba(9, 131, 254, 0.3);
            transition: background-color 0.3s, box-shadow 0.3s, opacity 0.3s;
        }

        .btn-update:hover {
            opacity: 0.9;
            box-shadow: 0 6px 15px rgba(9, 131, 254, 0.4);
        }

    </style>
</head>
<body>

    <div class="main-wrapper">
        
        <h2 class="page-title">Edit Tugas</h2>

        <form method="POST">
            
            <div class="form-card">
                
                <div class="form-group">
                    <label>Nama Tugas</label>
                    <input type="text" name="judul" value="<?= htmlspecialchars($task['judul']); ?>" required>
                </div>

                <div class="form-group">
                    <label>Date</label>
                    <input type="date" name="tenggat" value="<?= $task['tenggat']; ?>" required>
                </div>

                <div class="form-group">
                    <label>Catatan</label>
                    <textarea name="catatan"><?= htmlspecialchars($task['catatan']); ?></textarea>
                </div>

            </div>

            <div class="action-buttons">
                
                <a href="index.php" class="btn-back">
                    <i class="fa-solid fa-arrow-left"></i> Kembali
                </a>

                <button type="submit" name="update" class="btn-update">UPDATE</button>
            </div>

        </form>

    </div>

</body>
</html>