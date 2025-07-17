<?php
session_start();
require '../../controller/koneksi.php';

// Pastikan pengguna adalah admin
if ($_SESSION['role_user'] != 'Admin') {
    header("Location: login.php");
    exit();
}

// Ambil data admin
$id_user = $_SESSION['id_user'];
$query_admin = "SELECT nama_lengkap FROM admin WHERE id_user = $id_user";
$result_admin = $koneksi->query($query_admin);
$data_admin = $result_admin->fetch_assoc();

// Hitung jumlah peserta
$query_peserta_count = "SELECT COUNT(*) as total_peserta FROM peserta";
$result_peserta_count = $koneksi->query($query_peserta_count);
$total_peserta = $result_peserta_count->fetch_assoc()['total_peserta'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin</title>
    <link rel="stylesheet" href="../../css/sidebar.css">
</head>
<body>
    <!-- Include Sidebar -->
    <?php include ('../template/sidebar.php'); ?>

    <div class="main-content" style="margin-left: 250px; padding: 20px;">
        <h3 class="text-center mb-4">Dashboard Admin</h3>
        
        <!-- Pesan Selamat Datang -->
        <div class="alert alert-success">
            <h5>Halo Admin, <?php echo $data_admin['nama_lengkap']; ?>!</h5>
        </div>
        
        <!-- Statistik Jumlah Peserta dan Guru -->
        <div class="row">
            <div class="col-md-6 mb-4">
                <div class="card text-white bg-primary">
                    <div class="card-body">
                        <h5 class="card-title"><i class="fas fa-users"></i> Jumlah Peserta</h5>
                        <p class="card-text"><?php echo $total_peserta; ?> Peserta</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
