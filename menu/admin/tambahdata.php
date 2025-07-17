<?php
session_start();
require '../../controller/koneksi.php';

// Pastikan pengguna adalah admin
if ($_SESSION['role_user'] != 'Admin') {
    header("Location: login.php");
    exit();
}

// Inisialisasi variabel
$namaLengkap = $tempattglLahir = $alamat = $noHp = $username = $password = $roleUser = "";
$errors = [];

// Proses form ketika disubmit
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $namaLengkap = $_POST['nama_lengkap'];
    $tempattglLahir = $_POST['tempat_tgl_lahir'];
    $alamat = $_POST['alamat'];
    $noHp = $_POST['no_hp'];
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
    $roleUser = $_POST['role_user'];

    // Validasi data
    if (empty($namaLengkap)) {
        $errors[] = "Nama lengkap harus diisi.";
    }
    if (empty($username)) {
        $errors[] = "Username harus diisi.";
    }
    if (empty($_POST['password'])) {
        $errors[] = "Password harus diisi.";
    }
    if (empty($roleUser)) {
        $errors[] = "Role harus dipilih.";
    }

    // Jika tidak ada error, simpan data ke database
    if (empty($errors)) {
        // Tambahkan data ke login_user
        $stmt = $koneksi->prepare("INSERT INTO login_user (username, password, role_user) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $username, $password, $roleUser);

        if ($stmt->execute()) {
            $id_user = $stmt->insert_id; // Dapatkan id_user yang baru saja ditambahkan

            // Tambahkan data ke tabel yang sesuai berdasarkan role
            if ($roleUser == 'Peserta') {
                $stmt = $koneksi->prepare("INSERT INTO peserta (id_user, nama_lengkap, tempat_tgl_lahir, alamat, no_hp) VALUES (?, ?, ?, ?, ?)");
                $stmt->bind_param("issss", $id_user, $namaLengkap, $tempattglLahir, $alamat, $noHp);
            } elseif ($roleUser == 'Admin') {
                $stmt = $koneksi->prepare("INSERT INTO admin (id_user, nama_lengkap, tempat_tgl_lahir, alamat, no_hp) VALUES (?, ?, ?, ?, ?)");
                $stmt->bind_param("issss", $id_user, $namaLengkap, $tempattglLahir, $alamat, $noHp);
            } else {
                // Role tidak dikenali
                die("Role tidak valid");
            }


            if ($stmt->execute()) {
                header("Location: dashboard-admin.php");
                exit();
            } else {
                $errors[] = "Gagal menambahkan data ke tabel yang sesuai.";
            }
        } else {
            $errors[] = "Gagal menambahkan data login.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Data Peserta</title>
    <link rel="stylesheet" href="../../css/keloladata.css">
</head>
<body>
    <!-- Menyertakan sidebar -->
    <?php include('../template/sidebar.php'); ?>

    <div class="container" style="margin-left: 270px; padding-top: 20px;">
        <h4>Tambah Data User</h4>

        <?php if (!empty($errors)): ?>
            <div class="alert alert-danger">
                <?php foreach ($errors as $error): ?>
                    <p><?php echo $error; ?></p>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <form action="tambahdata.php" method="post" class="mt-4">
            <div class="form-group">
                <label for="nama_lengkap">Nama Lengkap:</label>
                <input type="text" name="nama_lengkap" id="nama_lengkap" class="form-control" value="<?php echo htmlspecialchars($namaLengkap); ?>" required>
            </div>

            <div class="form-group">
                <label for="tempat_tgl_lahir">Tempat Tanggal Lahir:</label>
                <input type="text" name="tempat_tgl_lahir" id="tempat_tgl_lahir" class="form-control" value="<?php echo htmlspecialchars($tempattglLahir); ?>">
            </div>

            <div class="form-group">
                <label for="alamat">Alamat:</label>
                <textarea name="alamat" id="alamat" class="form-control"><?php echo htmlspecialchars($alamat); ?></textarea>
            </div>

            <div class="form-group">
                <label for="no_hp">No HP:</label>
                <input type="text" name="no_hp" id="no_hp" class="form-control" value="<?php echo htmlspecialchars($noHp); ?>">
            </div>

            <div class="form-group">
                <label for="username">Username:</label>
                <input type="text" name="username" id="username" class="form-control" value="<?php echo htmlspecialchars($username); ?>" required>
            </div>

            <div class="form-group">
                <label for="password">Password:</label>
                <input type="password" name="password" id="password" class="form-control" required>
            </div>

            <div class="form-group">
                <label for="role_user">Role:</label>
                <select name="role_user" id="role_user" class="form-control" required>
                    <option value="">Pilih Role</option>
                    <option value="Admin" <?php if ($roleUser == 'Admin') echo 'selected'; ?>>Admin</option>
                    <option value="Peserta" <?php if ($roleUser == 'Peserta') echo 'selected'; ?>>Peserta</option>
                </select>
            </div>

            <button type="submit" class="btn btn-primary">Tambah Peserta</button>
            <br>
            <br>
        </form>
    </div>
</body>
</html>
