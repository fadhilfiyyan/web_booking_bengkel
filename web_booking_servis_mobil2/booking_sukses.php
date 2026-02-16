<?php
include 'koneksi.php';

$kode = isset($_GET['kode']) ? mysqli_real_escape_string($conn, $_GET['kode']) : '';

if (empty($kode)) {
    header("Location: form_booking.html");
    exit();
}

$query = "SELECT * FROM booking WHERE kode_akses = '$kode'";
$result = mysqli_query($conn, $query);
$data = mysqli_fetch_assoc($result);

if (!$data) {
    header("Location: form_booking.html");
    exit();
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking Berhasil - Speed Garage</title>
    <link rel="stylesheet" href="css/style.css">
    <style>
        .sukses-container {
            background: white;
            padding: 50px;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.2);
            max-width: 600px;
            margin: 80px auto;
            text-align: center;
        }
        .icon-sukses {
            font-size: 100px;
            color: #27ae60;
            margin-bottom: 20px;
            animation: scaleIn 0.5s ease-out;
        }
        @keyframes scaleIn {
            from { transform: scale(0); }
            to { transform: scale(1); }
        }
        .judul-sukses {
            color: #2c3e50;
            font-size: 2rem;
            margin-bottom: 10px;
        }
        .kode-box {
            background: linear-gradient(135deg, #e74c3c, #c0392b);
            color: white;
            padding: 30px;
            border-radius: 12px;
            margin: 30px 0;
            box-shadow: 0 8px 20px rgba(231, 76, 60, 0.4);
        }
        .label-kode {
            font-size: 1rem;
            opacity: 0.9;
            margin-bottom: 10px;
        }
        .kode-akses {
            font-size: 56px;
            font-weight: bold;
            letter-spacing: 12px;
            font-family: 'Courier New', monospace;
            margin: 15px 0;
            text-shadow: 3px 3px 6px rgba(0,0,0,0.3);
        }
        .peringatan {
            background: #fff3cd;
            color: #856404;
            padding: 15px;
            border-radius: 8px;
            border-left: 4px solid #f39c12;
            margin: 20px 0;
            text-align: left;
        }
        .info-booking {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 8px;
            text-align: left;
            margin-top: 20px;
        }
        .info-row {
            display: flex;
            justify-content: space-between;
            padding: 10px 0;
            border-bottom: 1px solid #dee2e6;
        }
        .info-row:last-child {
            border-bottom: none;
        }
        .info-label {
            font-weight: 600;
            color: #7f8c8d;
        }
        .info-value {
            color: #2c3e50;
        }
        .btn-group {
            margin-top: 30px;
            display: flex;
            gap: 15px;
            justify-content: center;
        }
        .btn-copy {
            background: #3498db;
            color: white;
            padding: 12px 30px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-size: 1rem;
            transition: all 0.3s;
        }
        .btn-copy:hover {
            background: #2980b9;
            transform: translateY(-2px);
        }
        .btn-home {
            background: #34495e;
            color: white;
            padding: 12px 30px;
            border-radius: 6px;
            text-decoration: none;
            display: inline-block;
            transition: all 0.3s;
        }
        .btn-home:hover {
            background: #2c3e50;
            transform: translateY(-2px);
        }
    </style>
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
            </ul>
        </div>
    </nav>

    <section class="form-section">
        <div class="container">
            <div class="sukses-container fade-in">
                <div class="icon-sukses">✅</div>
                <h2 class="judul-sukses">Booking Berhasil!</h2>
                <p>Terima kasih telah mempercayakan kendaraan Anda kepada kami</p>

                <div class="kode-box">
                    <div class="label-kode">KODE AKSES BOOKING ANDA:</div>
                    <div class="kode-akses" id="kodeAkses"><?php echo $data['kode_akses']; ?></div>
                    <small>Simpan kode ini untuk cek status booking</small>
                </div>

                <div class="peringatan">
                    <strong>⚠️ PENTING!</strong>
                    <ul style="margin: 10px 0 0 20px; text-align: left;">
                        <li>Simpan kode akses ini dengan baik</li>
                        <li>Gunakan kode untuk cek status servis Anda</li>
                        <li>Jangan berikan kode ini kepada orang lain</li>
                    </ul>
                </div>

                <div class="info-booking">
                    <h3 style="margin-bottom: 15px; color: #2c3e50;">📋 Detail Booking:</h3>
                    <div class="info-row">
                        <span class="info-label">Nama Pemilik:</span>
                        <span class="info-value"><?php echo htmlspecialchars($data['nama']); ?></span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">No. HP:</span>
                        <span class="info-value"><?php echo htmlspecialchars($data['hp']); ?></span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Mobil:</span>
                        <span class="info-value"><?php echo htmlspecialchars($data['merek'] . ' ' . $data['model'] . ' (' . $data['tahun'] . ')'); ?></span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Jenis Servis:</span>
                        <span class="info-value"><?php echo htmlspecialchars($data['jenis_servis']); ?></span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Harga:</span>
                        <span class="info-value" style="font-weight: bold; color: #e74c3c;">Rp <?php echo number_format($data['harga'], 0, ',', '.'); ?></span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Status:</span>
                        <span class="info-value"><span class="badge status-pending"><?php echo $data['status']; ?></span></span>
                    </div>
                </div>

                <div class="btn-group">
                    <button class="btn-copy" onclick="copyKode()">📋 Copy Kode</button>
                    <a href="cek_booking.php" class="btn-home">🔍 Cek Status Booking</a>
                    <a href="index.html" class="btn-home">🏠 Kembali ke Beranda</a>
                </div>
            </div>
        </div>
    </section>

    <footer class="footer">
        <div class="container">
            <p>&copy; 2026 Speed Garage. All Rights Reserved.</p>
        </div>
    </footer>

    <script>
        function copyKode() {
            const kode = document.getElementById('kodeAkses').innerText;
            navigator.clipboard.writeText(kode).then(function() {
                alert('✅ Kode akses berhasil di-copy: ' + kode);
            }, function(err) {
                alert('❌ Gagal copy kode');
            });
        }
    </script>
</body>
</html>