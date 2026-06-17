<?php
require_once '../includes/config.php';
$pageTitle = 'Checkout';

// Build cart items
$cartItems = [];
$total = 0;

if (isset($_GET['buy_now'])) {
    $pid = intval($_GET['buy_now']);
    $p = $conn->query("SELECT * FROM products WHERE id = $pid AND is_active=1")->fetch_assoc();
    if ($p) { $cartItems[] = array_merge($p, ['qty'=>1,'subtotal'=>$p['price']]); $total = $p['price']; }
} else {
    if (!empty($_SESSION['cart'])) {
        $ids = implode(',', array_map('intval', array_keys($_SESSION['cart'])));
        $products = $conn->query("SELECT * FROM products WHERE id IN ($ids)");
        while ($p = $products->fetch_assoc()) {
            $qty = $_SESSION['cart'][$p['id']]['qty'];
            $cartItems[] = array_merge($p, ['qty'=>$qty,'subtotal'=>$p['price']*$qty]);
            $total += $p['price'] * $qty;
        }
    }
}

if (empty($cartItems)) redirect('/pages/cart.php');

// Handle form submission
$success = false;
$orderCode = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name       = sanitize($_POST['name'] ?? '');
    $email      = sanitize($_POST['email'] ?? '');
    $phone      = sanitize($_POST['phone'] ?? '');
    $address    = sanitize($_POST['address'] ?? '');
    $city       = sanitize($_POST['city'] ?? '');
    $province   = sanitize($_POST['province'] ?? '');
    $postal     = sanitize($_POST['postal_code'] ?? '');
    $notes      = sanitize($_POST['notes'] ?? '');

    $errors = [];
    if (!$name)    $errors[] = 'Nama wajib diisi';
    if (!$phone)   $errors[] = 'Nomor HP wajib diisi';
    if (!$address) $errors[] = 'Alamat wajib diisi';
    if (!$city)    $errors[] = 'Kota wajib diisi';
    if (!$province) $errors[] = 'Provinsi wajib diisi';

    if (empty($errors)) {
        $orderCode = generateOrderCode();
        $uid = isLoggedIn() ? $_SESSION['user_id'] : 'NULL';

        $stmt = $conn->prepare("INSERT INTO orders (user_id, order_code, name, email, phone, address, city, province, postal_code, notes, total_price) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param('isssssssssd', $uid, $orderCode, $name, $email, $phone, $address, $city, $province, $postal, $notes, $total);
        $stmt->execute();
        $orderId = $conn->insert_id;

        foreach ($cartItems as $item) {
            $stmt2 = $conn->prepare("INSERT INTO order_items (order_id, product_id, product_name, price, quantity) VALUES (?, ?, ?, ?, ?)");
            $stmt2->bind_param('iisdi', $orderId, $item['id'], $item['name'], $item['price'], $item['qty']);
            $stmt2->execute();
        }

        // Clear cart
        $_SESSION['cart'] = [];

        // Build WA message
        $waMsg = "Halo Triascraf! 👋%0A%0A";
        $waMsg .= "Saya ingin melakukan pemesanan:%0A";
        $waMsg .= "No. Order: *" . $orderCode . "*%0A%0A";
        $waMsg .= "*Detail Produk:*%0A";
        foreach ($cartItems as $item) {
            $waMsg .= "• " . $item['name'] . " x" . $item['qty'] . " = " . formatPrice($item['subtotal']) . "%0A";
        }
        $waMsg .= "%0A*Total: " . formatPrice($total) . "*%0A%0A";
        $waMsg .= "*Data Pengiriman:*%0A";
        $waMsg .= "Nama: " . $name . "%0A";
        $waMsg .= "HP: " . $phone . "%0A";
        $waMsg .= "Alamat: " . $address . ", " . $city . ", " . $province;
        if ($postal) $waMsg .= " " . $postal;
        if ($notes) $waMsg .= "%0ACatatan: " . $notes;

        $waNumber = getSetting($conn, 'whatsapp_number');
        $waUrl = "https://wa.me/$waNumber?text=" . $waMsg;

        $success = true;
    }
}

