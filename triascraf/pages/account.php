<?php
require_once '../includes/config.php';
$pageTitle = 'Akun Saya';

if (!isLoggedIn()) redirect('/pages/login.php');

$uid = $_SESSION['user_id'];
$msg = $err = '';

// Handle update profil
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {

    if ($_POST['action'] === 'update_profile') {
        $name     = sanitize($_POST['name'] ?? '');
        $phone    = sanitize($_POST['phone'] ?? '');
        $address  = sanitize($_POST['address'] ?? '');
        $city     = sanitize($_POST['city'] ?? '');
        $province = sanitize($_POST['province'] ?? '');
        $postal   = sanitize($_POST['postal_code'] ?? '');

        if (!$name) { $err = 'Nama tidak boleh kosong.'; }
        else {
            $stmt = $conn->prepare("UPDATE users SET name=?, phone=?, address=?, city=?, province=?, postal_code=? WHERE id=?");
            $stmt->bind_param('ssssssi', $name, $phone, $address, $city, $province, $postal, $uid);
            $stmt->execute();
            $_SESSION['user_name'] = $name;
            $msg = 'Profil berhasil diperbarui!';
        }
    }

    if ($_POST['action'] === 'change_password') {
        $old  = $_POST['old_password'] ?? '';
        $new  = $_POST['new_password'] ?? '';
        $new2 = $_POST['new_password2'] ?? '';

        $user = $conn->query("SELECT password FROM users WHERE id=$uid")->fetch_assoc();
        if (!password_verify($old, $user['password']))   $err = 'Password lama tidak sesuai.';
        elseif (strlen($new) < 6)                         $err = 'Password baru minimal 6 karakter.';
        elseif ($new !== $new2)                           $err = 'Konfirmasi password tidak cocok.';
        else {
            $hash = password_hash($new, PASSWORD_DEFAULT);
            $conn->query("UPDATE users SET password='$hash' WHERE id=$uid");
            $msg = 'Password berhasil diubah!';
        }
    }
}

// Ambil data user terbaru
$user   = $conn->query("SELECT * FROM users WHERE id=$uid")->fetch_assoc();
$orders = $conn->query("SELECT * FROM orders WHERE user_id=$uid ORDER BY created_at DESC LIMIT 10");
$orderCount = $conn->query("SELECT COUNT(*) as c FROM orders WHERE user_id=$uid")->fetch_assoc()['c'];

$tab = $_GET['tab'] ?? 'profile';

$provinces = ['Aceh','Bali','Banten','Bengkulu','DI Yogyakarta','DKI Jakarta','Gorontalo','Jambi','Jawa Barat','Jawa Tengah','Jawa Timur','Kalimantan Barat','Kalimantan Selatan','Kalimantan Tengah','Kalimantan Timur','Kalimantan Utara','Kepulauan Bangka Belitung','Kepulauan Riau','Lampung','Maluku','Maluku Utara','Nusa Tenggara Barat','Nusa Tenggara Timur','Papua','Papua Barat','Riau','Sulawesi Barat','Sulawesi Selatan','Sulawesi Tengah','Sulawesi Tenggara','Sulawesi Utara','Sumatera Barat','Sumatera Selatan','Sumatera Utara'];
?>
<?php include '../includes/header.php'; ?>

<div class="page-header">
    <h1>Akun Saya</h1>
    <p>Kelola profil dan riwayat pesanan kamu</p>
    <div class="breadcrumb">
        <a href="<?= BASE_URL ?>">Beranda</a>
        <i class="fas fa-chevron-right" style="font-size:10px"></i>
        <span>Akun</span>
    </div>
</div>

<?php if ($msg): ?>
<div style="max-width:1100px;margin:20px auto;padding:0 24px">
    <div class="alert alert-success"><i class="fas fa-check-circle"></i> <?= $msg ?></div>
</div>
<?php endif; ?>
<?php if ($err): ?>
<div style="max-width:1100px;margin:20px auto;padding:0 24px">
    <div class="alert alert-error"><i class="fas fa-exclamation-circle"></i> <?= $err ?></div>
</div>
<?php endif; ?>

