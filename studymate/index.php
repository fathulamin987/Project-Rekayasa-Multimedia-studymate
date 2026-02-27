<?php
include 'koneksi.php';

// Cek login
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$id_user = $_SESSION['user_id'];
$username = $_SESSION['username'];

// --- LOGIKA MENANGANI AKSI (Centang & Favorit) ---
if (isset($_GET['aksi']) && isset($_GET['id'])) {
    $id_task = mysqli_real_escape_string($conn, $_GET['id']);
    
    // Toggle Selesai (Centang)
    if ($_GET['aksi'] == 'selesai') {
        $cek = mysqli_query($conn, "SELECT selesai FROM tasks WHERE id='$id_task'");
        $data = mysqli_fetch_assoc($cek);
        $status_baru = ($data['selesai'] == 1) ? 0 : 1;
        mysqli_query($conn, "UPDATE tasks SET selesai='$status_baru' WHERE id='$id_task'");
    }
    
    // Toggle Favorit (Bintang)
    if ($_GET['aksi'] == 'favorit') {
        $cek = mysqli_query($conn, "SELECT favorit FROM tasks WHERE id='$id_task'");
        $data = mysqli_fetch_assoc($cek);
        $status_baru = ($data['favorit'] == 1) ? 0 : 1;
        mysqli_query($conn, "UPDATE tasks SET favorit='$status_baru' WHERE id='$id_task'");
    }
    
    header("Location: index.php");
    exit;
}

// Ambil data tugas
$query = "SELECT * FROM tasks WHERE id_user = '$id_user' ORDER BY favorit DESC, tenggat ASC";
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - StudyMate</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">

    <style>
        /* --- GLOBAL STYLE --- */
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #C9EFF7; 
            margin: 0;
            padding: 20px;
            color: #333;
            min-height: 100vh;
        }

        .container {
            max-width: 700px;
            margin: 0 auto;
            padding-bottom: 80px;
        }

        /* --- HEADER --- */
        .top-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
        }

        .page-title {
            font-size: 24px;
            font-weight: 700;
            margin: 0;
            color: #000;
        }

        .right-section {
            display: flex;
            align-items: center;
            gap: 20px;
        }

        .user-row {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 14px;
        }

        .username-text { color: black; font-weight: 700; }
        .btn-logout { text-decoration: none; color: #0983FE; font-weight: 600; }

        .btn-add {
            width: 45px;
            height: 45px;
            background-color: #0983FE;
            color: #FFFFFF;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            text-decoration: none;
            box-shadow: 0 4px 10px rgba(9, 131, 254, 0.3);
            /* Animasi dihapus */
        }
        .btn-add:hover { background-color: #006ae0; }

        /* --- STYLE KARTU TUGAS --- */
        .task-card {
            background-color: #FFFFFF;
            padding: 15px 20px;
            border-radius: 15px;
            margin-bottom: 12px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
            display: flex;
            justify-content: space-between; 
            align-items: center;
            cursor: pointer; 
            position: relative;
            /* Animasi dihapus */
        }

        .task-card:hover {
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }

        .task-left {
            display: flex;
            align-items: center;
            gap: 15px;
            flex: 1;
            overflow: hidden;
        }

        .custom-checkbox {
            width: 24px;
            height: 24px;
            border: 2px solid #cbd5e0;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            text-decoration: none;
            color: white;
            font-size: 12px;
            flex-shrink: 0;
            z-index: 5;
        }

        .checked {
            background-color: #0983FE;
            border-color: #0983FE;
        }

        .task-info {
            display: flex;
            flex-direction: column;
            overflow: hidden;
        }

        .task-title {
            font-size: 16px;
            font-weight: 600;
            margin: 0;
            color: #333;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        
        .strikethrough {
            text-decoration: line-through;
            color: #a0aec0;
        }

        .task-date {
            font-size: 12px;
            color: #718096;
            margin-top: 2px;
        }

        /* --- BAGIAN KANAN (IKON) --- */
        .task-right {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-left: 15px;
            flex-shrink: 0;
            z-index: 5;
        }

        .icon-btn {
            font-size: 18px;
            text-decoration: none;
            color: #4A5568;
            padding: 5px;
            /* Animasi dihapus */
        }

        .star-active { color: #F6AD55; } 
        .star-inactive { color: #CBD5E0; }
        .btn-edit:hover { color: #0983FE; }
        .btn-delete:hover { color: #E53E3E; }

        .empty-state { text-align: center; margin-top: 50px; color: #4A5568; }
    </style>
</head>
<body>

    <div class="container">
        <div class="top-header">
            <h2 class="page-title">Daftar Tugas</h2>
            <div class="right-section">
                <div class="user-row">
                    <span class="username-text">Hi, <?= htmlspecialchars($username); ?>!</span>
                    <span>|</span>
                    <a href="logout.php" class="btn-logout" onclick="return confirm('Keluar?')">Logout</a>
                </div>
                <a href="tambah.php" class="btn-add" title="Tambah Tugas"><i class="fa-solid fa-plus"></i></a>
            </div>
        </div>

        <?php if (mysqli_num_rows($result) > 0): ?>
            <?php while ($task = mysqli_fetch_assoc($result)): ?>
                
                <div class="task-card" onclick="location.href='detail.php?id=<?= $task['id']; ?>'">
                    <div class="task-left">
                        <a href="index.php?aksi=selesai&id=<?= $task['id']; ?>" 
                           class="custom-checkbox <?= ($task['selesai'] == 1) ? 'checked' : ''; ?>"
                           onclick="event.stopPropagation();">
                           <?php if($task['selesai'] == 1) echo '<i class="fa-solid fa-check"></i>'; ?>
                        </a>

                        <div class="task-info">
                            <span class="task-title <?= ($task['selesai'] == 1) ? 'strikethrough' : ''; ?>">
                                <?= htmlspecialchars($task['judul']); ?>
                            </span>
                            <span class="task-date">
                                <i class="fa-regular fa-calendar-check" style="font-size: 10px;"></i> 
                                Due: <?= date('d M Y', strtotime($task['tenggat'])); ?>
                            </span>
                        </div>
                    </div>

                    <div class="task-right">
                        <a href="index.php?aksi=favorit&id=<?= $task['id']; ?>" class="icon-btn" onclick="event.stopPropagation();">
                            <?php if ($task['favorit'] == 1): ?>
                                <i class="fa-solid fa-star star-active"></i>
                            <?php else: ?>
                                <i class="fa-regular fa-star star-inactive"></i>
                            <?php endif; ?>
                        </a>

                        <a href="edit.php?id=<?= $task['id']; ?>" class="icon-btn btn-edit" onclick="event.stopPropagation();">
                            <i class="fa-solid fa-pen-to-square"></i>
                        </a>

                        <a href="hapus.php?id=<?= $task['id']; ?>" class="icon-btn btn-delete" onclick="event.stopPropagation(); return confirm('Hapus tugas ini?')">
                            <i class="fa-solid fa-trash-can"></i>
                        </a>
                    </div>
                </div>

            <?php endwhile; ?>
        <?php else: ?>
            <div class="empty-state">
                <i class="fa-solid fa-clipboard-list" style="font-size: 48px; margin-bottom: 10px; opacity: 0.3;"></i>
                <p>Belum ada tugas. Semangat belajarnya!</p>
            </div>
        <?php endif; ?>
    </div>

</body>
</html>