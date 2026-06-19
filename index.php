<?php
require_once 'koneksi/database.php';
require_once 'Tiket.php';
require_once 'TiketReguler.php';
require_once 'TiketIMAX.php';
require_once 'TiketVelvet.php';

// Inisialisasi koneksi database
$database = new Database();
$db = $database->getConnection();

$dbError = null;
$daftarReguler = [];
$daftarIMAX = [];
$daftarVelvet = [];

if ($db === null) {
    $dbError = "Koneksi ke database gagal. Pastikan XAMPP/MySQL Anda sudah aktif dan database `DB_LATIHAN_PBO_TRPL1A_DAPOTMATTHEWTAMPUBOLON` sudah di-import.";
} else {
    try {
        // Mengambil daftar tiket berdasarkan jenis studio menggunakan metode statis
        $daftarReguler = TiketReguler::getDaftarReguler($db);
        $daftarIMAX = TiketIMAX::getDaftarIMAX($db);
        $daftarVelvet = TiketVelvet::getDaftarVelvet($db);
    } catch (Exception $e) {
        $dbError = "Terjadi kesalahan query: " . $e->getMessage();
    }
}

// Menghitung statistik ringkas
$totalReguler = count($daftarReguler);
$totalIMAX = count($daftarIMAX);
$totalVelvet = count($daftarVelvet);
$totalTransaksi = $totalReguler + $totalIMAX + $totalVelvet;

