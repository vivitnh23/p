<?php
require_once '../../includes/config.php';
require_once '../includes/auth.php';
$adminTitle = 'Kelola Pesanan';

// Update status
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_status'])) {
    $id     = intval($_POST['order_id']);
    $status = sanitize($_POST['status']);
    $allowed = ['pending','confirmed','shipped','delivered','cancelled'];
    if (in_array($status, $allowed)) {
        $conn->query("UPDATE orders SET status='$status' WHERE id=$id");
    }
    redirect('/admin/pages/orders.php?msg=Status+pesanan+diperbarui');
}

if (isset($_GET['msg'])) $msg = sanitize($_GET['msg']);

// Filter
$statusFilter = isset($_GET['status']) ? sanitize($_GET['status']) : '';
$where = $statusFilter ? "WHERE status = '$statusFilter'" : '';

// Detail view
$viewOrder = null;
$orderItems = null;
if (isset($_GET['view'])) {
    $vid = intval($_GET['view']);
    $viewOrder = $conn->query("SELECT * FROM orders WHERE id = $vid")->fetch_assoc();
    if ($viewOrder) $orderItems = $conn->query("SELECT * FROM order_items WHERE order_id = $vid");
}

$orders = $conn->query("SELECT * FROM orders $where ORDER BY created_at DESC");
$statuses = ['pending','confirmed','shipped','delivered','cancelled'];
?>
<?php include '../includes/admin_header.php'; ?>

<?php if (isset($msg)): ?><div class="alert alert-success"><i class="fas fa-check-circle"></i> <?= $msg ?></div><?php endif; ?>

<!-- DETAIL VIEW -->
<?php if ($viewOrder): ?>
<div class="admin-table-box" style="padding:28px;margin-bottom:28px">
    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:24px">
        <div>
            <h3 style="font-size:22px">Detail Pesanan</h3>
            <code style="background:var(--ivory);padding:4px 12px;border-radius:6px;color:var(--rose);font-weight:700"><?= $viewOrder['order_code'] ?></code>
        </div>
        <a href="<?= BASE_URL ?>/admin/pages/orders.php" class="btn btn-outline btn-sm"><i class="fas fa-arrow-left"></i> Kembali</a>
    </div>

    <div style="display:grid;grid-template-columns:1fr 1fr;gap:24px;margin-bottom:28px">
        <div style="background:var(--cream);padding:20px;border-radius:var(--radius)">
            <h4 style="margin-bottom:14px;font-size:14px;letter-spacing:.08em;text-transform:uppercase;color:var(--muted)">Data Pelanggan</h4>
            <p><strong><?= htmlspecialchars($viewOrder['name']) ?></strong></p>
            <p style="color:var(--muted)"><?= htmlspecialchars($viewOrder['phone']) ?></p>
            <p style="color:var(--muted)"><?= htmlspecialchars($viewOrder['email']) ?></p>
        </div>
        <div style="background:var(--cream);padding:20px;border-radius:var(--radius)">
            <h4 style="margin-bottom:14px;font-size:14px;letter-spacing:.08em;text-transform:uppercase;color:var(--muted)">Alamat Pengiriman</h4>
            <p><?= htmlspecialchars($viewOrder['address']) ?></p>
            <p><?= htmlspecialchars($viewOrder['city']) ?>, <?= htmlspecialchars($viewOrder['province']) ?> <?= $viewOrder['postal_code'] ?></p>
        </div>
    </div>

    <?php if ($viewOrder['notes']): ?>
    <div style="background:#FBE8C8;padding:14px 18px;border-radius:var(--radius);margin-bottom:20px;font-size:14px">
        <strong>Catatan:</strong> <?= htmlspecialchars($viewOrder['notes']) ?>
    </div>
    <?php endif; ?>

    <table class="admin-table" style="margin-bottom:20px">
        <thead><tr><th>Produk</th><th>Harga</th><th>Qty</th><th>Subtotal</th></tr></thead>
        <tbody>
        <?php while($item = $orderItems->fetch_assoc()): ?>
        <tr>
            <td style="font-weight:600"><?= htmlspecialchars($item['product_name']) ?></td>
            <td><?= formatPrice($item['price']) ?></td>
            <td><?= $item['quantity'] ?></td>
            <td style="font-weight:700;color:var(--rose)"><?= formatPrice($item['price'] * $item['quantity']) ?></td>
        </tr>
        <?php endwhile; ?>
        <tr style="background:var(--cream)">
            <td colspan="3" style="font-weight:700;text-align:right">TOTAL</td>
            <td style="font-weight:700;color:var(--rose);font-size:16px"><?= formatPrice($viewOrder['total_price']) ?></td>
        </tr>
        </tbody>
    </table>

    <form method="POST" style="display:flex;align-items:center;gap:12px;flex-wrap:wrap">
        <input type="hidden" name="order_id" value="<?= $viewOrder['id'] ?>">
        <label style="font-weight:700;font-size:14px">Update Status:</label>
        <select name="status" class="form-control" style="width:auto">
            <?php foreach($statuses as $s): ?>
            <option value="<?= $s ?>" <?= $viewOrder['status'] == $s ? 'selected' : '' ?>><?= ucfirst($s) ?></option>
            <?php endforeach; ?>
        </select>
        <button type="submit" name="update_status" class="btn btn-primary btn-sm">Simpan Status</button>

        <?php
        $waMsg = urlencode("Halo " . $viewOrder['name'] . "! 👋\n\nPesanan kamu (No. *" . $viewOrder['order_code'] . "*) sudah kami terima dan sedang diproses.\n\nTerima kasih sudah belanja di Triascraf! 🧕✨");
        $waNumber = getSetting($conn, 'whatsapp_number');
        ?>
        <a href="https://wa.me/62<?= ltrim($viewOrder['phone'], '0') ?>?text=<?= $waMsg ?>" target="_blank" class="btn btn-sm" style="background:#25D366;color:white">
            <i class="fab fa-whatsapp"></i> Chat Pembeli
        </a>
    </form>
