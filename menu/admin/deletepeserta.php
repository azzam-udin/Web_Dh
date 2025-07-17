<?php
session_start();
require '../../controller/koneksi.php';

// Pastikan pengguna adalah admin
if ($_SESSION['role_user'] != 'Admin') {
    header("Location: login.php");
    exit();
}

// Cek apakah id peserta ada
if (isset($_GET['id'])) {
    $idPeserta = $_GET['id'];

    // Dapatkan id_user dari peserta yang akan dihapus
    $query = "SELECT id_user FROM peserta WHERE id_peserta = ?";
    $stmt = $koneksi->prepare($query);
    $stmt->bind_param("i", $idPeserta);
    $stmt->execute();
    $stmt->bind_result($idUser);
    $stmt->fetch();
    $stmt->close();

    // Hapus data dari peserta_program_kajian yang terkait
    $query = "DELETE FROM peserta_program_kajian WHERE id_peserta = ?";
    $stmt = $koneksi->prepare($query);
    $stmt->bind_param("i", $idPeserta);
    $stmt->execute();
    $stmt->close();

    // Hapus data dari peserta
    $query = "DELETE FROM peserta WHERE id_peserta = ?";
    $stmt = $koneksi->prepare($query);
    $stmt->bind_param("i", $idPeserta);

    if ($stmt->execute()) {
        $stmt->close();

        // Hapus entri dari login_user
        $query = "DELETE FROM login_user WHERE id_user = ?";
        $stmt = $koneksi->prepare($query);
        $stmt->bind_param("i", $idUser);
        $stmt->execute();
        $stmt->close();

        echo "<script>
                alert('Data berhasil dihapus!');
                window.location.href = 'keloladata.php';
              </script>";
    } else {
        echo "<script>
                alert('Gagal menghapus data!');
                window.location.href = 'keloladata.php';
              </script>";
    }
}
?>
