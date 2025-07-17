<?php
session_start();
require '../../controller/koneksi.php';

// Pastikan pengguna adalah admin
if ($_SESSION['role_user'] != 'Admin') {
    header("Location: login.php");
    exit();
}

// Cek apakah id program ada
if (isset($_GET['id'])) {
    $idProgram = $_GET['id'];

    // Dapatkan data program pelatihan berdasarkan id
    $query = "SELECT * FROM program_kajian WHERE id_program = ?";
    $stmt = $koneksi->prepare($query);
    $stmt->bind_param("i", $idProgram);
    $stmt->execute();
    $result = $stmt->get_result();
    $program = $result->fetch_assoc();
    $stmt->close();

    if (!$program) {
        echo "<script>
                alert('Data program tidak ditemukan!');
                window.location.href = 'kelolaprogram.php';
              </script>";
        exit();
    }
} else {
    header("Location: kelolaprogram.php");
    exit();
}

// Inisialisasi variabel
$namaProgram = $program['nama_program'];
$deskripsi = $program['deskripsi'];
$errors = [];

// Proses form ketika disubmit
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $namaProgram = $_POST['nama_program'];
    $deskripsi = $_POST['deskripsi'];

    // Validasi data
    if (empty($namaProgram)) {
        $errors[] = "Nama program harus diisi.";
    }

    // Jika tidak ada error, perbarui data di database
    if (empty($errors)) {
        $query = "UPDATE program_kajian SET nama_program = ?, deskripsi = ? WHERE id_program = ?";
        $stmt = $koneksi->prepare($query);
        $stmt->bind_param("ssi", $namaProgram, $deskripsi, $idProgram);

        if ($stmt->execute()) {
            echo "<script>
                    alert('Data berhasil diperbarui!');
                    window.location.href = 'kelolaprogram.php';
                  </script>";
        } else {
            $errors[] = "Gagal memperbarui data.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Program Kajian</title>
    <link rel="stylesheet" href="../../css/keloladata.css">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <!-- Menyertakan sidebar -->
    <?php include('../template/sidebar.php'); ?>

    <div class="container" style="margin-left: 270px; padding-top: 20px;">
        <h2>Edit Program Kajian</h2>

        <?php if (!empty($errors)): ?>
            <div class="alert alert-danger">
                <?php foreach ($errors as $error): ?>
                    <p><?php echo $error; ?></p>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <form action="editprogram.php?id=<?php echo $idProgram; ?>" method="post" class="mt-4">
            <div class="form-group">
                <label for="nama_program">Nama Program:</label>
                <input type="text" name="nama_program" id="nama_program" class="form-control" value="<?php echo htmlspecialchars($namaProgram); ?>" required>
            </div>

            <div class="form-group">
                <label for="deskripsi">Deskripsi:</label>
                <textarea name="deskripsi" id="deskripsi" class="form-control"><?php echo htmlspecialchars($deskripsi); ?></textarea>
            </div>

            <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
        </form>
    </div>
</body>
</html>