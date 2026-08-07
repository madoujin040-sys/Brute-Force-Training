<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | CyberNusa HRIS</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .bg-login {
            background: url('https://images.unsplash.com/photo-1497366216548-37526070297c?auto=format&fit=crop&q=80') center/cover;
        }
    </style>
</head>
<body class="d-flex align-items-center vh-100 bg-light">
    <div class="container">
        <div class="row shadow-lg rounded-4 overflow-hidden bg-white mx-auto" style="max-width: 900px;">
            <div class="col-md-6 d-none d-md-block bg-login"></div>
            <div class="col-md-6 p-5 d-flex flex-column justify-content-center">
                <div class="text-center mb-4">
                    <h2 class="fw-bold text-primary">CyberNusa HRIS</h2>
                    <p class="text-muted">Enterprise Resource & Attendance Portal</p>
                </div>
                
                <?php if (isset($_GET['pesan']) && $_GET['pesan'] == 'gagal'): ?>
                    <div class="alert alert-danger text-center shadow-sm">
                        Autentikasi Gagal! Kredensial tidak valid.
                    </div>
                <?php endif; ?>

                <form action="login.php" method="POST">
                    <div class="mb-3">
                        <label class="form-label fw-semibold text-secondary">Username / ID Karyawan</label>
                        <input type="text" name="username" class="form-control form-control-lg bg-light border-0" placeholder="username" required>
                    </div>
                    <div class="mb-4">
                        <label class="form-label fw-semibold text-secondary">Password</label>
                        <input type="password" name="password" class="form-control form-control-lg bg-light border-0" placeholder="********" required>
                    </div>
                    <button type="submit" class="btn btn-primary btn-lg w-100 fw-bold shadow-sm">Sign In</button>
                </form>
                <div class="text-center mt-5 text-muted small">
                    © 2026 PT CyberNusa Tech. All rights reserved.
                </div>
            </div>
        </div>
    </div>
</body>
</html>