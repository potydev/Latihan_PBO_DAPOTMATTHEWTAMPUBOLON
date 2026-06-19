Tahap 1: Konfigurasi Basis Data (MySQL)
1. Buatlah basis data relasional pada MySQL dengan format nama:
DB_SIMULASI_PBO_KELAS_NamaLengkap.
2. Rancang satu tabel terpusat bernama tabel_pendaftaran dengan ketentuan struktur
kolom yang mencakup seluruh atribut objek sebagai berikut:
o Atribut Global (Induk): id_pendaftaran (Primary Key), nama_calon,
asal_sekolah, nilai_ujian, biaya_pendaftaran_dasar, dan jalur_pendaftaran
(Enum: Reguler, Prestasi, Kedinasan).
o Atribut Spesifik (Anak - Set Menjadi Nullable): pilihan_prodi, lokasi_kampus,
jenis_prestasi, tingkat_prestasi, sk_ikatan_dinas, dan instansi_sponsor.
3. Isilah tabel tersebut dengan minimal data sampel untuk masing-masing jalur
pendaftaran (Total minimal 20 data baris).
Tahap 2: Manajemen Repositori GitHub &amp; Ketentuan Komit
1. Buatlah repositori GitHub publik dengan nama: Latihan_PBO_NamaLengkap.
2. Ekspor basis data latihan Anda menjadi file .sql dan masukkan ke dalam proyek.
3. Aturan Komit Latihan: Biasakan setiap kali melakukan commit perubahan kode ke
GitHub, pesan komit (commit message) harus diawali dengan nomor tahapan soal.
o Contoh: &quot;[Tahap 3] Membuat abstract class Pendaftaran&quot;, &quot;[Tahap 5]
Mengimplementasikan overriding biaya jalur Kedinasan&quot;.

4. Ketentuan Pengumpulan (Submission): Jika seluruh tahapan program telah selesai
di-push ke GitHub, salin tautan (link) URL repositori GitHub publik Anda, lalu
kumpulkan/submit ke kolom tugas yang tersedia di Google Classroom pada Tahap
ke-2 ini.
Tahap 3: Implementasi Abstraksi (Abstraction)
1. Buatlah file koneksi/database.php untuk menangani koneksi database menggunakan
PDO atau MySQLi.
2. Buatlah sebuah abstract class bernama Pendaftaran.
3. Properti/Atribut Terenkapsulasi (protected): id_pendaftaran, nama_calon,
asal_sekolah, nilai_ujian, dan biayaPendaftaranDasar. (Nilai properti ini wajib
dipetakan dari kolom tabel database pada Tahap 1).

4. Metode Abstrak (Tanpa Isi/Body): Wajib mendeklarasikan abstract method:
o abstract public function hitungTotalBiaya();
o abstract public function tampilkanInfoJalur();

Tahap 4: Implementasi Pewarisan (Inheritance) &amp; Metode Query Spesifik
Turunkan abstract class Pendaftaran menjadi 3 kelas anak (subclass) konkrit yang
merepresentasikan jalur pendaftaran mahasiswa baru.
Di setiap kelas anak, tambahkan satu metode khusus untuk mengeksekusi query SQL
spesifik ke tabel_pendaftaran guna mengambil data yang hanya relevan dengan jalur
tersebut:
1. PendaftaranReguler:
o Properti tambahan: pilihanProdi dan lokasiKampus.
o Metode Query Spesifik: getDaftarReguler($db) -&gt; Berisi query SELECT ...
WHERE jalur_pendaftaran = &#39;Reguler&#39;.

2. PendaftaranPrestasi:
o Properti tambahan: jenisPrestasi dan tingkatPrestasi.
o Metode Query Spesifik: getDaftarPrestasi($db) -&gt; Berisi query SELECT ...
WHERE jalur_pendaftaran = &#39;Prestasi&#39;.

3. PendaftaranKedinasan:
o Properti tambahan: skIkatanDinas dan instansiSponsor.
o Metode Query Spesifik: getDaftarKedinasan($db) -&gt; Berisi query SELECT ...
WHERE jalur_pendaftaran = &#39;Kedinasan&#39;.

Tahap 5: Implementasi Polimorfisme (Polymorphism Overriding)
Lakukan method overriding pada fungsi hitungTotalBiaya() di setiap kelas anak dengan
logika aturan biaya pendaftaran sebagai berikut:
1. PendaftaranReguler: Total Biaya = biayaPendaftaranDasar (Tarif standar murni
tanpa biaya tambahan seleksi/tes laboratorium).
2. PendaftaranPrestasi: Total Biaya = biayaPendaftaranDasar - 50000 (Mendapatkan
potongan/insentif apresiasi prestasi sebesar Rp50.000 dari biaya dasar).
3. PendaftaranKedinasan: Total Biaya = (biayaPendaftaranDasar) * 1.25 (Dikenakan
surcharge/biaya tambahan untuk pengurusan administrasi khusus dan kemitraan
dinas sebesar 25% dari biaya dasar).

Tahap 6: Pembuatan Komponen Antarmuka (View dengan PHP)
1. Bangun halaman antarmuka (view) menggunakan PHP untuk menampilkan daftar
pendaftaran mahasiswa baru yang masuk secara dinamis dari database.
2. Manfaatkan metode query spesifik dari Tahap 4 untuk memisahkan atau
mengelompokkan tampilan tabel data calon mahasiswa berdasarkan kategori: Jalur
Reguler, Jalur Prestasi, dan Jalur Kedinasan.
3. Manfaatkan metode polimorfik (tampilkanInfoJalur() dan hitungTotalBiaya()) untuk
mencetak atribut unik masing-masing jalur beserta kalkulasi akhir total biaya
pendaftaran langsung pada halaman web.