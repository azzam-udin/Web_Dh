<?php
session_start();
require '../controller/koneksi.php'; 
require '../controller/controller.php'; 

$error = '';
$success = false; // Menyimpan status sukses login

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];
    
    $error = handleLogin($username, $password, $success); // Pass by reference for success
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Halaman Login</title>
    <link rel="stylesheet" href="../css/login.css" />
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link rel="stylesheet" href="../css/responsive.css" />
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body>
    <section>
        <div class="login-container">
            <img src="../img/logodh.png" alt="Logo Daaru Hiraa" style="max-width: 100%; height: auto;" />
            <fieldset>
                <legend>Action</legend>
                <form id="loginForm" method="POST" action="">
                    <ul>
                        <li>
                            <label for="username">Username/Email</label>
                            <input type="email" name="username" id="username" placeholder="Masukan username/email anda" required />
                        </li>
                        <li>
                            <label for="password">Password</label>
                            <input type="password" name="password" id="password" placeholder="Masukan password anda" required />
                        </li>
                        <li>
                            <input type="submit" value="Login" name="submit" />
                        </li>
                        <li class="aksi">
                            <a href="regist.php">Daftar</a>
                        </li>
                        <li class="aksi">
                            <a href="../index.html">Kembali Ke Home</a>
                        </li>
                    </ul>
                </form>
                <!-- Pesan error jika login gagal -->
                <?php if (!empty($error)): ?>
                    <script>
                        Swal.fire({
                            icon: 'error',
                            title: 'Login Gagal',
                            text: '<?php echo $error; ?>',
                        });
                    </script>
                <?php endif; ?>

                <!-- Pesan sukses jika login berhasil -->
                <?php if ($success): ?>
                    <script>
                        Swal.fire({
                            icon: 'success',
                            title: 'Login Berhasil',
                            text: 'Selamat datang!',
                        }).then(() => {
                            // Jika role adalah Guru Ngaji, tampilkan pesan 'Menu belum tersedia'
                            <?php if ($_SESSION['role_user'] == 'Guru Ngaji'): ?>
                                Swal.fire({
                                    icon: 'info',
                                    title: 'Menu Guru Ngaji Belum Tersedia',
                                    text: 'Fitur ini masih dalam pengembangan.',
                                }).then(() => {
                                    window.location.href = 'login.php'; // Redirect ke halaman login setelah tampil pesan
                                });
                            <?php else: ?>
                                window.location.href = "<?php echo $_SESSION['redirect_url']; ?>"; // Redirect ke dashboard sesuai role
                            <?php endif; ?>
                        });
                    </script>
                <?php endif; ?>
            </fieldset>
        </div>
    </section>
</body>
</html>
