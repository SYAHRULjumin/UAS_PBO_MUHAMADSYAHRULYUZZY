<?php

namespace UasPbo;

/**
 * Class Mahasiswa sebagai Superclass (Abstract Class)
 * Menampung atribut global (induk) yang dipetakan dari database
 */
abstract class Mahasiswa {
    // Properti Terenkapsulasi (Protected) - Sesuai Atribut Global Database
    protected int $id_mahasiswa;
    protected String $nama_mahasiswa;
    protected String $nim;
    protected int $semester;
    protected float $tarif_ukt_nominal; // float di PHP setara dengan DECIMAL/double

    // Constructor untuk Inisialisasi Data dari Database
    public function __construct(
        int $id_mahasiswa, 
        String $nama_mahasiswa, 
        String $nim, 
        int $semester, 
        float $tarif_ukt_nominal
    ) {
        $this->id_mahasiswa = $id_mahasiswa;
        $this->nama_mahasiswa = $nama_mahasiswa;
        $this->nim = $nim;
        $this->semester = $semester;
        $this->tarif_ukt_nominal = $tarif_ukt_nominal;
    }

    // ================= GETTER AND SETTER (Enkapsulasi) =================
    public function getIdMahasiswa(): int { return $this->id_mahasiswa; }
    public function setIdMahasiswa(int $id_mahasiswa): void { $this->id_mahasiswa = $id_mahasiswa; }

    public function getNamaMahasiswa(): String { return $this->nama_mahasiswa; }
    public function setNamaMahasiswa(String $nama_mahasiswa): void { $this->nama_mahasiswa = $nama_mahasiswa; }

    public function getNim(): String { return $this->nim; }
    public function setNim(String $nim): void { $this->nim = $nim; }

    public function getSemester(): int { return $this->semester; }
    public function setSemester(int $semester): void { $this->semester = $semester; }

    public function getTarifUktNominal(): float { return $this->tarif_ukt_nominal; }
    public function setTarifUktNominal(float $tarif_ukt_nominal): void { $this->tarif_ukt_nominal = $tarif_ukt_nominal; }


    // ================= METODE ABSTRACT (Wajib Di-override di Class Anak) =================
    
    /**
     * Menghitung total tagihan semester berjalan.
     * @return float
     */
    abstract public function hitungTagihanSemester(): float;

    /**
     * Menampilkan spesifikasi akademik khusus (Atribut Anak)
     * @return void
     */
    abstract public function tampilkanSpesifikasiAkademik(): void;
}