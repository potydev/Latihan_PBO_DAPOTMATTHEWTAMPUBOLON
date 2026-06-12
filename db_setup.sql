-- 1. Membuat Database
CREATE DATABASE IF NOT EXISTS DB_LATIHAN_PBO_TRPL1A_DAPOTMATTHEWTAMPUBOLON;
USE DB_LATIHAN_PBO_TRPL1A_DAPOTMATTHEWTAMPUBOLON;

-- 2. Membuat Tabel Terpusat (tabel_tiket)
CREATE TABLE IF NOT EXISTS tabel_tiket (
    -- Atribut Global / Induk
    id_tiket INT AUTO_INCREMENT PRIMARY KEY,
    nama_film VARCHAR(150) NOT NULL,
    jadwal_tayang DATETIME NOT NULL,
    jumlah_kursi INT NOT NULL,
    harga_dasar_tiket DECIMAL(10, 2) NOT NULL,
    jenis_studio ENUM('reguler', 'imax', 'velvet') NOT NULL,
    
    -- Atribut Spesifik / Anak (Nullable)
    tipe_audio VARCHAR(50) NULL,
    lokasi_baris VARCHAR(10) NULL,
    kacamata_3d_id VARCHAR(50) NULL,
    efek_gerak_fitur VARCHAR(100) NULL,
    bantal_selimut_pack VARCHAR(50) NULL,
    layanan_butter VARCHAR(100) NULL
);

-- 3. Memasukkan Data Sampel (Minimal 2 untuk masing-masing studio, Total 20 Baris)
INSERT INTO tabel_tiket (
    nama_film, 
    jadwal_tayang, 
    jumlah_kursi, 
    harga_dasar_tiket, 
    jenis_studio, 
    tipe_audio, 
    lokasi_baris, 
    kacamata_3d_id, 
    efek_gerak_fitur, 
    bantal_selimut_pack, 
    layanan_butter
) VALUES 
-- --- KELOMPOK STUDIO: REGULER (7 Baris) ---
('Avengers: Endgame', '2026-06-15 13:00:00', 120, 35000.00, 'reguler', 'Dolby Atmos 7.1', 'Row A-K', NULL, NULL, NULL, NULL),
('Spider-Man: No Way Home', '2026-06-15 15:30:00', 120, 35000.00, 'reguler', 'Dolby Surround 5.1', 'Row B-L', NULL, NULL, NULL, NULL),
('Interstellar', '2026-06-15 18:00:00', 100, 40000.00, 'reguler', 'Dolby Surround 5.1', 'Row C-M', NULL, NULL, NULL, NULL),
('Inception', '2026-06-16 10:00:00', 100, 35000.00, 'reguler', 'Stereo 2.0', 'Row D-N', NULL, NULL, NULL, NULL),
('The Dark Knight', '2026-06-16 14:00:00', 120, 35000.00, 'reguler', 'Dolby Atmos 7.1', 'Row E-O', NULL, NULL, NULL, NULL),
('Dune: Part Two', '2026-06-16 17:00:00', 100, 40000.00, 'reguler', 'Dolby Atmos 7.1', 'Row F-P', NULL, NULL, NULL, NULL),
('How to Train Your Dragon', '2026-06-16 20:00:00', 120, 35000.00, 'reguler', 'Dolby Surround 5.1', 'Row G-Q', NULL, NULL, NULL, NULL),

-- --- KELOMPOK STUDIO: IMAX (7 Baris) ---
('Avatar: The Way of Water', '2026-06-15 12:00:00', 250, 60000.00, 'imax', 'IMAX 12.0 Channel', 'Row H-R', '3D-IMX-001', 'None', NULL, NULL),
('Jurassic World', '2026-06-15 15:00:00', 250, 60000.00, 'imax', 'IMAX 6.1 Channel', 'Row I-S', '3D-IMX-002', 'None', NULL, NULL),
('Oppenheimer', '2026-06-15 19:00:00', 200, 75000.00, 'imax', 'IMAX 12.0 Channel', 'Row J-T', NULL, 'Sub-Bass Shaker', NULL, NULL),
('Top Gun: Maverick', '2026-06-16 11:30:00', 200, 65000.00, 'imax', 'IMAX 12.0 Channel', 'Row K-U', NULL, 'None', NULL, NULL),
('Doctor Strange in the Multiverse of Madness', '2026-06-16 14:30:00', 250, 60000.00, 'imax', 'IMAX 12.0 Channel', 'Row L-V', '3D-IMX-005', 'Interactive Seat Vibration', NULL, NULL),
('Star Wars: The Force Awakens', '2026-06-16 18:00:00', 250, 60000.00, 'imax', 'IMAX 6.1 Channel', 'Row M-W', '3D-IMX-006', 'None', NULL, NULL),
('Guardians of the Galaxy Vol. 3', '2026-06-16 21:00:00', 250, 65000.00, 'imax', 'IMAX 12.0 Channel', 'Row N-X', '3D-IMX-007', 'Active 3D Synchronization', NULL, NULL),

-- --- KELOMPOK STUDIO: VELVET (6 Baris) ---
('La La Land', '2026-06-15 14:00:00', 40, 120000.00, 'velvet', 'Dolby Surround 5.1', 'Row V-A', NULL, NULL, 'Premium Satin Pack', 'Popcorn Caramel & Sweet Tea'),
('Titanic', '2026-06-15 18:30:00', 40, 150000.00, 'velvet', 'Dolby Atmos 7.1', 'Row V-B', NULL, NULL, 'Royal Velvet Pack', 'French Fries & Hot Latte'),
('The Great Gatsby', '2026-06-16 13:00:00', 30, 120000.00, 'velvet', 'Dolby Surround 5.1', 'Row V-C', NULL, NULL, 'Standard Cotton Pack', 'Popcorn Salty & Soft Drink'),
('A Star Is Born', '2026-06-16 16:30:00', 30, 120000.00, 'velvet', 'Dolby Surround 5.1', 'Row V-D', NULL, NULL, 'Premium Satin Pack', 'Nachos with Cheese Sauce'),
('About Time', '2026-06-16 19:30:00', 40, 130000.00, 'velvet', 'Dolby Surround 5.1', 'Row V-E', NULL, NULL, 'Royal Velvet Pack', 'Chocolate Lava Cake & Hot Tea'),
('The Notebook', '2026-06-16 22:15:00', 40, 130000.00, 'velvet', 'Dolby Surround 5.1', 'Row V-F', NULL, NULL, 'Standard Cotton Pack', 'Croissant & Lemon Tea');
