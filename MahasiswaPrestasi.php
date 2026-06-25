<?php

namespace UasPbo;

require_once 'Mahasiswa.php';

class MahasiswaPrestasi extends Mahasiswa {
    // Properti tambahan spesifik
    private string $nama_instansi_beasiswa;
    private float $minimal_ipk_syarat;

    public function __construct(
        int $id_mahasiswa, string $nama_mahasiswa, string $nim, int $semester, float $tarif_ukt_nominal,
        string $nama_instansi_beasiswa, float $minimal_ipk_syarat
    ) {
        parent::__construct($id_mahasiswa, $nama_mahasiswa, $nim, $semester, $tarif_ukt_nominal);
        $this->nama_instansi_beasiswa = $nama_instansi_beasiswa;
        $this->minimal_ipk_syarat = $minimal_ipk_syarat;
    }

    // Override Method: Hitung Tagihan Prestasi
    public function hitungTagihanSemester(): float {
        // Potongan 75%, mahasiswa hanya bayar 25% dari tarif UKT asli
        return $this->tarif_ukt_nominal * 0.25;
    }

    // Override Method 2: Tampilkan Spesifikasi
    public function tampilkanSpesifikasiAkademik(): void {
        echo "Tipe: Prestasi | Instansi: {$this->nama_instansi_beasiswa} | Syarat IPK: {$this->minimal_ipk_syarat}\n";
    }

    // Method Query: Ambil data khusus mahasiswa prestasi berdasarkan nama instansi beasiswa
    public static function getByInstansi(\PDO $pdo, string $instansi): array {
        $sql = "SELECT * FROM tabel_mahasiswa WHERE jenis_pembiayaan = 'Prestasi' AND nama_instansi_beasiswa LIKE :instansi";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['instansi' => "%$instansi%"]);
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }
}