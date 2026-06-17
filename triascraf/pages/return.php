<?php require_once '../includes/config.php'; $pageTitle = 'Retur & Refund'; ?>
<?php include '../includes/header.php'; ?>
<div class="page-header">
    <h1>Retur &amp; Refund</h1>
    <p>Kebijakan pengembalian barang dan dana</p>
    <div class="breadcrumb"><a href="<?= BASE_URL ?>">Beranda</a><i class="fas fa-chevron-right" style="font-size:10px"></i><span>Retur & Refund</span></div>
</div>
<section style="padding:72px 24px;max-width:760px;margin:0 auto">
    <?php $sections = [
        ['title'=>'Syarat Retur','icon'=>'fas fa-check-circle','items'=>['Produk belum dipakai dan masih dalam kondisi semula','Retur dilaporkan dalam 3×24 jam setelah barang diterima','Sertakan foto produk dan bukti pembelian (no. order)','Produk cacat produksi atau tidak sesuai deskripsi']],
        ['title'=>'Yang Tidak Bisa Diretur','icon'=>'fas fa-times-circle','items'=>['Produk yang sudah dicuci atau dipakai','Kerusakan akibat kesalahan penggunaan pembeli','Produk yang dibeli saat promo/diskon khusus (kecuali cacat)','Laporan setelah lewat 3×24 jam dari penerimaan']],
        ['title'=>'Proses Refund','icon'=>'fas fa-coins','items'=>['Hubungi kami via WhatsApp dengan foto bukti produk bermasalah','Tim kami akan memverifikasi dalam 1×24 jam','Jika disetujui, pengembalian dana dilakukan dalam 3–5 hari kerja','Refund ke rekening yang sama dengan pembayaran awal']],
    ];
    foreach($sections as $s): ?>
    <div style="background:white;border-radius:var(--radius-lg);padding:28px;box-shadow:var(--shadow-sm);margin-bottom:20px">
        <h3 style="margin-bottom:16px;display:flex;align-items:center;gap:10px"><i class="<?= $s['icon'] ?>" style="color:var(--rose)"></i><?= $s['title'] ?></h3>
        <ul style="color:var(--muted);font-size:14px;line-height:2.2;padding-left:20px">
            <?php foreach($s['items'] as $item): ?><li><?= $item ?></li><?php endforeach; ?>
        </ul>
    </div>
    <?php endforeach; ?>
    <div style="text-align:center;margin-top:36px">
        <p style="color:var(--muted);margin-bottom:16px">Ada masalah dengan pesanan kamu?</p>
        <a href="https://wa.me/<?= getSetting($conn,'whatsapp_number') ?>" class="btn-wa" style="margin-top:0;display:inline-flex" target="_blank"><i class="fab fa-whatsapp"></i> Hubungi Tim Kami</a>
    </div>
</section>
<?php include '../includes/footer.php'; ?>
