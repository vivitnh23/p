<?php
require_once '../includes/config.php';
$pageTitle = 'Koleksi';

$catId = isset($_GET['cat']) ? intval($_GET['cat']) : 0;
$sort  = isset($_GET['sort']) ? sanitize($_GET['sort']) : 'newest';
$search = isset($_GET['q']) ? sanitize($_GET['q']) : '';
$page  = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
$perPage = 12;
$offset  = ($page - 1) * $perPage;

// Build query
$where = "WHERE p.is_active = 1";
if ($catId) $where .= " AND p.category_id = $catId";
if ($search) $where .= " AND p.name LIKE '%" . $conn->real_escape_string($search) . "%'";

$orderBy = match($sort) {
    'price_asc'  => 'p.price ASC',
    'price_desc' => 'p.price DESC',
    'name'       => 'p.name ASC',
    default      => 'p.created_at DESC'
};

$total = $conn->query("SELECT COUNT(*) as c FROM products p $where")->fetch_assoc()['c'];
$totalPages = ceil($total / $perPage);

$products = $conn->query("SELECT p.*, c.name as cat_name FROM products p LEFT JOIN categories c ON p.category_id = c.id $where ORDER BY $orderBy LIMIT $perPage OFFSET $offset");

$categories = $conn->query("SELECT * FROM categories ORDER BY name");
$activeCat  = $catId ? $conn->query("SELECT * FROM categories WHERE id = $catId")->fetch_assoc() : null;
?>
<?php include '../includes/header.php'; ?>

<!-- PAGE HEADER -->
<div class="page-header">
    <h1><?= $activeCat ? htmlspecialchars($activeCat['name']) : 'Semua Koleksi' ?></h1>
    <p><?= $total ?> produk ditemukan<?= $search ? " untuk \"$search\"" : '' ?></p>
    <div class="breadcrumb">
        <a href="<?= BASE_URL ?>">Beranda</a>
        <i class="fas fa-chevron-right" style="font-size:10px"></i>
        <span>Koleksi</span>
        <?php if ($activeCat): ?>
        <i class="fas fa-chevron-right" style="font-size:10px"></i>
        <span><?= htmlspecialchars($activeCat['name']) ?></span>
        <?php endif; ?>
    </div>
</div>

<div class="shop-layout">
    <!-- FILTER PANEL -->
    <aside class="filter-panel">
        <h3 style="font-size:20px;margin-bottom:24px">Filter</h3>

        <!-- Search -->
        <div class="filter-group">
            <h4>Cari Produk</h4>
            <form method="GET">
                <?php if ($catId): ?><input type="hidden" name="cat" value="<?= $catId ?>"> <?php endif; ?>
                <input type="text" name="q" value="<?= htmlspecialchars($search) ?>" class="form-control" placeholder="Nama produk...">
                <button type="submit" class="btn btn-primary btn-block btn-sm" style="margin-top:10px">Cari</button>
            </form>
        </div>

        <!-- Categories -->
        <div class="filter-group">
            <h4>Kategori</h4>
            <div class="filter-option">
                <a href="<?= BASE_URL ?>/pages/shop.php" style="text-decoration:none;color:<?= !$catId ? 'var(--rose-gold)' : 'inherit' ?>;font-weight:<?= !$catId ? '500' : 'normal' ?>">Semua Kategori</a>
            </div>
            <?php $categories->data_seek(0); while($cat = $categories->fetch_assoc()): ?>
            <div class="filter-option">
                <a href="?cat=<?= $cat['id'] ?>" style="text-decoration:none;color:<?= $catId == $cat['id'] ? 'var(--rose-gold)' : 'inherit' ?>;font-weight:<?= $catId == $cat['id'] ? '500' : 'normal' ?>">
                    <?= htmlspecialchars($cat['name']) ?>
                </a>
            </div>
            <?php endwhile; ?>
        </div>

        <!-- Sort -->
        <div class="filter-group">
            <h4>Urutkan</h4>
            <?php $sorts = ['newest'=>'Terbaru','price_asc'=>'Harga Terendah','price_desc'=>'Harga Tertinggi','name'=>'Nama A-Z']; ?>
            <?php foreach($sorts as $key => $label): ?>
            <div class="filter-option">
                <a href="?<?= $catId ? 'cat='.$catId.'&' : '' ?>sort=<?= $key ?><?= $search ? '&q='.urlencode($search) : '' ?>"
                   style="text-decoration:none;color:<?= $sort == $key ? 'var(--rose-gold)' : 'inherit' ?>;font-weight:<?= $sort == $key ? '500' : 'normal' ?>">
                    <?= $label ?>
                </a>
            </div>
            <?php endforeach; ?>
        </div>
    </aside>

    <!-- PRODUCTS -->
    <div>
        <?php if ($products->num_rows > 0): ?>
        <div class="products-grid">
            <?php while($p = $products->fetch_assoc()): ?>
            <div class="product-card">
                <div class="product-img-wrap">
                    <?php if ($p['image']): ?>
                        <img src="<?= UPLOAD_URL . htmlspecialchars($p['image']) ?>" alt="<?= htmlspecialchars($p['name']) ?>">
                    <?php else: ?>
                        <div class="product-img-placeholder"><i class="fas fa-image"></i></div>
                    <?php endif; ?>
                    <?php if ($p['is_featured']): ?><span class="product-badge">Unggulan</span><?php endif; ?>
                    <div class="product-actions-hover">
                        <a href="<?= BASE_URL ?>/pages/product.php?slug=<?= $p['slug'] ?>" class="product-action-btn"><i class="fas fa-eye"></i></a>
                        <a href="<?= BASE_URL ?>/pages/cart.php?add=<?= $p['id'] ?>" class="product-action-btn"><i class="fas fa-shopping-bag"></i></a>
                    </div>
                </div>
                <div class="product-info">
                    <div class="product-category"><?= htmlspecialchars($p['cat_name'] ?? '') ?></div>
                    <div class="product-name">
                        <a href="<?= BASE_URL ?>/pages/product.php?slug=<?= $p['slug'] ?>"><?= htmlspecialchars($p['name']) ?></a>
                    </div>
                    <div class="product-price"><span class="rose"><?= formatPrice($p['price']) ?></span></div>
                    <?php if ($p['stock'] > 0): ?>
                    <a href="<?= BASE_URL ?>/pages/cart.php?add=<?= $p['id'] ?>" class="btn btn-primary btn-sm btn-block"><i class="fas fa-shopping-bag"></i> Tambah</a>
                    <?php else: ?>
                    <button class="btn btn-outline btn-sm btn-block" disabled>Stok Habis</button>
                    <?php endif; ?>
                </div>
            </div>
            <?php endwhile; ?>
        </div>

        <!-- PAGINATION -->
        <?php if ($totalPages > 1): ?>
        <div class="pagination">
            <?php for($i = 1; $i <= $totalPages; $i++): ?>
            <a href="?<?= $catId ? 'cat='.$catId.'&' : '' ?>sort=<?= $sort ?>&page=<?= $i ?>" class="page-btn <?= $page == $i ? 'active' : '' ?>"><?= $i ?></a>
            <?php endfor; ?>
        </div>
        <?php endif; ?>

        <?php else: ?>
        <div class="empty-state">
            <i class="fas fa-search"></i>
            <h3>Produk tidak ditemukan</h3>
            <p>Coba ubah filter atau kata kunci pencarian.</p>
            <a href="<?= BASE_URL ?>/pages/shop.php" class="btn btn-primary">Lihat Semua Produk</a>
        </div>
        <?php endif; ?>
    </div>
</div>

<?php include '../includes/footer.php'; ?>
