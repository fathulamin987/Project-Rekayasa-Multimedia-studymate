<?php
include "config.php";
if (!isset($_SESSION['user_id'])) exit("Login dulu");

if ($_POST['csrf'] !== $_SESSION['csrf']) exit("CSRF salah");

$id = (int)$_POST['id'];
$uid = $_SESSION['user_id'];

mysqli_query($conn, "DELETE FROM tasks WHERE id=$id AND id_user=$uid");

header("Location: index.php");
