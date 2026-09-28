<?php
declare(strict_types=1);
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/functions.php';

$checks = [
    ['Tambah produk valid', 'Produk muncul di daftar.'],
    ['Nama < 3 karakter', 'Ditolak dan tidak tersimpan.'],
    ['Harga negatif / 0', 'Ditolak dengan pesan yang jelas.'],
    ['Stok negatif', 'Ditolak dengan pesan yang jelas.'],
    ['Nama produk sama', 'Ditolak karena nama harus unik.'],
    ['Refresh setelah create', 'Tidak ada duplikasi.'],
    ['Edit berdasarkan ID', 'Data berubah sesuai ID yang dipilih.'],
    ['Delete melalui GET', 'Ditolak; delete hanya melalui POST.'],
    ['Delete tanpa CSRF', 'Ditolak; token keamanan wajib valid.'],
    ['Nama berisi <b>Promo</b>', 'Tampil sebagai teks, bukan HTML.'],
    ['Layar sempit', 'Card membungkus dan tetap rapi.'],
];
?>
<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Checklist Demo | ALYRA FASHION</title>
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>
<header class="topbar">
    <div class="container topbar-inner">
        <div>
            <p class="eyebrow">PENUTUP • CHECKLIST DEMO</p>
            <h1>Checklist Demo Sebelum Mengumpulkan</h1>
            <p class="subtitle">ALYRA FASHION dianggap selesai ketika perilaku normal dan perilaku saat input buruk sama-sama dapat dijelaskan dan didemonstrasikan.</p>
        </div>
        <a class="btn btn-secondary" href="index.php">← Kembali ke Produk</a>
    </div>
</header>
<main class="container page-content">
    <section class="demo-list">
        <?php foreach ($checks as $check): ?>
            <article class="demo-item">
                <span class="check">✓</span>
                <div><strong><?= e($check[0]) ?></strong><p><?= e($check[1]) ?></p></div>
            </article>
        <?php endforeach; ?>
    </section>

    <section class="security-note">
        <h2>Refleksi</h2>
        <p>Bagian yang paling rentan adalah input, query, output, dan alur request. Kontrol keamanan yang diterapkan adalah validasi server untuk input, PDO prepared statement untuk query, <code>htmlspecialchars()</code> untuk output, serta POST + CSRF untuk request penghapusan. Database juga menggunakan UNIQUE pada nama produk dan proses Create/Update/Delete menggunakan redirect setelah berhasil agar refresh tidak mengulang request.</p>
    </section>
</main>
<footer class="footer"><div class="container">ALYRA FASHION • PHP + MySQL • Mini Project</div></footer>
</body>
</html>
