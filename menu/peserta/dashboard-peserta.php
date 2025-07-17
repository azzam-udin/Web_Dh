<?php
session_start();
require '../../controller/koneksi.php'; 

// Pastikan pengguna sudah login dan memiliki role 'Peserta'
if (!isset($_SESSION['id_user']) || $_SESSION['role_user'] !== 'Peserta') {
    header('Location: login.php');
    exit();
}

$id_user = $_SESSION['id_user'];

// Ambil data peserta
$query = "SELECT * FROM peserta WHERE id_user = ?";
$stmt = $koneksi->prepare($query);
$stmt->bind_param("i", $id_user);
$stmt->execute();
$result = $stmt->get_result();
$peserta = $result->fetch_assoc();

// Ambil daftar program pelatihan
$query_program = "SELECT * FROM program_kajian";
$result_program = $koneksi->query($query_program);

// Ambil program yang sedang diikuti
$query_current_programs = "
    SELECT pp.id_program, pp.nama_program 
    FROM program_kajian pp
    JOIN peserta_program_kajian ppp ON pp.id_program = ppp.id_program
    WHERE ppp.id_peserta = ?";
$stmt_current_programs = $koneksi->prepare($query_current_programs);
$stmt_current_programs->bind_param("i", $peserta['id_peserta']);
$stmt_current_programs->execute();
$result_current_programs = $stmt_current_programs->get_result();

// Simpan id_program dan nama_program yang sedang diikuti dalam array
$current_programs = $result_current_programs->fetch_all(MYSQLI_ASSOC);
$current_program_ids = array_column($current_programs, 'id_program');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $selected_programs = $_POST['programs'] ?? [];

    // Hapus pilihan program sebelumnya
    $delete_query = "DELETE FROM peserta_program_kajian WHERE id_peserta = ?";
    $delete_stmt = $koneksi->prepare($delete_query);
    $delete_stmt->bind_param("i", $peserta['id_peserta']);
    $delete_stmt->execute();

    // Simpan pilihan program baru
    $insert_query = "INSERT INTO peserta_program_kajian (id_peserta, id_program) VALUES (?, ?)";
    $insert_stmt = $koneksi->prepare($insert_query);
    foreach ($selected_programs as $id_program) {
        $insert_stmt->bind_param("ii", $peserta['id_peserta'], $id_program);
        $insert_stmt->execute();
    }

    echo "<script>alert('Pilihan program berhasil disimpan!');</script>";
    // Refresh halaman untuk menampilkan program yang baru dipilih
    header("Location: dashboard-peserta.php");
    exit();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Peserta</title>
    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../css/dashboard.css">

    <!-- Link Font Awesome untuk ikon -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet">
</head>
<body>
  <?php include("../template/sidebar-peserta.php")?>

    <div class="container mt-5" style="margin-left: 260px;">
        <h4 class="mb-4">Selamat Datang, <?php echo htmlspecialchars($peserta['nama_lengkap']); ?></h4>

        <!-- Data Peserta -->
    <!-- Data Peserta -->
<div class="card mb-4">
    <div class="card-header bg-primary text-white">
        <strong><i class="fas fa-user"></i> Profil Peserta</strong>
    </div>
    <div class="card-body">
        <table class="table table-bordered">
            <tr>
                <th><i class="fas fa-user-circle"></i> Username</th>
                <td><?php echo htmlspecialchars($_SESSION['username']); ?></td>
            </tr>
            <tr>
                <th><i class="fas fa-cogs"></i> Role</th>
                <td><?php echo htmlspecialchars($_SESSION['role_user']); ?></td>
            </tr>
            <tr>
                <th><i class="fas fa-map-marker-alt"></i> Alamat</th>
                <td><?php echo htmlspecialchars($peserta['alamat']); ?></td>
            </tr>
            <tr>
                <th><i class="fas fa-phone-alt"></i> No HP</th>
                <td><?php echo htmlspecialchars($peserta['no_hp']); ?></td>
            </tr>
        </table>
    </div>
</div>


        <!-- Program yang Sedang Diikuti -->
        <div class="card mb-4">
            <div class="card-header bg-success text-white">
                <strong><i class="fas fa-cogs"></i> Kajian yang Sedang Diikuti</strong>
            </div>
            <div class="card-body">
                <?php if ($current_programs): ?>
                    <ul class="list-group">
                        <?php foreach ($current_programs as $program): ?>
                            <li class="list-group-item"><i class="fas fa-check-circle"></i> <?php echo htmlspecialchars($program['nama_program']); ?></li>
                        <?php endforeach; ?>
                    </ul>
                <?php else: ?>
                    <p><i class="fas fa-times-circle"></i> Anda belum mengikuti program pelatihan apapun.</p>
                <?php endif; ?>
            </div>
        </div>

        <!-- Pilih Program Pelatihan -->
        <div class="card mb-4">
            <div class="card-header bg-warning text-white">
                <strong><i class="fas fa-list-ul"></i> Pilih Program Kajian</strong>
            </div>
            <div class="card-body">
                <form method="POST">
                    <div class="form-group">
                        <div class="row">
                            <?php while ($program = $result_program->fetch_assoc()): ?>
                                <div class="col-md-4">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="programs[]" value="<?php echo $program['id_program']; ?>"
                                            <?php echo in_array($program['id_program'], $current_program_ids) ? 'checked' : ''; ?>>
                                        <label class="form-check-label">
                                            <i class="fas fa-clipboard-list"></i> <?php echo htmlspecialchars($program['nama_program']); ?>
                                        </label>
                                    </div>
                                </div>
                            <?php endwhile; ?>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Simpan Pilihan</button>
                </form>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS and dependencies -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
