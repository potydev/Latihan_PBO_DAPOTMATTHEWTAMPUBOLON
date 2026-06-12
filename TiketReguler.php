<?php
require_once 'Tiket.php';

class TiketReguler extends Tiket {
    // Properti tambahan spesifik
    protected $tipeAudio;
    protected $lokasiBaris;

    public function __construct($id_tiket, $nama_film, $jadwal_tayang, $jumlah_kursi, $harga_dasar_tiket, $tipeAudio, $lokasiBaris) {
        // Memanggil constructor dari kelas induk (Tiket)
        parent::__construct($id_tiket, $nama_film, $jadwal_tayang, $jumlah_kursi, $harga_dasar_tiket);
        $this->tipeAudio = $tipeAudio;
        $this->lokasiBaris = $lokasiBaris;
    }

    // Getter untuk properti spesifik
    public function getTipeAudio() {
        return $this->tipeAudio;
    }

    public function getLokasiBaris() {
        return $this->lokasiBaris;
    }

    // Mengimplementasikan hitungTotalHarga()
    public function hitungTotalHarga() {
        // Studio Reguler menggunakan perhitungan harga dasar standar dikali jumlah kursi
        return $this->harga_dasar_tiket * $this->jumlah_kursi;
    }

    // Mengimplementasikan tampilkanInfoFasilitas()
    public function tampilkanInfoFasilitas() {
        return "Fasilitas Reguler: Audio {$this->tipeAudio}, Posisi Baris {$this->lokasiBaris}.";
    }
}
?>
