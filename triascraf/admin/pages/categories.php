<?php
require_once '../../includes/config.php';
require_once '../includes/auth.php';
$adminTitle = 'Kelola Kategori';

$msg = $err = '';

if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $conn->query("DELETE FROM categories WHERE id = $id");
    redirect('/admin/pages/categories.php?msg=Kategori+berhasil+dihapus');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id   = intval($_POST['id'] ?? 0);
    $name = sanitize($_POST['name'] ?? '');
    $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $name)));
    if (!$id) {
        $sc = $conn->query("SELECT id FROM categories WHERE slug='{$conn->real_escape_string($slug)}'")->num_rows;
        if ($sc) $slug .= '-' . time();
    }
    if ($name) {
        if ($id) $conn->query("UPDATE categories SET name='{$conn->real_escape_string($name)}', slug='{$conn->real_escape_string($slug)}' WHERE id=$id");
        else $conn->query("INSERT INTO categories (name, slug) VALUES ('{$conn->real_escape_string($name)}', '{$conn->real_escape_string($slug)}')");
        redirect('/admin/pages/categories.php?msg=' . urlencode($id ? 'Kategori diperbarui!' : 'Kategori ditambahkan!'));
    }
}

if (isset($_GET['msg'])) $msg = sanitize($_GET['msg']);
$editCat = isset($_GET['edit']) ? $conn->query("SELECT * FROM categories WHERE id=" . intval($_GET['edit']))->fetch_assoc() : null;
$categories = $conn->query("SELECT c.*, COUNT(p.id) as prod_count FROM categories c LEFT JOIN products p ON p.category_id=c.id GROUP BY c.id ORDER BY c.name");
?>
<?php include '../includes/admin_header.php'; ?>

<?php if ($msg): ?><div class="alert alert-success"><i class="fas fa-check-circle"></i> <?= $msg ?></div><?php endif; ?>

<div style="display:grid;grid-template-columns:340px 1fr;gap:24px">
    <div class="admin-table-box" style="padding:24px;height:fit-content">
        <h3 style="font-size:20px;margin-bottom:20px"><?= $editCat ? 'Edit Kategori' : 'Tambah Kategori' ?></h3>
        <form method="POST">
            <input type="hidden" name="id" value="<?= $editCat['id'] ?? '' ?>">
            <div class="form-group">
                <label>Nama Kategori *</label>
                <input type="text" name="name" class="form-control" value="<?= htmlspecialchars($editCat['name'] ?? '') ?>" placeholder="Contoh: Hijab Segi Empat" required>
            </div>
            <div style="display:flex;gap:10px">
                <button type="submit" class="btn btn-primary btn-sm"><?= $editCat ? 'Simpan' : 'Tambah' ?></button>
                <?php if ($editCat): ?><a href="?" class="btn btn-outline btn-sm">Batal</a><?php endif; ?>
            </div>
        </form>
    </div>

    <div class="admin-table-box">
        <div class="admin-table-header"><h3>Daftar Kategori</h3></div>
        <table class="admin-table">
            <thead><tr><th>Nama</th><th>Slug</th><th>Jumlah Produk</th><th>Aksi</th></tr></thead>
            <tbody>
            <?php while($cat = $categories->fetch_assoc()): ?>
            <tr>
                <td style="font-weight:600"><?= htmlspecialchars($cat['name']) ?></td>
                <td style="color:var(--muted);font-size:13px"><?= $cat['slug'] ?></td>
                <td><?= $cat['prod_count'] ?> produk</td>
                <td>
                    <a href="?edit=<?= $cat['id'] ?>" class="btn btn-outline btn-sm"><i class="fas fa-edit"></i></a>
                    <a href="?delete=<?= $cat['id'] ?>" class="btn btn-sm" style="background:#FCDCDB;color:#8C1530" onclick="return confirmDelete('Hapus kategori ini? Produk di dalamnya tidak akan terhapus.')"><i class="fas fa-trash"></i></a>
                </td>
            </tr>
            <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include '../includes/admin_footer.php'; ?>
