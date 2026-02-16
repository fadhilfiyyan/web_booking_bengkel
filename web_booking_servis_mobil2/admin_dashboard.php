<?php

include 'cek_session.php';
include 'koneksi.php';

// Query untuk mengambil semua data booking dengan status
$query = "SELECT * FROM booking ORDER BY 
          CASE 
              WHEN status = 'Sedang Diservis' THEN 1
              WHEN status = 'Pending' THEN 2
              WHEN status = 'Selesai' THEN 3
          END, id DESC";
$result = mysqli_query($conn, $query);

// Hitung statistik
$query_stats = "SELECT 
                COUNT(*) as total,
                SUM(CASE WHEN status = 'Pending' THEN 1 ELSE 0 END) as pending,
                SUM(CASE WHEN status = 'Sedang Diservis' THEN 1 ELSE 0 END) as proses,
                SUM(CASE WHEN status = 'Selesai' THEN 1 ELSE 0 END) as selesai,
                SUM(harga) as total_pendapatan
                FROM booking";
$stats = mysqli_fetch_assoc(mysqli_query($conn, $query_stats));
?>

<?php if (isset($_GET['sukses'])): ?>
    <div class="alert alert-success fade-in">
        <?php 
        if ($_GET['sukses'] == 'hapus') {
            $nama = isset($_GET['nama']) ? htmlspecialchars($_GET['nama']) : 'Data';
            echo "✅ Booking atas nama <strong>$nama</strong> berhasil dihapus!";
        } elseif ($_GET['sukses'] == 'status') {
            echo "✅ Status booking berhasil diupdate!";
        } elseif ($_GET['sukses'] == 'edit') {
            echo "✅ Data booking berhasil diupdate!";
        }
        ?>
    </div>
<?php endif; ?>

<?php if (isset($_GET['error'])): ?>
    <div class="alert alert-error fade-in">
        <?php 
        if ($_GET['error'] == 'hapus') {
            $msg = isset($_GET['msg']) ? htmlspecialchars($_GET['msg']) : 'Unknown error';
            echo "❌ Gagal menghapus data! Error: $msg";
        } elseif ($_GET['error'] == 'not_found') {
            echo "❌ Data booking tidak ditemukan!";
        } elseif ($_GET['error'] == 'no_id') {
            echo "❌ ID booking tidak valid!";
        }
        ?>
    </div>
<?php endif; ?>