// Prefill if logged in
$user = null;
if (isLoggedIn()) {
    $uid = $_SESSION['user_id'];
    $user = $conn->query("SELECT * FROM users WHERE id = $uid")->fetch_assoc();
}

$provinces = ['Aceh','Bali','Banten','Bengkulu','DI Yogyakarta','DKI Jakarta','Gorontalo','Jambi','Jawa Barat','Jawa Tengah','Jawa Timur','Kalimantan Barat','Kalimantan Selatan','Kalimantan Tengah','Kalimantan Timur','Kalimantan Utara','Kepulauan Bangka Belitung','Kepulauan Riau','Lampung','Maluku','Maluku Utara','Nusa Tenggara Barat','Nusa Tenggara Timur','Papua','Papua Barat','Riau','Sulawesi Barat','Sulawesi Selatan','Sulawesi Tengah','Sulawesi Tenggara','Sulawesi Utara','Sumatera Barat','Sumatera Selatan','Sumatera Utara'];
?>
<?php include '../includes/header.php'; ?>

<div class="page-header">
    <h1>Checkout</h1>
    <p>Isi data pengiriman untuk melanjutkan pesanan</p>
</div>

<?php if ($success): ?>
<!-- SUCCESS -->
<div style="max-width:600px;margin:60px auto;padding:0 24px;text-align:center">
    <div style="background:white;border-radius:20px;padding:48px;box-shadow:0 8px 32px rgba(0,0,0,.08)">
        <div style="width:80px;height:80px;background:#D5F0E0;border-radius:50%;display:flex;align-items:center;justify-content:center;margin:0 auto 24px;font-size:36px;color:#27AE60">✓</div>
        <h2 style="font-size:32px;margin-bottom:12px">Pesanan Diterima!</h2>
        <p style="color:var(--muted);margin-bottom:8px">No. Order: <strong style="color:var(--rose-gold)"><?= $orderCode ?></strong></p>
        <p style="color:var(--muted);font-size:14px;margin-bottom:32px">
            Klik tombol di bawah untuk melanjutkan konfirmasi pesanan via WhatsApp. Admin kami akan segera merespons.
        </p>
        <a href="<?= $waUrl ?>" class="btn-wa" target="_blank" style="display:inline-flex;font-size:16px;padding:14px 32px">
            <i class="fab fa-whatsapp"></i> Konfirmasi via WhatsApp
        </a>
        <div style="margin-top:24px">
            <a href="<?= BASE_URL ?>" class="btn btn-outline">Kembali ke Beranda</a>
        </div>
    </div>
</div>

<?php else: ?>

<?php if (!empty($errors)): ?>
<div style="max-width:1100px;margin:20px auto;padding:0 24px">
    <?php foreach($errors as $e): ?>
    <div class="alert alert-error"><i class="fas fa-exclamation-circle"></i> <?= $e ?></div>
    <?php endforeach; ?>
</div>
<?php endif; ?>

