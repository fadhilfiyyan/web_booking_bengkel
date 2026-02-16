<?php
include 'koneksi.php';

$booking_data = null;
$error = '';

if (isset($_POST['kode_akses'])) {
    $kode = mysqli_real_escape_string($conn, $_POST['kode_akses']);
    
    $query = "SELECT * FROM booking WHERE kode_akses = '$kode'";
    $result = mysqli_query($conn, $query);
    
    if (mysqli_num_rows($result) == 1) {
        $booking_data = mysqli_fetch_assoc($result);
    } else {
        $error = 'Kode akses tidak ditemukan!';
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cek Booking - Speed Garage</title>
    <link rel="stylesheet" href="css/style.css">
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
                <li><a href="form_booking.html">Booking Servis</a></li>
                <li><a href="cek_booking.php" class="active">Cek Booking</a></li>
            </ul>
        </div>
    </nav>

    <section class="form-section">
        <div class="container">
            <div class="form-container fade-in">
                <h2>🔍 Cek Status Booking</h2>
                <p class="form-subtitle">Masukkan kode akses Anda untuk melihat status servis</p>

                <?php if ($error): ?>
                    <div class="alert alert-error"><?php echo $error; ?></div>
                <?php endif; ?>

                <form method="POST" action="">
                    <div class="form-group">
                        <label for="kode_akses">Kode Akses (6 Karakter)</label>
                        <input type="text" 
                               id="kode_akses" 
                               name="kode_akses" 
                               placeholder="Contoh: A3X7K9" 
                               maxlength="6" 
                               style="text-transform: uppercase; letter-spacing: 4px; font-size: 1.3rem; text-align: center;"
                               required 
                               autofocus>
                    </div>
                    <button type="submit" class="btn btn-primary btn-block">🔍 Cek Status</button>
                </form>

                <?php if ($booking_data): ?>
                    <div class="info-box" style="margin-top: 30px;">
                        <h3>📋 Detail Booking Anda</h3>
                        <table class="info-table">
                            <tr>
                                <td><strong>Kode Akses</strong></td>
                                <td>: <code style="background: #ecf0f1; padding: 5px 10px; border-radius: 3px; font-size: 1.2rem; letter-spacing: 3px;"><?php echo $booking_data['kode_akses']; ?></code></td>
                            </tr>
                            <tr>
                                <td><strong>Nama Pemilik</strong></td>
                                <td>: <?php echo htmlspecialchars($booking_data['nama']); ?></td>
                            </tr>
                            <tr>
                                <td><strong>No. HP</strong></td>
                                <td>: <?php echo htmlspecialchars($booking_data['hp']); ?></td>
                            </tr>
                            <tr>
                                <td><strong>Mobil</strong></td>
                                <td>: <?php echo htmlspecialchars($booking_data['merek'] . ' ' . $booking_data['model'] . ' (' . $booking_data['tahun'] . ')'); ?></td>
                            </tr>
                            <tr>
                                <td><strong>Jenis Servis</strong></td>
                                <td>: <?php echo htmlspecialchars($booking_data['jenis_servis']); ?></td>
                            </tr>
                            <tr>
                                <td><strong>Harga</strong></td>
                                <td>: <strong>Rp <?php echo number_format($booking_data['harga'], 0, ',', '.'); ?></strong></td>
                            </tr>
                            <tr>
                                <td><strong>Status</strong></td>
                                <td>: 
                                    <?php
                                    $status_class = '';
                                    if ($booking_data['status'] == 'Pending') $status_class = 'status-pending';
                                    elseif ($booking_data['status'] == 'Sedang Diservis') $status_class = 'status-sedang-diservis';
                                    elseif ($booking_data['status'] == 'Selesai') $status_class = 'status-selesai';
                                    ?>
                                    <span class="badge <?php echo $status_class; ?>"><?php echo $booking_data['status']; ?></span>
                                </td>
                            </tr>
                            <?php if ($booking_data['estimasi_selesai'] && $booking_data['status'] == 'Sedang Diservis'): ?>
                            <tr>
                                <td><strong>Estimasi Selesai</strong></td>
                                <td>: <?php echo date('d/m/Y H:i', strtotime($booking_data['estimasi_selesai'])); ?>
                                    <?php
                                    $now = time();
                                    $estimasi = strtotime($booking_data['estimasi_selesai']);
                                    $diff = $estimasi - $now;
                                    if ($diff > 0) {
                                        $menit = floor($diff / 60);
                                        echo '<br><small class="countdown">(Sisa waktu: ' . $menit . ' menit)</small>';
                                    }
                                    ?>
                                </td>
                            </tr>
                            <?php endif; ?>
                            <tr>
                                <td><strong>Tanggal Booking</strong></td>
                                <td>: <?php echo date('d/m/Y H:i', strtotime($booking_data['tanggal_booking'])); ?></td>
                            </tr>
                        </table>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <footer class="footer">
        <div class="container">
            <p>&copy; 2026 Speed Garage. All Rights Reserved.</p>
        </div>
    </footer>

    <script>
        // Auto uppercase input
        document.getElementById('kode_akses').addEventListener('input', function() {
            this.value = this.value.toUpperCase();
        });
    </script>
</body>
</html>