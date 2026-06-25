<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Panggil koneksi database terpusat
require_once 'koneksi.php';

// Panggil semua class anak
require_once 'MahasiswaMandiri.php';
require_once 'MahasiswaBidikmisi.php';
require_once 'MahasiswaPrestasi.php';

// Query Ambil Semua Data Mahasiswa
$sql = "SELECT * FROM tabel_mahasiswa ORDER BY jenis_pembiayaan, nama_mahasiswa ASC";
$stmt = $pdo->query($sql);
$allData = $stmt->fetchAll();

// Mapping Data menjadi Objek Polimorfisme
$daftarMandiri = [];
$daftarBidikmisi = [];
$daftarPrestasi = [];

// ================= MAPPING DINAMIS MURNI DARI DATABASE =================
foreach ($allData as $row) {
    $jenis = strtolower($row['jenis_pembiayaan']);
    
    if ($jenis === 'mandiri') {
        // Ambil golongan_ukt langsung dari db, nama_wali pake default atau relasi nama
        $daftarMandiri[] = new \UasPbo\MahasiswaMandiri(
            $row['id_mahasiswa'], 
            $row['nama_mahasiswa'], 
            $row['nim'], 
            $row['semester'], 
            (float)$row['tarif_ukt_nominal'],
            $row['golongan_ukt'] ?? 'Tidak Ada', 
            'Wali ' . $row['nama_mahasiswa'] // Karena di db emang ga ada kolom wali, ini aman
        );
    } elseif ($jenis === 'bidikmisi') {
        // Nomor KIP & Dana Saku diambil secara dinamis / disesuaikan standar subsidi
        // Di db tarif_ukt_nominal untuk Bidikmisi kan 0.00, jadi klop!
        $daftarBidikmisi[] = new \UasPbo\MahasiswaBidikmisi(
            $row['id_mahasiswa'], 
            $row['nama_mahasiswa'], 
            $row['nim'], 
            $row['semester'], 
            (float)$row['tarif_ukt_nominal'],
            'KIP-' . $row['nim'], // Nomor KIP dibuat dinamis dari NIM mahasiswa
            700000.00             // Dana saku standar negara (flat)
        );
    } elseif ($jenis === 'prestasi') {
        // 🌟 INI YANG PALING PENTING! Ambil instansi & syarat IPK murni dari kolom database!
        $daftarPrestasi[] = new \UasPbo\MahasiswaPrestasi(
            $row['id_mahasiswa'], 
            $row['nama_mahasiswa'], 
            $row['nim'], 
            $row['semester'], 
            (float)$row['tarif_ukt_nominal'],
            $row['nama_instansi_beasiswa'] ?? 'Tanpa Instansi', 
            (float)($row['minimal_ipk_syarat'] ?? 0.0)
        );
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>UAS PBO - Sistem Registrasi Pembayaran</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gray-100 font-sans leading-normal tracking-normal">

    <nav class="bg-slate-800 p-4 shadow-lg sticky top-0 z-50">
        <div class="container mx-auto flex justify-between items-center">
            <div class="flex items-center space-x-3 text-white">
                <i class="fa-solid fa-graduation-cap text-3xl text-emerald-400"></i>
                <div>
                    <span class="font-bold text-lg block tracking-wider">PORTAL REGISTRASI UKT</span>
                    <span class="text-xs text-gray-400">TI-1D | Muhamad Syahrul Yuzzy</span>
                </div>
            </div>
            <span class="bg-emerald-500 text-white text-xs px-3 py-1 rounded-full font-semibold shadow-sm animate-pulse">
                <i class="fa-solid fa-database mr-1"></i> Live Database
            </span>
        </div>
    </nav>

    <div class="container mx-auto px-4 py-8">
        
        <div class="text-center mb-10">
            <h1 class="text-3xl font-extrabold text-gray-800 md:text-4xl">Daftar Tagihan Semester Mahasiswa</h1>
            <p class="text-gray-500 mt-2 text-sm md:text-base">Sistem Manajemen Objek Terkelompok Berbasis Pembiayaan Kuliah (Implementasi Polimorfisme PHP)</p>
        </div>

        <div class="bg-white rounded-xl shadow-md border border-gray-200 overflow-hidden mb-12">
            <div class="bg-gradient-to-r from-amber-500 to-orange-600 px-6 py-4 flex items-center justify-between text-white">
                <h2 class="font-bold text-lg md:text-xl flex items-center">
                    <i class="fa-solid fa-user-tie mr-2"></i> 1. Kategori Mahasiswa Mandiri
                </h2>
                <span class="bg-white/20 px-3 py-1 rounded-md text-xs font-medium">Biaya Kuliah Penuh</span>
            </div>
            <div class="p-6 overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="border-b-2 border-gray-200 bg-gray-50 text-gray-600 text-xs uppercase font-semibold">
                            <th class="py-3 px-4">NIM</th>
                            <th class="py-3 px-4">Nama Mahasiswa</th>
                            <th class="py-3 px-2 text-center">Semester</th>
                            <th class="py-3 px-4 text-right">Tarif UKT Asli</th>
                            <th class="py-3 px-4 text-center">Spesifikasi Akademik Khusus</th>
                            <th class="py-3 px-4 text-right">Total Tagihan (+Biaya Lab)</th>
                        </tr>
                    </thead>
                    <tbody class="text-gray-700 text-sm divide-y divide-gray-100">
                        <?php foreach ($daftarMandiri as $mhs): ?>
                        <tr class="hover:bg-amber-50/40 transition duration-150">
                            <td class="py-4 px-4 font-mono font-semibold text-gray-600"><?= htmlspecialchars($mhs->getNim()) ?></td>
                            <td class="py-4 px-4 font-medium text-gray-900"><?= htmlspecialchars($mhs->getNamaMahasiswa()) ?></td>
                            <td class="py-4 px-2 text-center font-bold text-amber-600"><?= htmlspecialchars($mhs->getSemester()) ?></td>
                            <td class="py-4 px-4 text-right font-medium text-gray-500">Rp <?= number_format($mhs->getTarifUktNominal(), 0, ',', '.') ?></td>
                            <td class="py-4 px-4 text-center text-xs text-gray-500 italic bg-gray-50/50">
                                <?php $mhs->tampilkanSpesifikasiAkademik(); ?>
                            </td>
                            <td class="py-4 px-4 text-right font-bold text-red-600 text-base">
                                Rp <?= number_format($mhs->hitungTagihanSemester(), 0, ',', '.') ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-md border border-gray-200 overflow-hidden mb-12">
            <div class="bg-gradient-to-r from-emerald-500 to-teal-600 px-6 py-4 flex items-center justify-between text-white">
                <h2 class="font-bold text-lg md:text-xl flex items-center">
                    <i class="fa-solid fa-hand-holding-hand mr-2"></i> 2. Kategori Mahasiswa Bidikmisi / KIP-K
                </h2>
                <span class="bg-white/20 px-3 py-1 rounded-md text-xs font-medium">Subsidi Negara 100%</span>
            </div>
            <div class="p-6 overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="border-b-2 border-gray-200 bg-gray-50 text-gray-600 text-xs uppercase font-semibold">
                            <th class="py-3 px-4">NIM</th>
                            <th class="py-3 px-4">Nama Mahasiswa</th>
                            <th class="py-3 px-2 text-center">Semester</th>
                            <th class="py-3 px-4 text-right">Tarif UKT Asli</th>
                            <th class="py-3 px-4 text-center">Spesifikasi Akademik Khusus</th>
                            <th class="py-3 px-4 text-right">Total Tagihan</th>
                        </tr>
                    </thead>
                    <tbody class="text-gray-700 text-sm divide-y divide-gray-100">
                        <?php foreach ($daftarBidikmisi as $mhs): ?>
                        <tr class="hover:bg-emerald-50/40 transition duration-150">
                            <td class="py-4 px-4 font-mono font-semibold text-gray-600"><?= htmlspecialchars($mhs->getNim()) ?></td>
                            <td class="py-4 px-4 font-medium text-gray-900"><?= htmlspecialchars($mhs->getNamaMahasiswa()) ?></td>
                            <td class="py-4 px-2 text-center font-bold text-emerald-600"><?= htmlspecialchars($mhs->getSemester()) ?></td>
                            <td class="py-4 px-4 text-right font-medium text-gray-500">Rp <?= number_format($mhs->getTarifUktNominal(), 0, ',', '.') ?></td>
                            <td class="py-4 px-4 text-center text-xs text-gray-500 italic bg-gray-50/50">
                                <?php $mhs->tampilkanSpesifikasiAkademik(); ?>
                            </td>
                            <td class="py-4 px-4 text-right font-bold text-emerald-600 text-base">
                                <span class="bg-emerald-100 text-emerald-800 px-3 py-1 rounded-full text-xs">FREE (Rp 0)</span>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-md border border-gray-200 overflow-hidden">
            <div class="bg-gradient-to-r from-blue-500 to-indigo-600 px-6 py-4 flex items-center justify-between text-white">
                <h2 class="font-bold text-lg md:text-xl flex items-center">
                    <i class="fa-solid fa-award mr-2"></i> 3. Kategori Mahasiswa Prestasi
                </h2>
                <span class="bg-white/20 px-3 py-1 rounded-md text-xs font-medium">Diskon Beasiswa 75%</span>
            </div>
            <div class="p-6 overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="border-b-2 border-gray-200 bg-gray-50 text-gray-600 text-xs uppercase font-semibold">
                            <th class="py-3 px-4">NIM</th>
                            <th class="py-3 px-4">Nama Mahasiswa</th>
                            <th class="py-3 px-2 text-center">Semester</th>
                            <th class="py-3 px-4 text-right">Tarif UKT Asli</th>
                            <th class="py-3 px-4 text-center">Spesifikasi Akademik Khusus</th>
                            <th class="py-3 px-4 text-right">Total Tagihan (Wajib Bayar)</th>
                        </tr>
                    </thead>
                    <tbody class="text-gray-700 text-sm divide-y divide-gray-100">
                        <?php foreach ($daftarPrestasi as $mhs): ?>
                        <tr class="hover:bg-blue-50/40 transition duration-150">
                            <td class="py-4 px-4 font-mono font-semibold text-gray-600"><?= htmlspecialchars($mhs->getNim()) ?></td>
                            <td class="py-4 px-4 font-medium text-gray-900"><?= htmlspecialchars($mhs->getNamaMahasiswa()) ?></td>
                            <td class="py-4 px-2 text-center font-bold text-indigo-600"><?= htmlspecialchars($mhs->getSemester()) ?></td>
                            <td class="py-4 px-4 text-right font-medium text-gray-500">Rp <?= number_format($mhs->getTarifUktNominal(), 0, ',', '.') ?></td>
                            <td class="py-4 px-4 text-center text-xs text-gray-500 italic bg-gray-50/50">
                                <?php $mhs->tampilkanSpesifikasiAkademik(); ?>
                            </td>
                            <td class="py-4 px-4 text-right font-bold text-blue-600 text-base">
                                Rp <?= number_format($mhs->hitungTagihanSemester(), 0, ',', '.') ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>

    </div>

    <footer class="text-center py-6 text-gray-400 text-xs mt-12 border-t border-gray-200 bg-white">
        &copy; 2026 Tugas UAS PBO - Teknik Informatika 1D. All Rights Reserved.
    </footer>

</body>
</html>