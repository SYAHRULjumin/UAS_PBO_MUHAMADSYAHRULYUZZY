<?php

namespace UasPbo;

require_once 'Mahasiswa.php';

class MahasiswaBidikmisi extends Mahasiswa {
    // Properti tambahan spesifik
    private string $nomor_kip_kuliah;
    private float $dana_saku_subsidi;

    public function __construct(
        int $id_mahasiswa, string $nama_mahasiswa, string $nim, int $semester, float $tarif_ukt_nominal,
        string $nomor_kip_kuliah, float $dana_saku_subsidi
    ) {
        // Bidikmisi biasanya tarif UKT di db = 0, tapi tetep kirim ke parent
        parent::__construct($id_mahasiswa, $nama_mahasiswa, $nim, $semester, $tarif_ukt_nominal);
        $this->nomor_kip_kuliah = $nomor_kip_kuliah;
        $this->dana_saku_subsidi = $dana_saku_subsidi;
    }

    // Override Method 1: Hitung Tagihan (Bidikmisi gratis, tagihan 0)
    public function hitungTagihanSemester(): float {
        return 0.0;
    }

    // Override Method 2: Tampilkan Spesifikasi
    public function tampilkanSpesifikasiAkademik(): void {
        echo "Tipe: Bidikmisi | No KIP: {$this->nomor_kip_kuliah} | Dana Saku: {$this->dana_saku_subsidi}\n";
    }

    // Method Query: Ambil data khusus mahasiswa bidikmisi pada semester tertentu
    public static function getBySemester(\PDO $pdo, int $semester): array {
        $sql = "SELECT * FROM tabel_mahasiswa WHERE jenis_pembiayaan = 'Bidikmisi' AND semester = :semester";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['semester' => $semester]);
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }
}