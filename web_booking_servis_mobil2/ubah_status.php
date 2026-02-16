<?php

include 'cek_session.php';
include 'koneksi.php';

if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: admin_dashboard.php");
    exit();
}

$id = mysqli_real_escape_string($conn, $_GET['id']);

$query = "SELECT * FROM booking WHERE id = '$id'";
$result = mysqli_query($conn, $query);

if (mysqli_num_rows($result) != 1) {
    header("Location: admin_dashboard.php");
    exit();
}

$data = mysqli_fetch_assoc($result);


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $status = mysqli_real_escape_string($conn, $_POST['status']);
    
    $estimasi_selesai = 'NULL';
    
    if ($status == 'Sedang Diservis') {
        $jam_estimasi = 1;
        
        switch($data['jenis_servis']) {
            case 'Ganti Oli':
                $jam_estimasi = 1;
                break;
            case 'Tune Up':
                $jam_estimasi = 2;
                break;
            case 'Servis Ringan':
                $jam_estimasi = 3;
                break;
            case 'Servis Besar':
                $jam_estimasi = 6;
                break;
            default:
                $jam_estimasi = 1;
        }

        $estimasi_selesai = "'" . date('Y-m-d H:i:s', strtotime("+{$jam_estimasi} hours")) . "'";
    }
    
    $update_query = "UPDATE booking SET 
                     status = '$status',
                     estimasi_selesai = $estimasi_selesai
                     WHERE id = '$id'";
    
    if (mysqli_query($conn, $update_query)) {
        header("Location: admin_dashboard.php?sukses=status");
        exit();
    } else {
        $error = "Gagal mengubah status!";
    }
}

function getEstimasiWaktu($jenis_servis) {
    switch($jenis_servis) {
        case 'Ganti Oli':
            return '1 jam';
        case 'Tune Up':
            return '2 jam';
        case 'Servis Ringan':
            return '3 jam';
        case 'Servis Besar':
            return '6 jam';
        default:
            return '1 jam';
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ubah Status - Speed Garage</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <nav class="navbar">
        <div class="container">
            <div class="logo">
                <img src="img/logo.png" alt="Logo Bengkel" onerror="this.style.display='none'">
                <h2>SPEED GARAGE - ADMIN</h2>
            </div>
            <ul class="nav-menu">
                <li><a href="admin_dashboard.php">Dashboard</a></li>
                <li><span style="color: #fff;">👤 <?php echo $_SESSION['admin_nama']; ?></span></li>
                <li><a href="logout.php" class="btn-logout">🚪 Logout</a></li>
            </ul>
        </div>
    </nav>

    <section class="form-section">
        <div class="container">
            <div class="form-container fade-in">
                <h2>🔄 Ubah Status Booking</h2>
                <p class="form-subtitle">Kelola status servis mobil</p>

                <?php if (isset($error)): ?>
                    <div class="alert alert-error"><?php echo $error; ?></div>
                <?php endif; ?>

                <div class="info-box">
                    <h3>📋 Informasi Booking</h3>
                    <table class="info-table">
                        <tr>
                            <td><strong>Kode Akses</strong></td>
                            <td>: <code style="background: #ecf0f1; padding: 5px 10px; border-radius: 3px; font-weight: bold;"><?php echo htmlspecialchars($data['kode_akses']); ?></code></td>
                        </tr>
                        <tr>
                            <td><strong>Nama Pemilik</strong></td>
                            <td>: <?php echo htmlspecialchars($data['nama']); ?></td>
                        </tr>
                        <tr>
                            <td><strong>No. HP</strong></td>
                            <td>: <?php echo htmlspecialchars($data['hp']); ?></td>
                        </tr>
                        <tr>
                            <td><strong>Mobil</strong></td>
                            <td>: <?php echo htmlspecialchars($data['merek'] . ' ' . $data['model'] . ' (' . $data['tahun'] . ')'); ?></td>
                        </tr>
                        <tr>
                            <td><strong>Jenis Servis</strong></td>
                            <td>: <?php echo htmlspecialchars($data['jenis_servis']); ?></td>
                        </tr>
                        <tr>
                            <td><strong>Harga</strong></td>
                            <td>: Rp <?php echo number_format($data['harga'], 0, ',', '.'); ?></td>
                        </tr>
                        <tr>
                            <td><strong>Estimasi Waktu Servis</strong></td>
                            <td>: <strong style="color: #e74c3c;">⏱️ <?php echo getEstimasiWaktu($data['jenis_servis']); ?></strong></td>
                        </tr>
                        <tr>
                            <td><strong>Status Saat Ini</strong></td>
                            <td>: <span class="badge status-<?php echo strtolower(str_replace(' ', '-', $data['status'])); ?>"><?php echo $data['status']; ?></span></td>
                        </tr>
                    </table>
                </div>

                <form action="" method="POST" style="margin-top: 30px;">
                    
                    <div class="form-group">
                        <label for="status">Pilih Status Baru <span class="required">*</span></label>
                        <select id="status" name="status" required onchange="showEstimasi(this.value)">
                            <option value="">-- Pilih Status --</option>
                            <option value="Pending" <?php echo ($data['status'] == 'Pending') ? 'selected' : ''; ?>>⏳ Pending</option>
                            <option value="Sedang Diservis" <?php echo ($data['status'] == 'Sedang Diservis') ? 'selected' : ''; ?>>🔧 Sedang Diservis</option>
                            <option value="Selesai" <?php echo ($data['status'] == 'Selesai') ? 'selected' : ''; ?>>✅ Selesai</option>
                        </select>
                    </div>

                    <div class="info-estimasi" id="infoEstimasi" style="display: none;">
                        <div class="alert alert-info">
                            ⏰ <strong>Catatan Estimasi:</strong><br>
                            Jika status diubah menjadi "Sedang Diservis", estimasi selesai akan otomatis diset:<br>
                            <ul style="margin: 10px 0; padding-left: 20px;">
                                <li>🛢️ <strong>Ganti Oli:</strong> 1 jam dari sekarang</li>
                                <li>⚙️ <strong>Tune Up:</strong> 2 jam dari sekarang</li>
                                <li>🔧 <strong>Servis Ringan:</strong> 3 jam dari sekarang</li>
                                <li>🛠️ <strong>Servis Besar:</strong> 6 jam dari sekarang</li>
                            </ul>
                            <strong>Jenis servis ini: <?php echo $data['jenis_servis']; ?> (<?php echo getEstimasiWaktu($data['jenis_servis']); ?>)</strong>
                        </div>
                    </div>

                    <div class="form-buttons">
                        <button type="submit" class="btn btn-primary">💾 Simpan Status</button>
                        <a href="admin_dashboard.php" class="btn btn-secondary">❌ Batal</a>
                    </div>
                </form>
            </div>
        </div>
    </section>

    <footer class="footer">
        <div class="container">
            <p>&copy; 2026 Speed Garage. All Rights Reserved.</p>
        </div>
    </footer>

    <script>
        function showEstimasi(status) {
            const infoEstimasi = document.getElementById('infoEstimasi');
            if (status == 'Sedang Diservis') {
                infoEstimasi.style.display = 'block';
            } else {
                infoEstimasi.style.display = 'none';
            }
        }
        
        window.addEventListener('load', function() {
            const status = document.getElementById('status').value;
            showEstimasi(status);
        });
    </script>
</body>
</html>

<?php
mysqli_close($conn);
?>