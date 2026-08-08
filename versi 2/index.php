<?php
session_start();
if (isset($_SESSION['id_user'])) {
    header("location:dashboard.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign In - CyberNusa HRIS</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    <style>
        * { box-sizing: border-box; font-family: 'Inter', sans-serif; margin: 0; padding: 0; }
        body { background-color: #f8fafc; display: flex; align-items: center; justify-content: center; height: 100vh; color: #0f172a; }
        .login-wrapper { background: #ffffff; padding: 48px 40px; border-radius: 8px; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -1px rgba(0, 0, 0, 0.03); width: 100%; max-width: 420px; border: 1px solid #e2e8f0; }
        .brand { text-align: center; margin-bottom: 32px; }
        .brand h1 { font-size: 24px; font-weight: 600; letter-spacing: -0.5px; margin-bottom: 8px; }
        .brand p { font-size: 14px; color: #64748b; }
        .alert { background: #fef2f2; border-left: 4px solid #ef4444; color: #991b1b; padding: 12px 16px; margin-bottom: 24px; font-size: 13px; border-radius: 4px; }
        .form-group { margin-bottom: 20px; }
        .form-group label { display: block; font-size: 13px; font-weight: 500; margin-bottom: 6px; color: #334155; }
        .form-group input { width: 100%; padding: 10px 12px; border: 1px solid #cbd5e1; border-radius: 6px; font-size: 14px; outline: none; transition: border-color 0.2s; }
        .form-group input:focus { border-color: #2563eb; box-shadow: 0 0 0 3px rgba(37,99,235,0.1); }
        .btn-submit { width: 100%; background: #0f172a; color: #ffffff; padding: 12px; border: none; border-radius: 6px; font-size: 14px; font-weight: 500; cursor: pointer; transition: background-color 0.2s; margin-top: 8px; }
        .btn-submit:hover { background: #1e293b; }
        .footer { text-align: center; margin-top: 32px; font-size: 12px; color: #94a3b8; }
    </style>
</head>
<body>

<div class="login-wrapper">
    <div class="brand">
        <h1>CyberNusa</h1>
        <p>Enterprise Human Resource System</p>
    </div>

    <?php if (isset($_GET['pesan'])): ?>
        <div class="alert">
            <?php 
                if ($_GET['pesan'] == "gagal") echo "Invalid credentials. Please verify your username and password.";
                elseif ($_GET['pesan'] == "locked") echo "Account locked due to multiple failed attempts. Please contact IT Support or wait 5 minutes.";
            ?>
        </div>
    <?php endif; ?>

    <form action="login.php" method="POST" autocomplete="off">
        <div class="form-group">
            <label>Employee Username</label>
            <input type="text" name="username" required>
        </div>
        <div class="form-group">
            <label>Password</label>
            <input type="password" name="password" required>
        </div>
        <button type="submit" class="btn-submit">Sign In</button>
    </form>
    <div class="footer">&copy; 2026 CyberNusa Corporation. All rights reserved.</div>
</div>

</body>
</html>