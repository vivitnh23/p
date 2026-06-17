<?php require_once __DIR__ . '/config.php'; ?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($pageTitle) ? $pageTitle . ' — Triascarf' : 'Triascarf | Elegan dalam Balutan Syar\'i' ?></title>
    <link rel="icon" type="image/jpeg+xml" href="<?= BASE_URL ?>/assets/favicon.jpeg">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,300;0,400;0,600;1,300;1,400&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/style.css">
    <?= isset($extraHead) ? $extraHead : '' ?>
</head>
<body>

<!-- NAVBAR -->
<nav class="navbar">
    <div class="nav-container">
        <a href="<?= BASE_URL ?>" class="nav-logo">
            <span class="logo-script">Trias</span><span class="logo-bold">carf</span>
        </a>

        <div class="nav-links" id="navLinks">
            <a href="<?= BASE_URL ?>">Beranda</a>
            <a href="<?= BASE_URL ?>/pages/shop.php">Koleksi</a>
            <a href="<?= BASE_URL ?>/pages/about.php">Tentang</a>
            <a href="<?= BASE_URL ?>/pages/contact.php">Kontak</a>
        </div>

        <div class="nav-actions">
            <a href="<?= BASE_URL ?>/pages/cart.php" class="nav-icon" title="Keranjang">
                <i class="fas fa-shopping-bag"></i>
                <?php $cartCount = getCartCount($conn); if ($cartCount > 0): ?>
                <span class="badge"><?= $cartCount ?></span>
                <?php endif; ?>
            </a>
            <?php if (isLoggedIn()): ?>
                <a href="<?= BASE_URL ?>/pages/account.php" class="nav-icon" title="Akun"><i class="fas fa-user"></i></a>
                <a href="<?= BASE_URL ?>/pages/logout.php" class="btn-nav">Keluar</a>
            <?php else: ?>
                <a href="<?= BASE_URL ?>/pages/login.php" class="btn-nav">Masuk</a>
            <?php endif; ?>
            <button class="hamburger" onclick="toggleMenu()"><i class="fas fa-bars"></i></button>
        </div>
    </div>
</nav>

<div class="page-content">