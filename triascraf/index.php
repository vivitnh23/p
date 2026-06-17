<?php
require_once 'includes/config.php';
$pageTitle = 'Beranda';

// Featured products
$featured = $conn->query("SELECT p.*, c.name as cat_name FROM products p LEFT JOIN categories c ON p.category_id = c.id WHERE p.is_featured = 1 AND p.is_active = 1 LIMIT 6");

// Categories with product count
$categories = $conn->query("SELECT c.*, COUNT(p.id) as prod_count FROM categories c LEFT JOIN products p ON p.category_id = c.id AND p.is_active=1 GROUP BY c.id LIMIT 4");

// Testimonials
$testimonials = $conn->query("SELECT * FROM testimonials WHERE is_approved = 1 LIMIT 3");
?>
<?php include 'includes/header.php'; ?>

<!-- HERO -->
<section>
    <div class="hero">
        <div class="hero-text">
            <div class="hero-eyebrow"><span>Koleksi Terbaru 2026</span></div>
            <h1>Elegan dalam<br>Balutan <em>Syar'i</em></h1>
            <p class="hero-desc">Temukan koleksi hijab premium kami — dari voal lembut hingga pashmina mewah. Dibuat untuk perempuan modern yang menghargai keindahan dan kesederhanaan.</p>
            <div class="hero-cta">
                <a href="<?= BASE_URL ?>/pages/shop.php" class="btn btn-primary"><i class="fas fa-shopping-bag"></i> Belanja Sekarang</a>
                <a href="<?= BASE_URL ?>/pages/about.php" class="btn btn-outline">Tentang Kami</a>
            </div>
            <div class="hero-stats">
                <div>
                    <span class="hero-stat-num">500+</span>
                    <span class="hero-stat-label">Produk Terjual</span>
                </div>
                <div>
                    <span class="hero-stat-num">200+</span>
                    <span class="hero-stat-label">Pelanggan Puas</span>
                </div>
                <div>
                    <span class="hero-stat-num">4.9★</span>
                    <span class="hero-stat-label">Rating Toko</span>
                </div>
            </div>
        </div>
        <div class="hero-image">
    <img src="<?= BASE_URL ?>/assets/images/hero-banner.jpg" alt="Hero banner Triascraf" class="hero-image-main">
    <div class="hero-image-badge">
        <div style="background:var(--rose-gold);width:40px;height:40px;border-radius:50%;display:flex;align-items:center;justify-content:center;color:white;font-size:18px;"><i class="fas fa-award"></i></div>
        <div>
            <strong style="font-size:14px">Kualitas Premium</strong>
            <span style="display:block;font-size:12px;color:var(--muted)">Bahan terbaik pilihan</span>
        </div>
    </div>
</div>
</section>

<!-- CATEGORIES -->
<section class="section" style="background:var(--ivory)">
    <div class="section-header">
        <span class="section-label">Jelajahi</span>
        <h2 class="section-title">Koleksi Kami</h2>
        <p>Dari hijab kasual hingga premium, semua tersedia untuk memperindah penampilanmu.</p>
    </div>
    <div class="categories-grid">
        <?php while($cat = $categories->fetch_assoc()): ?>
        <a href="<?= BASE_URL ?>/pages/shop.php?cat=<?= $cat['id'] ?>" class="category-card">
            <div class="category-icon">🧕</div>
            <h3><?= htmlspecialchars($cat['name']) ?></h3>
            <span class="category-count"><?= $cat['prod_count'] ?> produk</span>
        </a>
        <?php endwhile; ?>
    </div>
</section>

