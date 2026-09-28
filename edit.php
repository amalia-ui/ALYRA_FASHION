<?php
declare(strict_types=1);
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/functions.php';

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if (!$id || $id < 1) {
    flash('error', 'ID produk tidak valid.');
    redirect('index.php');
}

$stmt = db()->prepare('SELECT id, name, category, price, stock FROM products WHERE id = ? LIMIT 1');
$stmt->execute([$id]);
$product = $stmt->fetch();
if (!$product) {
    flash('error', 'Produk tidak ditemukan.');
    redirect('index.php');
}

$data = $product;
$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verify_csrf($_POST['csrf_token'] ?? null)) {
        $errors['general'] = 'Token keamanan tidak valid. Silakan coba lagi.';
    } else {
        $result = validate_product($_POST, (int)$id);
        $data = array_merge($data, $result['data']);
        $errors = $result['errors'];

        if (!$errors) {
            $update = db()->prepare('UPDATE products SET name = ?, category = ?, price = ?, stock = ? WHERE id = ?');
            $update->execute([$data['name'], $data['category'], $data['price'], $data['stock'], $id]);
            flash('success', 'Produk berhasil diperbarui.');
            redirect('index.php');
        }
    }
}
?>
<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Edit Produk | ALYRA FASHION</title>
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>
<header class="topbar"><div class="container topbar-inner"><div><p class="eyebrow">UPDATE</p><h1>Edit Produk #<?= (int)$id ?></h1><p class="subtitle">Perbarui data produk yang sudah tersimpan.</p></div><a class="btn btn-secondary" href="index.php">← Kembali</a></div></header>
<main class="container page-content narrow">
    <section class="form-card">
        <?php if (isset($errors['general'])): ?><div class="alert error"><?= e($errors['general']) ?></div><?php endif; ?>
        <form method="post" novalidate>
            <input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>">
            <div class="form-group"><label for="name">Nama Produk</label><input id="name" name="name" type="text" value="<?= e((string)$data['name']) ?>" minlength="3" required autofocus><?php if (isset($errors['name'])): ?><small class="field-error"><?= e($errors['name']) ?></small><?php endif; ?></div>
            <div class="form-group"><label for="category">Kategori</label><input id="category" name="category" type="text" value="<?= e((string)$data['category']) ?>" required><?php if (isset($errors['category'])): ?><small class="field-error"><?= e($errors['category']) ?></small><?php endif; ?></div>
            <div class="form-row">
                <div class="form-group"><label for="price">Harga</label><input id="price" name="price" type="number" value="<?= e((string)$data['price']) ?>" min="0.01" step="0.01" required><?php if (isset($errors['price'])): ?><small class="field-error"><?= e($errors['price']) ?></small><?php endif; ?></div>
                <div class="form-group"><label for="stock">Stok</label><input id="stock" name="stock" type="number" value="<?= e((string)$data['stock']) ?>" min="0" step="1" required><?php if (isset($errors['stock'])): ?><small class="field-error"><?= e($errors['stock']) ?></small><?php endif; ?></div>
            </div>
            <div class="form-actions"><a class="btn btn-secondary" href="index.php">Batal</a><button class="btn btn-primary" type="submit">Simpan Perubahan</button></div>
        </form>
    </section>
</main>
</body>
</html>
