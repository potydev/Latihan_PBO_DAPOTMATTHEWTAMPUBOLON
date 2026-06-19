<?php
require_once 'Tiket.php';

class TiketIMAX extends Tiket {
    // Properti tambahan spesifik
    protected $kacamata3dId;
    protected $efekGerakFitur;

    public function __construct($id_tiket, $nama_film, $jadwal_tayang, $jumlah_kursi, $harga_dasar_tiket, $kacamata3dId, $efekGerakFitur) {
        // Memanggil constructor dari kelas induk (Tiket)
        parent::__construct($id_tiket, $nama_film, $jadwal_tayang, $jumlah_kursi, $harga_dasar_tiket);
        $this->kacamata3dId = $kacamata3dId;
        $this->efekGerakFitur = $efekGerakFitur;
    }

    // Getter untuk properti spesifik
    public function getKacamata3dId() {
        return $this->kacamata3dId;
    }

    public function getEfekGerakFitur() {
        return $this->efekGerakFitur;
    }

    // Mengimplementasikan hitungTotalHarga()
    public function hitungTotalHarga() {
        // Biaya tambahan kacamata 3D (sebesar 15,000) jika ID kacamata tersedia/tidak null
        $biayaKacamata = !empty($this->kacamata3dId) ? 15000 : 0;
        return ($this->harga_dasar_tiket + $biayaKacamata) * $this->jumlah_kursi;
    }

    // Mengimplementasikan tampilkanInfoFasilitas()
    public function tampilkanInfoFasilitas() {
        $kacamataText = !empty($this->kacamata3dId) ? "Kacamata 3D (ID: {$this->kacamata3dId})" : "Tanpa Kacamata 3D";
        $efekText = !empty($this->efekGerakFitur) && $this->efekGerakFitur !== 'None' ? "Efek Gerak: {$this->efekGerakFitur}" : "Tanpa Efek Gerak";
        return "Fasilitas IMAX: Layar Lebar IMAX, {$kacamataText}, {$efekText}.";
    }

    // Metode Query Spesifik untuk mengambil data tiket IMAX
    public static function getDaftarIMAX(PDO $db) {
        $query = "SELECT * FROM tabel_tiket WHERE jenis_studio = 'imax'";
        $stmt = $db->prepare($query);
        $stmt->execute();

        $tiketList = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $tiketList[] = new self(
                $row['id_tiket'],
                $row['nama_film'],
                $row['jadwal_tayang'],
                $row['jumlah_kursi'],
                $row['harga_dasar_tiket'],
                $row['kacamata_3d_id'],
                $row['efek_gerak_fitur']
            );
        }
        return $tiketList;
    }
}
?>
