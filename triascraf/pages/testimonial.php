<?php
require_once '../includes/config.php';
$pageTitle = 'Tulis Ulasan';

$success = $err = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name    = sanitize($_POST['name'] ?? '');
    $message = sanitize($_POST['message'] ?? '');
    $rating  = intval($_POST['rating'] ?? 5);
    $uid     = isLoggedIn() ? $_SESSION['user_id'] : null;

    if (!$name || !$message) {
        $err = 'Nama dan pesan wajib diisi.';
    } elseif ($rating < 1 || $rating > 5) {
        $err = 'Rating tidak valid.';
    } else {
        $rating  = max(1, min(5, $rating));
        $uid_val = $uid ? $uid : 'NULL';
        $stmt = $conn->prepare("INSERT INTO testimonials (user_id, name, message, rating, is_approved) VALUES (?, ?, ?, ?, 0)");
        $stmt->bind_param('issi', $uid, $name, $message, $rating);
        $stmt->execute();
        $success = true;
    }
}

// Ambil testimoni yang sudah disetujui
$testimonials = $conn->query("SELECT * FROM testimonials WHERE is_approved = 1 ORDER BY created_at DESC LIMIT 12");
?>
<?php include '../includes/header.php'; ?>

<div class="page-header">
    <h1>Ulasan Pelanggan</h1>
    <p>Apa kata mereka tentang Triascraf?</p>
    <div class="breadcrumb">
        <a href="<?= BASE_URL ?>">Beranda</a>
        <i class="fas fa-chevron-right" style="font-size:10px"></i>
        <span>Ulasan</span>
    </div>
</div>

