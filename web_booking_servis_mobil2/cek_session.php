<?php

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['admin_id']) || !isset($_SESSION['admin_username'])) {
    // Jika belum login, redirect ke halaman login
    header("Location: login.php");
    exit();
}

$timeout_duration = 1800; 
if (isset($_SESSION['login_time'])) {
    $elapsed_time = time() - $_SESSION['login_time'];
    if ($elapsed_time > $timeout_duration) {
        session_unset();
        session_destroy();
        header("Location: login.php?error=timeout");
        exit();
    }
}

$_SESSION['login_time'] = time();
?>
