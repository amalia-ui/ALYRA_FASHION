<?php
declare(strict_types=1);
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/functions.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    flash('error', 'Penghapusan hanya dapat dilakukan melalui POST.');
    redirect('index.php');
}

if (!verify_csrf($_POST['csrf_token'] ?? null)) {
    flash('error', 'Token CSRF tidak valid. Penghapusan dibatalkan.');
    redirect('index.php');
}

$id = filter_var($_POST['id'] ?? null, FILTER_VALIDATE_INT);
if (!$id || $id < 1) {
    flash('error', 'ID produk tidak valid.');
    redirect('index.php');
}

$stmt = db()->prepare('DELETE FROM products WHERE id = ?');
$stmt->execute([$id]);

if ($stmt->rowCount() > 0) {
    flash('success', 'Produk berhasil dihapus.');
} else {
    flash('error', 'Produk tidak ditemukan atau sudah dihapus.');
}
redirect('index.php');
