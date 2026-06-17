<?php
require_once '../includes/config.php';
$pageTitle = 'Kontak';

$success = $err = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name    = sanitize($_POST['name'] ?? '');
    $email   = sanitize($_POST['email'] ?? '');
    $subject = sanitize($_POST['subject'] ?? '');
    $message = sanitize($_POST['message'] ?? '');

    if (!$name || !$email || !$message) {
        $err = 'Nama, email, dan pesan wajib diisi.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $err = 'Format email tidak valid.';
    } else {
        // Arahkan ke WA dengan isi pesan
        $waNumber = getSetting($conn, 'whatsapp_number');
        $waMsg = urlencode("Halo Triascraf! 👋\n\nNama: $name\nEmail: $email\nSubjek: $subject\n\nPesan:\n$message");
        $waUrl = "https://wa.me/$waNumber?text=$waMsg";
        header("Location: $waUrl");
        exit;
    }
}
?>
<?php include '../includes/header.php'; ?>

<div class="page-header">
    <h1>Hubungi Kami</h1>
    <p>Kami siap membantu kebutuhan kamu</p>
    <div class="breadcrumb">
        <a href="<?= BASE_URL ?>">Beranda</a>
        <i class="fas fa-chevron-right" style="font-size:10px"></i>
        <span>Kontak</span>
    </div>
</div>

<section class="contact-section">
    <div class="contact-container">

        <!-- INFO KONTAK -->
        <div class="contact-info">
            <div style="margin-bottom:28px">
                <span class="section-label">Ayo Ngobrol</span>
                <h2 style="font-size:32px;margin-bottom:10px">Ada yang bisa<br>kami bantu?</h2>
                <p style="color:var(--muted);font-size:15px">Hubungi kami lewat WhatsApp, email, atau isi form di samping. Kami biasanya membalas dalam 1 jam!</p>
            </div>

            <div class="contact-item">
                <i class="fas fa-map-marker-alt"></i>
                <div>
                    <h4 style="font-size:14px;font-weight:700;color:var(--charcoal);margin-bottom:4px">Alamat</h4>
                    <p style="color:var(--muted);font-size:14px"><?= getSetting($conn,'store_address') ?: 'Bekasi, Jawa Barat' ?></p>
                </div>
            </div>

            <div class="contact-item">
                <i class="fab fa-whatsapp" style="color:#25D366"></i>
                <div>
                    <h4 style="font-size:14px;font-weight:700;color:var(--charcoal);margin-bottom:4px">WhatsApp</h4>
                    <?php $wa = getSetting($conn,'whatsapp_number'); ?>
                    <a href="https://wa.me/<?= $wa ?>" target="_blank" style="color:var(--rose);font-weight:600;text-decoration:none;font-size:14px">
                        <?= $wa ?>
                    </a>
                </div>
            </div>

            <div class="contact-item">
                <i class="fas fa-envelope"></i>
                <div>
                    <h4 style="font-size:14px;font-weight:700;color:var(--charcoal);margin-bottom:4px">Email</h4>
                    <p style="color:var(--muted);font-size:14px"><?= getSetting($conn,'store_email') ?: 'hello@triascraf.com' ?></p>
                </div>
            </div>

            <div class="contact-item">
                <i class="fab fa-instagram" style="color:#E1306C"></i>
                <div>
                    <h4 style="font-size:14px;font-weight:700;color:var(--charcoal);margin-bottom:4px">Instagram</h4>
                    <a href="https://instagram.com/<?= getSetting($conn,'store_instagram') ?>" target="_blank" style="color:var(--rose);font-weight:600;text-decoration:none;font-size:14px">
                        @<?= getSetting($conn,'store_instagram') ?: 'triascrafofficial' ?>
                    </a>
                </div>
            </div>

            <!-- WA Quick Button -->
            <a href="https://wa.me/<?= getSetting($conn,'whatsapp_number') ?>" target="_blank" class="btn-wa" style="display:inline-flex;margin-top:8px">
                <i class="fab fa-whatsapp"></i> Chat Langsung via WhatsApp
            </a>

            <!-- Jam operasional -->
            <div style="margin-top:24px;background:var(--ivory);border-radius:var(--radius);padding:18px 20px">
                <div style="font-size:12px;letter-spacing:.1em;text-transform:uppercase;color:var(--rose);font-weight:700;margin-bottom:10px">Jam Operasional</div>
                <div style="font-size:14px;color:var(--muted);line-height:2">
                    Senin – Jumat: <strong style="color:var(--charcoal)">08.00 – 17.00</strong><br>
                    Sabtu: <strong style="color:var(--charcoal)">08.00 – 13.00</strong><br>
                    Minggu & Hari Libur: <strong style="color:var(--charcoal)">Tutup</strong>
                </div>
            </div>
        </div>

        <!-- FORM -->
        <div class="contact-form">
            <h3 style="font-size:24px;margin-bottom:6px">Kirim Pesan</h3>
            <p style="color:var(--muted);font-size:14px;margin-bottom:24px">Pesan akan diteruskan ke WhatsApp kami.</p>

            <?php if ($err): ?>
            <div class="alert alert-error"><i class="fas fa-exclamation-circle"></i> <?= $err ?></div>
            <?php endif; ?>

            <form method="POST">
                <div class="form-row">
                    <div class="form-group">
                        <label>Nama Lengkap *</label>
                        <input type="text" name="name" class="form-control" value="<?= htmlspecialchars($_POST['name'] ?? '') ?>" placeholder="Nama kamu" required>
                    </div>
                    <div class="form-group">
                        <label>Email *</label>
                        <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($_POST['email'] ?? '') ?>" placeholder="email@kamu.com" required>
                    </div>
                </div>
                <div class="form-group">
                    <label>Subjek</label>
                    <select name="subject" class="form-control">
                        <option value="Tanya Produk">Tanya Produk</option>
                        <option value="Status Pesanan">Status Pesanan</option>
                        <option value="Komplain">Komplain</option>
                        <option value="Kerjasama">Kerjasama / Reseller</option>
                        <option value="Lainnya">Lainnya</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Pesan *</label>
                    <textarea name="message" class="form-control" rows="5" placeholder="Tulis pesanmu di sini..." required><?= htmlspecialchars($_POST['message'] ?? '') ?></textarea>
                </div>
                <button type="submit" class="btn btn-primary btn-block" style="font-size:15px;padding:14px">
                    <i class="fab fa-whatsapp"></i> Kirim via WhatsApp
                </button>
                <p style="font-size:12px;color:var(--muted);text-align:center;margin-top:12px">
                    <i class="fas fa-lock"></i> Pesan kamu aman dan hanya akan diterima oleh tim Triascraf
                </p>
            </form>
        </div>

    </div>
</section>

<?php include '../includes/footer.php'; ?>
