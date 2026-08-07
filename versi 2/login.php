<?php
session_start();
require 'config.php';

$username = trim($_POST['username'] ?? '');
$password = $_POST['password'] ?? '';

$max_attempts = 3; 
$lockout_duration = 300; 

// Cek apakah akun sedang dikunci
if (isset($_SESSION['lockout_time']) && time() < $_SESSION['lockout_time']) {
    header("location:index.php?pesan=locked");
    exit();
}

// Lepaskan kunci jika waktu habis
if (isset($_SESSION['lockout_time']) && time() >= $_SESSION['lockout_time']) {
    unset($_SESSION['login_attempts']);
    unset($_SESSION['lockout_time']);
}

// Gunakan Prepared Statement untuk mencegah SQL Injection
$stmt = $conn->prepare("SELECT id_user, username, password, nama_lengkap, role, jabatan, penempatan FROM users WHERE username = ?");
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 1) {
    $user = $result->fetch_assoc();
    
    // Verifikasi hash BCRYPT yang aman
    if (password_verify($password, $user['password'])) {
        
        unset($_SESSION['login_attempts']);
        session_regenerate_id(true); // Cegah session hijacking

        $_SESSION['id_user']      = $user['id_user'];
        $_SESSION['nama_lengkap'] = $user['nama_lengkap'];
        $_SESSION['role']         = $user['role'];
        $_SESSION['jabatan']      = $user['jabatan'];
        $_SESSION['penempatan']   = $user['penempatan'];

        header("location:dashboard.php");
        exit();
    }
}

// Catat kegagalan login untuk sistem Rate Limiting
if (!isset($_SESSION['login_attempts'])) {
    $_SESSION['login_attempts'] = 1;
} else {
    $_SESSION['login_attempts']++;
}

// Kunci sesi jika gagal 3 kali
if ($_SESSION['login_attempts'] >= $max_attempts) {
    $_SESSION['lockout_time'] = time() + $lockout_duration;
    header("location:index.php?pesan=locked");
    exit();
}

header("location:index.php?pesan=gagal");
exit();
?>