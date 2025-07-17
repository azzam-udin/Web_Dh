<?php 
include("../template/sidebar.php");
session_start();
require '../../controller/koneksi.php';

// Pastikan pengguna adalah admin
if ($_SESSION['role_user'] != 'Admin') {
    header("Location: login.php");
    exit();
}

// Query untuk mendapatkan data peserta dan detailnya
$query = "
    SELECT p.id_peserta, p.nama_lengkap, p.tempat_tgl_lahir, p.alamat, p.no_hp, 
           GROUP_CONCAT(pp.nama_program SEPARATOR ', ') AS program_dipilih
    FROM peserta p
    LEFT JOIN peserta_program_kajian ppp ON p.id_peserta = ppp.id_peserta
    LEFT JOIN program_kajian pp ON ppp.id_program = pp.id_program
    GROUP BY p.id_peserta
";
$result = $koneksi->query($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Data Peserta</title>
    <link rel="stylesheet" href="../../css/keloladata.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- FontAwesome Icons -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
</head>
<body>
    <?php include('../template/sidebar.php'); ?>

    <div class="main-content">
        <h4>Kelola Data Peserta</h4>
    
        <!-- Tabel untuk menampilkan data peserta -->
        <table class="table">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama Lengkap</th>
                    <th>ID Peserta</th>
                    <th>Aksi</th>
                    <th>Detail</th>
                </tr>
            </thead>
            <tbody>
                <?php $no = 1; ?>
                <?php while ($peserta = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo $no++; ?></td>
                        <td><?php echo $peserta['nama_lengkap']; ?></td>
                        <td><?php echo $peserta['id_peserta']; ?></td>
                        <td>
                            <a href="editdatapeserta.php?id=<?php echo $peserta['id_peserta']; ?>" class="btn btn-primary">
                                <i class="fas fa-edit"></i> Edit
                            </a>
                            <button class="btn btn-danger" onclick="deletePeserta(<?php echo $peserta['id_peserta']; ?>)">
                                <i class="fas fa-trash-alt"></i> Hapus
                            </button>
                        </td>
                        <td>
                            <!-- Tombol Detail dengan data peserta di atribut data- -->
                            <button class="btn btn-info" onclick="showDetailModal(this)" 
                                    data-id="<?php echo $peserta['id_peserta']; ?>"
                                    data-nama="<?php echo htmlspecialchars($peserta['nama_lengkap']); ?>"
                                    data-ttl="<?php echo htmlspecialchars($peserta['tempat_tgl_lahir']); ?>"
                                    data-alamat="<?php echo htmlspecialchars($peserta['alamat']); ?>"
                                    data-nohp="<?php echo htmlspecialchars($peserta['no_hp']); ?>"
                                    data-program="<?php echo !empty($peserta['program_dipilih']) ? htmlspecialchars($peserta['program_dipilih']) : 'Belum ada program yang dipilih'; ?>">
                                <i class="fas fa-eye"></i> Detail
                            </button>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>

    <!-- Modal Detail Peserta -->
    <div class="modal fade" id="detailModal" tabindex="-1" aria-labelledby="detailModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-info text-white">
                    <h5 class="modal-title" id="detailModalLabel">
                        <i class="fas fa-user-circle"></i> Detail Peserta
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <!-- Tabel untuk Detail Peserta -->
                    <table class="table table-bordered">
                        <tbody>
                            <tr>
                                <th>ID Peserta</th>
                                <td><i class="fas fa-id-card"></i> <span id="idPeserta"></span></td>
                            </tr>
                            <tr>
                                <th>Nama Lengkap</th>
                                <td><i class="fas fa-user"></i> <span id="namaLengkap"></span></td>
                            </tr>
                            <tr>
                                <th>Tempat, Tgl Lahir</th>
                                <td><i class="fas fa-birthday-cake"></i> <span id="ttl"></span></td>
                            </tr>
                            <tr>
                                <th>Alamat</th>
                                <td><i class="fas fa-map-marker-alt"></i> <span id="alamat"></span></td>
                            </tr>
                            <tr>
                                <th>No HP</th>
                                <td><i class="fas fa-phone-alt"></i> <span id="noHp"></span></td>
                            </tr>
                            <tr>
                                <th>Program Dipilih</th>
                                <td><i class="fas fa-book"></i> <span id="programDipilih"></span></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times-circle"></i> Tutup
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
    function showDetailModal(button) {
        // Mengambil data dari atribut data- tombol
        document.getElementById('idPeserta').textContent = button.getAttribute('data-id');
        document.getElementById('namaLengkap').textContent = button.getAttribute('data-nama');
        document.getElementById('ttl').textContent = button.getAttribute('data-ttl');
        document.getElementById('alamat').textContent = button.getAttribute('data-alamat');
        document.getElementById('noHp').textContent = button.getAttribute('data-nohp');
        
        // Mendapatkan data program dan memeriksa apakah ada program yang dipilih
        const programDipilih = button.getAttribute('data-program');
        document.getElementById('programDipilih').textContent = programDipilih !== 'Belum ada program yang dipilih' 
            ? programDipilih 
            : 'Belum ada program yang dipilih';

        // Menampilkan modal
        new bootstrap.Modal(document.getElementById('detailModal')).show();
    }

    function deletePeserta(idPeserta) {
        if (confirm("Apakah Anda yakin ingin menghapus peserta ini?")) {
            window.location.href = 'deletepeserta.php?id=' + idPeserta;
        }
    }
    </script>
</body>
</html>
