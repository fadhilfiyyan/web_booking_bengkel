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
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Booking - Speed Garage Admin</title>
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
                <li><a href="index.html">Lihat Website</a></li>
                <li><span style="color: #fff;">👤 <?php echo $_SESSION['admin_nama']; ?></span></li>
                <li><a href="logout.php" class="btn-logout">🚪 Logout</a></li>
            </ul>
        </div>
    </nav>

    <section class="form-section">
        <div class="container">
            <div class="form-container fade-in">
                <h2>✏️ Edit Data Booking (Admin)</h2>
                <p class="form-subtitle">Perbarui data booking dan status servis</p>
                
                
                <div style="background: #e3f2fd; padding: 15px; border-radius: 5px; margin-bottom: 25px; border-left: 4px solid #2196f3;">
                    <strong>🔐 Kode Akses:</strong> 
                    <code style="background: #fff; padding: 5px 10px; border-radius: 3px; font-size: 1.1rem; font-weight: bold; margin-left: 10px;"><?php echo htmlspecialchars($data['kode_akses']); ?></code>
                </div>

                               <form action="proses_edit.php" method="POST" id="bookingForm" onsubmit="return validateForm()">
                    
                  
                    <input type="hidden" name="id" value="<?php echo $data['id']; ?>">

                                        <h3 style="color: #2c3e50; margin-top: 20px; padding-bottom: 10px; border-bottom: 2px solid #e74c3c;">
                        👤 Data Pelanggan
                    </h3>

                    
                    <div class="form-group">
                        <label for="nama">Nama Pemilik <span class="required">*</span></label>
                        <input type="text" id="nama" name="nama" value="<?php echo htmlspecialchars($data['nama']); ?>" placeholder="Masukkan nama lengkap" required>
                        <span class="error-message" id="error-nama"></span>
                    </div>

                    
                    <div class="form-group">
                        <label for="hp">Nomor HP <span class="required">*</span></label>
                        <input type="text" id="hp" name="hp" value="<?php echo htmlspecialchars($data['hp']); ?>" placeholder="Contoh: 081234567890" required>
                        <span class="error-message" id="error-hp"></span>
                    </div>

                   
                    <h3 style="color: #2c3e50; margin-top: 30px; padding-bottom: 10px; border-bottom: 2px solid #e74c3c;">
                        🚗 Data Kendaraan
                    </h3>

                
                    <div class="form-group">
                        <label for="merek">Merek Mobil <span class="required">*</span></label>
                        <select id="merek" name="merek" required>
                            <option value="">-- Pilih Merek --</option>
                            <option value="Toyota" <?php echo ($data['merek'] == 'Toyota') ? 'selected' : ''; ?>>Toyota</option>
                            <option value="Honda" <?php echo ($data['merek'] == 'Honda') ? 'selected' : ''; ?>>Honda</option>
                            <option value="Daihatsu" <?php echo ($data['merek'] == 'Daihatsu') ? 'selected' : ''; ?>>Daihatsu</option>
                            <option value="Mitsubishi" <?php echo ($data['merek'] == 'Mitsubishi') ? 'selected' : ''; ?>>Mitsubishi</option>
                            <option value="Suzuki" <?php echo ($data['merek'] == 'Suzuki') ? 'selected' : ''; ?>>Suzuki</option>
                            <option value="Nissan" <?php echo ($data['merek'] == 'Nissan') ? 'selected' : ''; ?>>Nissan</option>
                            <option value="Mazda" <?php echo ($data['merek'] == 'Mazda') ? 'selected' : ''; ?>>Mazda</option>
                            <option value="BMW" <?php echo ($data['merek'] == 'BMW') ? 'selected' : ''; ?>>BMW</option>
                            <option value="Mercedes-Benz" <?php echo ($data['merek'] == 'Mercedes-Benz') ? 'selected' : ''; ?>>Mercedes-Benz</option>
                            <option value="Lainnya" <?php echo ($data['merek'] == 'Lainnya') ? 'selected' : ''; ?>>Lainnya</option>
                        </select>
                        <span class="error-message" id="error-merek"></span>
                    </div>

                
                    <div class="form-group">
                        <label for="model">Model / Tipe Mobil <span class="required">*</span></label>
                        <input type="text" id="model" name="model" value="<?php echo htmlspecialchars($data['model']); ?>" placeholder="Contoh: Avanza, Jazz, Xenia" required>
                        <span class="error-message" id="error-model"></span>
                    </div>

                
                    <div class="form-group">
                        <label for="tahun">Tahun Mobil <span class="required">*</span></label>
                        <input type="number" id="tahun" name="tahun" value="<?php echo htmlspecialchars($data['tahun']); ?>" placeholder="Contoh: 2020" min="1990" max="2026" required>
                        <span class="error-message" id="error-tahun"></span>
                    </div>

                    <h3 style="color: #2c3e50; margin-top: 30px; padding-bottom: 10px; border-bottom: 2px solid #e74c3c;">
                        🔧 Data Servis
                    </h3>

                    <div class="form-group">
                        <label for="jenis_servis">Jenis Servis <span class="required">*</span></label>
                        <select id="jenis_servis" name="jenis_servis" onchange="updateHarga()" required>
                            <option value="">-- Pilih Jenis Servis --</option>
                            <option value="Ganti Oli" <?php echo ($data['jenis_servis'] == 'Ganti Oli') ? 'selected' : ''; ?>>Ganti Oli - Rp 150.000 (Est. 1 jam)</option>
                            <option value="Tune Up" <?php echo ($data['jenis_servis'] == 'Tune Up') ? 'selected' : ''; ?>>Tune Up - Rp 300.000 (Est. 2 jam)</option>
                            <option value="Servis Ringan" <?php echo ($data['jenis_servis'] == 'Servis Ringan') ? 'selected' : ''; ?>>Servis Ringan - Rp 500.000 (Est. 3 jam)</option>
                            <option value="Servis Besar" <?php echo ($data['jenis_servis'] == 'Servis Besar') ? 'selected' : ''; ?>>Servis Besar - Rp 1.000.000 (Est. 6 jam)</option>
                        </select>
                        <span class="error-message" id="error-jenis"></span>
                    </div>

                    
                    <div class="form-group">
                        <label for="harga">Harga Servis</label>
                        <input type="text" id="harga_display" value="Rp <?php echo number_format($data['harga'], 0, ',', '.'); ?>" placeholder="Harga akan muncul otomatis" readonly>
                        <input type="hidden" id="harga" name="harga" value="<?php echo $data['harga']; ?>">
                    </div>

                    <h3 style="color: #2c3e50; margin-top: 30px; padding-bottom: 10px; border-bottom: 2px solid #e74c3c;">
                        📊 Status & Estimasi
                    </h3>

                    <div class="form-group">
                        <label for="status">Status Servis <span class="required">*</span></label>
                        <select id="status" name="status" required onchange="showStatusInfo(this.value)">
                            <option value="Pending" <?php echo ($data['status'] == 'Pending') ? 'selected' : ''; ?>>⏳ Pending</option>
                            <option value="Sedang Diservis" <?php echo ($data['status'] == 'Sedang Diservis') ? 'selected' : ''; ?>>🔧 Sedang Diservis</option>
                            <option value="Selesai" <?php echo ($data['status'] == 'Selesai') ? 'selected' : ''; ?>>✅ Selesai</option>
                        </select>
                    </div>

                    <div id="statusInfo" style="display: none; background: #fff3cd; padding: 15px; border-radius: 5px; border-left: 4px solid #ffc107; margin-top: 10px;">
                        <strong>ℹ️ Info:</strong> Untuk mengubah estimasi selesai, gunakan menu <strong>"Ubah Status"</strong> di dashboard.
                    </div>

                    <?php if (!empty($data['estimasi_selesai'])): ?>
                    <div class="form-group">
                        <label>Estimasi Selesai</label>
                        <input type="text" value="<?php echo date('d/m/Y H:i', strtotime($data['estimasi_selesai'])); ?>" readonly style="background: #ecf0f1;">
                    </div>
                    <?php endif; ?>

                    <div class="form-buttons" style="margin-top: 40px;">
                        <button type="submit" class="btn btn-primary">💾 Simpan Perubahan</button>
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

    
    <script src="js/script.js"></script>
    

    <script>
        window.addEventListener('load', function() {
            updateHarga();
        });


        function showStatusInfo(status) {
            const statusInfo = document.getElementById('statusInfo');
            if (status === 'Sedang Diservis') {
                statusInfo.style.display = 'block';
            } else {
                statusInfo.style.display = 'none';
            }
        }

        showStatusInfo(document.getElementById('status').value);
    </script>
</body>
</html>

<?php

mysqli_close($conn);
?>