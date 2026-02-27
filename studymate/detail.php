<?php
include 'koneksi.php';

// Cek login
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$id_task = $_GET['id'];
$id_user = $_SESSION['user_id'];

// --- LOGIKA TOGGLE FAVORIT ---
if (isset($_GET['aksi']) && $_GET['aksi'] == 'toggle_fav') {
    $q_cek = mysqli_query($conn, "SELECT favorit FROM tasks WHERE id = '$id_task'");
    $d_cek = mysqli_fetch_assoc($q_cek);
    
    $status_baru = ($d_cek['favorit'] == 1) ? 0 : 1;
    mysqli_query($conn, "UPDATE tasks SET favorit = '$status_baru' WHERE id = '$id_task'");
    
    header("Location: detail.php?id=$id_task");
    exit;
}

// Ambil detail tugas
$query = "SELECT * FROM tasks WHERE id = '$id_task' AND id_user = '$id_user'";
$result = mysqli_query($conn, $query);
$task = mysqli_fetch_assoc($result);

if (!$task) {
    echo "<script>alert('Tugas tidak ditemukan!'); window.location='index.php';</script>";
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Tugas - StudyMate</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
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
        }

        .main-wrapper {
            width: 100%;
            max-width: 500px;
            display: flex;
            flex-direction: column;
            gap: 15px; /* Jarak antara tombol kembali dan card */
        }

        /* TOMBOL KEMBALI (DI LUAR CONTAINER, ATAS KIRI) */
        .btn-back {
            text-decoration: none;
            color: #0983FE; /* WARNA BIRU SESUAI REQUEST */
            font-weight: 700;
            font-size: 16px;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            align-self: flex-start; /* Agar nempel di kiri */
            transition: 0.3s;
        }
        .btn-back:hover { transform: translateX(-5px); }

        /* CARD PUTIH */
        .detail-card {
            background-color: #FFFFFF;
            padding: 40px;
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
            position: relative;
        }

        /* Judul Tugas */
        .task-title {
            font-size: 26px;
            font-weight: 700;
            margin: 0 0 10px 0;
            color: #000;
        }

        /* Tanggal (HITAM) */
        .task-date {
            font-size: 14px;
            color: black; 
            font-weight: 500;
            margin-bottom: 25px;
            display: block;
        }

        /* Label Catatan */
        .label-note {
            font-size: 14px;
            font-weight: 600;
            color: #888;
            margin-bottom: 8px;
        }

        /* Isi Catatan */
        .task-note {
            background-color: #F9F9F9;
            padding: 15px;
            border-radius: 15px;
            line-height: 1.6;
            color: #333;
            min-height: 80px;
            margin-bottom: 30px;
            border: 1px solid #eee;
        }

        /* --- ACTION BUTTONS --- */
        .action-row {
            display: flex;
            align-items: center;
            justify-content: flex-end; 
            gap: 20px; 
            border-top: 1px solid #eee; 
            padding-top: 20px;
        }

        .action-link {
            text-decoration: none;
            font-weight: 600;
            font-size: 14px;
            display: flex;
            align-items: center;
            gap: 6px;
            transition: 0.2s;
            cursor: pointer;
        }
        .action-link:hover { transform: scale(1.05); }

        /* 1. FAVORITE (KUNING) */
        .btn-fav {
            color: #FFD700; /* FONT WARNA KUNING SESUAI REQUEST */
            margin-right: auto; 
        }
        .star-icon { color: #FFD700; font-size: 18px; }

        /* 2. EDIT (MERAH) */
        .btn-edit { color: red; }

        /* 3. HAPUS (HITAM) */
        .btn-delete { color: black; }

    </style>
</head>
<body>

    <div class="main-wrapper">
        
        <a href="index.php" class="btn-back">
            <i class="fa-solid fa-arrow-left"></i> Kembali
        </a>
        
        <div class="detail-card">
            
            <h1 class="task-title"><?= htmlspecialchars($task['judul']); ?></h1>
            
            <span class="task-date">
                Due : <?= date('Y - m - d', strtotime($task['tenggat'])); ?>
            </span>
            
            <div class="label-note">Catatan</div>
            <div class="task-note">
                <?= nl2br(htmlspecialchars($task['catatan'])); ?>
            </div>

            <div class="action-row">
                
                <a href="detail.php?id=<?= $task['id']; ?>&aksi=toggle_fav" class="action-link btn-fav">
                    <i class="<?= ($task['favorit'] == 1) ? 'fa-solid' : 'fa-regular'; ?> fa-star star-icon"></i> 
                    Favorite
                </a>

                <a href="edit.php?id=<?= $task['id']; ?>" class="action-link btn-edit">
                    Edit
                </a>

                <a href="hapus.php?id=<?= $task['id']; ?>" class="action-link btn-delete" onclick="return confirm('Yakin ingin menghapus tugas ini?')">
                    Hapus
                </a>

            </div>

        </div>

    </div>

</body>
</html>