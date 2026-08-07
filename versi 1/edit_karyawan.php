<?php
session_start();
require 'config.php';

if(!isset($_SESSION['id_user']) || $_SESSION['role'] != 'admin'){ 
    header("location:index.php"); 
    exit; 
}

$id_target = $_GET['id'];

// LOGIKA MANIPULASI GAJI
if(isset($_POST['update_gaji'])){
    $gaji_pokok_baru = $_POST['gaji_pokok'];
    $tunjangan_baru = $_POST['tunjangan'];
    
    mysqli_query($conn, "UPDATE gaji SET gaji_pokok='$gaji_pokok_baru', tunjangan='$tunjangan_baru' WHERE id_user='$id_target'");
    echo "<script>alert('Data Gaji Berhasil Dimanipulasi!'); window.location='edit_karyawan.php?id=$id_target';</script>";
}

$query_user = mysqli_query($conn, "SELECT * FROM users WHERE id_user='$id_target'");
$data_user = mysqli_fetch_assoc($query_user);

$query_gaji = mysqli_query($conn, "SELECT * FROM gaji WHERE id_user='$id_target'");
$data_gaji = mysqli_fetch_assoc($query_gaji);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Manipulasi Data Karyawan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-5" style="max-width: 800px;">
    <a href="dashboard.php" class="btn btn-secondary btn-sm mb-3 fw-bold">← Kembali ke Dashboard</a>
    
    <div class="card shadow-lg border-0 rounded-4">
        <div class="card-header bg-danger text-white py-3 text-center">
            <h4 class="mb-0 fw-bold">Informasi Karyawan</h4>
            <small>Data & Gaji Karyawan</small>
        </div>
        <div class="card-body p-4">
            
            <h5 class="fw-bold border-bottom pb-2 mb-3">Informasi Karyawan</h5>
            <div class="row mb-4">
                <div class="col-md-6">
                    <p class="mb-1 text-muted">Nama Lengkap</p>
                    <h5 class="fw-bold"><?= $data_user['nama_lengkap'] ?></h5>
                </div>
                <div class="col-md-6">
                    <p class="mb-1 text-muted">Jabatan / Penempatan</p>
                    <h5 class="fw-bold"><?= $data_user['jabatan'] ?> (<?= $data_user['penempatan'] ?>)</h5>
                </div>
            </div>

            <h5 class="fw-bold border-bottom pb-2 mb-3 text-danger">Detail Gaji</h5>
            <form method="POST">
                <?php if($data_gaji): ?>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Gaji Pokok (Rp)</label>
                        <input type="number" name="gaji_pokok" class="form-control form-control-lg bg-light" value="<?= $data_gaji['gaji_pokok'] ?>" required>
                    </div>
                    <div class="mb-4">
                        <label class="form-label fw-bold">Tunjangan (Rp)</label>
                        <input type="number" name="tunjangan" class="form-control form-control-lg bg-light" value="<?= $data_gaji['tunjangan'] ?>" required>
                    </div>
                    <div class="d-grid gap-2">
                        <button type="submit" name="update_gaji" class="btn btn-danger btn-lg fw-bold">Simpan Perubahan</button>
                    </div>
                <?php else: ?>
                    <div class="alert alert-warning">Data gaji untuk karyawan ini belum ada di database.</div>
                <?php endif; ?>
            </form>

        </div>
    </div>
</div>

</body>
</html>