<?php
session_start();
try {
    $db = new PDO('mysql:host=localhost;dbname=stokku;charset=utf8mb4', 'root', '', [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
}
catch (PDOException $e) {
    exit('Koneksi gagal. Aktifkan MySQL dan import database.sql terlebih dahulu.');
}
function e($v) {
    return htmlspecialchars((string)$v, ENT_QUOTES, 'UTF-8');
}
function rupiah($v) {
    return 'Rp ' . number_format($v, 0, ',', '.');
}
function token() {
    return $_SESSION['csrf'] ??= bin2hex(random_bytes(32));
}
function checkToken() {
    if (!hash_equals(token(), $_POST['csrf'] ?? '')) {
        http_response_code(403);
        exit('Token tidak valid.');
    }
}
function go($url) {
    header('Location: '.$url);
    exit;
}
