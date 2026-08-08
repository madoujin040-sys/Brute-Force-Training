<?php
session_start();
require 'config.php';

if (!isset($_SESSION['id_user']) || ($_SESSION['role'] !== 'Administrator' && $_SESSION['role'] !== 'Management')) {
    header("location:dashboard.php");
    exit();
}

$target_id = $_GET['id'] ?? 0;
$pesan = '';

if (isset($_POST['update_profile'])) {
    $nama_lengkap = htmlspecialchars(trim($_POST['nama_lengkap']));
    $role         = htmlspecialchars(trim($_POST['role']));
    $jabatan      = htmlspecialchars(trim($_POST['jabatan']));
    $penempatan   = htmlspecialchars(trim($_POST['penempatan']));
    $stmt = $conn->prepare("UPDATE users SET nama_lengkap=?, role=?, jabatan=?, penempatan=? WHERE id_user=?");
    $stmt->bind_param("ssssi", $nama_lengkap, $role, $jabatan, $penempatan, $target_id);
    if ($stmt->execute()) $pesan = "<div class='alert-success'>Personnel record updated successfully.</div>";
}

if (isset($_POST['update_payroll'])) {
    $periode    = htmlspecialchars(trim($_POST['periode']));
    $gaji_pokok = (float)$_POST['gaji_pokok'];
    $tunjangan  = (float)$_POST['tunjangan'];
    $potongan   = (float)$_POST['potongan'];
    $cek = $conn->query("SELECT id_gaji FROM penggajian WHERE id_user = $target_id AND periode = '$periode'");
    if ($cek->num_rows > 0) {
        $stmt = $conn->prepare("UPDATE penggajian SET gaji_pokok=?, tunjangan=?, potongan=? WHERE id_user=? AND periode=?");
        $stmt->bind_param("dddis", $gaji_pokok, $tunjangan, $potongan, $target_id, $periode);
    } else {
        $stmt = $conn->prepare("INSERT INTO penggajian (id_user, periode, gaji_pokok, tunjangan, potongan) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("isddd", $target_id, $periode, $gaji_pokok, $tunjangan, $potongan);
    }
    if ($stmt->execute()) $pesan = "<div class='alert-success'>Payroll configuration applied successfully.</div>";
}

if (isset($_POST['add_attendance'])) {
    $tanggal = $_POST['tanggal'];
    $waktu_masuk = $_POST['waktu_masuk'] ?: NULL;
    $waktu_keluar = $_POST['waktu_keluar'] ?: NULL;
    $status = $_POST['status'];
    $stmt = $conn->prepare("INSERT INTO absensi (id_user, tanggal, waktu_masuk, waktu_keluar, status_kehadiran) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("issss", $target_id, $tanggal, $waktu_masuk, $waktu_keluar, $status);
    if ($stmt->execute()) $pesan = "<div class='alert-success'>Attendance log recorded successfully.</div>";
}

$stmt_target = $conn->prepare("SELECT * FROM users WHERE id_user = ?");
$stmt_target->bind_param("i", $target_id);
$stmt_target->execute();
$target_data = $stmt_target->get_result()->fetch_assoc();
if (!$target_data) die("Target not found.");

$stmt_gaji = $conn->query("SELECT * FROM penggajian WHERE id_user = $target_id ORDER BY id_gaji DESC LIMIT 1");
$gaji_data = $stmt_gaji->fetch_assoc();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Employee - CyberNusa</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    <style>
        * { box-sizing: border-box; font-family: 'Inter', sans-serif; margin: 0; padding: 0; }
        body { background-color: #f1f5f9; color: #0f172a; padding: 40px 20px; display: flex; justify-content: center; }
        .container { width: 100%; max-width: 600px; }
        
        .header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px; }
        .header h1 { font-size: 20px; font-weight: 600; }
        .btn-back { background: transparent; border: 1px solid #cbd5e1; padding: 6px 12px; border-radius: 6px; text-decoration: none; color: #475569; font-size: 13px; font-weight: 500; }
        
        .alert-success { background: #f0fdf4; border: 1px solid #bbf7d0; color: #166534; padding: 12px; border-radius: 8px; font-size: 13px; margin-bottom: 24px; }
        
        .card { background: #ffffff; border-radius: 12px; border: 1px solid #e2e8f0; padding: 32px; box-shadow: 0 1px 3px rgba(0,0,0,0.02); margin-bottom: 24px; }
        .section-title { font-size: 14px; font-weight: 600; color: #475569; margin-bottom: 16px; text-transform: uppercase; letter-spacing: 0.5px; border-bottom: 1px solid #f1f5f9; padding-bottom: 8px; }
        
        .form-group { margin-bottom: 16px; }
        .form-group label { display: block; font-size: 12px; font-weight: 500; color: #64748b; margin-bottom: 6px; }
        .form-group input, .form-group select { width: 100%; padding: 10px 12px; border: 1px solid #cbd5e1; border-radius: 6px; font-size: 14px; outline: none; }
        .form-group input:focus, .form-group select:focus { border-color: #2563eb; }
        
        .grid-2 { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; }
        
        .btn-submit { background: #0f172a; color: white; border: none; padding: 10px 16px; border-radius: 6px; font-size: 13px; font-weight: 500; cursor: pointer; transition: 0.2s; margin-top: 8px; }
        .btn-submit:hover { background: #1e293b; }
    </style>
</head>
<body>

<div class="container">
    <div class="header">
        <h1>Managing: <?= htmlspecialchars($target_data['nama_lengkap']); ?></h1>
        <a href="dashboard.php" class="btn-back">Close</a>
    </div>

    <?= $pesan; ?>

    <!-- PROFILE SECTION -->
    <div class="card">
        <div class="section-title">1. Personnel Profile</div>
        <form method="POST">
            <div class="form-group">
                <label>Full Name</label>
                <input type="text" name="nama_lengkap" value="<?= htmlspecialchars($target_data['nama_lengkap']); ?>" required>
            </div>
            <div class="grid-2">
                <div class="form-group">
                    <label>System Role</label>
                    <select name="role" required>
                        <option value="Administrator" <?= $target_data['role'] == 'Administrator' ? 'selected' : '' ?>>Administrator</option>
                        <option value="Management" <?= $target_data['role'] == 'Management' ? 'selected' : '' ?>>Management</option>
                        <option value="Employee" <?= $target_data['role'] == 'Employee' ? 'selected' : '' ?>>Employee</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Location</label>
                    <input type="text" name="penempatan" value="<?= htmlspecialchars($target_data['penempatan']); ?>" required>
                </div>
            </div>
            <div class="form-group">
                <label>Job Title</label>
                <input type="text" name="jabatan" value="<?= htmlspecialchars($target_data['jabatan']); ?>" required>
            </div>
            <button type="submit" name="update_profile" class="btn-submit">Save Profile Updates</button>
        </form>
    </div>

    <!-- PAYROLL SECTION -->
    <div class="card">
        <div class="section-title">2. Payroll Settings</div>
        <form method="POST">
            <div class="form-group">
                <label>Active Period</label>
                <input type="text" name="periode" value="<?= $gaji_data ? htmlspecialchars($gaji_data['periode']) : date('F Y'); ?>" required>
            </div>
            <div class="grid-2">
                <div class="form-group">
                    <label>Base Salary (Rp)</label>
                    <input type="number" name="gaji_pokok" value="<?= $gaji_data ? $gaji_data['gaji_pokok'] : 0; ?>" required>
                </div>
                <div class="form-group">
                    <label>Allowances (Rp)</label>
                    <input type="number" name="tunjangan" value="<?= $gaji_data ? $gaji_data['tunjangan'] : 0; ?>">
                </div>
            </div>
            <div class="form-group">
                <label>Deductions (Rp)</label>
                <input type="number" name="potongan" value="<?= $gaji_data ? $gaji_data['potongan'] : 0; ?>">
            </div>
            <button type="submit" name="update_payroll" class="btn-submit">Update Payroll Data</button>
        </form>
    </div>

    <!-- ATTENDANCE SECTION -->
    <div class="card">
        <div class="section-title">3. Add Attendance Log</div>
        <form method="POST">
            <div class="grid-2">
                <div class="form-group">
                    <label>Date</label>
                    <input type="date" name="tanggal" required>
                </div>
                <div class="form-group">
                    <label>Status</label>
                    <select name="status" required>
                        <option value="Hadir">Hadir</option>
                        <option value="Izin">Izin</option>
                        <option value="Sakit">Sakit</option>
                        <option value="Alpa">Alpa</option>
                    </select>
                </div>
            </div>
            <div class="grid-2">
                <div class="form-group">
                    <label>Time In</label>
                    <input type="time" name="waktu_masuk">
                </div>
                <div class="form-group">
                    <label>Time Out</label>
                    <input type="time" name="waktu_keluar">
                </div>
            </div>
            <button type="submit" name="add_attendance" class="btn-submit">Submit Manual Log</button>
        </form>
    </div>

</div>

</body>
</html>