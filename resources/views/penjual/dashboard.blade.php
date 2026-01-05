<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vendor Dashboard - Kantin UP</title>
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;700&display=swap" rel="stylesheet">
    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <style>
        * { box-sizing: border-box; }
        body { 
            font-family: 'Roboto', sans-serif; 
            margin: 0; 
            background-color: #f4f7f6; 
        }

        /* --- NAVBAR --- */
        .navbar {
            background-color: #1b4332; /* Hijau tua profesional */
            padding: 10px 50px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            color: white;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }

        /* Container Logo Fix */
        .navbar-brand {
            background: white; /* Memberi background putih agar logo terlihat jelas */
            padding: 8px 15px;
            border-radius: 6px;
            display: flex;
            align-items: center;
        }
        .navbar-brand img {
            height: 40px;
            width: auto;
        }

        .user-nav {
            display: flex;
            align-items: center;
            gap: 20px;
        }
        .btn-logout {
            background-color: #e74c3c;
            color: white;
            padding: 8px 16px;
            border-radius: 5px;
            border: none;
            cursor: pointer;
            font-weight: bold;
            font-size: 13px;
            transition: 0.3s;
        }
        .btn-logout:hover { background-color: #c0392b; }

        /* --- CONTENT --- */
        .container { padding: 40px 50px; max-width: 1200px; margin: 0 auto; }

        .welcome-box {
            background: white;
            padding: 30px;
            border-radius: 12px;
            border-left: 6px solid #2d6a4f;
            box-shadow: 0 4px 6px rgba(0,0,0,0.05);
            margin-bottom: 30px;
        }
        .welcome-box h1 { margin: 0; color: #1b4332; font-size: 24px; }
        .welcome-box p { margin: 10px 0 0 0; color: #718096; }

        /* Stats Grid */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 20px;
        }
        .stat-card {
            background: white;
            padding: 25px;
            border-radius: 10px;
            text-align: center;
            box-shadow: 0 4px 6px rgba(0,0,0,0.03);
            border-bottom: 4px solid transparent;
            transition: 0.3s;
        }
        .stat-card:hover { transform: translateY(-5px); border-bottom: 4px solid #2d6a4f; }
        .stat-card i { font-size: 35px; color: #2d6a4f; margin-bottom: 15px; }
        .stat-card h2 { margin: 0; font-size: 28px; color: #2d3748; }
        .stat-card p { margin: 5px 0 0 0; color: #a0aec0; text-transform: uppercase; font-size: 12px; font-weight: bold; letter-spacing: 1px; }

        /* Action Buttons */
        .action-section {
            margin-top: 40px;
            display: flex;
            gap: 15px;
        }
        .btn-action {
            display: flex;
            align-items: center;
            gap: 10px;
            background: #2d6a4f;
            color: white;
            padding: 15px 30px;
            border-radius: 8px;
            text-decoration: none;
            font-weight: bold;
            box-shadow: 0 4px 10px rgba(45, 106, 79, 0.2);
            transition: 0.3s;
        }
        .btn-action:hover { background: #1b4332; transform: scale(1.02); }
        .btn-secondary { background: #2c3e50; box-shadow: 0 4px 10px rgba(44, 62, 80, 0.2); }
        .btn-secondary:hover { background: #1a252f; }

    </style>
</head>
<body>

    <!-- NAVBAR -->
    <nav class="navbar">
        <div class="navbar-brand">
            <!-- Logo Universitas Pertamina -->
            <img src="https://sso.universitaspertamina.ac.id/images/logo.png" alt="Logo UP">
        </div>
        
        <div class="user-nav">
            <span>Lapak: <strong>{{ Auth::user()->name }}</strong> <i class="fa fa-store" style="margin-left: 5px;"></i></span>
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="btn-logout">
                    <i class="fa fa-sign-out-alt"></i> LOGOUT
                </button>
            </form>
        </div>
    </nav>

    <!-- MAIN CONTENT -->
    <div class="container">
        
        <div class="welcome-box">
            <h1>Selamat Datang, Mitra Kantin!</h1>
            <p>Kelola dagangan Anda dan pantau pesanan dari mahasiswa Universitas Pertamina dengan mudah.</p>
        </div>

        <!-- Dashboard Stats -->
        <div class="stats-grid">
            <div class="stat-card">
                <i class="fa fa-hamburger"></i>
                <h2>14</h2>
                <p>Menu Aktif</p>
            </div>
            <div class="stat-card" style="background: #fff9db;">
                <i class="fa fa-clock" style="color: #f39c12;"></i>
                <h2>3</h2>
                <p>Pesanan Menunggu</p>
            </div>
            <div class="stat-card">
                <i class="fa fa-clipboard-check"></i>
                <h2>182</h2>
                <p>Total Penjualan</p>
            </div>
            <div class="stat-card">
                <i class="fa fa-chart-line"></i>
                <h2>Rp 2.400.000</h2>
                <p>Omset Bulan Ini</p>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="action-section">
            <a href="#" class="btn-action">
                <i class="fa fa-plus-circle"></i> TAMBAH MENU BARU
            </a>
            <a href="#" class="btn-action btn-secondary">
                <i class="fa fa-list-alt"></i> LIHAT SEMUA PESANAN
            </a>
        </div>

    </div>

</body>
</html>