$totalPendapatan = 0;
foreach ($daftarReguler as $t) { $totalPendapatan += $t->hitungTotalHarga(); }
foreach ($daftarIMAX as $t) { $totalPendapatan += $t->hitungTotalHarga(); }
foreach ($daftarVelvet as $t) { $totalPendapatan += $t->hitungTotalHarga(); }
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cineplex Ticket Dashboard | PBO TRPL 1A</title>
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <!-- FontAwesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        :root {
            --bg-light: #f8fafc;
            --text-main: #0f172a; /* Slate 900 */
            --text-muted: #64748b; /* Slate 500 */
            --primary: #4f46e5; /* Indigo 600 */
            --primary-glow: rgba(79, 70, 229, 0.08);
            
            --reguler-color: #0284c7; /* Sky 600 */
            --reguler-glow: rgba(2, 132, 199, 0.08);
            --imax-color: #d97706; /* Amber 600 */
            --imax-glow: rgba(217, 119, 6, 0.08);
            --velvet-color: #9333ea; /* Purple 600 */
            --velvet-glow: rgba(147, 51, 234, 0.08);
            
            --success: #059669; /* Emerald 600 */
            --border-glass: rgba(255, 255, 255, 0.5);
            --bg-glass: rgba(255, 255, 255, 0.45);
            --font: 'Outfit', sans-serif;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: var(--font);
            -webkit-font-smoothing: antialiased;
        }

        body {
            background-color: #f1f5f9;
            color: var(--text-main);
            min-height: 100vh;
            padding: 2.5rem 1.5rem;
            position: relative;
            overflow-x: hidden;
        }

        /* Decorative Background Blobs */
        .bg-blobs {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: -1;
            overflow: hidden;
            pointer-events: none;
        }

        .blob {
            position: absolute;
            border-radius: 50%;
            filter: blur(90px);
            opacity: 0.55;
            animation: float 20s ease-in-out infinite alternate;
        }

        .blob-1 {
            top: -10%;
            left: -10%;
            width: 500px;
            height: 500px;
            background: radial-gradient(circle, rgba(99, 102, 241, 0.25) 0%, rgba(168, 85, 247, 0.1) 100%);
            animation-duration: 25s;
        }

        .blob-2 {
            bottom: -10%;
            right: -10%;
            width: 600px;
            height: 600px;
            background: radial-gradient(circle, rgba(14, 165, 233, 0.25) 0%, rgba(16, 185, 129, 0.1) 100%);
            animation-duration: 30s;
            animation-delay: -5s;
        }

        .blob-3 {
            top: 40%;
            left: 50%;
            width: 450px;
            height: 450px;
            background: radial-gradient(circle, rgba(236, 72, 153, 0.18) 0%, rgba(245, 158, 11, 0.1) 100%);
            animation-duration: 22s;
            animation-delay: -10s;
        }

        @keyframes float {
            0% { transform: translate(0, 0) scale(1); }
            50% { transform: translate(40px, -60px) scale(1.1); }
            100% { transform: translate(-30px, 30px) scale(0.95); }
        }

        .container {
            max-width: 1280px;
            margin: 0 auto;
            position: relative;
            z-index: 1;
        }

        /* Header Style */
        header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2.5rem;
            padding-bottom: 1.5rem;
            border-bottom: 1px solid rgba(0, 0, 0, 0.05);
        }

        .brand-section {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .logo-icon {
            background: linear-gradient(135deg, var(--primary), #a855f7);
            width: 48px;
            height: 48px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            color: white;
            box-shadow: 0 8px 20px rgba(99, 102, 241, 0.25);
            transition: transform 0.3s ease;
        }

        header:hover .logo-icon {
            transform: rotate(15deg) scale(1.1);
        }

        .brand-title h1 {
            font-size: 1.75rem;
            font-weight: 800;
            letter-spacing: -0.5px;
            background: linear-gradient(135deg, #1e1b4b 0%, #4338ca 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .brand-title p {
            font-size: 0.875rem;
            color: var(--text-muted);
        }

        .badge-student {
            background: rgba(255, 255, 255, 0.65);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.5);
            padding: 0.5rem 1.25rem;
            border-radius: 50px;
            font-size: 0.875rem;
            display: flex;
            align-items: center;
            gap: 0.6rem;
            color: #334155;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.03);
            font-weight: 500;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .badge-student:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.05);
        }

        .badge-student i {
            color: var(--primary);
        }

        /* Error Message Card */
        .error-card {
            background: rgba(239, 68, 68, 0.05);
            border: 1px solid rgba(239, 68, 68, 0.15);
            border-radius: 20px;
            padding: 2.5rem;
            text-align: center;
            margin-bottom: 2.5rem;
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            box-shadow: 0 10px 30px rgba(239, 68, 68, 0.05);
        }

        .error-card i {
            font-size: 3rem;
            color: #ef4444;
            margin-bottom: 1rem;
        }

        .error-card h3 {
            font-size: 1.5rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
        }

        .error-card p {
            color: var(--text-muted);
            max-width: 600px;
            margin: 0 auto 1.5rem;
        }

        .error-card pre {
            background-color: rgba(15, 23, 42, 0.05);
            color: #0f172a;
            padding: 1rem;
            border-radius: 12px;
            text-align: left;
            overflow-x: auto;
            max-width: 700px;
            margin: 0 auto;
            border: 1px solid rgba(0, 0, 0, 0.06);
            font-family: monospace;
        }

        /* Stats Cards */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
            gap: 1.5rem;
            margin-bottom: 3rem;
        }

        .stat-card {
            background: rgba(255, 255, 255, 0.45);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border: 1px solid rgba(255, 255, 255, 0.5);
            border-radius: 20px;
            padding: 1.75rem 1.5rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.04);
        }

        .stat-card:hover {
            transform: translateY(-5px);
            background: rgba(255, 255, 255, 0.65);
        }

        .stat-card:hover .stat-icon {
            transform: scale(1.1) rotate(-5deg);
        }

        .stat-info h4 {
            font-size: 0.8rem;
            color: var(--text-muted);
            text-transform: uppercase;
            letter-spacing: 0.75px;
            margin-bottom: 0.5rem;
            font-weight: 700;
        }

        .stat-info p {
            font-size: 1.75rem;
            font-weight: 800;
            color: var(--text-main);
        }

        .stat-icon {
            width: 48px;
            height: 48px;
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.25rem;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.02);
            transition: transform 0.3s ease;
        }

        .stat-card.total-income {
            background: rgba(255, 255, 255, 0.55);
            border: 1px solid rgba(79, 70, 229, 0.2);
            box-shadow: 0 8px 32px 0 rgba(79, 70, 229, 0.06);
        }

        .stat-card.total-income .stat-icon {
            background-color: var(--primary-glow);
            color: var(--primary);
        }

        .stat-card.reguler {
            border-bottom: 3px solid var(--reguler-color);
        }
        .stat-card.reguler .stat-icon {
            background-color: var(--reguler-glow);
            color: var(--reguler-color);
        }
        .stat-card.reguler:hover {
            box-shadow: 0 12px 30px rgba(2, 132, 199, 0.1);
        }

        .stat-card.imax {
            border-bottom: 3px solid var(--imax-color);
        }
        .stat-card.imax .stat-icon {
            background-color: var(--imax-glow);
            color: var(--imax-color);
        }
        .stat-card.imax:hover {
            box-shadow: 0 12px 30px rgba(217, 119, 6, 0.1);
        }

        .stat-card.velvet {
            border-bottom: 3px solid var(--velvet-color);
        }
        .stat-card.velvet .stat-icon {
            background-color: var(--velvet-glow);
            color: var(--velvet-color);
        }
        .stat-card.velvet:hover {
            box-shadow: 0 12px 30px rgba(147, 51, 234, 0.1);
        }

        /* Interactive Tabs */
        .tabs-header {
            display: flex;
            gap: 0.5rem;
            margin-bottom: 2rem;
            background: rgba(255, 255, 255, 0.35);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.5);
            border-radius: 16px;
            padding: 0.4rem;
            width: fit-content;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.02);
        }

        .tab-btn {
            background: none;
            border: none;
            color: var(--text-muted);
            font-size: 0.95rem;
            font-weight: 600;
            padding: 0.75rem 1.5rem;
            cursor: pointer;
            border-radius: 12px;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            display: flex;
            align-items: center;
            gap: 0.6rem;
        }

        .tab-btn:hover {
            color: var(--text-main);
            background-color: rgba(255, 255, 255, 0.5);
            transform: translateY(-1px);
        }

        .tab-btn:active {
            transform: translateY(1px);
        }

        .tab-btn.active {
            color: var(--text-main);
            background-color: rgba(255, 255, 255, 0.9);
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
        }

        .tab-btn.active[data-target="reguler-panel"] {
            box-shadow: 0 4px 12px rgba(2, 132, 199, 0.1), inset 0 -2px 0 var(--reguler-color);
        }

        .tab-btn.active[data-target="imax-panel"] {
            box-shadow: 0 4px 12px rgba(217, 119, 6, 0.1), inset 0 -2px 0 var(--imax-color);
        }

        .tab-btn.active[data-target="velvet-panel"] {
            box-shadow: 0 4px 12px rgba(147, 51, 234, 0.1), inset 0 -2px 0 var(--velvet-color);
        }

        .tab-panel {
            display: none;
            animation: fadeIn 0.4s ease;
        }

        .tab-panel.active {
            display: block;
        }

        /* Studio Title Badge */
        .panel-heading {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.5rem;
            flex-wrap: wrap;
            gap: 1rem;
        }

        .panel-title {
            display: flex;
            align-items: center;
            gap: 1rem;
            flex-wrap: wrap;
        }

        .panel-title h2 {
            font-size: 1.5rem;
            font-weight: 800;
            color: var(--text-main);
            letter-spacing: -0.5px;
        }

        .studio-badge {
            font-size: 0.75rem;
            text-transform: uppercase;
            font-weight: 700;
            letter-spacing: 1.25px;
            padding: 0.35rem 0.85rem;
            border-radius: 50px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.02);
        }

        .studio-badge.reguler {
            background-color: var(--reguler-glow);
            color: var(--reguler-color);
            border: 1px solid rgba(2, 132, 199, 0.15);
        }

        .studio-badge.imax {
            background-color: var(--imax-glow);
            color: var(--imax-color);
            border: 1px solid rgba(217, 119, 6, 0.15);
        }

        .studio-badge.velvet {
            background-color: var(--velvet-glow);
            color: var(--velvet-color);
            border: 1px solid rgba(147, 51, 234, 0.15);
        }

        /* Modern Table Card */
        .table-card {
            background: rgba(255, 255, 255, 0.45);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.5);
            border-radius: 24px;
            overflow: hidden;
            box-shadow: 0 10px 40px rgba(31, 38, 135, 0.04);
            margin-bottom: 2.5rem;
            transition: all 0.3s ease;
        }

        .table-responsive {
            width: 100%;
            overflow-x: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            text-align: left;
        }

        th {
            background: rgba(255, 255, 255, 0.35);
            color: var(--text-muted);
            font-weight: 700;
            font-size: 0.8rem;
            text-transform: uppercase;
            letter-spacing: 1px;
            padding: 1.25rem 1.5rem;
            border-bottom: 1px solid rgba(0, 0, 0, 0.05);
        }

        td {
            padding: 1.25rem 1.5rem;
            border-bottom: 1px solid rgba(0, 0, 0, 0.04);
            font-size: 0.95rem;
            color: var(--text-main);
            vertical-align: middle;
            transition: background-color 0.2s ease;
        }

        tr:last-child td {
            border-bottom: none;
        }

        tr:hover td {
            background: rgba(255, 255, 255, 0.55);
        }

        /* Table Column Styles */
        .film-info {
            display: flex;
            flex-direction: column;
            gap: 0.35rem;
        }

        .film-title {
            font-weight: 700;
            color: var(--text-main);
            font-size: 1rem;
        }

        .ticket-id {
            font-family: monospace;
            font-size: 0.75rem;
            color: var(--text-muted);
            background: rgba(0, 0, 0, 0.04);
            padding: 0.15rem 0.5rem;
            border-radius: 6px;
            width: fit-content;
            font-weight: 500;
        }

        .datetime-cell {
            font-weight: 500;
            color: var(--text-main);
        }

        .datetime-cell i {
            color: var(--primary);
            margin-right: 0.4rem;
        }

        .seat-badge {
            background-color: rgba(0, 0, 0, 0.04);
            padding: 0.35rem 0.75rem;
            border-radius: 8px;
            font-weight: 700;
            font-size: 0.85rem;
            color: var(--text-main);
            border: 1px solid rgba(0, 0, 0, 0.02);
            display: inline-block;
        }

        .price-text {
            font-weight: 600;
            color: var(--text-main);
        }

        /* Facility Info Styles */
        .facility-wrapper {
            background-color: rgba(255, 255, 255, 0.5);
            border-left: 4px solid var(--primary);
            padding: 0.65rem 1rem;
            border-radius: 0 12px 12px 0;
            font-size: 0.875rem;
            max-width: 320px;
            line-height: 1.45;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.01);
            border-top: 1px solid rgba(255, 255, 255, 0.5);
            border-bottom: 1px solid rgba(255, 255, 255, 0.5);
            border-right: 1px solid rgba(255, 255, 255, 0.5);
        }

        .reguler .facility-wrapper { border-left-color: var(--reguler-color); }
        .imax .facility-wrapper { border-left-color: var(--imax-color); }
        .velvet .facility-wrapper { border-left-color: var(--velvet-color); }

        .facility-title {
            font-weight: 800;
            margin-bottom: 0.25rem;
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 0.75px;
        }

        .reguler .facility-title { color: var(--reguler-color); }
        .imax .facility-title { color: var(--imax-color); }
        .velvet .facility-title { color: var(--velvet-color); }

        /* Total Price Column */
        .total-price-cell {
            font-weight: 800;
            font-size: 1.1rem;
            color: var(--success);
        }

        /* Empty State */
        .empty-state {
            padding: 5rem 2rem;
            text-align: center;
            color: var(--text-muted);
        }

        .empty-state i {
            font-size: 3.5rem;
            margin-bottom: 1.25rem;
            opacity: 0.4;
            color: var(--text-muted);
        }

        .empty-state p {
            font-size: 1.05rem;
            font-weight: 500;
        }

        /* Footer */
        footer {
            text-align: center;
            margin-top: 6rem;
            padding-top: 2rem;
            border-top: 1px solid rgba(0, 0, 0, 0.05);
            color: var(--text-muted);
            font-size: 0.875rem;
            line-height: 1.6;
        }

        footer a {
            color: var(--primary);
            text-decoration: none;
            font-weight: 600;
            transition: color 0.2s ease;
        }

        footer a:hover {
            color: #312e81;
            text-decoration: underline;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(8px); }
            to { opacity: 1; transform: translateY(0); }
        }

        @media (max-width: 768px) {
            header {
                flex-direction: column;
                align-items: flex-start;
                gap: 1rem;
            }

            .tabs-header {
                flex-wrap: wrap;
                width: 100%;
            }

            .tab-btn {
                flex: 1 1 auto;
                text-align: center;
                justify-content: center;
            }
        }
    </style>
