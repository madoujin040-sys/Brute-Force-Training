<?php
session_start();
require 'config.php';

$username = $_POST['username'];
$password = $_POST['password'];
$password_md5 = md5($password);

// ===================================================================================
// [ CELAH KEAMANAN - BRUTE FORCE ]
// 1. Tidak ada CAPTCHA.
// 2. Tidak ada Rate Limiting / delay antar percobaan.
// 3. Tidak ada Account Lockout (pemblokiran akun setelah gagal berkali-kali).
// ===================================================================================

$query = mysqli_query($conn, "SELECT * FROM users WHERE username='$username' AND password='$password_md5'");

if(mysqli_num_rows($query) > 0){
    $data = mysqli_fetch_assoc($query);
    
    $_SESSION['id_user']      = $data['id_user'];
    $_SESSION['nama_lengkap'] = $data['nama_lengkap'];
    $_SESSION['role']         = $data['role'];
    $_SESSION['jabatan']      = $data['jabatan'];
    $_SESSION['penempatan']   = $data['penempatan'];
    
    header("location:dashboard.php");
} else {
    header("location:index.php?pesan=gagal");
}
?>