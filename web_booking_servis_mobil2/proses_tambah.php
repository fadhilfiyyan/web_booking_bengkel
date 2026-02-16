<?php


include 'koneksi.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    
    // Ambil data dari form
    $nama = mysqli_real_escape_string($conn, $_POST['nama']);
    $hp = mysqli_real_escape_string($conn, $_POST['hp']);
    $merek = mysqli_real_escape_string($conn, $_POST['merek']);
    $model = mysqli_real_escape_string($conn, $_POST['model']);
    $tahun = mysqli_real_escape_string($conn, $_POST['tahun']);
    $jenis_servis = mysqli_real_escape_string($conn, $_POST['jenis_servis']);
    $harga = mysqli_real_escape_string($conn, $_POST['harga']);
    
    // Validasi
    if (empty($nama) || empty($hp) || empty($merek) || empty($model) || empty($tahun) || empty($jenis_servis) || empty($harga)) {
        header("Location: form_booking.html?error=tambah");
        exit();
    }
    
    //GENERATE KODE AKSES 6 KARAKTER (Contoh: A3X7K9)
    function buatKodeAkses() {
        $karakter = 'ABCDEFGHJKLMNPQRSTUVWXYZ23456789';
        $kode = '';
        for ($i = 0; $i < 6; $i++) {
            $kode .= $karakter[rand(0, strlen($karakter) - 1)];
        }
        return $kode;
    }
    
    // Generate kode unik (cek jika sudah ada)
    do {
        $kode_akses = buatKodeAkses();
        $cek = mysqli_query($conn, "SELECT id FROM booking WHERE kode_akses = '$kode_akses'");
    } while(mysqli_num_rows($cek) > 0);

    $query = "INSERT INTO booking (kode_akses, nama, hp, merek, model, tahun, jenis_servis, harga, status) 
              VALUES ('$kode_akses', '$nama', '$hp', '$merek', '$model', '$tahun', '$jenis_servis', '$harga', 'Pending')";
    
    if (mysqli_query($conn, $query)) {
        header("Location: booking_sukses.php?kode=$kode_akses");
        exit();
    } else {
        header("Location: form_booking.html?error=tambah");
        exit();
    }
    
} else {
    header("Location: form_booking.html");
    exit();
}

mysqli_close($conn);
?>