<?php
require_once '../includes/config.php';
$pageTitle = 'Keranjang';

// Handle add to cart
if (isset($_GET['add'])) {
    $pid = intval($_GET['add']);
    $prod = $conn->query("SELECT * FROM products WHERE id = $pid AND is_active=1 AND stock > 0")->fetch_assoc();
    if ($prod) {
        if (!isset($_SESSION['cart'])) $_SESSION['cart'] = [];
        if (isset($_SESSION['cart'][$pid])) $_SESSION['cart'][$pid]['qty']++;
        else $_SESSION['cart'][$pid] = ['id' => $pid, 'qty' => 1];
        $_SESSION['flash_success'] = 'Produk ditambahkan ke keranjang!';
    }
    redirect('/pages/cart.php');
}

// Handle form add (with qty)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    if ($_POST['action'] === 'add') {
        $pid = intval($_POST['product_id']);
        $qty = max(1, intval($_POST['qty']));
        $prod = $conn->query("SELECT * FROM products WHERE id = $pid AND is_active=1")->fetch_assoc();
        if ($prod) {
            if (!isset($_SESSION['cart'])) $_SESSION['cart'] = [];
            if (isset($_SESSION['cart'][$pid])) $_SESSION['cart'][$pid]['qty'] += $qty;
            else $_SESSION['cart'][$pid] = ['id' => $pid, 'qty' => $qty];
            $_SESSION['flash_success'] = 'Produk ditambahkan ke keranjang!';
        }
        redirect('/pages/cart.php');
    }
    if ($_POST['action'] === 'update') {
        foreach ($_POST['qty'] as $pid => $qty) {
            $pid = intval($pid); $qty = intval($qty);
            if ($qty <= 0) unset($_SESSION['cart'][$pid]);
            else $_SESSION['cart'][$pid]['qty'] = $qty;
        }
        $_SESSION['flash_success'] = 'Keranjang diperbarui!';
        redirect('/pages/cart.php');
    }
    if ($_POST['action'] === 'remove') {
        $pid = intval($_POST['product_id']);
        unset($_SESSION['cart'][$pid]);
        $_SESSION['flash_success'] = 'Produk dihapus dari keranjang.';
        redirect('/pages/cart.php');
    }
}

// Load cart items from DB
$cartItems = [];
$total = 0;
if (!empty($_SESSION['cart'])) {
    $ids = implode(',', array_map('intval', array_keys($_SESSION['cart'])));
    $products = $conn->query("SELECT * FROM products WHERE id IN ($ids)");
    while ($p = $products->fetch_assoc()) {
        $qty = $_SESSION['cart'][$p['id']]['qty'];
        $cartItems[] = array_merge($p, ['qty' => $qty, 'subtotal' => $p['price'] * $qty]);
        $total += $p['price'] * $qty;
    }
}
?>
<?php include '../includes/header.php'; ?>

<div class="page-header">
    <h1>Keranjang Belanja</h1>
    <p><?= count($cartItems) ?> produk dalam keranjang</p>
</div>

<?php if (isset($_SESSION['flash_success'])): ?>
<div style="max-width:1100px;margin:20px auto;padding:0 24px">
    <div class="alert alert-success"><i class="fas fa-check-circle"></i> <?= $_SESSION['flash_success'] ?></div>
</div>
<?php unset($_SESSION['flash_success']); ?>
<?php endif; ?>

<?php if (empty($cartItems)): ?>
<div class="empty-state">
    <i class="fas fa-shopping-bag"></i>
    <h3>Keranjang kosong</h3>
    <p>Belum ada produk yang ditambahkan ke keranjang.</p>
    <a href="<?= BASE_URL ?>/pages/shop.php" class="btn btn-primary"><i class="fas fa-shopping-bag"></i> Mulai Belanja</a>
</div>

