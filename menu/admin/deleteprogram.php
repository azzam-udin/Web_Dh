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

    // Hapus dulu semua relasi peserta dari peserta_program_kajian
    $query = "DELETE FROM peserta_program_kajian WHERE id_program = ?";
    $stmt = $koneksi->prepare($query);
    $stmt->bind_param("i", $idProgram);
    $stmt->execute();
    $stmt->close();

    // Baru hapus program dari tabel program_kajian
    $query = "DELETE FROM program_kajian WHERE id_program = ?";
    $stmt = $koneksi->prepare($query);
    $stmt->bind_param("i", $idProgram);

    if ($stmt->execute()) {
        echo "<script>
                alert('Program berhasil dihapus!');
                window.location.href = 'kelolaprogram.php';
              </script>";
    } else {
        echo "<script>
                alert('Gagal menghapus program!');
                window.location.href = 'kelolaprogram.php';
              </script>";
    }
}
?>
