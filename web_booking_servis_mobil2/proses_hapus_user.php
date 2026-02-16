<?php

include 'koneksi.php';
session_start();

// Cek parameter
if (!isset($_GET['id']) || !isset($_GET['kode'])) {
    header("Location: data_booking.php");
    exit();
}

$id = mysqli_real_escape_string($conn, $_GET['id']);
$kode = mysqli_real_escape_string($conn, $_GET['kode']);

// Hapus hanya jika kode akses cocok
$query = "DELETE FROM booking WHERE id = '$id' AND kode_akses = '$kode'";

if (mysqli_query($conn, $query)) {
    // Tetap set session untuk kembali ke halaman yang sama
    $_SESSION['verified_kode'] = $kode;
    header("Location: data_booking.php?sukses=hapus");
    exit();
} else {
    header("Location: data_booking.php?error=hapus");
    exit();
}

mysqli_close($conn);
?>