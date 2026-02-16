<?php

echo "<h2>🔍 Debug Login Admin</h2><hr>";


include 'koneksi.php';
if ($conn) {
    echo "✅ <strong>Koneksi database berhasil</strong><br><br>";
} else {
    echo "❌ <strong>Koneksi database gagal!</strong><br><br>";
    die();
}

session_start();
echo "✅ <strong>Session berjalan</strong><br><br>";

$query = "SELECT * FROM admin";
$result = mysqli_query($conn, $query);

if ($result) {
    echo "✅ <strong>Tabel admin ditemukan</strong><br>";
    echo "📊 Jumlah admin: " . mysqli_num_rows($result) . "<br><br>";
    
    echo "<table border='1' cellpadding='10'>";
    echo "<tr><th>ID</th><th>Username</th><th>Nama</th><th>Password Hash (50 karakter pertama)</th></tr>";
    
    while ($row = mysqli_fetch_assoc($result)) {
        echo "<tr>";
        echo "<td>" . $row['id'] . "</td>";
        echo "<td>" . $row['username'] . "</td>";
        echo "<td>" . $row['nama_lengkap'] . "</td>";
        echo "<td>" . substr($row['password'], 0, 50) . "...</td>";
        echo "</tr>";
    }
    echo "</table><br>";
} else {
    echo "❌ <strong>Tabel admin tidak ditemukan!</strong><br>";
    echo "Error: " . mysqli_error($conn) . "<br><br>";
}

// 4. Test Password Verification
echo "<hr><h3>🔐 Test Password Verification</h3>";
$test_password = "admin123";
echo "Password yang ditest: <strong>$test_password</strong><br><br>";

$query_admin = "SELECT * FROM admin WHERE username = 'admin'";
$result_admin = mysqli_query($conn, $query_admin);

if ($admin = mysqli_fetch_assoc($result_admin)) {
    $stored_hash = $admin['password'];
    
    if (password_verify($test_password, $stored_hash)) {
        echo "✅ <strong style='color: green;'>Password COCOK! Login seharusnya berhasil.</strong><br>";
    } else {
        echo "❌ <strong style='color: red;'>Password TIDAK COCOK!</strong><br>";
        echo "Kemungkinan password di database tidak ter-hash dengan benar.<br>";
        echo "Silakan jalankan file <strong>generate_password.php</strong><br>";
    }
} else {
    echo "❌ Username 'admin' tidak ditemukan di database!<br>";
}

mysqli_close($conn);
?>