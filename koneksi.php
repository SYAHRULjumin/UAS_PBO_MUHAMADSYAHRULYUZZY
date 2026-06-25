<?php
// Setup Koneksi Database PDO secara terpusat
$host = 'localhost';
$db   = 'DB_UAS_PBO_TI1D_MuhamadSyahrulYuzzy';
$user = 'root'; // Sesuaikan dengan user XAMPP/Laragon lo
$pass = '';     // Sesuaikan dengan password database lo
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
    // Variabel $pdo ini yang bakal dipanggil di file lain
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (\PDOException $e) {
     die("Koneksi Database Gagal total: " . $e->getMessage());
}