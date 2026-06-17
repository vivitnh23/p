<?php
require_once '../../includes/config.php';
require_once '../includes/auth.php';
$adminTitle = 'Pengaturan Toko';
$msg = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    foreach ($_POST as $key => $val) {
        $k = $conn->real_escape_string($key);
        $v = $conn->real_escape_string(sanitize($val));
        $conn->query("INSERT INTO settings (key_name, value) VALUES ('$k','$v') ON DUPLICATE KEY UPDATE value='$v'");
    }
    $msg = 'Pengaturan berhasil disimpan!';
}
$settings = [];
$r = $conn->query("SELECT * FROM settings");
while ($row = $r->fetch_assoc()) $settings[$row['key_name']] = $row['value'];
?>
<?php include '../includes/admin_header.php'; ?>
<?php if ($msg): ?><div class="alert alert-success"><i class="fas fa-check-circle"></i> <?= $msg ?></div><?php endif; ?>
<div class="admin-table-box" style="padding:32px;max-width:680px">
    <h3 style="font-size:22px;margin-bottom:24px">Pengaturan Toko</h3>
    <form method="POST">
        <div class="form-group"><label>Nama Toko</label><input type="text" name="store_name" class="form-control" value="<?= htmlspecialchars($settings['store_name'] ?? '') ?>"></div>
        <div class="form-group"><label>Tagline</label><input type="text" name="store_tagline" class="form-control" value="<?= htmlspecialchars($settings['store_tagline'] ?? '') ?>"></div>
        <div class="form-group"><label>Email Toko</label><input type="email" name="store_email" class="form-control" value="<?= htmlspecialchars($settings['store_email'] ?? '') ?>"></div>
        <div class="form-group">
            <label>Nomor WhatsApp <span style="font-weight:400;color:var(--muted)">(format: 628xxxxxxxxx)</span></label>
            <input type="text" name="whatsapp_number" class="form-control" value="<?= htmlspecialchars($settings['whatsapp_number'] ?? '') ?>" placeholder="6283807066072">
        </div>
        <div class="form-group"><label>Instagram</label><input type="text" name="store_instagram" class="form-control" value="<?= htmlspecialchars($settings['store_instagram'] ?? '') ?>" placeholder="triascrafofficial"></div>
        <div class="form-group"><label>Alamat Toko</label><textarea name="store_address" class="form-control" rows="2"><?= htmlspecialchars($settings['store_address'] ?? '') ?></textarea></div>
        <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Simpan Pengaturan</button>
    </form>
</div>
<?php include '../includes/admin_footer.php'; ?>
