<?php
require_once '../../includes/config.php';
require_once '../includes/auth.php';
$adminTitle = 'Kelola Produk';

$msg = $err = '';

if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $p = $conn->query("SELECT image FROM products WHERE id = $id")->fetch_assoc();
    if ($p && $p['image'] && file_exists(UPLOAD_PATH . $p['image'])) unlink(UPLOAD_PATH . $p['image']);
    $conn->query("DELETE FROM products WHERE id = $id");
    $msg = 'Produk berhasil dihapus.';
}

if (isset($_GET['toggle'])) {
    $id = intval($_GET['toggle']);
    $conn->query("UPDATE products SET is_active = 1 - is_active WHERE id = $id");
    redirect('/admin/pages/products.php?msg=Status+produk+diperbarui');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id    = intval($_POST['id'] ?? 0);
    $name  = sanitize($_POST['name'] ?? '');
    $cat   = intval($_POST['category_id'] ?? 0);
    $price = floatval($_POST['price'] ?? 0);
    $stock = intval($_POST['stock'] ?? 0);
    $desc  = sanitize($_POST['description'] ?? '');
    $feat  = isset($_POST['is_featured']) ? 1 : 0;
    $active= isset($_POST['is_active']) ? 1 : 0;

    $slug_base = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $name)));
    $slug = $slug_base;
    if (!$id) {
        $sc = $conn->query("SELECT id FROM products WHERE slug = '{$conn->real_escape_string($slug)}'")->num_rows;
        if ($sc) $slug .= '-' . time();
    }

    $image = sanitize($_POST['existing_image'] ?? '');
    if (!empty($_FILES['image']['name'])) {
        $ext = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
        if (!in_array($ext, ['jpg','jpeg','png','webp'])) $err = 'Format gambar tidak didukung (JPG/PNG/WEBP).';
        elseif ($_FILES['image']['size'] > 2*1024*1024) $err = 'Ukuran gambar maks 2MB.';
        else {
            $fname = uniqid('prod_') . '.' . $ext;
            if (move_uploaded_file($_FILES['image']['tmp_name'], UPLOAD_PATH . $fname)) {
                if ($image && file_exists(UPLOAD_PATH . $image)) unlink(UPLOAD_PATH . $image);
                $image = $fname;
            } else $err = 'Gagal upload gambar. Pastikan folder uploads/products/ ada dan writable.';
        }
    }

    if (!$err && $name) {
        if ($id) {
            $stmt = $conn->prepare("UPDATE products SET name=?, slug=?, category_id=?, price=?, stock=?, description=?, image=?, is_featured=?, is_active=? WHERE id=?");
            $stmt->bind_param('ssiidssiii', $name, $slug, $cat, $price, $stock, $desc, $image, $feat, $active, $id);
        } else {
            $stmt = $conn->prepare("INSERT INTO products (name, slug, category_id, price, stock, description, image, is_featured, is_active) VALUES (?,?,?,?,?,?,?,?,?)");
            $stmt->bind_param('ssiidssii', $name, $slug, $cat, $price, $stock, $desc, $image, $feat, $active);
        }
        $stmt->execute();
        redirect('/admin/pages/products.php?msg=' . urlencode($id ? 'Produk berhasil diperbarui!' : 'Produk baru berhasil ditambahkan!'));
    }
}

if (isset($_GET['msg'])) $msg = sanitize($_GET['msg']);

$editProduct = null;
if (isset($_GET['edit'])) {
    $editProduct = $conn->query("SELECT * FROM products WHERE id = " . intval($_GET['edit']))->fetch_assoc();
}

$products   = $conn->query("SELECT p.*, c.name as cat_name FROM products p LEFT JOIN categories c ON p.category_id = c.id ORDER BY p.created_at DESC");
$categories = $conn->query("SELECT * FROM categories ORDER BY name");
?>
<?php include '../includes/admin_header.php'; ?>

<?php if ($msg): ?><div class="alert alert-success"><i class="fas fa-check-circle"></i> <?= htmlspecialchars($msg) ?></div><?php endif; ?>
<?php if ($err): ?><div class="alert alert-error"><i class="fas fa-exclamation-circle"></i> <?= htmlspecialchars($err) ?></div><?php endif; ?>

