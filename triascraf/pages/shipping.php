<?php require_once '../includes/config.php'; $pageTitle = 'Info Pengiriman'; ?>
<?php include '../includes/header.php'; ?>
<div class="page-header">
    <h1>Info Pengiriman</h1>
    <p>Informasi lengkap seputar pengiriman pesanan</p>
    <div class="breadcrumb"><a href="<?= BASE_URL ?>">Beranda</a><i class="fas fa-chevron-right" style="font-size:10px"></i><span>Info Pengiriman</span></div>
</div>
<section style="padding:72px 24px;max-width:900px;margin:0 auto">
    <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(260px,1fr));gap:20px;margin-bottom:48px">
        <?php $cards = [
            ['icon'=>'fas fa-clock','title'=>'Proses Pesanan','desc'=>'Pesanan diproses dalam 1×24 jam setelah konfirmasi pembayaran via WhatsApp.','color'=>'#F5DFD0'],
            ['icon'=>'fas fa-truck','title'=>'Estimasi Tiba','desc'=>'2–5 hari kerja untuk Jawa. 3–7 hari kerja untuk luar Jawa.','color'=>'var(--mint-light)'],
            ['icon'=>'fas fa-box-open','title'=>'Pengemasan','desc'=>'Setiap produk dikemas rapi dengan plastik bubble wrap dan kardus aman.','color'=>'var(--ivory)'],
        ];
        foreach($cards as $c): ?>
        <div style="background:white;border-radius:var(--radius-lg);padding:28px;box-shadow:var(--shadow-sm);text-align:center">
            <div style="width:60px;height:60px;background:<?= $c['color'] ?>;border-radius:50%;display:flex;align-items:center;justify-content:center;margin:0 auto 16px;font-size:24px;color:var(--rose)"><i class="<?= $c['icon'] ?>"></i></div>
            <h3 style="margin-bottom:8px"><?= $c['title'] ?></h3>
            <p style="color:var(--muted);font-size:14px"><?= $c['desc'] ?></p>
        </div>
        <?php endforeach; ?>
    </div>

    <div style="background:white;border-radius:var(--radius-lg);padding:32px;box-shadow:var(--shadow-sm);margin-bottom:24px">
        <h3 style="margin-bottom:20px;font-size:24px">Ekspedisi Tersedia</h3>
        <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:16px">
            <?php foreach(['JNE','J&T Express','SiCepat'] as $exp): ?>
            <div style="border:2px solid var(--ivory);border-radius:var(--radius);padding:16px;text-align:center;font-weight:700;color:var(--charcoal)"><?= $exp ?></div>
            <?php endforeach; ?>
        </div>
        <p style="margin-top:16px;font-size:13px;color:var(--muted)">* Ongkos kirim dihitung berdasarkan berat paket dan lokasi tujuan. Akan dikonfirmasi via WhatsApp sebelum pembayaran.</p>
    </div>

    <div style="background:var(--ivory);border-radius:var(--radius-lg);padding:24px">
        <h4 style="margin-bottom:12px"><i class="fas fa-info-circle" style="color:var(--rose);margin-right:8px"></i>Catatan Penting</h4>
        <ul style="color:var(--muted);font-size:14px;line-height:2;padding-left:20px">
            <li>Gratis ongkir untuk pembelian di atas Rp 150.000 (berlaku area tertentu)</li>
            <li>Pesanan dikirim setelah pembayaran dikonfirmasi</li>
            <li>Kami tidak bertanggung jawab atas keterlambatan akibat force majeure</li>
            <li>Pastikan alamat pengiriman sudah benar sebelum konfirmasi</li>
        </ul>
    </div>
</section>
<?php include '../includes/footer.php'; ?>
