<?php
include 'koneksi.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $kode_akses = strtoupper(mysqli_real_escape_string($conn, $_POST['kode_akses']));
    
    $query = "SELECT * FROM booking WHERE kode_akses = '$kode_akses'";
    $result = mysqli_query($conn, $query);
    
    if (mysqli_num_rows($result) == 1) {
        // Kode valid, tampilkan detail
        header("Location: detail_booking.php?kode=$kode_akses");
        exit();
    } else {
        // Kode tidak valid
        header("Location: cek_booking.html?error=invalid");
        exit();
    }
} else {
    header("Location: cek_booking.html");
    exit();
}

mysqli_close($conn);
?>