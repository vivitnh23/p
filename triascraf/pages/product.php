<?php
require_once '../includes/config.php';

$slug = isset($_GET['slug']) ? sanitize($_GET['slug']) : '';
if (!$slug) redirect('/pages/shop.php');

$slug_esc = $conn->real_escape_string($slug);
$product = $conn->query("SELECT p.*, c.name as cat_name FROM products p LEFT JOIN categories c ON p.category_id = c.id WHERE p.slug = '$slug_esc' AND p.is_active = 1")->fetch_assoc();

if (!$product) redirect('/pages/shop.php');
$pageTitle = $product['name'];

// Related
$related = $conn->query("SELECT p.* FROM products p WHERE p.category_id = {$product['category_id']} AND p.id != {$product['id']} AND p.is_active=1 LIMIT 4");
?>
<?php include '../includes/header.php'; ?>

<!-- BREADCRUMB -->
<div style="max-width:1100px;margin:24px auto;padding:0 24px">
    <div class="breadcrumb" style="justify-content:flex-start">
        <a href="<?= BASE_URL ?>">Beranda</a>
        <i class="fas fa-chevron-right" style="font-size:10px"></i>
        <a href="<?= BASE_URL ?>/pages/shop.php">Koleksi</a>
        <i class="fas fa-chevron-right" style="font-size:10px"></i>
        <span><?= htmlspecialchars($product['name']) ?></span>
    </div>
</div>

<!-- PRODUCT DETAIL -->
<div class="product-detail-layout">
    <!-- Image -->
    <div>
        <?php if ($product['image']): ?>
        <img src="<?= UPLOAD_URL . htmlspecialchars($product['image']) ?>" alt="<?= htmlspecialchars($product['name']) ?>" class="product-detail-img">
        <?php else: ?>
        <div class="product-img-placeholder" style="height:480px;border-radius:20px">
            <i class="fas fa-image" style="font-size:72px;color:var(--rose-gold);opacity:.3"></i>
        </div>
        <?php endif; ?>
    </div>

    <!-- Info -->
    <div class="product-detail-info">
        <div class="product-category"><?= htmlspecialchars($product['cat_name'] ?? '') ?></div>
        <h1><?= htmlspecialchars($product['name']) ?></h1>
        <div class="product-detail-price"><?= formatPrice($product['price']) ?></div>

        <?php if ($product['stock'] > 0): ?>
        <div class="stock-badge"><i class="fas fa-check-circle"></i> Stok tersedia (<?= $product['stock'] ?> pcs)</div>
        <?php else: ?>
        <div class="stock-badge" style="color:var(--error)"><i class="fas fa-times-circle"></i> Stok habis</div>
        <?php endif; ?>

        <p class="product-detail-desc"><?= nl2br(htmlspecialchars($product['description'])) ?></p>

        <?php if ($product['stock'] > 0): ?>
        <form action="<?= BASE_URL ?>/pages/cart.php" method="POST">
            <input type="hidden" name="action" value="add">
            <input type="hidden" name="product_id" value="<?= $product['id'] ?>">
            <div class="form-group">
                <label>Jumlah</label>
                <div class="qty-control" style="width:fit-content">
                    <button type="button" class="qty-btn" onclick="changeQty(this.nextElementSibling, -1)">−</button>
                    <input type="number" name="qty" class="qty-input" value="1" min="1" max="<?= $product['stock'] ?>">
                    <button type="button" class="qty-btn" onclick="changeQty(this.previousElementSibling, 1)">+</button>
                </div>
            </div>
            <div style="display:flex;gap:12px;flex-wrap:wrap">
                <button type="submit" class="btn btn-primary"><i class="fas fa-shopping-bag"></i> Tambah ke Keranjang</button>
                <a href="<?= BASE_URL ?>/pages/checkout.php?buy_now=<?= $product['id'] ?>" class="btn btn-gold">Beli Sekarang</a>
            </div>
        </form>
        <?php endif; ?>

        <!-- WA Quick Order -->
        <?php
        $waMsg = urlencode("Halo Triascraf! 👋\nSaya tertarik dengan produk:\n*{$product['name']}*\nHarga: " . formatPrice($product['price']) . "\n\nApakah masih tersedia?");
        $waNumber = getSetting($conn, 'whatsapp_number');
        ?>
        <a href="https://wa.me/<?= $waNumber ?>?text=<?= $waMsg ?>" class="btn-wa" target="_blank" style="margin-top:16px">
            <i class="fab fa-whatsapp"></i> Tanya via WhatsApp
        </a>

        <div style="margin-top:28px;padding-top:24px;border-top:1px solid var(--ivory)">
            <div class="product-detail-meta">
                <span><i class="fas fa-tag" style="color:var(--rose-gold);margin-right:8px"></i> Kategori: <?= htmlspecialchars($product['cat_name'] ?? '-') ?></span>
                <span><i class="fas fa-truck" style="color:var(--rose-gold);margin-right:8px"></i> Estimasi pengiriman: 2-5 hari kerja</span>
                <span><i class="fas fa-shield-alt" style="color:var(--rose-gold);margin-right:8px"></i> Garansi kepuasan pelanggan</span>
            </div>
        </div>
    </div>
</div>

<!-- RELATED PRODUCTS -->
<?php if ($related->num_rows > 0): ?>
<section class="section" style="padding-top:0">
    <div class="section-header">
        <span class="section-label">Produk Lainnya</span>
        <h2 class="section-title">Mungkin Kamu Suka</h2>
    </div>
    <div class="products-grid">
        <?php while($r = $related->fetch_assoc()): ?>
        <div class="product-card">
            <div class="product-img-wrap">
                <?php if ($r['image']): ?>
                <img src="<?= UPLOAD_URL . htmlspecialchars($r['image']) ?>" alt="<?= htmlspecialchars($r['name']) ?>">
                <?php else: ?>
                <div class="product-img-placeholder"><i class="fas fa-image"></i></div>
                <?php endif; ?>
            </div>
            <div class="product-info">
                <div class="product-name"><a href="<?= BASE_URL ?>/pages/product.php?slug=<?= $r['slug'] ?>"><?= htmlspecialchars($r['name']) ?></a></div>
                <div class="product-price"><span class="rose"><?= formatPrice($r['price']) ?></span></div>
                <a href="<?= BASE_URL ?>/pages/product.php?slug=<?= $r['slug'] ?>" class="btn btn-outline btn-sm btn-block">Lihat Detail</a>
            </div>
        </div>
        <?php endwhile; ?>
    </div>
</section>
<?php endif; ?>

<?php include '../includes/footer.php'; ?>
