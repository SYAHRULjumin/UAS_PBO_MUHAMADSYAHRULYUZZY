<?php
// Koneksi database dan panggil semua class anak
require_once 'MahasiswaMandiri.php';
require_once 'MahasiswaBidikmisi.php';
require_once 'MahasiswaPrestasi.php';

// 1. Setup Koneksi Database PDO
$host = 'localhost';
$db   = 'DB_UAS_PBO_TI1D_MuhamadSyahrulYuzzy';
$user = 'root'; // Sesuaikan dengan user xampp/laragon lo
$pass = '';     // Sesuaikan dengan password database lo
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (\PDOException $e) {
     die("Koneksi Database Gagal: " . $e->getMessage());
}

// 2. Query Ambil Semua Data Mahasiswa
$sql = "SELECT * FROM tabel_mahasiswa ORDER BY jenis_pembiayaan, nama_mahasiswa ASC";
$stmt = $pdo->query($sql);
$allData = $stmt->fetchAll();

// 3. Mapping Data dari Database Menjadi Objek Polimorfisme
$daftarMandiri = [];
$daftarBidikmisi = [];
$daftarPrestasi = [];

foreach ($allData as $row) {
    if ($row['jenis_pembiayaan'] === 'Mandiri') {
        // Karena di tabel awal kolom nama_wali belum ada, kita asumsikan atau isi default dulu
        $daftarMandiri[] = new \UasPbo\MahasiswaMandiri(
            $row['id_mahasiswa'], $row['nama_mahasiswa'], $row['nim'], $row['semester'], $row['tarif_ukt_nominal'],
            $row['golongan_ukt'] ?? 'Golongan 4', 'Wali ' . $row['nama_mahasiswa']
        );
    } elseif ($row['jenis_pembiayaan'] === 'Bidikmisi') {
        $daftarBidikmisi[] = new \UasPbo\MahasiswaBidikmisi(
            $row['id_mahasiswa'], $row['nama_mahasiswa'], $row['nim'], $row['semester'], $row['tarif_ukt_nominal'],
            'KIP-2026' . $row['id_mahasiswa'], 700000.00
        );
    } elseif ($row['jenis_pembiayaan'] === 'Prestasi') {
        $daftarPrestasi[] = new \UasPbo\MahasiswaPrestasi(
            $row['id_mahasiswa'], $row['nama_mahasiswa'], $row['nim'], $row['semester'], $row['tarif_ukt_nominal'],
            $row['nama_instansi_beasiswa'] ?? 'Instansi Swasta', (float)($row['minimal_ipk_syarat'] ?? 3.0)
        );
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>UAS PBO - Daftar Registrasi Pembayaran Kuliah</title>
    <style>
        body { font-family: Arial, sans-serif; background-color: #f4f6f9; margin: 20px; color: #333; }
        h1 { text-align: center; color: #2c3e50; margin-bottom: 30px; }
        h2 { color: #2980b9; border-left: 5px solid #2980b9; padding-left: 10px; margin-top: 40px; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; background: #fff; box-shadow: 0 2px 5px rgba(0,0,0,0.1); }
        th, td { padding: 12px 15px; text-align: left; border-bottom: 1px solid #ddd; }
        th { background-color: #34495e; color: #fff; }
        tr:hover { background-color: #f9f9f9; }
        .nominal { text-align: right; font-weight: bold; }
        .spesifikasi { font-style: italic; color: #7f8c8d; }
    </style>
</head>
<body>

    <h1>DAFTAR REGISTRASI PEMBAYARAN KULIAH MAHASISWA</h1>

    <h2>1. Kategori Mahasiswa Mandiri</h2>
    <table>
        <thead>
            <tr>
                <th>NIM</th>
                <th>Nama Mahasiswa</th>
                <th>Semester</th>
                <th>Tarif UKT Asli</th>
                <th>Spesifikasi Akademik Khusus</th>
                <th>Total Tagihan (Inc. Biaya Lab)</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($daftarMandiri as $mhs): ?>
            <tr>
                <td><?= htmlspecialchars($mhs->getNim()) ?></td>
                <td><?= htmlspecialchars($mhs->getNamaMahasiswa()) ?></td>
                <td><?= htmlspecialchars($mhs->getSemester()) ?></td>
                <td class="nominal">Rp <?= number_format($mhs->getTarifUktNominal(), 2, ',', '.') ?></td>
                <td class="spesifikasi"><?= $mhs->tampilkanSpesifikasiAkademik() ?></td>
                <td class="nominal" style="color: #c0392b;">Rp <?= number_format($mhs->hitungTagihanSemester(), 2, ',', '.') ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <h2>2. Kategori Mahasiswa Bidikmisi</h2>
    <table>
        <thead>
            <tr>
                <th>NIM</th>
                <th>Nama Mahasiswa</th>
                <th>Semester</th>
                <th>Tarif UKT Asli</th>
                <th>Spesifikasi Akademik Khusus</th>
                <th>Total Tagihan (Subsidi Negara)</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($daftarBidikmisi as $mhs): ?>
            <tr>
                <td><?= htmlspecialchars($mhs->getNim()) ?></td>
                <td><?= htmlspecialchars($mhs->getNamaMahasiswa()) ?></td>
                <td><?= htmlspecialchars($mhs->getSemester()) ?></td>
                <td class="nominal">Rp <?= number_format($mhs->getTarifUktNominal(), 2, ',', '.') ?></td>
                <td class="spesifikasi"><?= $mhs->tampilkanSpesifikasiAkademik() ?></td>
                <td class="nominal" style="color: #27ae60;">Rp <?= number_format($mhs->hitungTagihanSemester(), 2, ',', '.') ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <h2>3. Kategori Mahasiswa Prestasi</h2>
    <table>
        <thead>
            <tr>
                <th>NIM</th>
                <th>Nama Mahasiswa</th>
                <th>Semester</th>
                <th>Tarif UKT Asli</th>
                <th>Spesifikasi Akademik Khusus</th>
                <th>Total Tagihan (Diskon 75%)</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($daftarPrestasi as $mhs): ?>
            <tr>
                <td><?= htmlspecialchars($mhs->getNim()) ?></td>
                <td><?= htmlspecialchars($mhs->getNamaMahasiswa()) ?></td>
                <td><?= htmlspecialchars($mhs->getSemester()) ?></td>
                <td class="nominal">Rp <?= number_format($mhs->getTarifUktNominal(), 2, ',', '.') ?></td>
                <td class="spesifikasi"><?= $mhs->tampilkanSpesifikasiAkademik() ?></td>
                <td class="nominal" style="color: #2980b9;">Rp <?= number_format($mhs->hitungTagihanSemester(), 2, ',', '.') ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

</body>
</html>