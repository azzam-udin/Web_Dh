<?php
// Link CSS dan Bootstrap
?>
<link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="../../css/sidebar.css">

<!-- Link Font Awesome untuk ikon -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet">

<!-- Sidebar -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark" style="height: 100vh; position: fixed; width: 250px; top: 0; left: 0; z-index: 1000;">
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#sidebarMenu" aria-controls="sidebarMenu" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="sidebarMenu">
        <ul class="navbar-nav flex-column">
            <li class="nav-item">
                <a class="nav-link active" href="dashboard-peserta.php">
                    <i class="fas fa-tachometer-alt"></i> Dashboard
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#" id="logoutButton">
                    <i class="fas fa-sign-out-alt"></i> Logout
                </a>
            </li>
        </ul>
    </div>
</nav>

<!-- Tambahkan Bootstrap JS dan jQuery di akhir file -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    // Event listener untuk tombol logout
    document.getElementById('logoutButton').addEventListener('click', function (e) {
        e.preventDefault();  // Mencegah aksi default link (redirect langsung)

        // Menampilkan SweetAlert konfirmasi logout
        Swal.fire({
            title: 'Apakah Anda yakin ingin keluar?',
            text: "Anda akan diarahkan ke halaman login!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya, keluar!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                // Jika konfirmasi logout diterima, arahkan pengguna ke halaman logout
                window.location.href = '../logout.php';  // Ganti dengan path logout Anda
            }
        });
    });
</script>
