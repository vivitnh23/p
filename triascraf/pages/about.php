<?php
require_once '../includes/config.php';
$pageTitle = 'Tentang Kami';
?>
<?php include '../includes/header.php'; ?>

<div class="page-header">
    <h1>Tentang Triascraf</h1>
    <p>Elegan dalam Balutan Syar'i</p>
    <div class="breadcrumb">
        <a href="<?= BASE_URL ?>">Beranda</a>
        <i class="fas fa-chevron-right" style="font-size:10px"></i>
        <span>Tentang Kami</span>
    </div>
</div>

<section class="about-section">
    <div class="about-container">

        <!-- STORY -->
        <div class="about-grid">
            <div class="about-content">
                <span class="section-label">Cerita Kami</span>
                <h2 class="section-title">Hijab Premium untuk<br>Muslimah Modern</h2>
                <p>Triascraf hadir sebagai brand hijab yang mengutamakan kualitas, kenyamanan, dan keindahan dalam setiap produk. Kami percaya bahwa setiap muslimah berhak tampil elegan tanpa meninggalkan nilai-nilai syar'i.</p>
                <p>Dengan pilihan bahan premium, warna yang timeless, dan desain yang modern, kami berkomitmen memberikan pengalaman berbelanja terbaik bagi pelanggan di seluruh Indonesia.</p>
                <p>Berawal dari kecintaan terhadap fashion muslim, Triascraf kini hadir dengan ratusan koleksi — dari hijab voal lembut hingga pashmina mewah untuk acara spesial.</p>
                <div style="margin-top:28px;display:flex;gap:16px;flex-wrap:wrap">
                    <a href="<?= BASE_URL ?>/pages/shop.php" class="btn btn-primary"><i class="fas fa-shopping-bag"></i> Lihat Koleksi</a>
                    <a href="https://wa.me/<?= getSetting($conn,'whatsapp_number') ?>" class="btn-wa" style="margin-top:0" target="_blank"><i class="fab fa-whatsapp"></i> Chat Kami</a>
                </div>
            </div>
            <div class="about-image">
                <img src="<?= BASE_URL ?>/assets/images/about-image.jpg" alt="Tim Triascraf" class="about-img-real">
            </div>
        </div>

        <!-- STATS -->
        <div style="display:grid;grid-template-columns:repeat(4,1fr);gap:20px;margin-bottom:64px">
            <?php
            $stats = [
                ['num'=>'500+', 'label'=>'Produk Terjual',  'icon'=>'fas fa-shopping-bag'],
                ['num'=>'200+', 'label'=>'Pelanggan Puas',  'icon'=>'fas fa-users'],
                ['num'=>'4.9★', 'label'=>'Rating Toko',     'icon'=>'fas fa-star'],
                ['num'=>'50+',  'label'=>'Varian Produk',   'icon'=>'fas fa-tags'],
            ];
            foreach($stats as $s): ?>
            <div style="background:white;border-radius:var(--radius-lg);padding:28px 20px;text-align:center;box-shadow:var(--shadow-sm);border:2px solid transparent;transition:border-color var(--transition)" onmouseover="this.style.borderColor='var(--rose)'" onmouseout="this.style.borderColor='transparent'">
                <i class="<?= $s['icon'] ?>" style="font-size:28px;color:var(--rose);margin-bottom:12px;display:block"></i>
                <div style="font-family:'Cormorant Garamond',serif;font-size:32px;font-weight:700;color:var(--charcoal)"><?= $s['num'] ?></div>
                <div style="font-size:13px;color:var(--muted);margin-top:4px"><?= $s['label'] ?></div>
            </div>
            <?php endforeach; ?>
        </div>

        <!-- FEATURES -->
        <div style="text-align:center;margin-bottom:40px">
            <span class="section-label">Keunggulan Kami</span>
            <h2 class="section-title">Mengapa Memilih Triascraf?</h2>
        </div>
        <div class="about-features">
            <div class="about-card">
                <i class="fas fa-award"></i>
                <h3>Kualitas Premium</h3>
                <p>Setiap produk dipilih dari bahan terbaik yang nyaman dipakai seharian — lembut, breathable, dan tahan lama.</p>
            </div>
            <div class="about-card">
                <i class="fas fa-shipping-fast"></i>
                <h3>Pengiriman Cepat</h3>
                <p>Pesanan diproses dalam 1x24 jam dan dikirim via ekspedisi terpercaya ke seluruh penjuru Indonesia.</p>
            </div>
            <div class="about-card">
                <i class="fas fa-heart"></i>
                <h3>Pelayanan Ramah</h3>
                <p>Tim kami siap membantu via WhatsApp setiap hari. Kepuasan pelanggan adalah prioritas utama kami.</p>
            </div>
        </div>

        <!-- CTA -->
        <div style="background:linear-gradient(135deg,#2E1F0F,#6B4226,#C0714F);border-radius:var(--radius-lg);padding:52px;text-align:center;margin-top:64px;position:relative;overflow:hidden">
            <div style="position:absolute;width:280px;height:280px;background:rgba(255,255,255,.05);border-radius:50%;top:-80px;right:-60px"></div>
            <span style="font-size:11px;letter-spacing:.24em;text-transform:uppercase;color:#F5C9AA;font-weight:700;display:block;margin-bottom:12px">Mulai Sekarang</span>
            <h2 style="color:white;font-size:36px;margin-bottom:12px">Temukan Hijab Impianmu</h2>
            <p style="color:rgba(255,255,255,.65);margin-bottom:28px">Jelajahi ratusan koleksi hijab premium kami dan tampil memukau setiap hari.</p>
            <a href="<?= BASE_URL ?>/pages/shop.php" class="btn btn-gold"><i class="fas fa-shopping-bag"></i> Belanja Sekarang</a>
        </div>

    </div>
</section>

<?php include '../includes/footer.php'; ?>
