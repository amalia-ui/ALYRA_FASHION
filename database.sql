CREATE DATABASE IF NOT EXISTS alyra_fashion
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;

USE alyra_fashion;

CREATE TABLE IF NOT EXISTS products (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(150) NOT NULL UNIQUE,
    category VARCHAR(100) NOT NULL,
    price DECIMAL(15,2) NOT NULL,
    stock INT NOT NULL DEFAULT 0,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- Data contoh opsional. Jalankan sekali jika ingin langsung melihat card produk.
INSERT INTO products (name, category, price, stock) VALUES
('Blouse Alyra', 'Fashion', 175000, 12),
('Heels Alyra', 'Sepatu', 325000, 8),
('Shoulder Bag Alyra', 'Aksesori', 145000, 20)
ON DUPLICATE KEY UPDATE name = VALUES(name);
