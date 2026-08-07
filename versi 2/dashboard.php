<?php
session_start();
require 'config.php';

if (!isset($_SESSION['id_user'])) {
    header("location:index.php");
    exit();
}

$session_user_id = $_SESSION['id_user'];
$stmt = $conn->prepare("SELECT * FROM users WHERE id_user = ?");
$stmt->bind_param("i", $session_user_id);
$stmt->execute();
$data = $stmt->get_result()->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Dashboard - CyberNusa 2.0</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <style>
        * { box-sizing: border-box; font-family: 'Poppins', sans-serif; margin: 0; padding: 0; }
        body { background-color: #f4f7f6; color: #333; }
        
        /* Navbar */
        .navbar {
            background: #fff; padding: 15px 40px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            display: flex; justify-content: space-between; align-items: center;
        }
        .navbar .brand { font-size: 22px; font-weight: 600; color: #2a5298; }
        .navbar .brand span { color: #28a745; font-size: 14px; background: #e6f4ea; padding: 4px 10px; border-radius: 12px; margin-left: 10px; vertical-align: middle;}
        .navbar-nav a {
            text-decoration: none; color: #dc3545; font-weight: 600;
            padding: 8px 20px; border: 2px solid #dc3545; border-radius: 8px; transition: 0.3s;
        }
        .navbar-nav a:hover { background: #dc3545; color: white; }

        /* Content */
        .container { max-width: 1000px; margin: 40px auto; padding: 0 20px; }
        .welcome-text { font-size: 24px; font-weight: 600; margin-bottom: 20px; }
        
        .card {
            background: #fff; border-radius: 12px; padding: 30px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.05); margin-bottom: 25px;
        }
        .card-header { border-bottom: 2px solid #f0f0f0; padding-bottom: 15px; margin-bottom: 20px; display: flex; justify-content: space-between; align-items: center;}
        .card-header h3 { font-size: 18px; color: #444; }
        
        .info-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; }
        .info-box { background: #f8fafc; padding: 15px; border-radius: 8px; border-left: 4px solid #2a5298;}
        .info-box label { font-size: 12px; color: #777; text-transform: uppercase; font-weight: 600;}
        .info-box p { font-size: 16px; font-weight: 600; margin-top: 5px; color: #222;}
        
        .btn-edit {
            background: #2a5298; color: white; text-decoration: none; padding: 10px 20px;
            border-radius: 8px; font-weight: 600; display: inline-block; transition: 0.3s;
        }
        .btn-edit:hover { background: #1e3c72; transform: translateY(-2px);}
        
        .security-notice { font-size: 13px; color: #856404; background: #fff3cd; padding: 10px 15px; border-radius: 6px; border-left: 4px solid #ffeeba; margin-top: 15px;}
    </style>
</head>
<body>

    <nav class="navbar">
        <div class="brand">CyberNusa <span>V2.0 SECURE</span></div>
        <div class="navbar-nav">
            <a href="logout.php">Logout</a>
        </div>
    </nav>

    <div class="container">
        <div class="welcome-text">Halo, <?= htmlspecialchars($data['nama_lengkap']); ?> 👋</div>

        <div class="card">
            <div class="card-header">
                <h3>Informasi Profil Karyawan</h3>
                <a href="edit_karyawan.php" class="btn-edit">Edit Profil</a>
            </div>
            
            <div class="info-grid">
                <div class="info-box">
                    <label>Nama Lengkap</label>
                    <p><?= htmlspecialchars($data['nama_lengkap']); ?></p>
                </div>
                <div class="info-box">
                    <label>Hak Akses / Role</label>
                    <p><?= htmlspecialchars($data['role']); ?></p>
                </div>
                <div class="info-box">
                    <label>Jabatan</label>
                    <p><?= htmlspecialchars($data['jabatan']); ?></p>
                </div>
                <div class="info-box">
                    <label>Lokasi Penempatan</label>
                    <p><?= htmlspecialchars($data['penempatan']); ?></p>
                </div>
            </div>
            
            <div class="security-notice">
                🔒 <strong>Anti-IDOR Active:</strong> Data ini ditarik langsung melalui sesi server (Session ID), bukan dari parameter URL.
            </div>
        </div>
    </div>

</body>
</html>