</div>
<?php endif; ?>

<!-- FILTER -->
<div style="display:flex;gap:10px;margin-bottom:20px;flex-wrap:wrap">
    <a href="?" class="btn btn-sm <?= !$statusFilter ? 'btn-primary' : 'btn-outline' ?>">Semua</a>
    <?php foreach($statuses as $s): ?>
    <a href="?status=<?= $s ?>" class="btn btn-sm <?= $statusFilter === $s ? 'btn-primary' : 'btn-outline' ?>"><?= ucfirst($s) ?></a>
    <?php endforeach; ?>
</div>

<!-- LIST -->
<div class="admin-table-box">
    <div class="admin-table-header">
        <h3>Daftar Pesanan (<?= $orders->num_rows ?>)</h3>
    </div>
    <table class="admin-table">
        <thead>
            <tr><th>No. Order</th><th>Pelanggan</th><th>Kota</th><th>Total</th><th>Status</th><th>Tanggal</th><th>Aksi</th></tr>
        </thead>
        <tbody>
        <?php if ($orders->num_rows === 0): ?>
        <tr><td colspan="7" style="text-align:center;padding:48px;color:var(--muted)">
            <i class="fas fa-inbox" style="font-size:36px;display:block;margin-bottom:12px;opacity:.3"></i>
            Tidak ada pesanan ditemukan
        </td></tr>
        <?php else: while($o = $orders->fetch_assoc()): ?>
        <tr>
            <td><code style="background:var(--ivory);padding:2px 8px;border-radius:6px;font-size:12px;color:var(--rose);font-weight:700"><?= $o['order_code'] ?></code></td>
            <td>
                <div style="font-weight:600"><?= htmlspecialchars($o['name']) ?></div>
                <div style="font-size:12px;color:var(--muted)"><?= htmlspecialchars($o['phone']) ?></div>
            </td>
            <td><?= htmlspecialchars($o['city']) ?></td>
            <td style="font-weight:700;color:var(--rose)"><?= formatPrice($o['total_price']) ?></td>
            <td><span class="status-badge status-<?= $o['status'] ?>"><?= ucfirst($o['status']) ?></span></td>
            <td style="font-size:13px;color:var(--muted)"><?= date('d/m/Y H:i', strtotime($o['created_at'])) ?></td>
            <td><a href="?view=<?= $o['id'] ?>" class="btn btn-outline btn-sm"><i class="fas fa-eye"></i> Detail</a></td>
        </tr>
        <?php endwhile; endif; ?>
        </tbody>
    </table>
</div>

<?php include '../includes/admin_footer.php'; ?>
