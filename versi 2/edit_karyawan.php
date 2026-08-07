<?php
session_start();
require 'config.php';

if (!isset($_SESSION['id_user'])) {
    header("location:index.php");
    exit();
}

$session_user_id = $_SESSION['id_user'];

if (isset($_POST['update'])) {
    $nama_lengkap = htmlspecialchars(trim($_POST['nama_lengkap']));
    $jabatan      = htmlspecialchars(trim($_POST['jabatan']));
    $penempatan   = htmlspecialchars(trim($_POST['penempatan']));

    $update_stmt = $conn->prepare("UPDATE users SET nama_lengkap = ?, jabatan = ?, penempatan = ? WHERE id_user = ?");
    $update_stmt->bind_param("sssi", $nama_lengkap, $jabatan, $penempatan, $session_user_id);
    
    if ($update_stmt->execute()) {
        $pesan_sukses = "Data berhasil diperbarui secara aman!";
    }
}

$stmt = $conn->prepare("SELECT * FROM users WHERE id_user = ?");
$stmt->bind_param("i", $session_user_id);
$stmt->execute();
$data = $stmt->get_result()->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Edit Profil - CyberNusa 2.0</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <style>
        * { box-sizing: border-box; font-family: 'Poppins', sans-serif; margin: 0; padding: 0; }
        body { background-color: #f4f7f6; color: #333; display: flex; justify-content: center; padding: 40px 20px;}
        
        .card {
            background: #fff; border-radius: 12px; padding: 40px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.05); width: 100%; max-width: 550px;
        }
        .card h2 { margin-bottom: 5px; color: #2a5298; }
        .card p.subtitle { font-size: 13px; color: #777; margin-bottom: 25px; line-height: 1.5;}
        
        .alert { background: #d4edda; color: #155724; padding: 12px 15px; border-radius: 8px; margin-bottom: 20px; font-size: 14px; font-weight: 600; border-left: 4px solid #28a745;}
        
        .form-group { margin-bottom: 20px; }
        .form-group label { display: block; font-size: 14px; font-weight: 600; color: #444; margin-bottom: 8px;}
        .form-group input { width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 8px; font-size: 14px; transition: 0.3s;}
        .form-group input:focus { border-color: #2a5298; outline: none; box-shadow: 0 0 0 3px rgba(42,82,152,0.1);}
        
        .btn-group { display: flex; gap: 15px; margin-top: 30px; }
        .btn-submit { flex: 1; background: #28a745; color: white; border: none; padding: 12px; border-radius: 8px; font-weight: 600; cursor: pointer; transition: 0.3s;}
        .btn-submit:hover { background: #218838; }
        .btn-back { padding: 12px 20px; background: #f8f9fa; border: 1px solid #ddd; color: #444; text-decoration: none; border-radius: 8px; font-weight: 600; transition: 0.3s;}
        .btn-back:hover { background: #e2e6ea; }
    </style>
</head>
<body>

    <div class="card">
        <h2>Edit Profil Pengguna</h2>
        <p class="subtitle">🔒 Jalur aman (Secure Route). Form ini kebal dari manipulasi ID (Insecure Direct Object Reference) melalui Inspect Element maupun modifikasi URL.</p>
        
        <?php if (isset($pesan_sukses)) echo "<div class='alert'>✅ $pesan_sukses</div>"; ?>

        <form method="POST" action="">
            <div class="form-group">
                <label>Nama Lengkap</label>
                <input type="text" name="nama_lengkap" value="<?= htmlspecialchars($data['nama_lengkap']); ?>" required>
            </div>

            <div class="form-group">
                <label>Jabatan</label>
                <input type="text" name="jabatan" value="<?= htmlspecialchars($data['jabatan']); ?>" required>
            </div>

            <div class="form-group">
                <label>Penempatan</label>
                <input type="text" name="penempatan" value="<?= htmlspecialchars($data['penempatan']); ?>" required>
            </div>

            <div class="btn-group">
                <a href="dashboard.php" class="btn-back">Batal</a>
                <button type="submit" name="update" class="btn-submit">Simpan Perubahan</button>
            </div>
        </form>
    </div>

</body>
</html>