<?php
include 'koneksi.php';
if (isset($_GET['id']) && isset($_SESSION['user_id'])) {
    $id = $_GET['id'];
    $user = $_SESSION['user_id'];
    mysqli_query($conn, "DELETE FROM tasks WHERE id='$id' AND id_user='$user'");
}
header("Location: index.php");
?>