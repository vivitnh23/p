<?php
require_once '../../includes/config.php';
require_once '../includes/auth.php';
$adminTitle = 'Kelola Testimoni';

if (isset($_GET['approve'])) { $conn->query("UPDATE testimonials SET is_approved=1 WHERE id=".intval($_GET['approve'])); redirect('/admin/pages/testimonials.php?msg=Testimoni+disetujui'); }
if (isset($_GET['delete']))  { $conn->query("DELETE FROM testimonials WHERE id=".intval($_GET['delete']));  redirect('/admin/pages/testimonials.php?msg=Testimoni+dihapus'); }

$msg = sanitize($_GET['msg'] ?? '');
$testimonials = $conn->query("SELECT * FROM testimonials ORDER BY created_at DESC");
?>
<?php include '../includes/admin_header.php'; ?>
<?php if ($msg): ?><div class="alert alert-success"><i class="fas fa-check-circle"></i> <?= $msg ?></div><?php endif; ?>
<div class="admin-table-box">
    <div class="admin-table-header"><h3>Daftar Testimoni</h3></div>
    <table class="admin-table">
        <thead><tr><th>Nama</th><th>Pesan</th><th>Rating</th><th>Status</th><th>Tanggal</th><th>Aksi</th></tr></thead>
        <tbody>
        <?php while($t = $testimonials->fetch_assoc()): ?>
        <tr>
            <td style="font-weight:600"><?= htmlspecialchars($t['name']) ?></td>
            <td style="max-width:300px;color:var(--muted)"><?= htmlspecialchars(mb_strimwidth($t['message'],0,80,'...')) ?></td>
            <td style="color:var(--rose)"><?= str_repeat('★',$t['rating']) ?></td>
            <td><span class="status-badge <?= $t['is_approved'] ? 'status-shipped' : 'status-pending' ?>"><?= $t['is_approved'] ? 'Disetujui' : 'Menunggu' ?></span></td>
            <td style="font-size:13px;color:var(--muted)"><?= date('d/m/Y', strtotime($t['created_at'])) ?></td>
            <td>
                <?php if (!$t['is_approved']): ?><a href="?approve=<?= $t['id'] ?>" class="btn btn-sm" style="background:var(--mint-light);color:var(--mint)"><i class="fas fa-check"></i></a><?php endif; ?>
                <a href="?delete=<?= $t['id'] ?>" class="btn btn-sm" style="background:#FCDCDB;color:#8C1530" onclick="return confirmDelete('Hapus testimoni ini?')"><i class="fas fa-trash"></i></a>
            </td>
        </tr>
        <?php endwhile; ?>
        </tbody>
    </table>
</div>
<?php include '../includes/admin_footer.php'; ?>