<?php else: ?>
<div class="cart-layout">
    <!-- Cart Items -->
    <div>
        <form method="POST">
            <input type="hidden" name="action" value="update">
            <div class="admin-table-box">
                <table class="cart-table">
                    <thead>
                        <tr>
                            <th>Produk</th>
                            <th>Harga</th>
                            <th>Jumlah</th>
                            <th>Subtotal</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($cartItems as $item): ?>
                        <tr>
                            <td>
                                <div class="cart-product-info">
                                    <?php if ($item['image']): ?>
                                    <img src="<?= UPLOAD_URL . htmlspecialchars($item['image']) ?>" class="cart-product-img" alt="">
                                    <?php else: ?>
                                    <div class="cart-product-img" style="display:flex;align-items:center;justify-content:center;background:var(--ivory)"><i class="fas fa-image" style="color:var(--muted)"></i></div>
                                    <?php endif; ?>
                                    <div>
                                        <a href="<?= BASE_URL ?>/pages/product.php?slug=<?= $item['slug'] ?>" style="text-decoration:none;color:var(--charcoal);font-weight:500"><?= htmlspecialchars($item['name']) ?></a>
                                        <div style="font-size:12px;color:var(--muted);margin-top:4px">Stok: <?= $item['stock'] ?></div>
                                    </div>
                                </div>
                            </td>
                            <td><?= formatPrice($item['price']) ?></td>
                            <td>
                                <div class="qty-control">
                                    <button type="button" class="qty-btn" onclick="changeQty(this.nextElementSibling, -1)">−</button>
                                    <input type="number" name="qty[<?= $item['id'] ?>]" class="qty-input" value="<?= $item['qty'] ?>" min="0" max="<?= $item['stock'] ?>">
                                    <button type="button" class="qty-btn" onclick="changeQty(this.previousElementSibling, 1)">+</button>
                                </div>
                            </td>
                            <td style="font-weight:600;color:var(--rose-gold)"><?= formatPrice($item['subtotal']) ?></td>
                            <td>
                                <form method="POST" style="display:inline">
                                    <input type="hidden" name="action" value="remove">
                                    <input type="hidden" name="product_id" value="<?= $item['id'] ?>">
                                    <button type="submit" style="background:none;border:none;cursor:pointer;color:var(--muted);font-size:16px" onclick="return confirm('Hapus produk ini?')">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <div style="margin-top:16px;display:flex;gap:12px">
                <button type="submit" class="btn btn-outline btn-sm"><i class="fas fa-sync"></i> Perbarui Keranjang</button>
                <a href="<?= BASE_URL ?>/pages/shop.php" class="btn btn-outline btn-sm"><i class="fas fa-arrow-left"></i> Lanjut Belanja</a>
            </div>
        </form>
    </div>

    <!-- Order Summary -->
    <div class="order-summary">
        <h3>Ringkasan Pesanan</h3>
        <?php foreach ($cartItems as $item): ?>
        <div class="summary-row">
            <span><?= htmlspecialchars(mb_strimwidth($item['name'], 0, 25, '...')) ?> ×<?= $item['qty'] ?></span>
            <span><?= formatPrice($item['subtotal']) ?></span>
        </div>
        <?php endforeach; ?>
        <hr class="summary-divider">
        <div class="summary-row summary-total">
            <span>Total</span>
            <span style="color:var(--rose-gold)"><?= formatPrice($total) ?></span>
        </div>
        <div style="margin-top:8px;margin-bottom:20px">
            <div class="summary-row" style="color:var(--muted);font-size:13px">
                <span>Ongkos kirim</span>
                <span>Dihitung saat checkout</span>
            </div>
        </div>
        <a href="<?= BASE_URL ?>/pages/checkout.php" class="btn btn-gold btn-block">
            <i class="fas fa-shopping-bag"></i> Lanjut ke Checkout
        </a>
        <a href="https://wa.me/<?= getSetting($conn, 'whatsapp_number') ?>" class="btn-wa" target="_blank" style="display:block;text-align:center;margin-top:0">
            <i class="fab fa-whatsapp"></i> Pesan via WhatsApp
        </a>
    </div>
</div>
<?php endif; ?>

<?php include '../includes/footer.php'; ?>
