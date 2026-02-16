<?php

include 'koneksi.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    
    $id = mysqli_real_escape_string($conn, $_POST['id']);
    $kode_akses = mysqli_real_escape_string($conn, $_POST['kode_akses']);
    $nama = mysqli_real_escape_string($conn, $_POST['nama']);
    $hp = mysqli_real_escape_string($conn, $_POST['hp']);
    $merek = mysqli_real_escape_string($conn, $_POST['merek']);
    $model = mysqli_real_escape_string($conn, $_POST['model']);
    $tahun = mysqli_real_escape_string($conn, $_POST['tahun']);
    $jenis_servis = mysqli_real_escape_string($conn, $_POST['jenis_servis']);
    $harga = mysqli_real_escape_string($conn, $_POST['harga']);
    
    // Validasi
    if (empty($id) || empty($kode_akses) || empty($nama) || empty($hp)) {
        header("Location: data_booking.php?error=edit");
        exit();
    }
    
    // Verifikasi kode akses dan update
    $query = "UPDATE booking SET 
              nama = '$nama',
              hp = '$hp',
              merek = '$merek',
              model = '$model',
              tahun = '$tahun',
              jenis_servis = '$jenis_servis',
              harga = '$harga'
              WHERE id = '$id' AND kode_akses = '$kode_akses'";
    
    if (mysqli_query($conn, $query)) {
        // Set session verified kode
        $_SESSION['verified_kode'] = $kode_akses;
        header("Location: data_booking.php?sukses=edit");
        exit();
    } else {
        header("Location: data_booking.php?error=edit");
        exit();
    }
    
} else {
    header("Location: data_booking.php");
    exit();
}

mysqli_close($conn);
?>