<?php
include 'koneksi.php';

if (isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit;
}

if (isset($_POST['register'])) {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $email    = mysqli_real_escape_string($conn, $_POST['email']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    // Cek apakah username/email sudah ada
    $cek = mysqli_query($conn, "SELECT * FROM users WHERE username = '$username' OR email = '$email'");
    
    if (mysqli_num_rows($cek) > 0) {
        $error = "Username atau Email sudah terdaftar!";
    } else {
        // Masukkan data baru
        $query = "INSERT INTO users (username, email, password) VALUES ('$username', '$email', '$password')";
        if (mysqli_query($conn, $query)) {
            echo "<script>
                    alert('Akun berhasil dibuat! Silakan Login.');
                    window.location = 'login.php';
                  </script>";
            exit;
        } else {
            $error = "Gagal mendaftar, coba lagi.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - StudyMate</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">

    <style>
        /* --- GLOBAL STYLE --- */
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #C9EFF7; /* Background Biru Muda */
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            color: #333;
        }

        .main-wrapper {
            width: 100%;
            padding: 20px;
            display: flex;
            justify-content: center;
        }

        /* --- KOTAK PUTIH (CONTAINER) --- */
        .register-card {
            background-color: #FFFFFF;
            width: 100%;
            max-width: 420px;
            padding: 40px;
            border-radius: 20px;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
            text-align: center;
        }

        /* Judul */
        h2.page-title {
            font-size: 28px;
            font-weight: 700;
            margin-bottom: 5px;
            margin-top: 0;
            color: black; /* Judul Hitam */
        }

        p.subtitle {
            font-size: 14px;
            margin-bottom: 30px;
            color: #888;
        }

        /* --- FORM STYLES --- */
        .form-group {
            margin-bottom: 20px;
            text-align: left;
        }

        .form-group label {
            display: block;
            font-weight: 600;
            font-size: 14px;
            color: #333;
            margin-bottom: 8px;
            padding-left: 10px;
        }

        /* Input Styles */
        input[type="text"],
        input[type="email"],
        input[type="password"] {
            width: 100%;
            padding: 14px 20px;
            border: 1px solid #E0E0E0;
            border-radius: 50px; /* Input Bulat */
            background-color: #F9F9F9;
            font-family: 'Poppins', sans-serif;
            font-size: 14px;
            color: #333;
            box-sizing: border-box;
            outline: none;
            transition: all 0.3s;
        }

        input:focus {
            border-color: #0983FE;
            background-color: #FFFFFF;
            box-shadow: 0 0 0 4px rgba(9, 131, 254, 0.1);
        }

        /* --- TOMBOL SIGN UP --- */
        .btn-register {
            width: 100%;
            padding: 15px;
            border: none;
            border-radius: 50px;
            
            /* Warna Biru #0983FE */
            background-color: #0983FE; 
            
            /* Teks Hitam */
            color: black;
            
            font-size: 16px;
            font-weight: 700;
            cursor: pointer;
            box-shadow: 0 5px 15px rgba(9, 131, 254, 0.2);
            transition: all 0.3s;
            margin-top: 10px;
        }

        .btn-register:hover {
            opacity: 0.9;
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(9, 131, 254, 0.3);
        }

        /* Link Bawah */
        .bottom-link {
            margin-top: 25px;
            font-size: 14px;
            color: #666;
        }

        .bottom-link a {
            color: #0983FE;
            text-decoration: none;
            font-weight: 600;
        }

        .error-msg {
            background-color: #ffeaea;
            border: 1px solid #ff4757;
            padding: 12px;
            border-radius: 12px;
            color: #ff4757;
            margin-bottom: 25px;
            font-size: 14px;
            text-align: left;
        }
    </style>
</head>
<body>

    <div class="main-wrapper">
        <div class="register-card">
            
            <h2 class="page-title">Create Account</h2>

            <?php if(isset($error)) echo "<div class='error-msg'>$error</div>"; ?>

            <form method="POST">
                <div class="form-group">
                    <label>Username</label>
                    <input type="text" name="username"  required>
                </div>

                <div class="form-group">
                    <label>Email</label>
                    <input type="email" name="email" required>
                </div>
                
                <div class="form-group">
                    <label>Password</label>
                    <input type="password" name="password"  required>
                </div>
                
                <button type="submit" name="register" class="btn-register">SIGN UP</button>
            </form>

            <div class="bottom-link">
                Sudah punya akun? <a href="login.php">Login</a>
            </div>
            
        </div>
    </div>

</body>
</html>