<div style="max-width:1100px;margin:0 auto;padding:40px 24px;display:grid;grid-template-columns:1fr 360px;gap:32px" class="checkout-grid">
    <!-- FORM -->
    <form method="POST">
        <div class="admin-table-box" style="padding:28px;margin-bottom:24px">
            <h3 style="font-size:24px;margin-bottom:24px">Data Pengiriman</h3>
            <div class="form-row">
                <div class="form-group">
                    <label>Nama Lengkap *</label>
                    <input type="text" name="name" class="form-control" value="<?= $user['name'] ?? '' ?>" required>
                </div>
                <div class="form-group">
                    <label>Nomor HP *</label>
                    <input type="text" name="phone" class="form-control" value="<?= $user['phone'] ?? '' ?>" placeholder="08xxxxxxxxxx" required>
                </div>
            </div>
            <div class="form-group">
                <label>Email</label>
                <input type="email" name="email" class="form-control" value="<?= $user['email'] ?? '' ?>">
            </div>
            <div class="form-group">
                <label>Alamat Lengkap *</label>
                <textarea name="address" class="form-control" rows="3" required><?= $user['address'] ?? '' ?></textarea>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label>Kota *</label>
                    <input type="text" name="city" class="form-control" value="<?= $user['city'] ?? '' ?>" required>
                </div>
                <div class="form-group">
                    <label>Provinsi *</label>
                    <select name="province" class="form-control" required>
                        <option value="">Pilih Provinsi</option>
                        <?php foreach($provinces as $prov): ?>
                        <option value="<?= $prov ?>" <?= ($user['province'] ?? '') == $prov ? 'selected' : '' ?>><?= $prov ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label>Kode Pos</label>
                <input type="text" name="postal_code" class="form-control" value="<?= $user['postal_code'] ?? '' ?>" placeholder="e.g. 17210">
            </div>
            <div class="form-group">
                <label>Catatan Pesanan <span style="color:var(--muted);font-weight:normal">(opsional)</span></label>
                <textarea name="notes" class="form-control" rows="2" placeholder="Warna, ukuran khusus, atau catatan lain..."></textarea>
            </div>
        </div>

        <!-- Pengiriman Info -->
        <div class="admin-table-box" style="padding:24px;margin-bottom:24px">
            <h3 style="font-size:20px;margin-bottom:16px"><i class="fas fa-truck" style="color:var(--rose-gold);margin-right:8px"></i> Informasi Pengiriman</h3>
            <p style="color:var(--muted);font-size:14px">Pengiriman dilakukan via JNE / J&T / SiCepat. Biaya ongkir akan dikonfirmasi via WhatsApp setelah pemesanan. Estimasi 2-5 hari kerja.</p>
        </div>

        <button type="submit" class="btn btn-gold" style="font-size:16px;padding:14px 36px">
            <i class="fas fa-check"></i> Buat Pesanan & Chat WhatsApp
        </button>
    </form>

    <!-- SUMMARY -->
    <div class="order-summary" style="position:sticky;top:88px;height:fit-content">
        <h3>Ringkasan Pesanan</h3>
        <?php foreach ($cartItems as $item): ?>
        <div style="display:flex;align-items:center;gap:12px;margin-bottom:14px">
            <?php if ($item['image']): ?>
            <img src="<?= UPLOAD_URL . htmlspecialchars($item['image']) ?>" style="width:48px;height:48px;object-fit:cover;border-radius:8px">
            <?php else: ?>
            <div style="width:48px;height:48px;background:var(--ivory);border-radius:8px;display:flex;align-items:center;justify-content:center;"><i class="fas fa-image" style="color:var(--muted)"></i></div>
            <?php endif; ?>
            <div style="flex:1">
                <div style="font-size:13px;font-weight:500"><?= htmlspecialchars($item['name']) ?></div>
                <div style="font-size:12px;color:var(--muted)">×<?= $item['qty'] ?></div>
            </div>
            <div style="font-size:14px;font-weight:600"><?= formatPrice($item['subtotal']) ?></div>
        </div>
        <?php endforeach; ?>
        <hr class="summary-divider">
        <div class="summary-row summary-total">
            <span>Total Produk</span>
            <span style="color:var(--rose-gold)"><?= formatPrice($total) ?></span>
        </div>
        <div style="font-size:12px;color:var(--muted);margin-top:8px;padding:12px;background:var(--ivory);border-radius:var(--radius)">
            <i class="fab fa-whatsapp" style="color:#25D366"></i> Setelah submit, kamu akan diarahkan ke WhatsApp untuk konfirmasi dan pembayaran.
        </div>
    </div>
</div>

<style>
@media (max-width:768px) {
    .checkout-grid { grid-template-columns: 1fr !important; }
}
</style>
<?php endif; ?>

<?php include '../includes/footer.php'; ?>
