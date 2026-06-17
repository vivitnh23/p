<?php
require_once '../includes/config.php';
$pageTitle = 'Daftar';
if (isLoggedIn()) redirect('/');

$error = $success = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name  = sanitize($_POST['name'] ?? '');
    $email = sanitize($_POST['email'] ?? '');
    $phone = sanitize($_POST['phone'] ?? '');
    $pass  = $_POST['password'] ?? '';
    $pass2 = $_POST['password2'] ?? '';

    if (!$name || !$email || !$pass) $error = 'Semua field wajib diisi.';
    elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) $error = 'Format email tidak valid.';
    elseif (strlen($pass) < 6) $error = 'Password minimal 6 karakter.';
    elseif ($pass !== $pass2) $error = 'Konfirmasi password tidak cocok.';
    else {
        $e = $conn->real_escape_string($email);
        $exists = $conn->query("SELECT id FROM users WHERE email = '$e'")->num_rows;
        if ($exists) $error = 'Email sudah terdaftar. Silakan masuk.';
        else {
            $hash = password_hash($pass, PASSWORD_DEFAULT);
            $stmt = $conn->prepare("INSERT INTO users (name, email, phone, password) VALUES (?, ?, ?, ?)");
            $stmt->bind_param('ssss', $name, $email, $phone, $hash);
            if ($stmt->execute()) {
                $uid = $conn->insert_id;
                $_SESSION['user_id']    = $uid;
                $_SESSION['user_name']  = $name;
                $_SESSION['user_email'] = $email;
                $_SESSION['user_role']  = 'user';
                redirect('/');
            } else $error = 'Terjadi kesalahan. Coba lagi.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar — Triascarf</title>
    <link rel="icon" type="image/jpeg+xml" href="<?= BASE_URL ?>/assets/favicon.jpeg">
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,300;0,400;0,600;1,300;1,400&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/style.css">
</head>
<body>
<div class="auth-wrapper">
    <div class="auth-visual">
        <div class="auth-visual-logo"><span class="logo-script">Trias</span><span class="logo-bold">carf</span></div>
        <h2>Bergabung Bersama Kami</h2>
        <p>Daftar sekarang dan nikmati koleksi hijab premium kami dengan layanan terbaik.</p>
    </div>
    <div class="auth-form-side">
        <div class="auth-form-box">
            <h2>Buat Akun</h2>
            <p>Sudah punya akun? <a href="<?= BASE_URL ?>/pages/login.php">Masuk di sini</a></p>

            <?php if ($error): ?>
            <div class="alert alert-error"><i class="fas fa-exclamation-circle"></i> <?= $error ?></div>
            <?php endif; ?>

            <form method="POST">
                <div class="form-group">
                    <label>Nama Lengkap *</label>
                    <input type="text" name="name" class="form-control" value="<?= htmlspecialchars($_POST['name'] ?? '') ?>" required>
                </div>
                <div class="form-group">
                    <label>Email *</label>
                    <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($_POST['email'] ?? '') ?>" required>
                </div>
                <div class="form-group">
                    <label>Nomor HP</label>
                    <input type="text" name="phone" class="form-control" value="<?= htmlspecialchars($_POST['phone'] ?? '') ?>" placeholder="08xxxxxxxxxx">
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label>Password *</label>
                        <input type="password" name="password" class="form-control" placeholder="Min. 6 karakter" required>
                    </div>
                    <div class="form-group">
                        <label>Konfirmasi Password *</label>
                        <input type="password" name="password2" class="form-control" placeholder="Ulangi password" required>
                    </div>
                </div>
                <div style="font-size:12px;color:var(--muted);margin-bottom:16px">
                    Dengan mendaftar, kamu menyetujui <a href="#" style="color:var(--rose-gold)">Syarat & Ketentuan</a> kami.
                </div>
                <button type="submit" class="btn btn-gold btn-block">Daftar Sekarang <i class="fas fa-arrow-right"></i></button>
            </form>

            <div class="auth-footer-link" style="margin-top:24px">
                <a href="<?= BASE_URL ?>"><i class="fas fa-arrow-left"></i> Kembali ke Beranda</a>
            </div>
        </div>
    </div>
</div>
</body>
</html>