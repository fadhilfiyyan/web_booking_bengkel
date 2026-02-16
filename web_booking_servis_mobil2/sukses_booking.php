<?php
include 'koneksi.php';

if (!isset($_GET['kode'])) {
    header("Location: index.html");
    exit();
}

$kode = mysqli_real_escape_string($conn, $_GET['kode']);
$query = "SELECT * FROM booking WHERE kode_akses = '$kode'";
$result = mysqli_query($conn, $query);

if (mysqli_num_rows($result) != 1) {
    header("Location: index.html");
    exit();
}

$booking = mysqli_fetch_assoc($result);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking Berhasil - Speed Garage</title>
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
                <li><a href="cek_booking.html">Cek Booking</a></li>
            </ul>
        </div>
    </nav>

    <section class="form-section">
        <div class="container">
            <div class="form-container fade-in">
                <div style="text-align: center;">
                    <h2 style="color: #27ae60;">✅ Booking Berhasil!</h2>
                    <p>Terima kasih telah melakukan booking servis</p>
                </div>

                <div class="alert alert-success" style="margin: 30px 0;">
                    <h3 style="margin-bottom: 15px;">🔑 KODE AKSES ANDA:</h3>
                    <div style="background: #2c3e50; color: #fff; padding: 20px; border-radius: 10px; text-align: center;">
                        <h1 style="font-size: 2.5rem; letter-spacing: 3px; margin: 0; font-family: monospace;">
                            <?php echo $booking['kode_akses']; ?>
                        </h1>
                    </div>
                    <p style="margin-top: 15px; color: #e74c3c; font-weight: bold;">
                        ⚠️ SIMPAN kode ini untuk melihat status booking Anda!
                    </p>
                </div>

                <div class="info-box">
                    <h3>📋 Detail Booking Anda</h3>
                    <table class="info-table">
                        <tr>
                            <td><strong>Nama</strong></td>
                            <td>: <?php echo htmlspecialchars($booking['nama']); ?></td>
                        </tr>
                        <tr>
                            <td><strong>No. HP</strong></td>
                            <td>: <?php echo htmlspecialchars($booking['hp']); ?></td>
                        </tr>
                        <tr>
                            <td><strong>Mobil</strong></td>
                            <td>: <?php echo htmlspecialchars($booking['merek'] . ' ' . $booking['model'] . ' (' . $booking['tahun'] . ')'); ?></td>
                        </tr>
                        <tr>
                            <td><strong>Jenis Servis</strong></td>
                            <td>: <?php echo htmlspecialchars($booking['jenis_servis']); ?></td>
                        </tr>
                        <tr>
                            <td><strong>Harga</strong></td>
                            <td>: Rp <?php echo number_format($booking['harga'], 0, ',', '.'); ?></td>
                        </tr>
                        <tr>
                            <td><strong>Status</strong></td>
                            <td>: <span class="badge status-pending"><?php echo $booking['status']; ?></span></td>
                        </tr>
                    </table>
                </div>

                <div style="text-align: center; margin-top: 30px;">
                    <a href="cek_booking.html" class="btn btn-primary">🔍 Cek Status Booking</a>
                    <a href="index.html" class="btn btn-secondary">🏠 Kembali ke Beranda</a>
                </div>

                <div style="margin-top: 30px; padding: 15px; background: #fff3cd; border-radius: 5px; border-left: 4px solid #f39c12;">
                    <p style="margin: 0; color: #856404;">
                        <strong>💡 Tips:</strong> Screenshot halaman ini atau catat kode akses Anda untuk tracking status servis.
                    </p>
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