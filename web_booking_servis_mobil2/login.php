<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Admin - Speed Garage</title>
    <link rel="stylesheet" href="css/style.css">
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
                <li><a href="login.php" class="active">Login Admin</a></li>
            </ul>
        </div>
    </nav>

   
    <section class="form-section">
        <div class="container">
            <div class="login-container fade-in">
                <div class="login-header">
                    <h2>🔐 Login Admin</h2>
                    <p class="form-subtitle">Masuk untuk mengelola data booking servis</p>
                </div>

                <?php
                
                if (isset($_GET['error'])) {
                    echo '<div class="alert alert-error">';
                    if ($_GET['error'] == 'invalid') {
                        echo '❌ Username atau password salah!';
                    } elseif ($_GET['error'] == 'empty') {
                        echo '⚠️ Username dan password harus diisi!';
                    } elseif ($_GET['error'] == 'logout') {
                        echo '✅ Anda telah berhasil logout!';
                    }
                    echo '</div>';
                }

              
                if (isset($_GET['success'])) {
                    echo '<div class="alert alert-success">';
                    if ($_GET['success'] == 'registered') {
                        echo '✅ Registrasi berhasil! Silakan login.';
                    }
                    echo '</div>';
                }
                ?>
                
            
                <form action="proses_login.php" method="POST" id="loginForm">
                    
                    
                    <div class="form-group">
                        <label for="username">
                            <span class="icon">👤</span> Username
                        </label>
                        <input type="text" id="username" name="username" placeholder="Masukkan username" required autofocus>
                    </div>

                    >
                    <div class="form-group">
                        <label for="password">
                            <span class="icon">🔒</span> Password
                        </label>
                        <input type="password" id="password" name="password" placeholder="Masukkan password" required>
                    </div>

                    
                    <div class="form-buttons">
                        <button type="submit" class="btn btn-primary btn-block">
                            🚀 Login Sekarang
                        </button>
                    </div>
                </form>

                <div class="login-footer">
                </div>
            </div>
        </div>
    </section>

    <footer class="footer">
        <div class="container">
            <p>&copy; 2026 Speed Garage. All Rights Reserved.</p>
        </div>
    </footer>

   
    <script src="js/script.js"></script>
</body>
</html>
