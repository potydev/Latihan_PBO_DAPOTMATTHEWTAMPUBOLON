buat db DB_LATIHAN_PBO_TRPL1A_DAPOTMATTHEWTAMPUBOLON (Done)

rancang satu tabel terpusat bernama tabel_tiket dengan ketentuan struktur kolom yang mencakup seluruh atribut objek sebagai berikut :
atribut global ( induk ): id_tiket (primary key), nama film, jadwal_tayang, jumlah_kursi, harga_dasar_tiket, dan jenis_studio  (enum: reguler,imax, dan velvet)

atribut spesifik (anak - set menjadi Nullable): tipe_audio, lokasi_baris, kacamata_3d_id, efek_gerak_fitur, bantal_selimut_pack, dan layanan_butter

isilah table tersebut dengan minimal 2 data sampel untuk masing masing  jenis studio( total minimal 20 data baris)