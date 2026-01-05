<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Kantin UP</title>
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;700&display=swap" rel="stylesheet">
    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <style>
        * { box-sizing: border-box; }
        body { 
            font-family: 'Roboto', sans-serif; 
            margin: 0; 
            background-color: #f0f2f5; 
            display: flex; 
            min-height: 100vh; 
        }
        
        /* --- SIDEBAR LEFT --- */
        .sidebar { 
            width: 260px; 
            background-color: #1a202c; 
            color: white; 
            display: flex;
            flex-direction: column;
            position: fixed;
            height: 100%;
        }
        .sidebar-brand { 
            padding: 30px 20px; 
            text-align: center; 
            background-color: #ffffff; /* Background putih agar logo terlihat jelas */
            margin: 15px;
            border-radius: 8px;
        }
        .sidebar-brand img { 
            width: 100%; 
            max-width: 160px;
            height: auto;
            object-fit: contain;
        }
        .sidebar-menu { padding: 10px 0; flex-grow: 1; }
        .sidebar-menu a { 
            display: flex;
            align-items: center;
            padding: 15px 25px; 
            color: #a0aec0; 
            text-decoration: none; 
            transition: 0.3s;
            font-size: 14px;
        }
        .sidebar-menu a:hover, .sidebar-menu a.active { 
            background-color: #2d3748; 
            color: white; 
            border-left: 4px solid #3182ce; 
        }
        .sidebar-menu i { margin-right: 15px; font-size: 18px; width: 25px; text-align: center; }

        /* --- MAIN CONTENT RIGHT --- */
        .main-content { 
            flex: 1; 
            margin-left: 260px; /* Jarak agar tidak tertutup sidebar fixed */
            display: flex;
            flex-direction: column;
        }
        
        /* Topbar */
        .topbar { 
            background: white; 
            padding: 15px 30px; 
            display: flex; 
            justify-content: space-between; 
            align-items: center; 
            box-shadow: 0 1px 4px rgba(0,0,0,0.1);
            position: sticky;
            top: 0;
            z-index: 100;
        }
        .topbar h3 { margin: 0; color: #2d3748; font-size: 18px; }
        
        .user-profile { display: flex; align-items: center; gap: 15px; }
        .btn-logout { 
            background: #e53e3e; 
            color: white; 
            border: none; 
            padding: 8px 16px; 
            border-radius: 5px; 
            cursor: pointer; 
            font-weight: bold;
            font-size: 13px;
            transition: 0.3s;
        }
        .btn-logout:hover { background: #c53030; }

        /* Container Body */
        .container { padding: 30px; }
        .page-title { margin-bottom: 25px; color: #1a202c; }

        /* Stats Card Grid */
        .stats-grid { 
            display: grid; 
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); 
            gap: 25px; 
            margin-bottom: 30px; 
        }
        .stat-card { 
            background: white; 
            padding: 25px; 
            border-radius: 10px; 
            box-shadow: 0 4px 6px rgba(0,0,0,0.02); 
            display: flex; 
            align-items: center; 
            transition: transform 0.3s;
        }
        .stat-card:hover { transform: translateY(-5px); }
        .stat-icon { 
            width: 60px; 
            height: 60px; 
            border-radius: 12px; 
            display: flex; 
            align-items: center; 
            justify-content: center; 
            font-size: 28px; 
            margin-right: 20px; 
        }
        
        /* Warna Ikon */
        .bg-blue { background: #ebf8ff; color: #3182ce; }
        .bg-green { background: #f0fff4; color: #38a169; }
        .bg-purple { background: #faf5ff; color: #805ad5; }
        .bg-orange { background: #fffaf0; color: #dd6b20; }

        .stat-info p { margin: 0; color: #718096; font-size: 14px; font-weight: 500; }
        .stat-info h2 { margin: 5px 0 0 0; color: #2d3748; font-size: 24px; }

        /* Table Placeholder Area */
        .content-box { 
            background: white; 
            padding: 30px; 
            border-radius: 10px; 
            box-shadow: 0 4px 6px rgba(0,0,0,0.02); 
        }
        .content-box h4 { margin-top: 0; border-bottom: 2px solid #f0f2f5; padding-bottom: 15px; margin-bottom: 20px; }
        .placeholder-text { color: #a0aec0; text-align: center; padding: 40px 0; font-style: italic; }

    </style>
</head>
<body>

    <!-- SIDEBAR -->
    <div class="sidebar">
        <div class="sidebar-brand">
            <!-- Link Logo UP -->
            <img src="https://sso.universitaspertamina.ac.id/images/logo.png" alt="Logo Universitas Pertamina">
        </div>
        
        <div class="sidebar-menu">
            <a href="#" class="active"><i class="fa fa-chart-line"></i> Dashboard</a>
            <a href="#"><i class="fa fa-users"></i> Kelola User</a>
            <a href="#"><i class="fa fa-store"></i> Kelola Vendor</a>
            <a href="#"><i class="fa fa-utensils"></i> Manajemen Menu</a>
            <a href="#"><i class="fa fa-receipt"></i> Laporan Transaksi</a>
            <a href="#"><i class="fa fa-cog"></i> Pengaturan Sistem</a>
        </div>

        <div style="padding: 20px; font-size: 12px; color: #4a5568; text-align: center;">
            &copy; 2026 Kantin-UP Admin
        </div>
    </div>

    <!-- MAIN CONTENT -->
    <div class="main-content">
        <!-- TOPBAR -->
        <div class="topbar">
            <h3>Pusat Kendali Admin</h3>
            <div class="user-profile">
                <span>Selamat Datang, <strong>{{ Auth::user()->name }}</strong></span>
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="btn-logout">
                        <i class="fa fa-sign-out-alt"></i> KELUAR
                    </button>
                </form>
            </div>
        </div>

        <!-- CONTENT AREA -->
        <div class="container">
            <h1 class="page-title">Ringkasan Statistik</h1>
            
            <!-- Cards Grid -->
            <div class="stats-grid">
                <!-- Card 1 -->
                <div class="stat-card">
                    <div class="stat-icon bg-blue">
                        <i class="fa fa-user-graduate"></i>
                    </div>
                    <div class="stat-info">
                        <p>Total Mahasiswa</p>
                        <h2>1,420</h2>
                    </div>
                </div>

                <!-- Card 2 -->
                <div class="stat-card">
                    <div class="stat-icon bg-green">
                        <i class="fa fa-store"></i>
                    </div>
                    <div class="stat-info">
                        <p>Total Vendor</p>
                        <h2>28</h2>
                    </div>
                </div>

                <!-- Card 3 -->
                <div class="stat-card">
                    <div class="stat-icon bg-purple">
                        <i class="fa fa-calendar-check"></i>
                    </div>
                    <div class="stat-info">
                        <p>Transaksi Hari Ini</p>
                        <h2>86</h2>
                    </div>
                </div>

                <!-- Card 4 -->
                <div class="stat-card">
                    <div class="stat-icon bg-orange">
                        <i class="fa fa-wallet"></i>
                    </div>
                    <div class="stat-info">
                        <p>Total Omzet</p>
                        <h2>Rp 4.200.000</h2>
                    </div>
                </div>
            </div>

            <!-- Recent Activity Placeholder -->
            <div class="content-box">
                <h4><i class="fa fa-history"></i> Aktivitas Terakhir</h4>
                <div class="placeholder-text">
                    <i class="fa fa-info-circle"></i> Belum ada aktivitas terbaru untuk ditampilkan saat ini.
                </div>
            </div>
        </div>
    </div>

</body>
</html>