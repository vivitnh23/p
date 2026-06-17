<?php
require_once '../includes/config.php';
$pageTitle = 'Masuk';
if (isLoggedIn()) redirect('/');

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = sanitize($_POST['email'] ?? '');
    $pass  = $_POST['password'] ?? '';

    if (!$email || !$pass) {
        $error = 'Email dan password wajib diisi.';
    } else {
        $e = $conn->real_escape_string($email);
        $user = $conn->query("SELECT * FROM users WHERE email = '$e'")->fetch_assoc();
        if ($user && password_verify($pass, $user['password'])) {
            $_SESSION['user_id']    = $user['id'];
            $_SESSION['user_name']  = $user['name'];
            $_SESSION['user_email'] = $user['email'];
            $_SESSION['user_role']  = $user['role'];
            if ($user['role'] === 'admin') redirect('/admin/');
            redirect('/');
        } else {
            $error = 'Email atau password salah.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Masuk — Triascarf</title>
    <link rel="icon" type="image/svg+xml" href="<?= BASE_URL ?>/assets/favicon.svg">
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,300;0,400;0,600;1,300;1,400&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/style.css">
</head>
<body>
<div class="auth-wrapper">
    <div class="auth-visual">
        <div class="auth-visual-logo">
            <span class="logo-script">Trias</span><span class="logo-bold">carf</span>
        </div>
        <h2>Selamat Datang Kembali</h2>
        <p>Masuk untuk menikmati pengalaman belanja hijab yang lebih personal dan mudah.</p>
        <div style="margin-top:48px;padding:24px;background:rgba(255,255,255,.05);border-radius:16px;max-width:280px;text-align:center">
            <div style="color:var(--rose-gold);font-size:13px;letter-spacing:.1em;text-transform:uppercase;margin-bottom:16px">Keunggulan Member</div>
            <div style="color:rgba(255,255,255,.7);font-size:14px;line-height:2">
                ✦ Riwayat pesanan tersimpan<br>
                ✦ Proses checkout lebih cepat<br>
                ✦ Info promo eksklusif
            </div>
        </div>
    </div>

    <div class="auth-form-side">
        <div class="auth-form-box">
            <h2>Masuk Akun</h2>
            <p>Belum punya akun? <a href="<?= BASE_URL ?>/pages/register.php">Daftar sekarang</a></p>

            <?php if ($error): ?>
            <div class="alert alert-error"><i class="fas fa-exclamation-circle"></i> <?= $error ?></div>
            <?php endif; ?>

            <form method="POST">
                <div class="form-group">
                    <label>Alamat Email</label>
                    <input type="email" name="email" class="form-control" value="<?= isset($_POST['email']) ? htmlspecialchars($_POST['email']) : '' ?>" placeholder="email@kamu.com" required autofocus>
                </div>
                <div class="form-group">
                    <label>Password</label>
                    <div style="position:relative">
                        <input type="password" name="password" id="passInput" class="form-control" placeholder="••••••••" required style="padding-right:44px">
                        <button type="button" onclick="togglePass()" style="position:absolute;right:14px;top:50%;transform:translateY(-50%);background:none;border:none;cursor:pointer;color:var(--muted)">
                            <i class="fas fa-eye" id="passIcon"></i>
                        </button>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary btn-block" style="margin-top:8px">Masuk <i class="fas fa-arrow-right"></i></button>
            </form>

            <div class="auth-divider">atau lanjut sebagai</div>
            <a href="<?= BASE_URL ?>/" class="btn btn-outline btn-block">
                <i class="fas fa-shopping-bag"></i> Tamu (tanpa login)
            </a>

            <div class="auth-footer-link" style="margin-top:28px">
                <a href="<?= BASE_URL ?>"><i class="fas fa-arrow-left"></i> Kembali ke Beranda</a>
            </div>
        </div>
    </div>
</div>
<script>
function togglePass() {
    const i = document.getElementById('passInput');
    const ic = document.getElementById('passIcon');
    i.type = i.type === 'password' ? 'text' : 'password';
    ic.className = i.type === 'password' ? 'fas fa-eye' : 'fas fa-eye-slash';
}
</script>
</body>
</html>