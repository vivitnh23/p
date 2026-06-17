<?php
// ============================================
// KONFIGURASI DATABASE & APLIKASI
// Ganti sesuai hosting kamu
// ============================================

define('DB_HOST', 'localhost');
define('DB_USER', 'root');         // Ganti dengan user DB hosting
define('DB_PASS', '');             // Ganti dengan password DB hosting
define('DB_NAME', 'triascraf_db');

define('BASE_URL', 'http://localhost/triascraf');  // Ganti dengan domain hosting
define('UPLOAD_PATH', __DIR__ . '/../uploads/products/');
define('UPLOAD_URL', BASE_URL . '/uploads/products/');

// WhatsApp
define('WA_NUMBER', '6281234567890'); // Ganti nomor WA toko

// ============================================
// KONEKSI DATABASE
// ============================================
$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

if ($conn->connect_error) {
    die('<div style="text-align:center;padding:50px;font-family:sans-serif;">
        <h2>⚠️ Koneksi Database Gagal</h2>
        <p>Silakan cek konfigurasi di <code>includes/config.php</code></p>
        <small>' . $conn->connect_error . '</small>
    </div>');
}

$conn->set_charset('utf8mb4');

// ============================================
// SESSION & HELPER FUNCTIONS
// ============================================
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

function isAdmin() {
    return isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin';
}

function redirect($url) {
    header("Location: " . BASE_URL . $url);
    exit;
}

function sanitize($data) {
    return htmlspecialchars(strip_tags(trim($data)));
}

function formatPrice($price) {
    return 'Rp ' . number_format($price, 0, ',', '.');
}

function generateOrderCode() {
    return 'TRC-' . strtoupper(substr(uniqid(), -6)) . '-' . date('ymd');
}

function getCartCount($conn) {
    if (!isLoggedIn()) {
        return isset($_SESSION['cart']) ? array_sum(array_column($_SESSION['cart'], 'qty')) : 0;
    }
    $uid = $_SESSION['user_id'];
    $r = $conn->query("SELECT SUM(quantity) as total FROM carts WHERE user_id = $uid");
    $row = $r->fetch_assoc();
    return $row['total'] ?? 0;
}

function getSetting($conn, $key) {
    $key = $conn->real_escape_string($key);
    $r = $conn->query("SELECT value FROM settings WHERE key_name = '$key'");
    if ($r && $r->num_rows > 0) return $r->fetch_assoc()['value'];
    return '';
}

function buildWAMessage($items, $total, $customerName, $orderCode) {
    $wa = WA_NUMBER;
    $msg = "Halo Triascraf! 👋\n\nSaya ingin memesan:\n";
    $msg .= "No. Order: *$orderCode*\n\n";
    foreach ($items as $item) {
        $msg .= "• {$item['name']} x{$item['qty']} = " . formatPrice($item['price'] * $item['qty']) . "\n";
    }
    $msg .= "\n*Total: " . formatPrice($total) . "*";
    $msg .= "\n\nNama: $customerName";
    return "https://wa.me/$wa?text=" . urlencode($msg);
}
?>
