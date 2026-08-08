<?php
session_start();
require 'config.php';

if (!isset($_SESSION['id_user'])) {
    header("location:index.php");
    exit();
}

$session_user_id = $_SESSION['id_user'];
$session_role = $_SESSION['role'];

// Query Detail Profil Pribadi
$stmt = $conn->prepare("SELECT * FROM users WHERE id_user = ?");
$stmt->bind_param("i", $session_user_id);
$stmt->execute();
$user_data = $stmt->get_result()->fetch_assoc();

$all_users = null;
$total_karyawan = 0;
$total_gaji_perusahaan = 0;

if ($session_role === 'Administrator' || $session_role === 'Management') {
    // Analytics
    $stmt_count = $conn->query("SELECT COUNT(id_user) as total FROM users");
    $total_karyawan = $stmt_count->fetch_assoc()['total'];

    $stmt_sum = $conn->query("SELECT SUM(total_gaji) as grand_total FROM penggajian");
    $total_gaji_perusahaan = $stmt_sum->fetch_assoc()['grand_total'] ?? 0;

    // DIRECTORY QUERY: Diperbarui dengan LEFT JOIN untuk cek absensi HARI INI
    $query_dir = "
        SELECT u.id_user, u.username, u.nama_lengkap, u.role, u.jabatan, u.penempatan, 
               a.status_kehadiran, a.waktu_masuk
        FROM users u
        LEFT JOIN absensi a ON u.id_user = a.id_user AND a.tanggal = CURDATE()
    ";
    $stmt_all = $conn->prepare($query_dir);
    $stmt_all->execute();
    $all_users = $stmt_all->get_result();
} else {
    // Data Karyawan Biasa
    $stmt_gaji = $conn->prepare("SELECT * FROM penggajian WHERE id_user = ? ORDER BY id_gaji DESC LIMIT 1");
    $stmt_gaji->bind_param("i", $session_user_id);
    $stmt_gaji->execute();
    $gaji_data = $stmt_gaji->get_result()->fetch_assoc();

    $stmt_absen = $conn->prepare("SELECT * FROM absensi WHERE id_user = ? ORDER BY tanggal DESC LIMIT 5");
    $stmt_absen->bind_param("i", $session_user_id);
    $stmt_absen->execute();
    $absen_result = $stmt_absen->get_result();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - CyberNusa HRIS</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * { box-sizing: border-box; font-family: 'Inter', sans-serif; margin: 0; padding: 0; }
        body { background-color: #f8fafc; color: #0f172a; }
        .topbar { background: #ffffff; border-bottom: 1px solid #e2e8f0; padding: 16px 40px; display: flex; justify-content: space-between; align-items: center; position: sticky; top: 0; z-index: 100; }
        .topbar-brand { font-weight: 700; font-size: 18px; color: #0f172a; }
        .topbar-nav { display: flex; align-items: center; gap: 24px; }
        .user-info { font-size: 14px; color: #475569; }
        .btn-logout { text-decoration: none; color: #ef4444; font-size: 13px; font-weight: 600; padding: 6px 12px; border: 1px solid #fecaca; border-radius: 6px; background: #fef2f2; }
        .content { max-width: 1200px; margin: 40px auto; padding: 0 24px; }
        .page-header { margin-bottom: 32px; }
        .page-header h1 { font-size: 24px; font-weight: 600; }
        
        .analytics-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 24px; margin-bottom: 32px; }
        .stat-card { background: #ffffff; padding: 24px; border-radius: 12px; border: 1px solid #e2e8f0; }
        .stat-card h3 { font-size: 13px; color: #64748b; font-weight: 600; text-transform: uppercase; margin-bottom: 8px; }
        .stat-card .value { font-size: 28px; font-weight: 700; color: #0f172a; }
        
        .card { background: #ffffff; border: 1px solid #e2e8f0; border-radius: 12px; padding: 24px; margin-bottom: 24px; }
        .card-title { font-size: 15px; font-weight: 600; margin-bottom: 16px; border-bottom: 1px solid #f1f5f9; padding-bottom: 12px; }
        
        .table-responsive { width: 100%; border-collapse: collapse; }
        .table-responsive th, .table-responsive td { padding: 14px 12px; text-align: left; border-bottom: 1px solid #e2e8f0; font-size: 14px; }
        .table-responsive th { font-weight: 600; color: #475569; background: #f8fafc; font-size: 13px; text-transform: uppercase; }
        .btn-admin { background: #0f172a; color: white; padding: 6px 16px; border-radius: 6px; text-decoration: none; font-size: 12px; font-weight: 500; }
        
        .badge-status { padding: 4px 10px; border-radius: 20px; font-size: 11px; font-weight: 600; }
        .status-hadir { background: #dcfce7; color: #166534; }
        .status-izin { background: #fef3c7; color: #92400e; }
        .status-alpa { background: #fee2e2; color: #991b1b; }
        .status-none { background: #f1f5f9; color: #64748b; }
        
        .data-list { list-style: none; }
        .data-list li { margin-bottom: 16px; border-bottom: 1px solid #f1f5f9; padding-bottom: 12px; }
        .data-label { display: block; font-size: 12px; color: #64748b; margin-bottom: 4px; }
        .data-value { font-size: 15px; font-weight: 500; }
        .grid-emp { display: grid; grid-template-columns: repeat(2, 1fr); gap: 24px; }
    </style>
</head>
<body>

<div class="topbar">
    <div class="topbar-brand">CyberNusa HRIS</div>
    <div class="topbar-nav">
        <span class="user-info">Signed in as <strong><?= htmlspecialchars($user_data['nama_lengkap']); ?></strong></span>
        <a href="logout.php" class="btn-logout">Sign Out</a>
    </div>
</div>

<div class="content">
    <div class="page-header">
        <h1><?= ($session_role === 'Administrator' || $session_role === 'Management') ? 'Management Dashboard' : 'Employee Portal' ?></h1>
    </div>

    <?php if ($session_role === 'Administrator' || $session_role === 'Management'): ?>
    
    <div class="analytics-grid">
        <div class="stat-card"><h3>Total Employees</h3><div class="value"><?= $total_karyawan; ?></div></div>
        <div class="stat-card"><h3>Total Payroll Payload</h3><div class="value" style="color:#16a34a;">Rp <?= number_format($total_gaji_perusahaan, 0, ',', '.'); ?></div></div>
        <div class="stat-card"><h3>System Status</h3><div class="value" style="color:#2563eb; font-size: 20px; margin-top:5px;">Secure</div></div>
    </div>

    <div class="card">
        <div class="card-title">Employee Directory & Live Status</div>
        <table class="table-responsive">
            <thead>
                <tr>
                    <th>Emp. ID</th>
                    <th>Name</th>
                    <th>Location</th>
                    <th>Today's Status</th>
                    <th style="text-align: right;">Action</th>
                </tr>
            </thead>
            <tbody>
                <?php while($row = $all_users->fetch_assoc()): ?>
                <tr>
                    <td style="font-weight: 600; color: #64748b;">USR-<?= sprintf('%03d', $row['id_user']); ?></td>
                    <td style="font-weight: 500;"><?= htmlspecialchars($row['nama_lengkap']); ?><br><span style="font-size: 11px; color: #94a3b8; font-weight: 400;"><?= htmlspecialchars($row['jabatan']); ?></span></td>
                    <td><?= htmlspecialchars($row['penempatan']); ?></td>
                    <td>
                        <?php 
                            if ($row['status_kehadiran'] == 'Hadir') {
                                echo "<span class='badge-status status-hadir'>Hadir (".$row['waktu_masuk'].")</span>";
                            } elseif ($row['status_kehadiran'] == 'Sakit' || $row['status_kehadiran'] == 'Izin') {
                                echo "<span class='badge-status status-izin'>".$row['status_kehadiran']."</span>";
                            } elseif ($row['status_kehadiran'] == 'Alpa') {
                                echo "<span class='badge-status status-alpa'>Alpa</span>";
                            } else {
                                echo "<span class='badge-status status-none'>Belum Absen</span>";
                            }
                        ?>
                    </td>
                    <td style="text-align: right;">
                        <a href="admin_manage.php?id=<?= $row['id_user']; ?>" class="btn-admin">Manage</a>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>

    <?php else: ?>
    <!-- TAMPILAN KARYAWAN BIASA -->
    <div class="grid-emp">
        <div class="card">
            <div class="card-title">My Employment Details</div>
            <ul class="data-list">
                <li><span class="data-label">Full Name</span><span class="data-value"><?= htmlspecialchars($user_data['nama_lengkap']); ?></span></li>
                <li><span class="data-label">Position / Job Title</span><span class="data-value"><?= htmlspecialchars($user_data['jabatan']); ?></span></li>
                <li><span class="data-label">Department Role</span><span class="data-value"><?= htmlspecialchars($user_data['role']); ?></span></li>
            </ul>
        </div>
        <div class="card">
            <div class="card-title">My Latest Payroll</div>
            <?php if(isset($gaji_data) && $gaji_data): ?>
            <ul class="data-list">
                <li><span class="data-label">Base Salary</span><span class="data-value">Rp <?= number_format($gaji_data['gaji_pokok'], 0, ',', '.'); ?></span></li>
                <li><span class="data-label">Net Pay</span><span class="data-value" style="font-size: 20px; font-weight: 700; color: #16a34a;">Rp <?= number_format($gaji_data['total_gaji'], 0, ',', '.'); ?></span></li>
            </ul>
            <?php endif; ?>
        </div>
    </div>
    <?php endif; ?>

</div>

</body>
</html>