<?php
session_start();
require 'config.php';

if(!isset($_SESSION['id_user'])){ header("location:index.php"); exit; }

$id_user = $_SESSION['id_user'];
$role = $_SESSION['role'];

// LOGIKA MANIPULASI ABSENSI
if(isset($_POST['ubah_absen'])){
    $id_absen_ubah = $_POST['id_absen'];
    $status_baru = $_POST['status_baru'];
    mysqli_query($conn, "UPDATE absensi SET status='$status_baru' WHERE id_absen='$id_absen_ubah'");
    echo "<script>alert('Status Absensi Berhasil Dirubah!'); window.location='dashboard.php';</script>";
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Dashboard | CyberNusa HRIS</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<nav class="navbar navbar-expand-lg navbar-dark bg-dark mb-4 shadow-sm">
    <div class="container">
        <a class="navbar-brand fw-bold text-primary" href="#"><span class="text-white">CyberNusa</span> HRIS</a>
        <div class="d-flex align-items-center text-white">
            <span class="me-4 d-none d-md-block">Halo, <strong><?= $_SESSION['nama_lengkap'] ?></strong> <span class="badge bg-danger ms-1"><?= $_SESSION['jabatan'] ?></span></span>
            <a href="logout.php" class="btn btn-danger btn-sm fw-bold px-3">Logout</a>
        </div>
    </div>
</nav>

<div class="container pb-5">
    <?php if($role == 'admin'): ?>
        
        <h3 class="mb-4 fw-bold text-secondary">Admin Executive Dashboard <span class="badge bg-danger">Full Access</span></h3>
        <p class="text-muted">Akses level administrator didapatkan. Sistem terbuka penuh.</p>
        
        <div class="row">
            <!-- PENCURIAN & MANIPULASI DATA GAJI -->
            <div class="col-md-12 mb-4">
                <div class="card shadow-sm border-0 rounded-4 overflow-hidden">
                    <div class="card-header bg-danger text-white fw-bold py-3">Master Data & Gaji Karyawan (Data Sensitif)</div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-striped mb-0 align-middle">
                                <thead class="table-dark">
                                    <tr>
                                        <th class="ps-4">ID</th>
                                        <th>Nama Lengkap</th>
                                        <th>Jabatan</th>
                                        <th>Penempatan</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $q_users = mysqli_query($conn, "SELECT * FROM users WHERE role='karyawan' ORDER BY nama_lengkap ASC");
                                    while($row = mysqli_fetch_assoc($q_users)){
                                        echo "<tr>
                                                <td class='ps-4 fw-bold text-secondary'>EMP-100{$row['id_user']}</td>
                                                <td class='fw-semibold'>{$row['nama_lengkap']}</td>
                                                <td>{$row['jabatan']}</td>
                                                <td>{$row['penempatan']}</td>
                                                <td>
                                                    <a href='edit_karyawan.php?id={$row['id_user']}' class='btn btn-sm btn-primary fw-bold'>Detail Karyawan</a>
                                                </td>
                                              </tr>";
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- MANIPULASI OPERASIONAL (ABSENSI) -->
            <div class="col-md-12">
                <div class="card shadow-sm border-0 rounded-4 overflow-hidden">
                    <div class="card-header bg-warning text-dark fw-bold py-3">Manipulasi Log Absensi Karyawan</div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover mb-0 align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th class="ps-4">Tanggal</th>
                                        <th>Karyawan</th>
                                        <th>Jam Masuk</th>
                                        <th>Status Saat Ini</th>
                                        <th>Ubah Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $q_absen = mysqli_query($conn, "SELECT absensi.*, users.nama_lengkap FROM absensi JOIN users ON absensi.id_user = users.id_user ORDER BY absensi.tanggal DESC LIMIT 10");
                                    while($row = mysqli_fetch_assoc($q_absen)){
                                        $badge = 'success';
                                        if($row['status'] == 'Terlambat') $badge = 'danger';
                                        if($row['status'] == 'Izin') $badge = 'info';
                                        
                                        echo "<tr>
                                                <td class='ps-4'>{$row['tanggal']}</td>
                                                <td class='fw-bold'>{$row['nama_lengkap']}</td>
                                                <td>{$row['jam_masuk']}</td>
                                                <td><span class='badge bg-{$badge}'>{$row['status']}</span></td>
                                                <td>
                                                    <form method='POST' class='d-flex gap-2'>
                                                        <input type='hidden' name='id_absen' value='{$row['id_absen']}'>
                                                        <select name='status_baru' class='form-select form-select-sm'>
                                                            <option value='Hadir' ".($row['status']=='Hadir'?'selected':'').">Hadir</option>
                                                            <option value='Terlambat' ".($row['status']=='Terlambat'?'selected':'').">Terlambat</option>
                                                            <option value='Izin' ".($row['status']=='Izin'?'selected':'').">Izin</option>
                                                        </select>
                                                        <button type='submit' name='ubah_absen' class='btn btn-sm btn-warning fw-bold'>Ubah</button>
                                                    </form>
                                                </td>
                                              </tr>";
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    <?php else: ?>
        
        <!-- TAMPILAN KARYAWAN BIASA -->
        <h3 class="mb-4 fw-bold text-secondary">My Portal</h3>
        <div class="row">
            <div class="col-md-8 mb-4">
                <div class="card shadow-sm border-0 rounded-4 h-100">
                    <div class="card-header bg-white fw-bold d-flex justify-content-between align-items-center py-3">
                        Riwayat Absensi Harian
                    </div>
                    <div class="card-body p-0">
                        <table class="table table-hover mb-0 align-middle">
                            <thead class="table-light">
                                <tr><th class="ps-4">Tanggal</th><th>Jam Masuk</th><th>Lokasi Tercatat</th><th>Status</th></tr>
                            </thead>
                            <tbody>
                                <?php
                                $q_absen = mysqli_query($conn, "SELECT * FROM absensi WHERE id_user='$id_user' ORDER BY tanggal DESC");
                                while($row = mysqli_fetch_assoc($q_absen)){
                                    $badge = 'success';
                                    if($row['status'] == 'Terlambat') $badge = 'warning';
                                    if($row['status'] == 'Izin') $badge = 'info';
                                    
                                    echo "<tr>
                                            <td class='ps-4'>{$row['tanggal']}</td>
                                            <td class='fw-bold'>{$row['jam_masuk']}</td>
                                            <td><small class='text-muted'>{$row['lokasi']}</small></td>
                                            <td><span class='badge bg-{$badge}'>{$row['status']}</span></td>
                                          </tr>";
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            
            <div class="col-md-4 mb-4">
                <div class="card shadow-sm border-0 bg-dark text-white rounded-4 h-100">
                    <div class="card-header border-0 fw-bold py-3 text-white-50">Informasi Gaji Terakhir</div>
                    <div class="card-body px-4">
                        <?php
                        $q_gaji = mysqli_query($conn, "SELECT * FROM gaji WHERE id_user='$id_user' ORDER BY id_gaji DESC LIMIT 1");
                        if($gaji = mysqli_fetch_assoc($q_gaji)):
                            $total = $gaji['gaji_pokok'] + $gaji['tunjangan'];
                        ?>
                            <p class="mb-1">Periode: <strong><?= $gaji['periode'] ?></strong></p>
                            <h2 class="text-success fw-bold mb-4">Rp <?= number_format($total,0,',','.') ?></h2>
                            <hr class="border-secondary mb-4">
                            <div class="d-flex justify-content-between small mb-2">
                                <span class="text-white-50">Gaji Pokok:</span>
                                <span>Rp <?= number_format($gaji['gaji_pokok'],0,',','.') ?></span>
                            </div>
                            <div class="d-flex justify-content-between small mb-4">
                                <span class="text-white-50">Tunjangan:</span>
                                <span>Rp <?= number_format($gaji['tunjangan'],0,',','.') ?></span>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

    <?php endif; ?>
</div>

</body>
</html>