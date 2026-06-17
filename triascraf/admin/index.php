<?php
require_once '../includes/config.php';
require_once 'includes/auth.php';
$adminTitle = 'Dashboard';

$totalOrders   = $conn->query("SELECT COUNT(*) as c FROM orders")->fetch_assoc()['c'];
$totalRevenue  = $conn->query("SELECT SUM(total_price) as s FROM orders WHERE status != 'cancelled'")->fetch_assoc()['s'] ?? 0;
$totalProducts = $conn->query("SELECT COUNT(*) as c FROM products WHERE is_active=1")->fetch_assoc()['c'];
$totalUsers    = $conn->query("SELECT COUNT(*) as c FROM users WHERE role='user'")->fetch_assoc()['c'];
$pendingOrders = $conn->query("SELECT COUNT(*) as c FROM orders WHERE status='pending'")->fetch_assoc()['c'];
$recentOrders  = $conn->query("SELECT * FROM orders ORDER BY created_at DESC LIMIT 10");
?>
<?php include 'includes/admin_header.php'; ?>

<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-icon gold"><i class="fas fa-shopping-bag"></i></div>
        <div><div class="stat-num"><?= $totalOrders ?></div><div class="stat-label">Total Pesanan</div></div>
    </div>
    <div class="stat-card">
        <div class="stat-icon green"><i class="fas fa-coins"></i></div>
        <div><div class="stat-num" style="font-size:18px"><?= formatPrice($totalRevenue) ?></div><div class="stat-label">Total Pendapatan</div></div>
    </div>
    <div class="stat-card">
        <div class="stat-icon dark"><i class="fas fa-tags"></i></div>
        <div><div class="stat-num"><?= $totalProducts ?></div><div class="stat-label">Produk Aktif</div></div>
    </div>
    <div class="stat-card">
        <div class="stat-icon blue"><i class="fas fa-users"></i></div>
        <div><div class="stat-num"><?= $totalUsers ?></div><div class="stat-label">Total Pelanggan</div></div>
    </div>
</div>

<?php if ($pendingOrders > 0): ?>
<div class="alert alert-info" style="margin-bottom:24px">
    <i class="fas fa-bell"></i> Ada <strong><?= $pendingOrders ?></strong> pesanan menunggu konfirmasi.
    <a href="<?= BASE_URL ?>/admin/pages/orders.php?status=pending" style="color:var(--rose);font-weight:700;margin-left:8px">Lihat Sekarang →</a>
</div>
<?php endif; ?>

<div class="admin-table-box">
    <div class="admin-table-header">
        <h3>Pesanan Terbaru</h3>
        <a href="<?= BASE_URL ?>/admin/pages/orders.php" class="btn btn-outline btn-sm">Lihat Semua</a>
    </div>
    <table class="admin-table">
        <thead>
            <tr><th>No. Order</th><th>Pelanggan</th><th>Kota</th><th>Total</th><th>Status</th><th>Tanggal</th><th>Aksi</th></tr>
        </thead>
        <tbody>
        <?php while($order = $recentOrders->fetch_assoc()): ?>
        <tr>
            <td><code style="background:var(--ivory);padding:2px 8px;border-radius:6px;font-size:12px"><?= $order['order_code'] ?></code></td>
            <td>
                <div style="font-weight:600"><?= htmlspecialchars($order['name']) ?></div>
                <div style="font-size:12px;color:var(--muted)"><?= htmlspecialchars($order['phone']) ?></div>
            </td>
            <td><?= htmlspecialchars($order['city']) ?></td>
            <td style="font-weight:700;color:var(--rose)"><?= formatPrice($order['total_price']) ?></td>
            <td><span class="status-badge status-<?= $order['status'] ?>"><?= ucfirst($order['status']) ?></span></td>
            <td style="font-size:13px;color:var(--muted)"><?= date('d/m/Y H:i', strtotime($order['created_at'])) ?></td>
            <td><a href="<?= BASE_URL ?>/admin/pages/orders.php?view=<?= $order['id'] ?>" class="btn btn-outline btn-sm">Detail</a></td>
        </tr>
        <?php endwhile; ?>
        <?php if ($totalOrders == 0): ?>
        <tr><td colspan="7" style="text-align:center;padding:40px;color:var(--muted)"><i class="fas fa-inbox" style="font-size:32px;display:block;margin-bottom:12px;opacity:.3"></i>Belum ada pesanan masuk</td></tr>
        <?php endif; ?>
        </tbody>
    </table>
</div>

<?php include 'includes/admin_footer.php'; ?>
