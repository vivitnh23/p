<?php
require_once '../includes/config.php';
$pageTitle = 'FAQ';
?>
<?php include '../includes/header.php'; ?>

<div class="page-header">
    <h1>FAQ</h1>
    <p>Pertanyaan yang Sering Ditanyakan</p>
    <div class="breadcrumb">
        <a href="<?= BASE_URL ?>">Beranda</a>
        <i class="fas fa-chevron-right" style="font-size:10px"></i>
        <span>FAQ</span>
    </div>
</div>

<section style="padding:72px 24px;max-width:760px;margin:0 auto">
    <span class="section-label" style="text-align:center;display:block">Bantuan</span>
    <h2 class="section-title" style="text-align:center;margin-bottom:48px">Ada yang ingin kamu tanyakan?</h2>

    <?php
    $faqs = [
        ['q'=>'Bagaimana cara memesan produk?', 'a'=>'Kamu bisa pilih produk yang diinginkan, tambahkan ke keranjang, lalu isi data pengiriman di halaman checkout. Setelah itu kamu akan diarahkan ke WhatsApp untuk konfirmasi pesanan dengan tim kami.'],
        ['q'=>'Berapa lama proses pengiriman?', 'a'=>'Pesanan kami proses dalam 1×24 jam setelah konfirmasi pembayaran. Estimasi pengiriman 2–5 hari kerja tergantung lokasi tujuan menggunakan JNE, J&T, atau SiCepat.'],
        ['q'=>'Apakah bisa COD (bayar di tempat)?', 'a'=>'Saat ini kami belum menyediakan layanan COD. Pembayaran dilakukan via transfer bank setelah konfirmasi pesanan di WhatsApp.'],
        ['q'=>'Bagaimana jika produk yang diterima rusak?', 'a'=>'Jika produk yang kamu terima rusak atau tidak sesuai, segera hubungi kami via WhatsApp dalam 24 jam setelah barang diterima. Kami akan bantu proses penukaran.'],
        ['q'=>'Apakah bisa request warna/ukuran tertentu?', 'a'=>'Bisa! Kamu bisa menambahkan catatan di kolom "Catatan Pesanan" saat checkout, atau langsung tanyakan ke WhatsApp kami sebelum memesan.'],
        ['q'=>'Apakah tersedia program reseller?', 'a'=>'Ya, kami membuka kerjasama reseller dengan harga spesial. Hubungi kami via WhatsApp untuk informasi lebih lanjut tentang program reseller Triascraf.'],
        ['q'=>'Bagaimana cara melacak pesanan saya?', 'a'=>'Setelah pesanan dikirim, tim kami akan mengirimkan nomor resi via WhatsApp. Kamu bisa melacak paket di website ekspedisi yang bersangkutan.'],
        ['q'=>'Apakah ada garansi produk?', 'a'=>'Kami memberikan garansi kepuasan pelanggan. Jika produk tidak sesuai deskripsi atau ada cacat produksi, silakan hubungi kami dalam 3×24 jam setelah barang diterima.'],
    ];
    foreach($faqs as $i => $faq): ?>
    <div style="border:2px solid var(--ivory);border-radius:var(--radius);margin-bottom:12px;overflow:hidden;transition:border-color var(--transition)" onmouseover="this.style.borderColor='var(--rose)'" onmouseout="this.style.borderColor='var(--ivory)'">
        <button onclick="toggleFaq(<?= $i ?>)" style="width:100%;text-align:left;padding:20px 24px;background:white;border:none;cursor:pointer;display:flex;justify-content:space-between;align-items:center;font-family:'DM Sans',sans-serif;font-size:15px;font-weight:700;color:var(--charcoal)">
            <?= $faq['q'] ?>
            <i class="fas fa-plus" id="icon-<?= $i ?>" style="color:var(--rose);font-size:14px;flex-shrink:0;margin-left:16px;transition:transform .3s"></i>
        </button>
        <div id="faq-<?= $i ?>" style="display:none;padding:0 24px 20px;color:var(--muted);font-size:14px;line-height:1.85;background:white">
            <?= $faq['a'] ?>
        </div>
    </div>
    <?php endforeach; ?>

    <div style="background:linear-gradient(135deg,#2E1F0F,#6B4226);border-radius:var(--radius-lg);padding:36px;text-align:center;margin-top:48px">
        <h3 style="color:white;margin-bottom:10px">Masih ada pertanyaan?</h3>
        <p style="color:rgba(255,255,255,.65);margin-bottom:20px;font-size:14px">Tim kami siap membantu kamu via WhatsApp!</p>
        <a href="https://wa.me/<?= getSetting($conn,'whatsapp_number') ?>" class="btn-wa" style="margin-top:0;display:inline-flex" target="_blank">
            <i class="fab fa-whatsapp"></i> Chat WhatsApp Sekarang
        </a>
    </div>
</section>

<script>
function toggleFaq(i) {
    const content = document.getElementById('faq-'+i);
    const icon = document.getElementById('icon-'+i);
    const isOpen = content.style.display === 'block';
    content.style.display = isOpen ? 'none' : 'block';
    icon.style.transform = isOpen ? 'rotate(0)' : 'rotate(45deg)';
}
</script>

<?php include '../includes/footer.php'; ?>
