<?php
require_once 'Tiket.php';

class TiketVelvet extends Tiket {
    // Properti tambahan spesifik
    protected $bantalSelimutPack;
    protected $layananButler;

    public function __construct($id_tiket, $nama_film, $jadwal_tayang, $jumlah_kursi, $harga_dasar_tiket, $bantalSelimutPack, $layananButler) {
        // Memanggil constructor dari kelas induk (Tiket)
        parent::__construct($id_tiket, $nama_film, $jadwal_tayang, $jumlah_kursi, $harga_dasar_tiket);
        $this->bantalSelimutPack = $bantalSelimutPack;
        $this->layananButler = $layananButler;
    }

    // Getter untuk properti spesifik
    public function getBantalSelimutPack() {
        return $this->bantalSelimutPack;
    }

    public function getLayananButler() {
        return $this->layananButler;
    }

    // Mengimplementasikan hitungTotalHarga()
    public function hitungTotalHarga() {
        // Studio Velvet memiliki biaya layanan butler premium tetap sebesar 50.000 per pemesanan tiket
        $biayaPelayanan = 50000;
        return ($this->harga_dasar_tiket * $this->jumlah_kursi) + $biayaPelayanan;
    }

    // Mengimplementasikan tampilkanInfoFasilitas()
    public function tampilkanInfoFasilitas() {
        return "Fasilitas Velvet: Sofa Bed, {$this->bantalSelimutPack}, Layanan Butler: {$this->layananButler}.";
    }
}
?>
