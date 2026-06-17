<?php
require_once '../../includes/config.php';
require_once '../includes/auth.php';
$adminTitle = 'Data Pelanggan';
$users = $conn->query("SELECT u.*, COUNT(o.id) as order_count FROM users u LEFT JOIN orders o ON o.user_id=u.id WHERE u.role='user' GROUP BY u.id ORDER BY u.created_at DESC");
?>
<?php include '../includes/admin_header.php'; ?>
<div class="admin-table-box">
    <div class="admin-table-header"><h3>Data Pelanggan (<?= $users->num_rows ?>)</h3></div>
    <table class="admin-table">
        <thead><tr><th>Nama</th><th>Email</th><th>HP</th><th>Total Pesanan</th><th>Bergabung</th></tr></thead>
        <tbody>
        <?php while($u = $users->fetch_assoc()): ?>
        <tr>
            <td>
                <div style="display:flex;align-items:center;gap:10px">
                    <div style="width:36px;height:36px;background:var(--petal);border-radius:50%;display:flex;align-items:center;justify-content:center;font-family:'Cormorant Garamond',serif;font-size:16px;color:var(--rose);font-weight:700">
                        <?= strtoupper(substr($u['name'],0,1)) ?>
                    </div>
                    <span style="font-weight:600"><?= htmlspecialchars($u['name']) ?></span>
                </div>
            </td>
            <td style="color:var(--muted)"><?= htmlspecialchars($u['email']) ?></td>
            <td><?= htmlspecialchars($u['phone'] ?: '—') ?></td>
            <td><span style="background:var(--ivory);padding:3px 12px;border-radius:50px;font-size:13px;font-weight:700"><?= $u['order_count'] ?> pesanan</span></td>
            <td style="font-size:13px;color:var(--muted)"><?= date('d/m/Y', strtotime($u['created_at'])) ?></td>
        </tr>
        <?php endwhile; ?>
        </tbody>
    </table>
</div>
<?php include '../includes/admin_footer.php'; ?>
