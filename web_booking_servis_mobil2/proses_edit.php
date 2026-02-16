<?php

include 'cek_session.php';

include 'koneksi.php';


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    
    $id = mysqli_real_escape_string($conn, $_POST['id']);
    $nama = mysqli_real_escape_string($conn, $_POST['nama']);
    $hp = mysqli_real_escape_string($conn, $_POST['hp']);
    $merek = mysqli_real_escape_string($conn, $_POST['merek']);
    $model = mysqli_real_escape_string($conn, $_POST['model']);
    $tahun = mysqli_real_escape_string($conn, $_POST['tahun']);
    $jenis_servis = mysqli_real_escape_string($conn, $_POST['jenis_servis']);
    $harga = mysqli_real_escape_string($conn, $_POST['harga']);
    $status = mysqli_real_escape_string($conn, $_POST['status']);

    if (empty($id) || empty($nama) || empty($hp) || empty($merek) || empty($model) || empty($tahun) || empty($jenis_servis) || empty($harga) || empty($status)) {
        header("Location: admin_dashboard.php?error=edit");
        exit();
    }
    
    $query = "UPDATE booking SET 
              nama = '$nama',
              hp = '$hp',
              merek = '$merek',
              model = '$model',
              tahun = '$tahun',
              jenis_servis = '$jenis_servis',
              harga = '$harga',
              status = '$status'
              WHERE id = '$id'";
    
    if (mysqli_query($conn, $query)) {
        // Berhasil
        header("Location: admin_dashboard.php?sukses=edit");
        exit();
    } else {
        // Gagal
        header("Location: admin_dashboard.php?error=edit");
        exit();
    }
    
} else {
    header("Location: admin_dashboard.php");
    exit();
}

mysqli_close($conn);
?>