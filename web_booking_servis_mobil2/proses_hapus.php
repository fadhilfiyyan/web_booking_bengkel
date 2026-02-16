<?php

include 'cek_session.php';
include 'koneksi.php';

if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: admin_dashboard.php?error=no_id");
    exit();
}

$id = mysqli_real_escape_string($conn, $_GET['id']);

$check_query = "SELECT * FROM booking WHERE id = '$id'";
$check_result = mysqli_query($conn, $check_query);

if (mysqli_num_rows($check_result) == 0) {
    header("Location: admin_dashboard.php?error=not_found");
    exit();
}


$booking_data = mysqli_fetch_assoc($check_result);
$nama_pemilik = $booking_data['nama'];
$kode_akses = $booking_data['kode_akses'];

$query = "DELETE FROM booking WHERE id = '$id'";
if (mysqli_query($conn, $query)) {
    
    header("Location: admin_dashboard.php?sukses=hapus&nama=" . urlencode($nama_pemilik));
    exit();
} else {
    header("Location: admin_dashboard.php?error=hapus&msg=" . urlencode(mysqli_error($conn)));
    exit();
}

mysqli_close($conn);
?>