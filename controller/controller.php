<?php
function handleLogin($username, $password, &$success) { // Added &$success parameter
    global $koneksi;

    // Menggunakan prepared statement untuk keamanan
    $stmt = $koneksi->prepare("SELECT * FROM login_user WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    // Cek apakah username ada di database
    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();

        // Verifikasi password dengan password_verify()
        if (password_verify($password, $user['password'])) {
            // Password benar, simpan informasi login ke session
            $_SESSION['id_user'] = $user['id_user'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['role_user'] = $user['role_user'];

            // Tentukan URL pengalihan berdasarkan peran pengguna
            if ($user['role_user'] == 'Peserta') {
                $_SESSION['redirect_url'] = '../menu/peserta/dashboard-peserta.php';
            } elseif ($user['role_user'] == 'Admin') {
                $_SESSION['redirect_url'] = '../menu/admin/dashboard-admin.php';
            } else {
                return 'Role tidak dikenali atau tidak diizinkan.';
            }

            $success = true; // Set status sukses
            return ''; // Kembalikan kosong jika tidak ada error
        } else {
            return "Password salah.";
        }
    } else {
        return "Username tidak ditemukan.";
    }
}

function handleRegistration($koneksi) {
    $nama = $_POST['nama'] ?? '';
    $tempatLahir = $_POST['tempatLahir'] ?? '';
    $alamat = $_POST['alamat'] ?? '';
    $noHp = $_POST['noHp'] ?? '';
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';
    $role = $_POST['role'] ?? null;

    // Cek jika ada field yang kosong
    if (empty($username) || empty($password) || empty($nama) || empty($tempatLahir) || empty($alamat) || empty($noHp) || empty($role)) {
        return "Mohon lengkapi data Anda!";
    }

    // Cek jika email sudah terdaftar
    $stmt_check = $koneksi->prepare("SELECT * FROM login_user WHERE username = ?");
    $stmt_check->bind_param("s", $username);
    $stmt_check->execute();
    $result_check = $stmt_check->get_result();

    if ($result_check->num_rows > 0) {
        return "Email sudah digunakan.";
    }

    // Hash password sebelum disimpan
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Insert data ke tabel login_user
    $stmt = $koneksi->prepare("INSERT INTO login_user (username, password, role_user) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $username, $hashedPassword, $role);

    if ($stmt->execute()) {
        $id_user = $koneksi->insert_id;

        // Insert data tambahan di tabel peserta
        $stmt2 = $koneksi->prepare("INSERT INTO peserta (id_user, nama_lengkap, tempat_tgl_lahir, alamat, no_hp) VALUES (?, ?, ?, ?, ?)");
        $stmt2->bind_param("issss", $id_user, $nama, $tempatLahir, $alamat, $noHp);

        if ($stmt2->execute()) {
            return true; // Kembali 'true' jika registrasi berhasil
        } else {
            return "Gagal menyimpan data peserta!";
        }
    } else {
        return "Gagal menyimpan data login!";
    }
}

function handleLogout() {
    session_start();

    // Hapus semua sesi
    $_SESSION = [];

    // Hapus cookie sesi jika ada
    if (ini_get("session.use_cookies")) {
        $params = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000,
            $params["path"], $params["domain"],
            $params["secure"], $params["httponly"]
        );
    }

    // Hancurkan sesi
    session_destroy();

    // Alihkan ke halaman login
    header("Location: login.php");
    exit();
}
?>
