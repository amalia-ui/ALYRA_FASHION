<?php
declare(strict_types=1);

if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

function e(?string $value): string
{
    return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');
}

function csrf_token(): string
{
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

function verify_csrf(?string $token): bool
{
    return is_string($token)
        && isset($_SESSION['csrf_token'])
        && hash_equals($_SESSION['csrf_token'], $token);
}

function redirect(string $url = 'index.php'): never
{
    header('Location: ' . $url);
    exit;
}

function flash(string $type, string $message): void
{
    $_SESSION['flash'] = ['type' => $type, 'message' => $message];
}

function get_flash(): ?array
{
    $flash = $_SESSION['flash'] ?? null;
    unset($_SESSION['flash']);
    return $flash;
}

function validate_product(array $input, ?int $ignoreId = null): array
{
    $name = trim((string)($input['name'] ?? ''));
    $category = trim((string)($input['category'] ?? ''));
    $priceRaw = trim((string)($input['price'] ?? ''));
    $stockRaw = trim((string)($input['stock'] ?? ''));
    $errors = [];

    if (mb_strlen($name) < 3) {
        $errors['name'] = 'Nama produk minimal 3 karakter.';
    }
    if ($category === '') {
        $errors['category'] = 'Kategori wajib diisi.';
    }
    if ($priceRaw === '' || !is_numeric($priceRaw) || (float)$priceRaw <= 0) {
        $errors['price'] = 'Harga harus berupa angka lebih dari 0.';
    }
    if ($stockRaw === '' || filter_var($stockRaw, FILTER_VALIDATE_INT) === false || (int)$stockRaw < 0) {
        $errors['stock'] = 'Stok harus berupa bilangan bulat 0 atau lebih.';
    }

    if (!$errors) {
        $pdo = db();
        $sql = 'SELECT id FROM products WHERE name = ?';
        $params = [$name];
        if ($ignoreId !== null) {
            $sql .= ' AND id <> ?';
            $params[] = $ignoreId;
        }
        $sql .= ' LIMIT 1';
        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        if ($stmt->fetch()) {
            $errors['name'] = 'Nama produk sudah digunakan. Gunakan nama lain.';
        }
    }

    return [
        'data' => [
            'name' => $name,
            'category' => $category,
            'price' => (float)$priceRaw,
            'stock' => (int)$stockRaw,
        ],
        'errors' => $errors,
    ];
}

function format_rupiah(float $price): string
{
    return 'Rp ' . number_format($price, 0, ',', '.');
}
