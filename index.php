<?php
declare(strict_types=1);
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/functions.php';

$products = db()->query('SELECT id, name, category, price, stock, created_at, updated_at FROM products ORDER BY id DESC')->fetchAll();
$flash = get_flash();
?>
<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>ALYRA FASHION | Manajemen Produk</title>
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>
<header class="topbar">
    <div class="container topbar-inner">
        <div>
            <p class="eyebrow">MINI PROJECT • ALYRA FASHION</p>
            <h1>Manajemen Produk</h1>
            <p class="subtitle">Aplikasi PHP-MySQL untuk Create, Read, Update, dan Delete produk dengan validasi dan kontrol keamanan.</p>
        </div>
        <div class="header-actions"><a class="btn btn-secondary" href="demo.php">Checklist Demo</a><a class="btn btn-primary" href="create.php">+ Tambah Produk</a></div>
    </div>
</header>

<main class="container page-content">
    <?php if ($flash): ?>
        <div class="alert <?= e($flash['type']) ?>" role="alert"><?= e($flash['message']) ?></div>
    <?php endif; ?>

    <section class="section-head">
        <div>
            <h2>Daftar Produk</h2>
            <p><?= count($products) ?> produk tersimpan di database.</p>
        </div>
    </section>

    <?php if (!$products): ?>
        <div class="empty-state">
            <h3>Belum ada produk</h3>
            <p>Tambahkan produk pertama menggunakan tombol di atas.</p>
            <a class="btn btn-primary" href="create.php">Tambah Produk</a>
        </div>
    <?php else: ?>
        <section class="product-grid" aria-label="Daftar produk">
            <?php foreach ($products as $product): ?>
                <article class="product-card">
                    <div class="card-top">
                        <span class="badge"><?= e($product['category']) ?></span>
                        <span class="stock <?= (int)$product['stock'] === 0 ? 'stock-empty' : '' ?>">
                            Stok: <?= (int)$product['stock'] ?>
                        </span>
                    </div>
                    <h3><?= e($product['name']) ?></h3>
                    <p class="price"><?= e(format_rupiah((float)$product['price'])) ?></p>
                    <div class="card-meta">
                        <span>ID #<?= (int)$product['id'] ?></span>
                        <span><?= e(date('d/m/Y H:i', strtotime($product['updated_at']))) ?></span>
                    </div>
                    <div class="card-actions">
                        <a class="btn btn-secondary" href="edit.php?id=<?= (int)$product['id'] ?>">Edit</a>
                        <form action="delete.php" method="post" onsubmit="return confirm('Hapus produk ini?');">
                            <input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>">
                            <input type="hidden" name="id" value="<?= (int)$product['id'] ?>">
                            <button class="btn btn-danger" type="submit">Hapus</button>
                        </form>
                    </div>
                </article>
            <?php endforeach; ?>
        </section>
    <?php endif; ?>

    <section class="security-note">
        <h2>Kontrol keamanan yang diterapkan</h2>
        <ul>
            <li>Semua INSERT, SELECT berdasarkan ID, UPDATE, dan DELETE menggunakan PDO prepared statement.</li>
            <li>Output nama, kategori, dan pesan menggunakan <code>htmlspecialchars()</code> agar input seperti <code>&lt;b&gt;Promo&lt;/b&gt;</code> tampil sebagai teks.</li>
            <li>DELETE memakai POST + CSRF token, bukan GET.</li>
            <li>Validasi server: nama minimal 3 karakter, harga lebih dari 0, stok minimal 0, dan nama produk unik.</li>
            <li>Constraint UNIQUE pada database menjadi lapisan tambahan untuk mencegah nama produk ganda.</li>
        </ul>
    </section>
</main>

<footer class="footer">
    <div class="container">ALYRA FASHION • PHP + MySQL • Mini Project</div>
</footer>
</body>
</html>
