<?php 
// Mulai session
session_start();
require '../controller/koneksi.php';
require '../controller/controller.php'; // Include the registration handler

$error = ''; // Variabel untuk pesan error atau status registrasi

// Proses form jika ada input dari pengguna
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $result = handleRegistration($koneksi);

    // Periksa apakah registrasi berhasil atau gagal
    if ($result === true) {
        $error = 'success'; // Set 'success' jika registrasi berhasil
    } else {
        $error = $result; // Set pesan error jika registrasi gagal
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Register DH</title>
  <link rel="stylesheet" href="../css/regist.css">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link rel="stylesheet" href="../css/responsive.css">
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body>
  <section>
    <div class="login-container">
      <img src="../img/logodh.png" alt="Logo Daaru Hiraa" style="max-width: 100%; height: auto;" />
      <fieldset>
        <legend>Action</legend>
        <form method="POST" enctype="multipart/form-data">
          <ul>
            <!-- Form input fields -->
            <li>
              <label for="nama">Nama Lengkap</label>
              <input type="text" name="nama" id="nama" placeholder="Masukan nama lengkap anda" value="<?php echo htmlspecialchars($_POST['nama'] ?? ''); ?>">
            </li>
            <li>
              <label for="tempatLahir">TTL</label>
              <input type="text" name="tempatLahir" id="tempatLahir" placeholder="Masukan tempat tanggal lahir anda" value="<?php echo htmlspecialchars($_POST['tempatLahir'] ?? ''); ?>">
            </li>
            <li>
              <label for="alamat">Alamat</label>
              <input type="text" name="alamat" id="alamat" placeholder="Masukan alamat anda" value="<?php echo htmlspecialchars($_POST['alamat'] ?? ''); ?>">
            </li>
            <li>
              <label for="noHp">No HP</label>
              <input type="number" name="noHp" id="noHp" placeholder="Masukan nomor HP anda" value="<?php echo htmlspecialchars($_POST['noHp'] ?? ''); ?>">
            </li>
            <li>
              <label for="role">Role</label>
              <select name="role" id="role">
                <option value="" disabled selected>Pilih Role</option>
                <!-- <option value="Admin" <?php echo ($_POST['role'] ?? '' == 'Admin') ? 'selected' : ''; ?>>Admin</option> -->
                <option value="Peserta" <?php echo ($_POST['role'] ?? '' == 'Peserta') ? 'selected' : ''; ?>>Peserta</option>
              </select>
            </li>
            <li>
              <label for="username">Username/Email</label>
              <input type="email" name="username" id="username" placeholder="Masukan username/email anda" value="<?php echo htmlspecialchars($_POST['username'] ?? ''); ?>">
            </li>
            <li>
              <label for="password">Password</label>
              <input type="password" name="password" id="password" placeholder="Masukan password anda">
            </li>
            <li>
              <input name="submit" type="submit" value="Daftar">
            </li>
            <li class="aksi">
              <a href="login.php">Kembali Ke Halaman Login</a>
            </li>
          </ul>
        </form>

        <!-- SweetAlert untuk notifikasi hasil registrasi -->
        <?php if ($error === 'success'): ?>
            <script>
                Swal.fire({
                    icon: 'success',
                    title: 'Registrasi Berhasil',
                    text: 'Anda telah berhasil mendaftar!',
                    showConfirmButton: false,
                    timer: 2000
                }).then(() => {
                    window.location.href = 'login.php'; // Redirect ke halaman login setelah sukses
                });
            </script>
        <?php elseif (!empty($error)): ?>
            <script>
                Swal.fire({
                    icon: 'error',
                    title: 'Registrasi Gagal',
                    text: '<?php echo $error; ?>',
                });
            </script>
        <?php endif; ?>
      </fieldset>
    </div>
  </section>
</body>
</html>
