<?php
session_start();
require '../../controller/koneksi.php';

// Pastikan pengguna adalah admin
if ($_SESSION['role_user'] != 'Admin') {
    header("Location: login.php");
    exit();
}

// Query untuk mendapatkan data program pelatihan
$query = "SELECT * FROM program_kajian";
$result = $koneksi->query($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Program Kajian</title>
    <link rel="stylesheet" href="../../css/keloladata.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet"> <!-- Menambahkan Font Awesome -->
    <style>
        /* Gaya khusus untuk tombol tambah program pelatihan */
        .btn-add {
            margin-bottom: 15px;
            font-weight: bold;
            background-color: #28a745;
            color: white;
        }
        .btn-add:hover {
            background-color: #218838;
        }

        /* Gaya untuk tabel dan tombol aksi */
        .table th, .table td {
            text-align: center;
            vertical-align: middle;
        }

        /* Styling tombol aksi */
        .btn {
            font-size: 0.9rem;
            padding: 6px 12px;
        }

        .table td a,
        .table td button {
            margin-right: 10px;
        }

        /* Responsivitas untuk tabel */
        @media (max-width: 768px) {
            .table th, .table td {
                font-size: 0.8rem;
            }
            .btn-add {
                font-size: 0.9rem;
            }
        }
    </style>
</head>
<body>
    <?php include('../template/sidebar.php'); ?>

    <div class="main-content">
        <h4>Kelola Program Kajian</h4>

        <!-- Tombol untuk menambah program pelatihan dengan ikon -->
        <a href="tambahprogram.php" class="btn btn-add btn-sm btn-primary">
            <i class="fas fa-plus-circle"></i> Tambah Program Kajian
        </a>

        <!-- Tabel untuk menampilkan data program pelatihan -->
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>No</th>
                    <th>ID Program</th>
                    <th>Nama Program</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php $no = 1; ?>
                <?php while ($program = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo $no++; ?></td>
                        <td><?php echo $program['id_program']; ?></td>
                        <td><?php echo htmlspecialchars($program['nama_program']); ?></td>
                        <td>
                            <!-- Tombol Edit dengan ikon -->
                            <a href="editprogram.php?id=<?php echo $program['id_program']; ?>" class="btn btn-primary btn-sm">
                                <i class="fas fa-edit"></i> Edit
                            </a>
                            <!-- Tombol Hapus dengan ikon -->
                            <button class="btn btn-danger btn-sm" onclick="deleteProgram(<?php echo $program['id_program']; ?>)">
                                <i class="fas fa-trash-alt"></i> Hapus
                            </button>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
    // Fungsi untuk menghapus program pelatihan
    function deleteProgram(idProgram) {
        if (confirm("Apakah Anda yakin ingin menghapus program ini?")) {
            window.location.href = 'deleteprogram.php?id=' + idProgram;
        }
    }
    </script>
</body>
</html>
