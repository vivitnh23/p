</div><footer class="footer">
    <div class="footer-container">
        <div class="footer-brand">
            <div class="footer-logo"><span class="logo-script">Trias</span><span class="logo-bold">carf</span></div>
            <p>Elegan dalam Balutan Syar'i.<br>Hijab berkualitas untuk perempuan modern yang menghargai keindahan dan kesederhanaan.</p>
            <div class="footer-social">
                <a href="https://www.instagram.com/<?= getSetting($conn, 'store_instagram') ?: 'triascarfofficial' ?>" target="_blank" title="Instagram"><i class="fab fa-instagram"></i></a>
                <a href="https://wa.me/<?= preg_replace('/[^0-9]/', '', getSetting($conn, 'whatsapp_number') ?: '083807066072') ?>" target="_blank" title="WhatsApp"><i class="fab fa-whatsapp"></i></a>
                <a href="https://tiktok.com/@triascarf" target="_blank" title="TikTok"><i class="fab fa-tiktok"></i></a>
            </div>
        </div>

        <div class="footer-links">
            <h4>Navigasi</h4>
            <a href="<?= BASE_URL ?>">Beranda</a>
            <a href="<?= BASE_URL ?>/pages/shop.php">Koleksi</a>
            <a href="<?= BASE_URL ?>/pages/about.php">Tentang Kami</a>
            <a href="<?= BASE_URL ?>/pages/contact.php">Kontak</a>
            <a href="<?= BASE_URL ?>/pages/testimonial.php">Tulis Ulasan</a>
        </div>

        <div class="footer-links">
            <h4>Bantuan</h4>
            <a href="<?= BASE_URL ?>/pages/faq.php">FAQ</a>
            <a href="<?= BASE_URL ?>/pages/shipping.php">Info Pengiriman</a>
            <a href="<?= BASE_URL ?>/pages/return.php">Retur &amp; Refund</a>
            <a href="<?= BASE_URL ?>/pages/privacy.php">Kebijakan Privasi</a>
        </div>

        <div class="footer-contact">
            <h4>Hubungi Kami</h4>
            <p><i class="fab fa-whatsapp"></i> <?= getSetting($conn, 'whatsapp_number') ?: '083807066072' ?></p>
            <p><i class="fas fa-envelope"></i> <?= getSetting($conn, 'store_email') ?: 'hello@triascraf.com' ?></p>
            <p><i class="fas fa-map-marker-alt"></i> <?= getSetting($conn, 'store_address') ?: 'Bekasi, Jawa Barat' ?></p>
            
            <a href="https://wa.me/<?= preg_replace('/[^0-9]/', '', getSetting($conn, 'whatsapp_number') ?: '083807066072') ?>" class="btn-wa" target="_blank">
                <i class="fab fa-whatsapp"></i> Chat WhatsApp
            </a>
        </div>
    </div> <div class="footer-bottom">
        <p>© <?= date('Y') ?> Triascarf. All rights reserved. Made with <i class="fas fa-heart" style="color:var(--rose)"></i> for muslimah Indonesia</p>
    </div>
</footer>

<script src="<?= BASE_URL ?>/assets/js/main.js"></script>
<?= isset($extraScript) ? $extraScript : '' ?>
</body>
</html>