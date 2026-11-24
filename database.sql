CREATE DATABASE IF NOT EXISTS stokku CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE stokku;
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(60) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL
);
CREATE TABLE IF NOT EXISTS barang (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nama VARCHAR(100) NOT NULL,
    kategori VARCHAR(80) NOT NULL,
    stok INT UNSIGNED NOT NULL,
    harga DECIMAL(12,0) UNSIGNED NOT NULL
);
INSERT INTO barang (nama,kategori,stok,harga) VALUES
    ('Keyboard','Elektronik',12,150000),
    ('Mouse','Elektronik',4,75000),
    ('Buku Catatan','ATK',25,12000);
