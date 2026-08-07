<?php
session_start();
if (isset($_SESSION['id_user'])) {
    header("location:dashboard.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - CyberNusa 2.0 Secure</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <style>
        * { box-sizing: border-box; font-family: 'Poppins', sans-serif; }
        body { 
            margin: 0; 
            height: 100vh; 
            display: flex; 
            align-items: center; 
            justify-content: center; 
            background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);
        }
        .login-wrapper {
            background: rgba(255, 255, 255, 0.95);
            padding: 40px;
            border-radius: 16px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.2);
            width: 100%;
            max-width: 400px;
            text-align: center;
        }
        .logo-text { font-size: 28px; font-weight: 600; color: #333; margin-bottom: 5px; }
        .logo-text span { color: #2a5298; }
        .badge {
            display: inline-block;
            background: #28a745;
            color: white;
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            margin-bottom: 25px;
            letter-spacing: 1px;
        }
        .error {
            background: #ffe3e6;
            color: #dc3545;
            padding: 12px;
            border-radius: 8px;
            font-size: 14px;
            margin-bottom: 20px;
            border-left: 4px solid #dc3545;
        }
        .input-group { text-align: left; margin-bottom: 20px; }
        .input-group label { display: block; font-size: 14px; color: #666; margin-bottom: 8px; font-weight: 600; }
        .input-group input { 
            width: 100%; padding: 12px 15px; border: 1px solid #ccc; border-radius: 8px; 
            font-size: 14px; transition: 0.3s;
        }
        .input-group input:focus { border-color: #2a5298; outline: none; box-shadow: 0 0 0 3px rgba(42, 82, 152, 0.2); }
        .btn-login {
            width: 100%; padding: 14px; background: #2a5298; color: white; border: none;
            border-radius: 8px; font-size: 16px; font-weight: 600; cursor: pointer;
            transition: 0.3s; box-shadow: 0 4px 15px rgba(42, 82, 152, 0.4);
        }
        .btn-login:hover { background: #1e3c72; transform: translateY(-2px); }
    </style>
</head>
<body>

<div class="login-wrapper">
    <div class="logo-text">Cyber<span>Nusa</span></div>
    <div class="badge">V2.0 SECURE HRIS</div>
    
    <?php
    if (isset($_GET['pesan'])) {
        if ($_GET['pesan'] == "gagal") {
            echo "<div class='error'>Username atau password salah!</div>";
        } else if ($_GET['pesan'] == "locked") {
            $sisa = isset($_SESSION['lockout_time']) ? ceil(($_SESSION['lockout_time'] - time()) / 60) : 5;
            echo "<div class='error'><strong>AKUN TERKUNCI!</strong><br>Sistem mendeteksi brute force.<br>Coba lagi dalam $sisa menit.</div>";
        }
    }
    ?>

    <form action="login.php" method="POST">
        <div class="input-group">
            <label>Username</label>
            <input type="text" name="username" placeholder="Masukkan username" required autocomplete="off">
        </div>
        
        <div class="input-group">
            <label>Password</label>
            <input type="password" name="password" placeholder="••••••••" required>
        </div>
        
        <button type="submit" class="btn-login">Masuk ke Sistem</button>
    </form>
</div>

</body>
</html>