<!-- FORM TAMBAH / EDIT -->
<div class="admin-table-box" style="padding:28px;margin-bottom:28px">
    <h3 style="font-size:22px;margin-bottom:24px;color:var(--charcoal)">
        <?= $editProduct ? '✏️ Edit Produk' : '➕ Tambah Produk Baru' ?>
    </h3>
    <form method="POST" enctype="multipart/form-data">
        <input type="hidden" name="id" value="<?= $editProduct['id'] ?? '' ?>">
        <input type="hidden" name="existing_image" value="<?= $editProduct['image'] ?? '' ?>">

        <div class="form-row">
            <div class="form-group">
                <label>Nama Produk *</label>
                <input type="text" name="name" class="form-control" value="<?= htmlspecialchars($editProduct['name'] ?? '') ?>" placeholder="Contoh: Hijab Voal Motif Bunga" required>
            </div>
            <div class="form-group">
                <label>Kategori</label>
                <select name="category_id" class="form-control">
                    <option value="0">-- Tanpa Kategori --</option>
                    <?php $categories->data_seek(0); while($cat = $categories->fetch_assoc()): ?>
                    <option value="<?= $cat['id'] ?>" <?= ($editProduct['category_id'] ?? 0) == $cat['id'] ? 'selected' : '' ?>>
                        <?= htmlspecialchars($cat['name']) ?>
                    </option>
                    <?php endwhile; ?>
                </select>
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label>Harga (Rp) *</label>
                <input type="number" name="price" class="form-control" value="<?= $editProduct['price'] ?? '' ?>" placeholder="65000" min="0" required>
            </div>
            <div class="form-group">
                <label>Stok *</label>
                <input type="number" name="stock" class="form-control" value="<?= $editProduct['stock'] ?? 0 ?>" min="0" required>
            </div>
        </div>

        <div class="form-group">
            <label>Deskripsi Produk</label>
            <textarea name="description" class="form-control" rows="3" placeholder="Deskripsikan produk..."><?= htmlspecialchars($editProduct['description'] ?? '') ?></textarea>
        </div>

        <div class="form-group">
            <label>Foto Produk <span style="color:var(--muted);font-weight:400">(JPG/PNG/WEBP, maks 2MB)</span></label>
            <?php if (!empty($editProduct['image'])): ?>
            <div style="margin-bottom:10px;display:flex;align-items:center;gap:12px">
                <img src="<?= UPLOAD_URL . htmlspecialchars($editProduct['image']) ?>" style="width:72px;height:72px;object-fit:cover;border-radius:10px;border:2px solid var(--ivory)">
                <span style="font-size:13px;color:var(--muted)">Foto saat ini. Upload baru untuk mengganti.</span>
            </div>
            <?php endif; ?>
            <input type="file" name="image" class="form-control" accept="image/*" onchange="previewImage(this,'imgPreview')">
            <img id="imgPreview" style="display:none;margin-top:10px;width:80px;height:80px;object-fit:cover;border-radius:10px;border:2px solid var(--ivory)">
        </div>

        <div style="display:flex;gap:28px;margin-bottom:24px">
            <label style="display:flex;align-items:center;gap:8px;cursor:pointer;font-size:14px;font-weight:600;color:var(--body-text)">
                <input type="checkbox" name="is_featured" style="accent-color:var(--rose);width:16px;height:16px" <?= ($editProduct['is_featured'] ?? 0) ? 'checked' : '' ?>>
                ★ Produk Unggulan (tampil di beranda)
            </label>
            <label style="display:flex;align-items:center;gap:8px;cursor:pointer;font-size:14px;font-weight:600;color:var(--body-text)">
                <input type="checkbox" name="is_active" style="accent-color:var(--rose);width:16px;height:16px" <?= ($editProduct['is_active'] ?? 1) ? 'checked' : '' ?>>
                ✓ Aktif (tampil di toko)
            </label>
        </div>

        <div style="display:flex;gap:12px;flex-wrap:wrap">
            <button type="submit" class="btn btn-primary">
                <?= $editProduct ? '<i class="fas fa-save"></i> Simpan Perubahan' : '<i class="fas fa-plus"></i> Tambah Produk' ?>
            </button>
            <?php if ($editProduct): ?>
            <a href="<?= BASE_URL ?>/admin/pages/products.php" class="btn btn-outline">
                <i class="fas fa-times"></i> Batal Edit
            </a>
            <?php endif; ?>
        </div>
    </form>
