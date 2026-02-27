<?php
session_start();
session_destroy();
header("Location: landing.php"); // Logout kembali ke Landing Page
?>