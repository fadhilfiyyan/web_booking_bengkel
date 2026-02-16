<?php
include 'koneksi.php';

if (!isset($_GET['kode'])) {
    header("Location: cek_booking.html");
    exit();
}

$kode = mysqli_real_escape_string($conn, $_GET['kode']);
$query = "SELECT * FROM booking WHERE kode_akses = '$kode'";
$result = mysqli_query($conn, $query);

if (mysqli_num_rows($result) != 1) {
    header("Location: cek_booking.html?error=notfound");
    exit();
}

$data = mysqli_fetch_assoc($result);

// Status class
$status_class = '';
if ($data['status'] == 'Pending') $status_class = 'status-pending';
elseif ($data['status'] == 'Sedang Diservis') $status_class = 'status-proses';
elseif ($data['status'] == 'Selesai') $status_class = 'status-selesai';

// Estimasi
$estimasi_text = '-';
if ($data['estimasi_selesai']) {
    $estimasi_time = strtotime($data['estimasi_selesai']);
    $estimasi_text = date('d/m/Y H:i', $estimasi_time);
    
    if ($data['status'] == 'Sedang Diservis') {
        $diff = $estimasi_time - time();
        if ($diff > 0) {
            $hours = floor($diff / 3600);
            $minutes = floor(($diff % 3600) / 60);
            $estimasi_text .= '<br><span class="countdown">(Sisa: ' . $hours . ' jam ' . $minutes . ' menit)</span>';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Booking - Speed Garage</title>
    <link rel="stylesheet" href="css/style.css">
    <meta http-equiv="refresh" content="60"> 
</head>
<body>
    <nav class="navbar">
        <div class="container">
            <div class="logo">
                <img src="img/logo.png" alt="Logo" onerror="this.style.display='none'">
                <h2>SPEED GARAGE</h2>
            </div>
            <ul class="nav-menu">
                <li><a href="index.html">Beranda</a></li>
                <li><a href="cek_booking.html">Cek Booking</a></li>
            </ul>
        </div>
    </nav>

    <section class="form-section">
        <div class="container">
            <div class="form-container fade-in">
                <h2>📋 Detail Booking Servis</h2>
                <p class="form-subtitle">Status booking Anda</p>

                <div style="background: #2c3e50; color: #fff; padding: 15px; border-radius: 10px; text-align: center; margin-bottom: 30px;">
                    <p style="margin: 0; font-size: 0.9rem;">Kode Akses</p>
                    <h2 style="margin: 5px 0; letter-spacing: 2px; font-family: monospace;">
                        <?php echo $data['kode_akses']; ?>
                    </h2>
                </div>

                <div class="info-box">
                    <h3>👤 Informasi Pemilik</h3>
                    <table class="info-table">
                        <tr>
                            <td><strong>Nama</strong></td>
                            <td>: <?php echo htmlspecialchars($data['nama']); ?></td>
                        </tr>
                        <tr>
                            <td><strong>No. HP</strong></td>
                            <td>: <?php echo htmlspecialchars($data['hp']); ?></td>
                        </tr>
                    </table>
                </div>

                <div class="info-box">
                    <h3>🚗 Informasi Kendaraan</h3>
                    <table class="info-table">
                        <tr>
                            <td><strong>Merek</strong></td>
                            <td>: <?php echo htmlspecialchars($data['merek']); ?></td>
                        </tr>
                        <tr>
                            <td><strong>Model</strong></td>
                            <td>: <?php echo htmlspecialchars($data['model']); ?></td>
                        </tr>
                        <tr>
                            <td><strong>Tahun</strong></td>
                            <td>: <?php echo htmlspecialchars($data['tahun']); ?></td>
                        </tr>
                    </table>
                </div>

                <div class="info-box">
                    <h3>🔧 Informasi Servis</h3>
                    <table class="info-table">
                        <tr>
                            <td><strong>Jenis Servis</strong></td>
                            <td>: <?php echo htmlspecialchars($data['jenis_servis']); ?></td>
                        </tr>
                        <tr>
                            <td><strong>Harga</strong></td>
                            <td>: Rp <?php echo number_format($data['harga'], 0, ',', '.'); ?></td>
                        </tr>
                        <tr>
                            <td><strong>Tanggal Booking</strong></td>
                            <td>: <?php echo date('d/m/Y H:i', strtotime($data['tanggal_booking'])); ?></td>
                        </tr>
                        <tr>
                            <td><strong>Status</strong></td>
                            <td>: <span class="badge <?php echo $status_class; ?>"><?php echo $data['status']; ?></span></td>
                        </tr>
                        <tr>
                            <td><strong>Estimasi Selesai</strong></td>
                            <td>: <?php echo $estimasi_text; ?></td>
                        </tr>
                    </table>
                </div>

                <?php if ($data['status'] == 'Sedang Diservis'): ?>
                <div class="alert alert-info">
                    <strong>⏰ Mobil Anda sedang dalam proses servis</strong><br>
                    Halaman ini akan refresh otomatis setiap 1 menit untuk update status terbaru.
                </div>
                <?php elseif ($data['status'] == 'Selesai'): ?>
                <div class="alert alert-success">
                    <strong>✅ Servis mobil Anda sudah selesai!</strong><br>
                    Silakan ambil kendaraan Anda di bengkel.
                </div>
                <?php else: ?>
                <div class="alert alert-info">
                    <strong>⏳ Booking Anda sedang menunggu antrian</strong><br>
                    Kami akan segera memproses servis Anda.
                </div>
                <?php endif; ?>

                <div style="text-align: center; margin-top: 30px;">
                    <button onclick="location.reload()" class="btn btn-primary">🔄 Refresh Status</button>
                    <a href="cek_booking.html" class="btn btn-secondary">🔍 Cek Booking Lain</a>
                </div>
            </div>
        </div>
    </section>

    <footer class="footer">
        <div class="container">
            <p>&copy; 2026 Speed Garage. All Rights Reserved.</p>
        </div>
    </footer>
</body>
</html>
<?php mysqli_close($conn); ?>