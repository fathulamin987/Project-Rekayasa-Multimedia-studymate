<?php
session_start();
if (isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>StudyMate</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700;800&display=swap" rel="stylesheet">

    <style>
        /* --- GLOBAL STYLE --- */
        body {
            margin: 0;
            padding: 0;
            font-family: 'Poppins', sans-serif;
            
            /* BACKGROUND FULL SESUAI KODE WARNA ANDA */
            background-color: #47C9E3; 
            
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            color: #FFFFFF;
        }

        .landing-container {
            text-align: center;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        /* --- 1. LOGO GAMBAR (UKURAN DIPERBESAR) --- */
        .logo-img {
            width: 250px;       /* DIUBAH JADI LEBIH BESAR (sebelumnya 150px) */
            height: auto;       /* Tinggi menyesuaikan agar proporsional */
            margin-bottom: 20px;
            border-radius: 0; 
            object-fit: contain;
        }

        /* --- 2. JUDUL --- */
        .app-title {
            font-size: 36px;
            font-weight: 800;
            color: #FFFFFF;
            margin: 0;
            letter-spacing: 1px;
        }

        /* --- 3. SUBTITLE --- */
        .app-subtitle {
            font-size: 16px;
            font-weight: 400;
            color: rgba(255, 255, 255, 0.9);
            margin-top: 5px;
            margin-bottom: 60px;
        }

        /* --- 4. TOMBOL --- */
        .btn-start {
            background-color: #FFFFFF;
            color: #47C9E3;
            
            text-decoration: none;
            font-weight: 700;
            font-size: 18px;
            padding: 16px 60px;
            border-radius: 50px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            transition: background-color 0.2s ease, color 0.2s ease;
        }

        .btn-start:hover {
            background-color: #f0f8ff;
            color: #3bbbd3;
            cursor: pointer;
        }

        /* Responsif HP */
        @media (max-width: 400px) {
            /* Di HP ukurannya disesuaikan sedikit agar tidak terlalu penuh */
            .logo-img { width: 180px; } 
            .app-title { font-size: 28px; }
            .btn-start { width: 70%; padding: 14px 0; }
        }
    </style>
</head>
<body>

    <div class="landing-container">
        
        <img src="logo.png" alt="Logo StudyMate" class="logo-img">
        
        <h1 class="app-title">StudyMate</h1>
        
        <p class="app-subtitle">Study Planner App</p>
        
        <a href="login.php" class="btn-start">Get Started</a>
    </div>

</body>
</html>