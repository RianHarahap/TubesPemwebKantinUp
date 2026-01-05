<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Dashboard - Kantin UP</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            margin: 0;
            background-color: #f4f7f6;
        }
        /* Navbar */
        .navbar {
            background-color: #ffffff;
            padding: 15px 50px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
        .navbar img { height: 40px; }
        .user-info { display: flex; align-items: center; gap: 15px; }
        .btn-logout {
            background-color: #dc3545;
            color: white;
            padding: 8px 15px;
            text-decoration: none;
            border-radius: 4px;
            font-size: 14px;
            font-weight: bold;
            border: none;
            cursor: pointer;
        }

        /* Content */
        .container { padding: 40px 50px; }
        .welcome-card {
            background: linear-gradient(135deg, #007bff, #0056b3);
            color: white;
            padding: 30px;
            border-radius: 10px;
            margin-bottom: 30px;
        }
        .grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
        }
        .card {
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            text-align: center;
        }
        .card i { font-size: 40px; color: #007bff; margin-bottom: 15px; }
    </style>
</head>
<body>

    <nav class="navbar">
        <img src="https://sso.universitaspertamina.ac.id/images/logo.png" alt="Logo UP">
        <div class="user-info">
            <span>Halo, <strong>{{ Auth::user()->name }}</strong></span>
            <!-- Form Logout -->
            <form action="{{ route('logout') }}" method="POST" style="display: inline;">
                @csrf
                <button type="submit" class="btn-logout">LOGOUT</button>
            </form>
        </div>
    </nav>

    <div class="container">
        <div class="welcome-card">
            <h1>Selamat Datang di Kantin UP!</h1>
            <p>Hari ini mau makan apa? Jelajahi menu lezat dari penjual favoritmu di Universitas Pertamina.</p>
        </div>

        <div class="grid">
            <div class="card">
                <i class="fa fa-utensils"></i>
                <h3>Daftar Menu</h3>
                <p>Lihat semua makanan dan minuman yang tersedia hari ini.</p>
            </div>
            <div class="card">
                <i class="fa fa-history"></i>
                <h3>Riwayat Pesanan</h3>
                <p>Cek status pesanan atau lihat apa yang kamu beli sebelumnya.</p>
            </div>
            <div class="card">
                <i class="fa fa-user-circle"></i>
                <h3>Profil Saya</h3>
                <p>Update informasi akun dan pengaturan keamanan kamu.</p>
            </div>
        </div>
    </div>

</body>
</html>