<?php
session_start();
require 'config.php';

$username = trim($_POST['username'] ?? '');
$password = $_POST['password'] ?? '';
$ip_address = $_SERVER['REMOTE_ADDR'];

$max_attempts = 3; 
$lockout_duration_minutes = 5; 

$stmt_cek = $conn->prepare("SELECT COUNT(*) as total_gagal FROM login_attempts WHERE ip_address = ? AND username = ? AND waktu_coba > (NOW() - INTERVAL ? MINUTE)");
$stmt_cek->bind_param("ssi", $ip_address, $username, $lockout_duration_minutes);
$stmt_cek->execute();
$hasil_cek = $stmt_cek->get_result()->fetch_assoc();

if ($hasil_cek['total_gagal'] >= $max_attempts) {
    header("location:index.php?pesan=locked");
    exit();
}

$stmt = $conn->prepare("SELECT id_user, username, password, nama_lengkap, role, jabatan, penempatan FROM users WHERE username = ?");
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 1) {
    $user = $result->fetch_assoc();
    
    if (password_verify($password, $user['password'])) {
        $stmt_hapus = $conn->prepare("DELETE FROM login_attempts WHERE ip_address = ? AND username = ?");
        $stmt_hapus->bind_param("ss", $ip_address, $username);
        $stmt_hapus->execute();
        
        session_regenerate_id(true);

        $_SESSION['id_user']      = $user['id_user'];
        $_SESSION['nama_lengkap'] = $user['nama_lengkap'];
        $_SESSION['role']         = $user['role'];
        $_SESSION['jabatan']      = $user['jabatan'];
        $_SESSION['penempatan']   = $user['penempatan'];

        header("location:dashboard.php");
        exit();
    }
}

$stmt_catat = $conn->prepare("INSERT INTO login_attempts (ip_address, username) VALUES (?, ?)");
$stmt_catat->bind_param("ss", $ip_address, $username);
$stmt_catat->execute();

if (($hasil_cek['total_gagal'] + 1) >= $max_attempts) {
    header("location:index.php?pesan=locked");
    exit();
}

header("location:index.php?pesan=gagal");
exit();
?>