<div class="account-layout">

    <!-- SIDEBAR -->
    <div class="account-sidebar">
        <div class="account-avatar"><?= strtoupper(substr($user['name'],0,1)) ?></div>
        <div class="account-name"><?= htmlspecialchars($user['name']) ?></div>
        <div class="account-email"><?= htmlspecialchars($user['email']) ?></div>

        <nav class="account-menu">
            <a href="?tab=profile" class="<?= $tab==='profile'?'active':'' ?>">
                <i class="fas fa-user" style="width:18px"></i> Profil Saya
            </a>
            <a href="?tab=orders" class="<?= $tab==='orders'?'active':'' ?>">
                <i class="fas fa-shopping-bag" style="width:18px"></i> Pesanan
                <?php if ($orderCount > 0): ?>
                <span style="margin-left:auto;background:var(--rose);color:white;font-size:11px;padding:2px 8px;border-radius:50px;font-weight:700"><?= $orderCount ?></span>
                <?php endif; ?>
            </a>
            <a href="?tab=password" class="<?= $tab==='password'?'active':'' ?>">
                <i class="fas fa-lock" style="width:18px"></i> Ubah Password
            </a>
            <a href="<?= BASE_URL ?>/pages/testimonial.php">
                <i class="fas fa-star" style="width:18px"></i> Tulis Ulasan
            </a>
            <hr style="border:none;border-top:1px solid var(--ivory);margin:12px 0">
            <a href="<?= BASE_URL ?>/pages/logout.php" style="color:var(--error)!important">
                <i class="fas fa-sign-out-alt" style="width:18px"></i> Keluar
            </a>
        </nav>
    </div>

    <!-- CONTENT -->
    <div class="account-content">

        <!-- TAB: PROFIL -->
        <?php if ($tab === 'profile'): ?>
        <h3 style="font-size:24px;margin-bottom:24px">Profil Saya</h3>
        <form method="POST">
            <input type="hidden" name="action" value="update_profile">
            <div class="form-row">
                <div class="form-group">
                    <label>Nama Lengkap *</label>
                    <input type="text" name="name" class="form-control" value="<?= htmlspecialchars($user['name']) ?>" required>
                </div>
                <div class="form-group">
                    <label>Nomor HP</label>
                    <input type="text" name="phone" class="form-control" value="<?= htmlspecialchars($user['phone'] ?? '') ?>" placeholder="08xxxxxxxxxx">
                </div>
            </div>
            <div class="form-group">
                <label>Email</label>
                <input type="email" class="form-control" value="<?= htmlspecialchars($user['email']) ?>" readonly style="background:var(--ivory);cursor:not-allowed" title="Email tidak dapat diubah">
                <small style="color:var(--muted);font-size:12px">Email tidak dapat diubah.</small>
            </div>
            <div class="form-group">
                <label>Alamat Lengkap</label>
                <textarea name="address" class="form-control" rows="2" placeholder="Jl. ..."><?= htmlspecialchars($user['address'] ?? '') ?></textarea>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label>Kota</label>
                    <input type="text" name="city" class="form-control" value="<?= htmlspecialchars($user['city'] ?? '') ?>">
                </div>
                <div class="form-group">
                    <label>Provinsi</label>
                    <select name="province" class="form-control">
                        <option value="">Pilih Provinsi</option>
                        <?php foreach($provinces as $prov): ?>
                        <option value="<?= $prov ?>" <?= ($user['province'] ?? '') === $prov ? 'selected' : '' ?>><?= $prov ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label>Kode Pos</label>
                <input type="text" name="postal_code" class="form-control" value="<?= htmlspecialchars($user['postal_code'] ?? '') ?>" placeholder="17210" style="max-width:200px">
            </div>
            <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Simpan Perubahan</button>
        </form>

        <!-- TAB: PESANAN -->
        <?php elseif ($tab === 'orders'): ?>
        <h3 style="font-size:24px;margin-bottom:24px">Riwayat Pesanan</h3>
        <?php if ($orders->num_rows > 0): ?>
        <div style="display:flex;flex-direction:column;gap:16px">
            <?php while($o = $orders->fetch_assoc()):
                $items = $conn->query("SELECT * FROM order_items WHERE order_id={$o['id']}");
            ?>
            <div style="border:2px solid var(--ivory);border-radius:var(--radius-lg);overflow:hidden;transition:border-color var(--transition)" onmouseover="this.style.borderColor='var(--rose)'" onmouseout="this.style.borderColor='var(--ivory)'">
                <!-- Order Header -->
                <div style="padding:16px 20px;background:var(--cream);display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:10px">
                    <div>
                        <code style="background:var(--ivory);padding:3px 10px;border-radius:6px;font-size:12px;color:var(--rose);font-weight:700"><?= $o['order_code'] ?></code>
                        <span style="font-size:12px;color:var(--muted);margin-left:10px"><?= date('d M Y, H:i', strtotime($o['created_at'])) ?></span>
                    </div>
                    <span class="status-badge status-<?= $o['status'] ?>"><?= ucfirst($o['status']) ?></span>
                </div>
                <!-- Order Items -->
                <div style="padding:16px 20px">
                    <?php while($item = $items->fetch_assoc()): ?>
                    <div style="display:flex;justify-content:space-between;font-size:14px;padding:6px 0;border-bottom:1px solid var(--ivory)">
                        <span style="color:var(--body-text)"><?= htmlspecialchars($item['product_name']) ?> <span style="color:var(--muted)">×<?= $item['quantity'] ?></span></span>
                        <span style="font-weight:600;color:var(--charcoal)"><?= formatPrice($item['price'] * $item['quantity']) ?></span>
                    </div>
                    <?php endwhile; ?>
                    <div style="display:flex;justify-content:space-between;align-items:center;margin-top:14px">
                        <span style="font-size:13px;color:var(--muted)">Total pesanan</span>
                        <span style="font-weight:700;color:var(--rose);font-size:16px"><?= formatPrice($o['total_price']) ?></span>
                    </div>
                </div>
                <!-- Order Footer -->
                <div style="padding:12px 20px;border-top:1px solid var(--ivory);display:flex;gap:10px">
                    <?php
                    $waMsg = urlencode("Halo Triascraf! 👋\nSaya ingin menanyakan status pesanan saya:\nNo. Order: *{$o['order_code']}*\n\nBisa tolong dicek? Terima kasih 🙏");
                    $waNum = getSetting($conn,'whatsapp_number');
                    ?>
                    <a href="https://wa.me/<?= $waNum ?>?text=<?= $waMsg ?>" target="_blank" class="btn btn-sm" style="background:#25D366;color:white">
                        <i class="fab fa-whatsapp"></i> Cek Status
                    </a>
                    <?php if ($o['status'] === 'delivered'): ?>
                    <a href="<?= BASE_URL ?>/pages/testimonial.php" class="btn btn-outline btn-sm">
                        <i class="fas fa-star"></i> Tulis Ulasan
                    </a>
                    <?php endif; ?>
                </div>
            </div>
            <?php endwhile; ?>
        </div>
        <?php else: ?>
        <div class="empty-state" style="padding:48px 0">
            <i class="fas fa-shopping-bag"></i>
            <h3>Belum ada pesanan</h3>
            <p>Yuk mulai belanja hijab impianmu!</p>
            <a href="<?= BASE_URL ?>/pages/shop.php" class="btn btn-primary"><i class="fas fa-shopping-bag"></i> Belanja Sekarang</a>
        </div>
        <?php endif; ?>

        <!-- TAB: PASSWORD -->
        <?php elseif ($tab === 'password'): ?>
        <h3 style="font-size:24px;margin-bottom:24px">Ubah Password</h3>
        <div style="max-width:440px">
            <form method="POST">
                <input type="hidden" name="action" value="change_password">
                <div class="form-group">
                    <label>Password Lama *</label>
                    <input type="password" name="old_password" class="form-control" placeholder="••••••••" required>
                </div>
                <div class="form-group">
                    <label>Password Baru * <span style="color:var(--muted);font-weight:400">(min. 6 karakter)</span></label>
                    <input type="password" name="new_password" class="form-control" placeholder="••••••••" required>
                </div>
                <div class="form-group">
                    <label>Konfirmasi Password Baru *</label>
                    <input type="password" name="new_password2" class="form-control" placeholder="••••••••" required>
                </div>
                <button type="submit" class="btn btn-primary"><i class="fas fa-lock"></i> Ubah Password</button>
            </form>
        </div>
        <?php endif; ?>

    </div>
</div>

<?php include '../includes/footer.php'; ?>
