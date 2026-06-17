<?php
require_once __DIR__ . '/../../includes/config.php';
if (!isLoggedIn() || !isAdmin()) {
    header('Location: ' . BASE_URL . '/pages/login.php');
    exit;
}
?>