<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Speed Garage</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <!-- Navbar Admin -->
    <nav class="navbar">
        <div class="container">
            <div class="logo">
                <img src="img/Logo.png" alt="Logo Bengkel" onerror="this.style.display='none'">
                <h2>SPEED GARAGE - ADMIN</h2>
            </div>
            <ul class="nav-menu">
                <li><a href="admin_dashboard.php" class="active">Dashboard</a></li>
                <li><span style="color: #fff;">👤 <?php echo $_SESSION['admin_nama']; ?></span></li>
                <li><a href="logout.php" class="btn-logout">🚪 Logout</a></li>
            </ul>
        </div>
    </nav>

    <!-- Dashboard Section -->
    <section class="dashboard-section">
        <div class="container">
            <div class="dashboard-header fade-in">
                <h2>Dashboard Admin</h2>
                <p>Kelola semua booking servis mobil</p>
            </div>

            <!-- Statistik Cards -->
            <div class="stats-grid fade-in-delay">
                <div class="stat-card stat-total">
                    <div class="stat-icon">📊</div>
                    <div class="stat-info">
                        <h3><?php echo $stats['total']; ?></h3>
                        <p>Total Booking</p>
                    </div>
                </div>
                <div class="stat-card stat-pending">
                    <div class="stat-icon">⏳</div>
                    <div class="stat-info">
                        <h3><?php echo $stats['pending']; ?></h3>
                        <p>Pending</p>
                    </div>
                </div>
                <div class="stat-card stat-proses">
                    <div class="stat-icon">🔧</div>
                    <div class="stat-info">
                        <h3><?php echo $stats['proses']; ?></h3>
                        <p>Sedang Diservis</p>
                    </div>
                </div>
                <div class="stat-card stat-selesai">
                    <div class="stat-icon">✅</div>
                    <div class="stat-info">
                        <h3><?php echo $stats['selesai']; ?></h3>
                        <p>Selesai</p>
                    </div>
                </div>
                <div class="stat-card stat-pendapatan">
                    <div class="stat-icon">💰</div>
                    <div class="stat-info">
                        <h3>Rp <?php echo number_format($stats['total_pendapatan'], 0, ',', '.'); ?></h3>
                        <p>Total Pendapatan</p>
                    </div>
                </div>
            </div>

            <!-- Tabel Data Booking -->
            <div class="table-container fade-in-delay-2">
                <div class="table-header">
                    <h2>Data Booking Servis</h2>
                </div>

                <?php if (mysqli_num_rows($result) > 0): ?>
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
                                    <th>Estimasi Selesai</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                $no = 1;
                                while ($row = mysqli_fetch_assoc($result)): 
                                    // Tentukan class status
                                    $status_class = '';
                                    if ($row['status'] == 'Pending') $status_class = 'status-pending';
                                    elseif ($row['status'] == 'Sedang Diservis') $status_class = 'status-proses';
                                    elseif ($row['status'] == 'Selesai') $status_class = 'status-selesai';
                                    
                                    // Format estimasi selesai
                                    $estimasi = '-';
                                    if ($row['estimasi_selesai']) {
                                        $estimasi_time = strtotime($row['estimasi_selesai']);
                                        $estimasi = date('d/m/Y H:i', $estimasi_time);
                                        
                                        // Hitung sisa waktu
                                        $now = time();
                                        $diff = $estimasi_time - $now;
                                        if ($diff > 0 && $row['status'] == 'Sedang Diservis') {
                                            $minutes = floor($diff / 60);
                                            $estimasi .= '<br><small class="countdown">(Sisa: ' . $minutes . ' menit)</small>';
                                        }
                                    }
                                ?>
                                <tr>
                                    <td><?php echo $no++; ?></td>
                                    <td>
                                        <code style="background: #ecf0f1; padding: 5px 10px; border-radius: 3px; font-weight: bold; color: #e74c3c;">
                                            <?php echo $row['kode_akses']; ?>
                                        </code>
                                    </td>
                                    <td><?php echo htmlspecialchars($row['nama']); ?></td>
                                    <td><?php echo htmlspecialchars($row['hp']); ?></td>
                                    <td><?php echo htmlspecialchars($row['merek'] . ' ' . $row['model'] . ' (' . $row['tahun'] . ')'); ?></td>
                                    <td><?php echo htmlspecialchars($row['jenis_servis']); ?></td>
                                    <td>Rp <?php echo number_format($row['harga'], 0, ',', '.'); ?></td>
                                    <td>
                                        <span class="badge <?php echo $status_class; ?>">
                                            <?php echo $row['status']; ?>
                                        </span>
                                    </td>
                                    <td><?php echo $estimasi; ?></td>
                                    <td>
                                        <div class="action-buttons">
                                            <a href="form_edit.php?id=<?php echo $row['id']; ?>" class="btn btn-edit btn-sm">✏️ Edit</a>
                                            <a href="ubah_status.php?id=<?php echo $row['id']; ?>" class="btn btn-status btn-sm">🔄 Status</a>
                                            <button onclick="confirmDelete(<?php echo $row['id']; ?>, '<?php echo htmlspecialchars($row['nama']); ?>')" class="btn btn-delete btn-sm">🗑️ Hapus</button>
                                        </div>
                                    </td>
                                </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <div class="no-data">
                        <p>📋 Belum ada data booking.</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <p>&copy; 2026 Speed Garage. All Rights Reserved.</p>
        </div>
    </footer>

    <!-- Link JavaScript -->
    <script src="js/script.js"></script>
    
    <!-- Auto refresh setiap 1 menit untuk update countdown -->
    <script>
        setTimeout(function() {
            location.reload();
        }, 60000); // 60 detik
    </script>
</body>
</html>

<?php
// Tutup koneksi database
mysqli_close($conn);
?>