</head>
<body>
<div class="bg-blobs">
    <div class="blob blob-1"></div>
    <div class="blob blob-2"></div>
    <div class="blob blob-3"></div>
</div>

<div class="container">
    <!-- Header -->
    <header>
        <div class="brand-section">
            <div class="logo-icon">
                <i class="fa-solid fa-film"></i>
            </div>
            <div class="brand-title">
                <h1>Cineplex PBO Dashboard</h1>
                <p>Sistem Manajemen Tiket Bioskop & Layanan Studio</p>
            </div>
        </div>
        
        <div class="badge-student">
            <i class="fa-solid fa-graduation-cap"></i>
            <span><strong>Dapot Matthew Tampubolon</strong> | TRPL 1A</span>
        </div>
    </header>

    <?php if ($dbError): ?>
        <!-- Tampilan Error Database Connection -->
        <div class="error-card">
            <i class="fa-solid fa-triangle-exclamation"></i>
            <h3>Koneksi Database Bermasalah</h3>
            <p><?= htmlspecialchars($dbError); ?></p>
            <p>Untuk mensimulasikan database lokal, jalankan perintah import basis data menggunakan berkas SQL yang disediakan:</p>
            <pre>mysql -u root -p DB_LATIHAN_PBO_TRPL1A_DAPOTMATTHEWTAMPUBOLON < db_setup.sql</pre>
        </div>
    <?php else: ?>

        <!-- Ringkasan Statistik Utama -->
        <section class="stats-grid">
            <div class="stat-card reguler">
                <div class="stat-info">
                    <h4>Reguler Studio</h4>
                    <p><?= $totalReguler; ?> <span style="font-size: 0.95rem; font-weight: 400; color: var(--text-muted);">Tiket</span></p>
                </div>
                <div class="stat-icon">
                    <i class="fa-solid fa-ticket"></i>
                </div>
            </div>
            
            <div class="stat-card imax">
                <div class="stat-info">
                    <h4>IMAX 3D Studio</h4>
                    <p><?= $totalIMAX; ?> <span style="font-size: 0.95rem; font-weight: 400; color: var(--text-muted);">Tiket</span></p>
                </div>
                <div class="stat-icon">
                    <i class="fa-solid fa-glasses"></i>
                </div>
            </div>
            
            <div class="stat-card velvet">
                <div class="stat-info">
                    <h4>Velvet Suite</h4>
                    <p><?= $totalVelvet; ?> <span style="font-size: 0.95rem; font-weight: 400; color: var(--text-muted);">Tiket</span></p>
                </div>
                <div class="stat-icon">
                    <i class="fa-solid fa-couch"></i>
                </div>
            </div>

            <div class="stat-card total-income">
                <div class="stat-info">
                    <h4>Total Pendapatan (Polimorfis)</h4>
                    <p>Rp <?= number_format($totalPendapatan, 0, ',', '.'); ?></p>
                </div>
                <div class="stat-icon">
                    <i class="fa-solid fa-wallet"></i>
                </div>
            </div>
        </section>

        <!-- Navigation Tabs -->
        <div class="tabs-header">
            <button class="tab-btn active" data-target="reguler-panel">
                <i class="fa-solid fa-circle-play" style="color: var(--reguler-color);"></i> Reguler Studio
            </button>
            <button class="tab-btn" data-target="imax-panel">
                <i class="fa-solid fa-bolt" style="color: var(--imax-color);"></i> IMAX 3D
            </button>
            <button class="tab-btn" data-target="velvet-panel">
                <i class="fa-solid fa-crown" style="color: var(--velvet-color);"></i> Velvet Suite
            </button>
        </div>

        <!-- PANEL REGULER -->
        <div id="reguler-panel" class="tab-panel active reguler">
            <div class="panel-heading">
                <div class="panel-title">
                    <h2>Daftar Penjualan Tiket Reguler</h2>
                    <span class="studio-badge reguler">Studio Standard</span>
                </div>
                <span style="color: var(--text-muted); font-size: 0.9rem;">Menampilkan <?= $totalReguler; ?> entri</span>
            </div>
            
            <div class="table-card">
                <div class="table-responsive">
                    <table>
                        <thead>
                            <tr>
                                <th>Film & ID</th>
                                <th>Jadwal Tayang</th>
                                <th>Kursi</th>
                                <th>Harga Dasar</th>
                                <th>Detail Fasilitas (Polimorfik)</th>
                                <th>Total Bayar</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($daftarReguler)): ?>
                                <tr>
                                    <td colspan="6" class="empty-state">
                                        <i class="fa-solid fa-folder-open"></i>
                                        <p>Tidak ada data tiket reguler.</p>
                                    </td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($daftarReguler as $tiket): ?>
                                    <tr>
                                        <td>
                                            <div class="film-info">
                                                <span class="film-title"><?= htmlspecialchars($tiket->getNamaFilm()); ?></span>
                                                <span class="ticket-id">ID: <?= $tiket->getIdTiket(); ?></span>
                                            </div>
                                        </td>
                                        <td class="datetime-cell">
                                            <i class="fa-regular fa-calendar-check"></i>
                                            <?= date('d M Y - H:i', strtotime($tiket->getJadwalTayang())); ?> WIB
                                        </td>
                                        <td>
                                            <span class="seat-badge"><?= $tiket->getJumlahKursi(); ?> Pax</span>
                                        </td>
                                        <td class="price-text">
                                            Rp <?= number_format($tiket->getHargaDasarTiket(), 0, ',', '.'); ?>
                                        </td>
                                        <td>
                                            <div class="facility-wrapper">
                                                <div class="facility-title">Info Audio & Baris</div>
                                                <?= htmlspecialchars($tiket->tampilkanInfoFasilitas()); ?>
                                            </div>
                                        </td>
                                        <td class="total-price-cell">
                                            Rp <?= number_format($tiket->hitungTotalHarga(), 0, ',', '.'); ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- PANEL IMAX -->
        <div id="imax-panel" class="tab-panel imax">
            <div class="panel-heading">
                <div class="panel-title">
                    <h2>Daftar Penjualan Tiket IMAX 3D</h2>
                    <span class="studio-badge imax">Studio Premium</span>
                </div>
                <span style="color: var(--text-muted); font-size: 0.9rem;">Menampilkan <?= $totalIMAX; ?> entri</span>
            </div>
            
            <div class="table-card">
                <div class="table-responsive">
                    <table>
                        <thead>
                            <tr>
                                <th>Film & ID</th>
                                <th>Jadwal Tayang</th>
                                <th>Kursi</th>
                                <th>Harga Dasar</th>
                                <th>Detail Fasilitas (Polimorfik)</th>
                                <th>Total Bayar</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($daftarIMAX)): ?>
                                <tr>
                                    <td colspan="6" class="empty-state">
                                        <i class="fa-solid fa-folder-open"></i>
                                        <p>Tidak ada data tiket IMAX.</p>
                                    </td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($daftarIMAX as $tiket): ?>
                                    <tr>
                                        <td>
                                            <div class="film-info">
                                                <span class="film-title"><?= htmlspecialchars($tiket->getNamaFilm()); ?></span>
                                                <span class="ticket-id">ID: <?= $tiket->getIdTiket(); ?></span>
                                            </div>
                                        </td>
                                        <td class="datetime-cell">
                                            <i class="fa-regular fa-calendar-check"></i>
                                            <?= date('d M Y - H:i', strtotime($tiket->getJadwalTayang())); ?> WIB
                                        </td>
                                        <td>
                                            <span class="seat-badge"><?= $tiket->getJumlahKursi(); ?> Pax</span>
                                        </td>
                                        <td class="price-text">
                                            Rp <?= number_format($tiket->getHargaDasarTiket(), 0, ',', '.'); ?>
                                        </td>
                                        <td>
                                            <div class="facility-wrapper">
                                                <div class="facility-title">Layar Lebar & Aksesoris</div>
                                                <?= htmlspecialchars($tiket->tampilkanInfoFasilitas()); ?>
                                            </div>
                                        </td>
                                        <td class="total-price-cell">
                                            Rp <?= number_format($tiket->hitungTotalHarga(), 0, ',', '.'); ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- PANEL VELVET -->
        <div id="velvet-panel" class="tab-panel velvet">
            <div class="panel-heading">
                <div class="panel-title">
                    <h2>Daftar Penjualan Tiket Velvet Suite</h2>
                    <span class="studio-badge velvet">Studio Luxury</span>
                </div>
                <span style="color: var(--text-muted); font-size: 0.9rem;">Menampilkan <?= $totalVelvet; ?> entri</span>
            </div>
            
            <div class="table-card">
                <div class="table-responsive">
                    <table>
                        <thead>
                            <tr>
                                <th>Film & ID</th>
                                <th>Jadwal Tayang</th>
                                <th>Kursi</th>
                                <th>Harga Dasar</th>
                                <th>Detail Fasilitas (Polimorfik)</th>
                                <th>Total Bayar</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($daftarVelvet)): ?>
                                <tr>
                                    <td colspan="6" class="empty-state">
                                        <i class="fa-solid fa-folder-open"></i>
                                        <p>Tidak ada data tiket Velvet.</p>
                                    </td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($daftarVelvet as $tiket): ?>
                                    <tr>
                                        <td>
                                            <div class="film-info">
                                                <span class="film-title"><?= htmlspecialchars($tiket->getNamaFilm()); ?></span>
                                                <span class="ticket-id">ID: <?= $tiket->getIdTiket(); ?></span>
                                            </div>
                                        </td>
                                        <td class="datetime-cell">
                                            <i class="fa-regular fa-calendar-check"></i>
                                            <?= date('d M Y - H:i', strtotime($tiket->getJadwalTayang())); ?> WIB
                                        </td>
                                        <td>
                                            <span class="seat-badge"><?= $tiket->getJumlahKursi(); ?> Pax</span>
                                        </td>
                                        <td class="price-text">
                                            Rp <?= number_format($tiket->getHargaDasarTiket(), 0, ',', '.'); ?>
                                        </td>
                                        <td>
                                            <div class="facility-wrapper">
                                                <div class="facility-title">Sofa Bed & Butler Service</div>
                                                <?= htmlspecialchars($tiket->tampilkanInfoFasilitas()); ?>
                                            </div>
                                        </td>
                                        <td class="total-price-cell">
                                            Rp <?= number_format($tiket->hitungTotalHarga(), 0, ',', '.'); ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    <?php endif; ?>

    <!-- Footer -->
    <footer>
        <p>&copy; <?= date('Y'); ?> Cineplex Ticket System. Dikembangkan untuk tugas Pemrograman Berorientasi Objek (PBO).</p>
        <p style="margin-top: 0.5rem; font-size: 0.8rem;">Dibuat dengan <i class="fa-solid fa-heart" style="color: #ef4444;"></i> oleh <a href="#">Dapot Matthew Tampubolon</a> | Kelas TRPL 1A</p>
    </footer>
</div>

<script>
    // Tab functionality
    document.querySelectorAll('.tab-btn').forEach(button => {
        button.addEventListener('click', () => {
            // Remove active classes
            document.querySelectorAll('.tab-btn').forEach(btn => btn.classList.remove('active'));
            document.querySelectorAll('.tab-panel').forEach(panel => panel.classList.remove('active'));
            
            // Add active class to clicked button
            button.classList.add('active');
            
            // Show corresponding panel
            const targetId = button.getAttribute('data-target');
            document.getElementById(targetId).classList.add('active');
        });
    });
</script>
</body>
</html>
