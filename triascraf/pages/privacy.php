<?php require_once '../includes/config.php'; $pageTitle = 'Kebijakan Privasi'; ?>
<?php include '../includes/header.php'; ?>
<div class="page-header">
    <h1>Kebijakan Privasi</h1>
    <p>Terakhir diperbarui: <?= date('d F Y') ?></p>
    <div class="breadcrumb"><a href="<?= BASE_URL ?>">Beranda</a><i class="fas fa-chevron-right" style="font-size:10px"></i><span>Kebijakan Privasi</span></div>
</div>
<section style="padding:72px 24px;max-width:760px;margin:0 auto">
    <?php $privacies = [
        ['title'=>'Informasi yang Kami Kumpulkan','content'=>'Kami mengumpulkan informasi yang kamu berikan saat mendaftar atau melakukan pemesanan, meliputi: nama lengkap, alamat email, nomor telepon, dan alamat pengiriman. Informasi ini digunakan semata-mata untuk memproses pesananmu.'],
        ['title'=>'Penggunaan Informasi','content'=>'Data yang kamu berikan digunakan untuk memproses pesanan, mengirimkan konfirmasi pesanan via WhatsApp atau email, meningkatkan layanan kami, dan menghubungi kamu jika ada pertanyaan terkait pesanan.'],
        ['title'=>'Keamanan Data','content'=>'Kami berkomitmen menjaga keamanan data pribadimu. Data disimpan di server yang aman dan tidak akan dibagikan kepada pihak ketiga tanpa persetujuanmu, kecuali yang diperlukan untuk memproses pengiriman.'],
        ['title'=>'Cookies','content'=>'Website kami menggunakan cookies untuk meningkatkan pengalaman pengguna, seperti mengingat isi keranjang belanja. Kamu dapat menonaktifkan cookies melalui pengaturan browser, namun beberapa fitur mungkin tidak berfungsi optimal.'],
        ['title'=>'Hubungi Kami','content'=>'Jika kamu memiliki pertanyaan tentang kebijakan privasi ini, silakan hubungi kami via WhatsApp atau email di hello@triascraf.com.'],
    ];
    foreach($privacies as $p): ?>
    <div style="margin-bottom:32px">
        <h3 style="font-size:22px;margin-bottom:12px;color:var(--charcoal)"><?= $p['title'] ?></h3>
        <p style="color:var(--muted);line-height:1.9;font-size:15px"><?= $p['content'] ?></p>
    </div>
    <hr style="border:none;border-top:1px solid var(--ivory);margin-bottom:32px">
    <?php endforeach; ?>
</section>
<?php include '../includes/footer.php'; ?>
