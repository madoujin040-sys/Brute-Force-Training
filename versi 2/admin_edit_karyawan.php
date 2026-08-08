<?php
session_start();
require 'config.php';

// 1. VALIDASI SUPER KETAT (Secure RBAC)
// Pastikan user sudah login DAN role-nya adalah Administrator
if (!isset($_SESSION['id_user']) || $_SESSION['role'] !== 'Administrator') {
    // Jika bukan admin, tendang keluar! Ini pencegahan IDOR sejati.
    header("location:dashboard.php");
    exit();
}

// 2. AMBIL ID DARI URL (Aman karena sudah divalidasi admin di atas)
$target_id = $_GET['id'] ?? 0;
$pesan_sukses = '';

if (isset($_POST['update'])) {
    $nama_lengkap = htmlspecialchars(trim($_POST['nama_lengkap']));
    $role         = htmlspecialchars(trim($_POST['role']));
    $jabatan      = htmlspecialchars(trim($_POST['jabatan']));
    $penempatan   = htmlspecialchars(trim($_POST['penempatan']));

    $update_stmt = $conn->prepare("UPDATE users SET nama_lengkap = ?, role = ?, jabatan = ?, penempatan = ? WHERE id_user = ?");
    $update_stmt->bind_param("ssssi", $nama_lengkap, $role, $jabatan, $penempatan, $target_id);
    
    if ($update_stmt->execute()) {
        $pesan_sukses = "Employee record updated successfully.";
    }
}

// Ambil data karyawan yang mau diedit
$stmt = $conn->prepare("SELECT * FROM users WHERE id_user = ?");
$stmt->bind_param("i", $target_id);
$stmt->execute();
$target_data = $stmt->get_result()->fetch_assoc();

if (!$target_data) {
    die("Employee not found.");
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Employee - CyberNusa HRIS</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    <style>
        * { box-sizing: border-box; font-family: 'Inter', sans-serif; margin: 0; padding: 0; }
        body { background-color: #f8fafc; color: #0f172a; display: flex; justify-content: center; align-items: center; min-height: 100vh; padding: 20px; }
        .form-container { background: #ffffff; border: 1px solid #e2e8f0; border-radius: 8px; padding: 32px; width: 100%; max-width: 500px; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1); border-top: 4px solid #2563eb; }
        .form-header { margin-bottom: 24px; border-bottom: 1px solid #e2e8f0; padding-bottom: 16px; }
        .form-header h2 { font-size: 18px; font-weight: 600; color: #0f172a; margin-bottom: 4px; }
        .form-header p { font-size: 13px; color: #64748b; }
        .alert-success { background: #f0fdf4; border: 1px solid #bbf7d0; color: #166534; padding: 12px; border-radius: 6px; font-size: 13px; margin-bottom: 24px; }
        .form-group { margin-bottom: 16px; }
        .form-group label { display: block; font-size: 13px; font-weight: 500; color: #475569; margin-bottom: 6px; }
        .form-group input, .form-group select { width: 100%; padding: 10px 12px; border: 1px solid #cbd5e1; border-radius: 6px; font-size: 14px; color: #0f172a; outline: none; }
        .form-group input:focus, .form-group select:focus { border-color: #2563eb; box-shadow: 0 0 0 3px rgba(37,99,235,0.1); }
        .form-actions { display: flex; gap: 12px; margin-top: 32px; }
        .btn { padding: 10px 16px; border-radius: 6px; font-size: 13px; font-weight: 500; text-align: center; text-decoration: none; cursor: pointer; flex: 1; }
        .btn-cancel { background: #f1f5f9; color: #475569; border: 1px solid #cbd5e1; }
        .btn-submit { background: #2563eb; color: #ffffff; border: none; }
        .btn-submit:hover { background: #1d4ed8; }
    </style>
</head>
<body>

<div class="form-container">
    <div class="form-header">
        <h2>Administrator Management</h2>
        <p>Editing record for: <strong><?= htmlspecialchars($target_data['username']); ?></strong></p>
    </div>

    <?php if ($pesan_sukses): ?>
        <div class="alert-success"><?= $pesan_sukses; ?></div>
    <?php endif; ?>

    <form method="POST" action="">
        <div class="form-group">
            <label>Full Name</label>
            <input type="text" name="nama_lengkap" value="<?= htmlspecialchars($target_data['nama_lengkap']); ?>" required>
        </div>
        <div class="form-group">
            <label>Department Role</label>
            <select name="role" required>
                <option value="Administrator" <?= $target_data['role'] == 'Administrator' ? 'selected' : '' ?>>Administrator</option>
                <option value="Management" <?= $target_data['role'] == 'Management' ? 'selected' : '' ?>>Management</option>
                <option value="Employee" <?= $target_data['role'] == 'Employee' ? 'selected' : '' ?>>Employee</option>
            </select>
        </div>
        <div class="form-group">
            <label>Position / Job Title</label>
            <input type="text" name="jabatan" value="<?= htmlspecialchars($target_data['jabatan']); ?>" required>
        </div>
        <div class="form-group">
            <label>Branch Assignment</label>
            <input type="text" name="penempatan" value="<?= htmlspecialchars($target_data['penempatan']); ?>" required>
        </div>
        <div class="form-actions">
            <a href="dashboard.php" class="btn btn-cancel">Back to Dashboard</a>
            <button type="submit" name="update" class="btn btn-submit">Update Record</button>
        </div>
    </form>
</div>

</body>
</html>