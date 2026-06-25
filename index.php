<?php
// Panggil koneksi database terpusat
require_once 'koneksi.php';

// Panggil semua class anak
require_once 'MahasiswaMandiri.php';
require_once 'MahasiswaBidikmisi.php';
require_once 'MahasiswaPrestasi.php';

// KODE QUERY DAN MAPPING DI BAWAHNYA TETEP SAMA...
// Variabel $pdo di bawah bakal otomatis ngebaca dari koneksi.php
$sql = "SELECT * FROM tabel_mahasiswa ORDER BY jenis_pembiayaan, nama_mahasiswa ASC";
$stmt = $pdo->query($sql);
$allData = $stmt->fetchAll();

// ... Seterusnya ke bawah sama persis kayak kemarin