<div style="max-width:1100px;margin:0 auto;padding:60px 24px;display:grid;grid-template-columns:1fr 380px;gap:40px;align-items:start" class="testi-layout">

    <!-- DAFTAR ULASAN -->
    <div>
        <div style="margin-bottom:32px">
            <span class="section-label">Kata Mereka</span>
            <h2 class="section-title">Ulasan Pelanggan</h2>
        </div>

        <?php if ($testimonials->num_rows > 0): ?>
        <div style="display:flex;flex-direction:column;gap:16px">
            <?php while($t = $testimonials->fetch_assoc()): ?>
            <div class="testimonial-card">
                <div class="testimonial-stars"><?= str_repeat('★', $t['rating']) ?><?= str_repeat('☆', 5 - $t['rating']) ?></div>
                <p class="testimonial-text">"<?= htmlspecialchars($t['message']) ?>"</p>
                <div class="testimonial-author">
                    <div class="testimonial-avatar"><?= strtoupper(substr($t['name'],0,1)) ?></div>
                    <div>
                        <div class="testimonial-name"><?= htmlspecialchars($t['name']) ?></div>
                        <div class="testimonial-role"><?= date('d M Y', strtotime($t['created_at'])) ?></div>
                    </div>
                </div>
            </div>
            <?php endwhile; ?>
        </div>
        <?php else: ?>
        <div class="empty-state" style="padding:48px 24px">
            <i class="fas fa-star" style="font-size:48px;color:var(--ivory);display:block;margin-bottom:16px"></i>
            <h3>Belum ada ulasan</h3>
            <p>Jadilah yang pertama memberikan ulasan!</p>
        </div>
        <?php endif; ?>
    </div>

    <!-- FORM TULIS ULASAN -->
    <div style="position:sticky;top:88px">
        <?php if ($success): ?>
        <div style="background:white;border-radius:var(--radius-lg);padding:36px;box-shadow:var(--shadow-md);text-align:center">
            <div style="width:72px;height:72px;background:#D9EDDB;border-radius:50%;display:flex;align-items:center;justify-content:center;margin:0 auto 20px;font-size:32px;color:var(--success)">✓</div>
            <h3 style="margin-bottom:10px">Terima Kasih!</h3>
            <p style="color:var(--muted);font-size:14px;margin-bottom:24px">Ulasanmu sudah kami terima dan akan ditampilkan setelah disetujui admin.</p>
            <a href="<?= BASE_URL ?>/pages/testimonial.php" class="btn btn-primary btn-block">Tulis Ulasan Lain</a>
            <a href="<?= BASE_URL ?>/pages/shop.php" class="btn btn-outline btn-block" style="margin-top:10px">Belanja Lagi</a>
        </div>

        <?php else: ?>
        <div style="background:white;border-radius:var(--radius-lg);padding:32px;box-shadow:var(--shadow-md)">
            <h3 style="font-size:24px;margin-bottom:6px">Tulis Ulasanmu</h3>
            <p style="color:var(--muted);font-size:14px;margin-bottom:24px">Bagikan pengalamanmu berbelanja di Triascraf 🧕</p>

            <?php if ($err): ?>
            <div class="alert alert-error"><i class="fas fa-exclamation-circle"></i> <?= $err ?></div>
            <?php endif; ?>

            <form method="POST">
                <div class="form-group">
                    <label>Nama Lengkap *</label>
                    <input type="text" name="name" class="form-control"
                        value="<?= isLoggedIn() ? htmlspecialchars($_SESSION['user_name']) : htmlspecialchars($_POST['name'] ?? '') ?>"
                        <?= isLoggedIn() ? 'readonly style="background:var(--ivory)"' : '' ?>
                        placeholder="Nama kamu" required>
                </div>

                <!-- STAR RATING -->
                <div class="form-group">
                    <label>Rating *</label>
                    <div style="display:flex;gap:8px;margin-top:4px" id="starContainer">
                        <?php for($i=1;$i<=5;$i++): ?>
                        <label style="cursor:pointer;font-size:32px;color:var(--ivory);transition:color .2s;line-height:1" title="<?= $i ?> bintang">
                            <input type="radio" name="rating" value="<?= $i ?>" style="display:none" <?= $i==5?'checked':'' ?>>
                            <span class="star-btn" data-val="<?= $i ?>">★</span>
                        </label>
                        <?php endfor; ?>
                    </div>
                    <div id="ratingLabel" style="font-size:13px;color:var(--muted);margin-top:6px">5 bintang — Sangat Puas</div>
                </div>

                <div class="form-group">
                    <label>Ulasan *</label>
                    <textarea name="message" class="form-control" rows="4"
                        placeholder="Ceritakan pengalamanmu... Produknya bagaimana? Pelayanannya? Apakah sesuai ekspektasi?" required><?= htmlspecialchars($_POST['message'] ?? '') ?></textarea>
                </div>

                <div style="background:var(--ivory);border-radius:var(--radius);padding:14px 16px;font-size:13px;color:var(--muted);margin-bottom:20px">
                    <i class="fas fa-info-circle" style="color:var(--rose);margin-right:6px"></i>
                    Ulasan akan ditampilkan setelah disetujui oleh admin Triascraf.
                </div>

                <button type="submit" class="btn btn-primary btn-block">
                    <i class="fas fa-paper-plane"></i> Kirim Ulasan
                </button>

                <?php if (!isLoggedIn()): ?>
                <p style="text-align:center;font-size:13px;color:var(--muted);margin-top:14px">
                    Punya akun? <a href="<?= BASE_URL ?>/pages/login.php" style="color:var(--rose);font-weight:700">Masuk</a> untuk ulasan yang lebih mudah.
                </p>
                <?php endif; ?>
            </form>
        </div>
        <?php endif; ?>
    </div>
</div>

<style>
@media(max-width:768px){.testi-layout{grid-template-columns:1fr!important;}}
.star-btn{display:block;}
</style>

<script>
const stars  = document.querySelectorAll('.star-btn');
const labels = ['','1 bintang — Sangat Tidak Puas','2 bintang — Tidak Puas','3 bintang — Cukup','4 bintang — Puas','5 bintang — Sangat Puas'];

function setStars(val) {
    stars.forEach((s,i) => {
        s.parentElement.style.color = i < val ? 'var(--rose)' : 'var(--ivory)';
    });
    document.getElementById('ratingLabel').textContent = labels[val];
}

// Default 5 bintang
setStars(5);

stars.forEach(s => {
    s.addEventListener('click', () => {
        setStars(parseInt(s.dataset.val));
        s.previousElementSibling.checked = true;
    });
    s.addEventListener('mouseover', () => setStars(parseInt(s.dataset.val)));
    s.parentElement.addEventListener('mouseleave', () => {
        const checked = document.querySelector('input[name="rating"]:checked');
        setStars(checked ? parseInt(checked.value) : 5);
    });
});
</script>

<?php include '../includes/footer.php'; ?>