</div>

<!-- DAFTAR PRODUK -->
<div class="admin-table-box">
    <div class="admin-table-header">
        <h3>Daftar Produk (<?= $products->num_rows ?>)</h3>
        <a href="<?= BASE_URL ?>/admin/pages/products.php" class="btn btn-outline btn-sm"><i class="fas fa-sync"></i> Refresh</a>
    </div>
    <table class="admin-table">
        <thead>
            <tr><th>Produk</th><th>Kategori</th><th>Harga</th><th>Stok</th><th>Status</th><th>Aksi</th></tr>
        </thead>
        <tbody>
        <?php if ($products->num_rows === 0): ?>
        <tr><td colspan="6" style="text-align:center;padding:48px;color:var(--muted)">
            <i class="fas fa-tags" style="font-size:36px;display:block;margin-bottom:12px;opacity:.3"></i>
            Belum ada produk. Tambahkan produk pertamamu!
        </td></tr>
        <?php else: ?>
        <?php while($p = $products->fetch_assoc()): ?>
        <tr>
            <td>
                <div style="display:flex;align-items:center;gap:12px">
                    <?php if ($p['image'] && file_exists(UPLOAD_PATH . $p['image'])): ?>
                    <img src="<?= UPLOAD_URL . htmlspecialchars($p['image']) ?>" style="width:48px;height:48px;object-fit:cover;border-radius:8px;border:2px solid var(--ivory)">
                    <?php else: ?>
                    <div style="width:48px;height:48px;background:var(--petal);border-radius:8px;display:flex;align-items:center;justify-content:center;border:2px solid var(--ivory)">
                        <i class="fas fa-image" style="color:var(--muted);font-size:18px"></i>
                    </div>
                    <?php endif; ?>
                    <div>
                        <div style="font-weight:600;color:var(--charcoal)"><?= htmlspecialchars($p['name']) ?></div>
                        <?php if ($p['is_featured']): ?>
                        <span style="font-size:10px;background:#F5DFD0;color:var(--rose);padding:2px 8px;border-radius:50px;font-weight:700">★ Unggulan</span>
                        <?php endif; ?>
                    </div>
                </div>
            </td>
            <td style="color:var(--muted)"><?= htmlspecialchars($p['cat_name'] ?? '—') ?></td>
            <td style="font-weight:700;color:var(--rose)"><?= formatPrice($p['price']) ?></td>
            <td>
                <?php if ($p['stock'] > 10): ?>
                    <span style="color:var(--success);font-weight:600"><?= $p['stock'] ?></span>
                <?php elseif ($p['stock'] > 0): ?>
                    <span style="color:#8A5C00;font-weight:600"><?= $p['stock'] ?> (menipis)</span>
                <?php else: ?>
                    <span style="color:var(--error);font-weight:600">Habis</span>
                <?php endif; ?>
            </td>
            <td>
                <a href="?toggle=<?= $p['id'] ?>" class="status-badge <?= $p['is_active'] ? 'status-shipped' : 'status-cancelled' ?>" style="text-decoration:none;cursor:pointer" title="Klik untuk toggle">
                    <?= $p['is_active'] ? '✓ Aktif' : '✕ Nonaktif' ?>
                </a>
            </td>
            <td>
                <div style="display:flex;gap:6px">
                    <a href="?edit=<?= $p['id'] ?>" class="btn btn-outline btn-sm"><i class="fas fa-edit"></i></a>
                    <a href="<?= BASE_URL ?>/pages/product.php?slug=<?= $p['slug'] ?>" target="_blank" class="btn btn-sm" style="background:var(--ivory);color:var(--body-text)"><i class="fas fa-eye"></i></a>
                    <a href="?delete=<?= $p['id'] ?>" class="btn btn-sm" style="background:#FCDCDB;color:#8C1530;border:none" onclick="return confirmDelete('Hapus produk \'<?= htmlspecialchars(addslashes($p['name'])) ?>\'?')"><i class="fas fa-trash"></i></a>
                </div>
            </td>
        </tr>
        <?php endwhile; ?>
        <?php endif; ?>
        </tbody>
    </table>
</div>

<?php include '../includes/admin_footer.php'; ?>
