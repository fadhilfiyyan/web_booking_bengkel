<?php

include 'koneksi.php';

session_start();
$verified_kode = isset($_SESSION['verified_kode']) ? $_SESSION['verified_kode'] : '';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['verify_kode'])) {
    $input_kode = mysqli_real_escape_string($conn, trim(strtoupper($_POST['kode_akses'])));
    
    $check_query = "SELECT kode_akses FROM booking WHERE kode_akses = '$input_kode'";
    $check_result = mysqli_query($conn, $check_query);
    
    if (mysqli_num_rows($check_result) == 1) {
        $_SESSION['verified_kode'] = $input_kode;
        $verified_kode = $input_kode;
        $success_msg = "✅ Kode akses valid! Menampilkan booking Anda...";
    } else {
        $error_msg = "❌ Kode akses tidak ditemukan! Periksa kembali kode Anda.";
    }
}


if (isset($_GET['logout_kode'])) {
    unset($_SESSION['verified_kode']);
    $verified_kode = '';
    header("Location: data_booking.php");
    exit();
}


$result = null;
if (!empty($verified_kode)) {
    $query = "SELECT * FROM booking WHERE kode_akses = '$verified_kode' ORDER BY id DESC";
    $result = mysqli_query($conn, $query);
    
    if (!$result) {
        die("Query gagal: " . mysqli_error($conn));
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Booking - Speed Garage</title>
    <link rel="stylesheet" href="css/style.css">
    <style>
        .verify-box {
            background: linear-gradient(135deg, #3498db, #2980b9);
            color: white;
            padding: 40px;
            border-radius: 15px;
            text-align: center;
            box-shadow: 0 10px 30px rgba(52, 152, 219, 0.3);
        }
        .verify-box h2 {
            font-size: 2rem;
            margin-bottom: 10px;
        }
        .verify-box p {
            font-size: 1.1rem;
            margin-bottom: 30px;
            opacity: 0.9;
        }
        .verify-form {
            display: flex;
            gap: 15px;
            justify-content: center;
            align-items: center;
            flex-wrap: wrap;
        }
        .verify-input {
            padding: 15px 25px;
            border: none;
            border-radius: 8px;
            font-size: 1.2rem;
            width: 250px;
            text-transform: uppercase;
            letter-spacing: 4px;
            font-weight: bold;
            text-align: center;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }
        .verify-input:focus {
            outline: 3px solid #27ae60;
        }
        .verify-btn {
            padding: 15px 35px;
            background: #27ae60;
            color: white;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-weight: bold;
            font-size: 1.1rem;
            transition: all 0.3s ease;
            box-shadow: 0 5px 15px rgba(39, 174, 96, 0.3);
        }
        .verify-btn:hover {
            background: #229954;
            transform: translateY(-2px);
            box-shadow: 0 7px 20px rgba(39, 174, 96, 0.4);
        }
        .verify-info {
            margin-top: 25px;
            font-size: 0.95rem;
            opacity: 0.85;
        }
        .verify-info strong {
            color: #ffe066;
        }
        .verified-info {
            background: linear-gradient(135deg, #27ae60, #229954);
            color: white;
            padding: 20px;
            border-radius: 10px;
            margin-bottom: 25px;
            box-shadow: 0 5px 15px rgba(39, 174, 96, 0.3);
        }
        .verified-info code {
            background: rgba(255, 255, 255, 0.2);
            padding: 5px 15px;
            border-radius: 5px;
            font-size: 1.2rem;
            letter-spacing: 3px;
        }
        .logout-kode-btn {
            padding: 10px 20px;
            background: rgba(255, 255, 255, 0.2);
            color: white;
            border: 2px solid white;
            border-radius: 5px;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
            transition: all 0.3s ease;
            font-weight: bold;
        }
        .logout-kode-btn:hover {
            background: white;
            color: #27ae60;
        }
        .alert-success {
            background: #d4edda;
            color: #155724;
            padding: 15px 20px;
            border-radius: 8px;
            margin-bottom: 20px;
            border-left: 5px solid #28a745;
            animation: slideDown 0.5s ease;
        }
        .alert-error {
            background: #f8d7da;
            color: #721c24;
            padding: 15px 20px;
            border-radius: 8px;
            margin-bottom: 20px;
            border-left: 5px solid #dc3545;
            animation: shake 0.5s ease;
        }
        @keyframes slideDown {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            25% { transform: translateX(-10px); }
            75% { transform: translateX(10px); }
        }
        .my-booking {
            background: linear-gradient(to right, #fff3cd 0%, #ffffff 100%);
            border-left: 4px solid #ffc107;
        }
        .empty-state {
            text-align: center;
            padding: 60px 20px;
        }
        .empty-state-icon {
            font-size: 80px;
            margin-bottom: 20px;
            opacity: 0.5;
        }
        .empty-state h3 {
            color: #7f8c8d;
            margin-bottom: 10px;
        }
        .empty-state p {
            color: #95a5a6;
            margin-bottom: 25px;
        }
    </style>
</head>
<body>
    <nav class="navbar">
        <div class="container">
            <div class="logo">
                <img src="img/logo.png" alt="Logo Bengkel" onerror="this.style.display='none'">
                <h2>SPEED GARAGE</h2>
            </div>
            <ul class="nav-menu">
                <li><a href="index.html">Beranda</a></li>
                <li><a href="form_booking.html">Booking Servis</a></li>
                <li><a href="data_booking.php" class="active">Data Booking</a></li>
            </ul>
        </div>
    </nav>

    <section class="table-section">
        <div class="container">
            <div class="table-container fade-in">
                <div class="table-header">
                    <h2>🔐 Kelola Booking Anda</h2>
                </div>

                <?php if (isset($success_msg)): ?>
                    <div class="alert-success">
                        <strong><?php echo $success_msg; ?></strong>
                    </div>
                <?php endif; ?>

                <?php if (isset($error_msg)): ?>
                    <div class="alert-error">
                        <strong><?php echo $error_msg; ?></strong>
                    </div>
                <?php endif; ?>

                <?php if (empty($verified_kode)): ?>
                    <div class="verify-box">
                        <h2>🔐 Masukkan Kode Akses</h2>
                        <p>Masukkan kode akses booking Anda untuk melihat, mengedit, atau menghapus data</p>
                        
                        <form method="POST" class="verify-form">
                            <input 
                                type="text" 
                                name="kode_akses" 
                                class="verify-input" 
                                placeholder="KODE AKSES" 
                                maxlength="6" 
                                required
                                autocomplete="off"
                                autofocus>
                            <button type="submit" name="verify_kode" class="verify-btn">
                                🔍 Verifikasi Kode
                            </button>
                        </form>
                        
                        <div class="verify-info">
                            <p>
                                💡 <strong>Kode akses</strong> diberikan saat Anda melakukan booking.<br>
                                Contoh format: <code>A1B2C3</code> atau <code>XY9Z8W</code>
                            </p>
                            <p style="margin-top: 15px;">
                                📝 Belum punya kode? 
                                <a href="form_booking.html" style="color: #ffe066; text-decoration: underline; font-weight: bold;">
                                    Buat Booking Baru
                                </a>
                            </p>
                        </div>
                    </div>

                <?php else: ?>
                    <div class="verified-info">
                        <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap;">
                            <div>
                                <strong>✅ Anda sudah masuk dengan kode:</strong>
                                <code><?php echo htmlspecialchars($verified_kode); ?></code>
                            </div>
                            <a href="?logout_kode=1" class="logout-kode-btn">🚪 Keluar</a>
                        </div>
                    </div>

                    <?php if ($result && mysqli_num_rows($result) > 0): ?>
                        <div class="table-responsive">
                            <table>
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Kode Akses</th>
                                        <th>Nama Pemilik</th>
                                        <th>No. HP</th>
                                        <th>Mobil</th>
                                        <th>Jenis Servis</th>
                                        <th>Harga</th>
                                        <th>Status</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php 
                                    $no = 1;
                                    while ($row = mysqli_fetch_assoc($result)): 
                                        $status_class = '';
                                        if ($row['status'] == 'Pending') $status_class = 'status-pending';
                                        elseif ($row['status'] == 'Sedang Diservis') $status_class = 'status-proses';
                                        elseif ($row['status'] == 'Selesai') $status_class = 'status-selesai';
                                    ?>
                                    <tr class="my-booking">
                                        <td><?php echo $no++; ?></td>
                                        <td>
                                            <code style="background: #3498db; color: white; padding: 8px 12px; border-radius: 5px; font-weight: bold; font-size: 1rem;">
                                                <?php echo $row['kode_akses']; ?>
                                            </code>
                                        </td>
                                        <td><strong><?php echo htmlspecialchars($row['nama']); ?></strong></td>
                                        <td><?php echo htmlspecialchars($row['hp']); ?></td>
                                        <td><?php echo htmlspecialchars($row['merek'] . ' ' . $row['model'] . ' (' . $row['tahun'] . ')'); ?></td>
                                        <td><?php echo htmlspecialchars($row['jenis_servis']); ?></td>
                                        <td><strong>Rp <?php echo number_format($row['harga'], 0, ',', '.'); ?></strong></td>
                                        <td>
                                            <span class="badge <?php echo $status_class; ?>">
                                                <?php echo $row['status']; ?>
                                            </span>
                                        </td>
                                        <td>
                                            <div class="action-buttons">
                                                <a href="form_edit_user.php?id=<?php echo $row['id']; ?>&kode=<?php echo $verified_kode; ?>" class="btn btn-edit btn-sm">✏️ Edit</a>
                                                <button onclick="confirmDelete(<?php echo $row['id']; ?>, '<?php echo htmlspecialchars($row['nama']); ?>', '<?php echo $verified_kode; ?>')" class="btn btn-delete btn-sm">🗑️ Hapus</button>
                                            </div>
                                        </td>
                                    </tr>
                                    <?php endwhile; ?>
                                </tbody>
                            </table>
                        </div>
                        
                        <div style="margin-top: 25px; text-align: center;">
                            <a href="form_booking.html" class="btn btn-primary">➕ Tambah Booking Baru</a>
                        </div>

                    <?php else: ?>
                        <div class="empty-state">
                            <div class="empty-state-icon">📋</div>
                            <h3>Belum Ada Booking</h3>
                            <p>Kode akses <strong><?php echo htmlspecialchars($verified_kode); ?></strong> belum memiliki data booking.</p>
                            <a href="form_booking.html" class="btn btn-primary">➕ Buat Booking Baru</a>
                        </div>
                    <?php endif; ?>

                <?php endif; ?>
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
        function confirmDelete(id, nama, kode) {
            const confirmMessage = `Apakah Anda yakin ingin menghapus booking atas nama:\n\n"${nama}"\n\nData yang dihapus tidak dapat dikembalikan!`;
            
            if (confirm(confirmMessage)) {
                window.location.href = `proses_hapus_user.php?id=${id}&kode=${kode}`;
            }
        }
        
        document.addEventListener('DOMContentLoaded', function() {
            const kodeInput = document.querySelector('input[name="kode_akses"]');
            if (kodeInput) {
                kodeInput.addEventListener('input', function() {
                    this.value = this.value.toUpperCase();
                });
            }
        });
    </script>
</body>
</html>

<?php
if ($result) {
    mysqli_close($conn);
}
?>