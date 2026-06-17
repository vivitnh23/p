<?php require_once __DIR__ . '/../../includes/config.php'; ?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/jpeg+xml" href="<?= BASE_URL ?>/assets/favicon.jpeg">
    <title><?= isset($adminTitle) ? $adminTitle . ' — Admin Triascarf' : 'Admin Panel — Triascarf' ?></title>
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,300;0,400;0,600;1,300;1,400&family=DM+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/style.css">
    <?= isset($extraHead) ? $extraHead : '' ?>
</head>
<body>

<!-- Overlay untuk tutup sidebar saat klik di luar -->
<div class="admin-sidebar-overlay" id="sidebarOverlay" onclick="toggleAdminSidebar()"></div>

<div class="admin-layout">
    <!-- SIDEBAR -->
    <aside class="admin-sidebar" id="adminSidebar">
        <div class="admin-sidebar-logo">
            <span class="logo-script">Trias</span><span class="logo-bold">carf</span>
            <div style="font-size:10px;color:rgba(255,255,255,.35);margin-top:4px;letter-spacing:.14em;text-transform:uppercase">Admin Panel</div>
        </div>
        <nav class="admin-nav">
            <div class="nav-group-label">Utama</div>
            <a href="<?= BASE_URL ?>/admin/" class="<?= basename($_SERVER['PHP_SELF']) === 'index.php' ? 'active' : '' ?>">
                <i class="fas fa-chart-line"></i> Dashboard
            </a>
            <div class="nav-group-label">Toko</div>
            <a href="<?= BASE_URL ?>/admin/pages/products.php" class="<?= basename($_SERVER['PHP_SELF']) === 'products.php' ? 'active' : '' ?>">
                <i class="fas fa-tags"></i> Produk
            </a>
            <a href="<?= BASE_URL ?>/admin/pages/categories.php" class="<?= basename($_SERVER['PHP_SELF']) === 'categories.php' ? 'active' : '' ?>">
                <i class="fas fa-folder-open"></i> Kategori
            </a>
            <a href="<?= BASE_URL ?>/admin/pages/orders.php" class="<?= basename($_SERVER['PHP_SELF']) === 'orders.php' ? 'active' : '' ?>">
                <i class="fas fa-shopping-bag"></i> Pesanan
            </a>
            <div class="nav-group-label">Pengguna</div>
            <a href="<?= BASE_URL ?>/admin/pages/users.php" class="<?= basename($_SERVER['PHP_SELF']) === 'users.php' ? 'active' : '' ?>">
                <i class="fas fa-users"></i> Pelanggan
            </a>
            <a href="<?= BASE_URL ?>/admin/pages/testimonials.php" class="<?= basename($_SERVER['PHP_SELF']) === 'testimonials.php' ? 'active' : '' ?>">
                <i class="fas fa-star"></i> Testimoni
            </a>
            <div class="nav-group-label">Pengaturan</div>
            <a href="<?= BASE_URL ?>/admin/pages/settings.php" class="<?= basename($_SERVER['PHP_SELF']) === 'settings.php' ? 'active' : '' ?>">
                <i class="fas fa-cog"></i> Pengaturan
            </a>
            <a href="<?= BASE_URL ?>/" target="_blank">
                <i class="fas fa-external-link-alt"></i> Lihat Toko
            </a>
            <a href="<?= BASE_URL ?>/pages/logout.php" style="margin-top:16px;padding-top:16px;border-top:1px solid rgba(255,255,255,.08)">
                <i class="fas fa-sign-out-alt"></i> Keluar
            </a>
        </nav>
    </aside>

    <!-- MAIN -->
    <div class="admin-main">
        <div class="admin-topbar">
            <div style="display:flex;align-items:center;gap:12px">
                <!-- Hamburger hanya muncul di mobile -->
                <button class="admin-hamburger" onclick="toggleAdminSidebar()" title="Menu">
                    <i class="fas fa-bars"></i>
                </button>
                <h2><?= isset($adminTitle) ? $adminTitle : 'Dashboard' ?></h2>
            </div>
            <div style="display:flex;align-items:center;gap:12px">
                <span style="font-size:13px;color:var(--muted)">👋 <strong style="color:var(--charcoal)"><?= $_SESSION['user_name'] ?></strong></span>
                <a href="<?= BASE_URL ?>/pages/logout.php" class="btn btn-outline btn-sm">Keluar</a>
            </div>
        </div>
        <div class="admin-content">