<!-- FEATURED PRODUCTS -->
<section class="section">
    <div class="section-header">
        <span class="section-label">Pilihan Terbaik</span>
        <h2 class="section-title">Produk Unggulan</h2>
        <p>Produk-produk terpopuler yang paling banyak diminati pelanggan kami.</p>
    </div>
    <div class="products-grid">
        <?php while($p = $featured->fetch_assoc()): ?>
        <div class="product-card">
            <div class="product-img-wrap">
                <?php if ($p['image']): ?>
                    <img src="<?= UPLOAD_URL . htmlspecialchars($p['image']) ?>" alt="<?= htmlspecialchars($p['name']) ?>">
                <?php else: ?>
                    <div class="product-img-placeholder"><i class="fas fa-image"></i></div>
                <?php endif; ?>
                <span class="product-badge">Unggulan</span>
                <div class="product-actions-hover">
                    <a href="<?= BASE_URL ?>/pages/product.php?slug=<?= $p['slug'] ?>" class="product-action-btn" title="Lihat Detail"><i class="fas fa-eye"></i></a>
                    <a href="<?= BASE_URL ?>/pages/cart.php?add=<?= $p['id'] ?>" class="product-action-btn" title="Tambah ke Keranjang"><i class="fas fa-shopping-bag"></i></a>
                </div>
            </div>
            <div class="product-info">
                <div class="product-category"><?= htmlspecialchars($p['cat_name'] ?? '') ?></div>
                <div class="product-name"><a href="<?= BASE_URL ?>/pages/product.php?slug=<?= $p['slug'] ?>"><?= htmlspecialchars($p['name']) ?></a></div>
                <div class="product-price"><span class="rose"><?= formatPrice($p['price']) ?></span></div>
                <a href="<?= BASE_URL ?>/pages/cart.php?add=<?= $p['id'] ?>" class="btn btn-primary btn-sm btn-block">
                    <i class="fas fa-shopping-bag"></i> Tambah ke Keranjang
                </a>
            </div>
        </div>
        <?php endwhile; ?>
    </div>
    <div style="text-align:center;margin-top:48px">
        <a href="<?= BASE_URL ?>/pages/shop.php" class="btn btn-outline">Lihat Semua Produk <i class="fas fa-arrow-right"></i></a>
    </div>
</section>

<!-- PROMO BANNER -->
<section class="promo-banner">
    <span class="section-label" style="color:var(--rose-gold)">Penawaran Spesial</span>
    <h2>Gratis Ongkir untuk<br>Pembelian <em>di atas Rp 150.000</em></h2>
    <p>Berlaku untuk semua wilayah Indonesia. Pesan sekarang via WhatsApp!</p>
    <a href="https://wa.me/<?= getSetting($conn, 'whatsapp_number') ?>" class="btn btn-gold" target="_blank">
        <i class="fab fa-whatsapp"></i> Pesan via WhatsApp
    </a>
</section>

<!-- TESTIMONIALS -->
<?php if ($testimonials && $testimonials->num_rows > 0): ?>
<section class="section" style="background:var(--ivory)">
    <div class="section-header">
        <span class="section-label">Ulasan Pelanggan</span>
        <h2 class="section-title">Kata Mereka</h2>
        <p>Kepuasan pelanggan adalah prioritas utama kami.</p>
    </div>
    <div class="testimonial-grid">
        <?php while($t = $testimonials->fetch_assoc()): ?>
        <div class="testimonial-card">
            <div class="testimonial-stars"><?= str_repeat('★', $t['rating']) ?><?= str_repeat('☆', 5 - $t['rating']) ?></div>
            <p class="testimonial-text">"<?= htmlspecialchars($t['message']) ?>"</p>
            <div class="testimonial-author">
                <div class="testimonial-avatar"><?= strtoupper(substr($t['name'], 0, 1)) ?></div>
                <div>
                    <div class="testimonial-name"><?= htmlspecialchars($t['name']) ?></div>
                    <div class="testimonial-role">Pelanggan Triascraf</div>
                </div>
            </div>
        </div>
        <?php endwhile; ?>
    </div>
</section>
<?php endif; ?>

<!-- NEWSLETTER / CTA BOTTOM -->
<section class="section" style="text-align:center">
    <span class="section-label">Tetap Terhubung</span>
    <h2 class="section-title">Follow Instagram Kami</h2>
    <p style="color:var(--muted);max-width:400px;margin:0 auto 28px">Dapatkan inspirasi OOTD dan info produk terbaru setiap hari.</p>
    <a href="https://instagram.com/triascrafofficial" class="btn btn-primary" target="_blank">
        <i class="fab fa-instagram"></i> @triascrafofficial
    </a>
</section>

<?php include 'includes/footer.php'; ?>
