<?php

namespace UasPbo;

require_once 'Mahasiswa.php';

class MahasiswaMandiri extends Mahasiswa {
    // Properti tambahan spesifik
    private string $golongan_ukt;
    private string $nama_wali;

    // Constructor wajib memanggil parent constructor
    public function __construct(
        int $id_mahasiswa, string $nama_mahasiswa, string $nim, int $semester, float $tarif_ukt_nominal,
        string $golongan_ukt, string $nama_wali
    ) {
        parent::__construct($id_mahasiswa, $nama_mahasiswa, $nim, $semester, $tarif_ukt_nominal);
        $this->golongan_ukt = $golongan_ukt;
        $this->nama_wali = $nama_wali;
    }

    // Override Method 1: Hitung Tagihan (Mandiri bayar full UKT)
    public function hitungTagihanSemester(): float {
        return $this->tarif_ukt_nominal;
    }

    // Override Method 2: Tampilkan Spesifikasi
    public function tampilkanSpesifikasiAkademik(): void {
        echo "Tipe: Mandiri | Golongan UKT: {$this->golongan_ukt} | Wali: {$this->nama_wali}\n";
    }

    // Method Query: Ambil data khusus mahasiswa mandiri berdasarkan golongan UKT
    public static function getByGolongan(\PDO $pdo, string $golongan): array {
        $sql = "SELECT * FROM tabel_mahasiswa WHERE jenis_pembiayaan = 'Mandiri' AND golongan_ukt = :golongan";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['golongan' => $golongan]);
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }
}