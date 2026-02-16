<?php

include 'koneksi.php';

// Cek parameter
if (!isset($_GET['id']) || !isset($_GET['kode'])) {
    header("Location: data_booking.php");
    exit();
}

$id = mysqli_real_escape_string($conn, $_GET['id']);
$kode = mysqli_real_escape_string($conn, $_GET['kode']);

// Ambil data dan verifikasi kode akses
$query = "SELECT * FROM booking WHERE id = '$id' AND kode_akses = '$kode'";
$result = mysqli_query($conn, $query);

// Jika data tidak ditemukan atau kode tidak cocok
if (mysqli_num_rows($result) != 1) {
    header("Location: data_booking.php");
    exit();
}

$data = mysqli_fetch_assoc($result);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Booking - Speed Garage</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar">
        <div class="container">
            <div class="logo">
                <img src="img/logo.png" alt="Logo Bengkel" onerror="this.style.display='none'">
                <h2>SPEED GARAGE</h2>
            </div>
            <ul class="nav-menu">
                <li><a href="index.html">Beranda</a></li>
                <li><a href="form_booking.html">Booking Servis</a></li>
                <li><a href="data_booking.php">Data Booking</a></li>
            </ul>
        </div>
    </nav>

    <!-- Form Edit Section -->
    <section class="form-section">
        <div class="container">
            <div class="form-container fade-in">
                <h2>✏️ Edit Booking Anda</h2>
                <p class="form-subtitle">Perbarui data booking servis mobil</p>
                
                <!-- Form Edit -->
                <form action="proses_edit_user.php" method="POST" id="bookingForm" onsubmit="return validateForm()">
                    
                    <!-- Hidden Input -->
                    <input type="hidden" name="id" value="<?php echo $data['id']; ?>">
                    <input type="hidden" name="kode_akses" value="<?php echo $data['kode_akses']; ?>">

                    <!-- Nama Pemilik -->
                    <div class="form-group">
                        <label for="nama">Nama Pemilik <span class="required">*</span></label>
                        <input type="text" id="nama" name="nama" value="<?php echo htmlspecialchars($data['nama']); ?>" required>
                    </div>

                    <!-- Nomor HP -->
                    <div class="form-group">
                        <label for="hp">Nomor HP <span class="required">*</span></label>
                        <input type="text" id="hp" name="hp" value="<?php echo htmlspecialchars($data['hp']); ?>" required>
                    </div>

                    <!-- Merek Mobil -->
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
                    </div>

                    <!-- Model -->
                    <div class="form-group">
                        <label for="model">Model / Tipe Mobil <span class="required">*</span></label>
                        <input type="text" id="model" name="model" value="<?php echo htmlspecialchars($data['model']); ?>" required>
                    </div>

                    <!-- Tahun -->
                    <div class="form-group">
                        <label for="tahun">Tahun Mobil <span class="required">*</span></label>
                        <input type="number" id="tahun" name="tahun" value="<?php echo $data['tahun']; ?>" min="1990" max="2026" required>
                    </div>

                    <!-- Jenis Servis -->
                    <div class="form-group">
                        <label for="jenis_servis">Jenis Servis <span class="required">*</span></label>
                        <select id="jenis_servis" name="jenis_servis" onchange="updateHarga()" required>
                            <option value="">-- Pilih Jenis Servis --</option>
                            <option value="Ganti Oli" <?php echo ($data['jenis_servis'] == 'Ganti Oli') ? 'selected' : ''; ?>>Ganti Oli - Rp 150.000</option>
                            <option value="Tune Up" <?php echo ($data['jenis_servis'] == 'Tune Up') ? 'selected' : ''; ?>>Tune Up - Rp 300.000</option>
                            <option value="Servis Ringan" <?php echo ($data['jenis_servis'] == 'Servis Ringan') ? 'selected' : ''; ?>>Servis Ringan - Rp 500.000</option>
                            <option value="Servis Besar" <?php echo ($data['jenis_servis'] == 'Servis Besar') ? 'selected' : ''; ?>>Servis Besar - Rp 1.000.000</option>
                        </select>
                    </div>

                    <!-- Harga -->
                    <div class="form-group">
                        <label for="harga">Harga Servis</label>
                        <input type="text" id="harga_display" value="Rp <?php echo number_format($data['harga'], 0, ',', '.'); ?>" readonly>
                        <input type="hidden" id="harga" name="harga" value="<?php echo $data['harga']; ?>">
                    </div>

                    <!-- Info Status -->
                    <div class="form-group">
                        <div style="background: #fff3cd; padding: 15px; border-radius: 5px; border-left: 4px solid #ffc107;">
                            <strong>ℹ️ Status Booking:</strong> <?php echo $data['status']; ?><br>
                            <small>Status hanya bisa diubah oleh admin</small>
                        </div>
                    </div>

                    <!-- Tombol -->
                    <div class="form-buttons">
                        <button type="submit" class="btn btn-primary">💾 Simpan Perubahan</button>
                        <a href="data_booking.php" class="btn btn-secondary">❌ Batal</a>
                    </div>
                </form>
            </div>
        </div>
    </section>

    <!-- Footer -->
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
    </script>
</body>
</html>

<?php mysqli_close($conn); ?>