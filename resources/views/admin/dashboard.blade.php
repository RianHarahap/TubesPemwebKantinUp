<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - KantinUp</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        :root { 
            --primary: #0b132b; 
            --secondary: #1c2541; 
            --highlight: #3a506b;
            --blue: #1e3c72;
            --blue-light: #2a5298;
        }
        
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Poppins', sans-serif; background-color: #f0f5f9; display: flex; }
        
        /* Sidebar */
        .sidebar { 
            width: 280px; position: fixed; height: 100vh; background: var(--primary); 
            color: white; padding: 20px; display: flex; flex-direction: column; z-index: 1000;
        }
        .logo-section { 
            text-align: center; margin-bottom: 30px; background: white; 
            padding: 10px; border-radius: 10px; 
        }
        .logo-section img { width: 140px; }
        .menu-item { 
            display: flex; align-items: center; padding: 12px 15px; 
            border-radius: 10px; text-decoration: none; color: #a0aec0; 
            margin-bottom: 5px; transition: 0.3s; 
        }
        .menu-item:hover, .menu-item.active { background: var(--highlight); color: white; }
        .menu-item i { margin-right: 15px; width: 20px; }
        .btn-logout { 
            background: none; border: none; color: #ff4d4d; cursor: pointer; 
            font-weight: 600; margin-top: auto; padding: 10px; text-align: left; width: 100%;
        }

        /* Main Content */
        .main-content { margin-left: 280px; flex: 1; padding: 30px; }
        .header { 
            display: flex; justify-content: space-between; align-items: center; 
            margin-bottom: 30px; 
        }
        .header h1 { color: #333; font-size: 28px; margin: 0; }

        /* Stats Grid */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .stat-card {
            background: white;
            padding: 25px;
            border-radius: 15px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.08);
            border-left: 5px solid var(--blue);
            transition: all 0.3s;
        }

        .stat-card:hover {
            box-shadow: 0 8px 20px rgba(0,0,0,0.12);
            transform: translateY(-3px);
        }

        .stat-icon {
            width: 60px;
            height: 60px;
            background: linear-gradient(135deg, var(--blue) 0%, var(--blue-light) 100%);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 28px;
            margin-bottom: 15px;
        }

        .stat-label {
            color: #999;
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
            margin-bottom: 8px;
            letter-spacing: 1px;
        }

        .stat-value {
            color: var(--blue);
            font-size: 28px;
            font-weight: 700;
        }

        .stat-change {
            font-size: 12px;
            color: #28a745;
            margin-top: 8px;
        }

        /* Charts Section */
        .section-title {
            color: #333;
            font-size: 20px;
            font-weight: 700;
            margin-top: 40px;
            margin-bottom: 20px;
        }

        .chart-container {
            background: white;
            padding: 25px;
            border-radius: 15px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.08);
            margin-bottom: 30px;
        }

        .chart-wrapper {
            position: relative;
            height: 300px;
        }

        /* Activity Log */
        .activity-container {
            background: white;
            padding: 25px;
            border-radius: 15px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.08);
        }

        .activity-list {
            max-height: 400px;
            overflow-y: auto;
        }

        .activity-item {
            padding: 15px;
            border-bottom: 1px solid #f0f0f0;
            display: flex;
            align-items: flex-start;
            gap: 15px;
            transition: background 0.2s;
        }

        .activity-item:hover {
            background: #f9f9f9;
        }

        .activity-icon {
            width: 45px;
            height: 45px;
            background: linear-gradient(135deg, var(--blue) 0%, var(--blue-light) 100%);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 18px;
            flex-shrink: 0;
        }

        .activity-content {
            flex: 1;
        }

        .activity-message {
            color: #333;
            font-weight: 500;
            margin-bottom: 3px;
        }

        .activity-time {
            color: #999;
            font-size: 12px;
        }

        @media (max-width: 768px) {
            .sidebar { width: 100%; height: auto; position: static; }
            .main-content { margin-left: 0; padding: 15px; }
            .stats-grid { grid-template-columns: 1fr; }
        }
    </style>
</head>
<body>

    <div class="sidebar">
        <div class="logo-section"><img src="https://sso.universitaspertamina.ac.id/images/logo.png" alt="Logo"></div>
        <a href="{{ route('admin.dashboard') }}" class="menu-item active"><i class="fa fa-chart-line"></i> Ringkasan Sistem</a>
        <a href="{{ route('admin.kelola-vendor') }}" class="menu-item"><i class="fa fa-store"></i> Kelola Vendor</a>
        <a href="{{ route('admin.laporan-transaksi') }}" class="menu-item"><i class="fa fa-file-invoice-dollar"></i> Laporan Transaksi</a>

        <form action="{{ route('logout') }}" method="POST" style="margin-top: auto;">
            @csrf
            <button type="submit" class="btn-logout"><i class="fa fa-power-off"></i> Shutdown Session</button>
        </form>
    </div>

    <div class="main-content">
        <div class="header">
            <div>
                <h1>Ringkasan Sistem</h1>
                <p style="color: #999; margin-top: 5px;">Selamat datang di dashboard administrator KantinUp</p>
            </div>
        </div>

        <!-- Stats Grid -->
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fa fa-store"></i>
                </div>
                <div class="stat-label">Vendor Aktif</div>
                <div class="stat-value">{{ $totalVendors }}</div>
                <div class="stat-change">Toko online siap melayani</div>
            </div>

            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fa fa-shopping-cart"></i>
                </div>
                <div class="stat-label">Transaksi Hari Ini</div>
                <div class="stat-value">{{ $totalTransactionsToday }}</div>
                <div class="stat-change">Pesanan masuk hari ini</div>
            </div>

            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fa fa-money-bill"></i>
                </div>
                <div class="stat-label">Revenue Hari Ini</div>
                <div class="stat-value">Rp {{ number_format($totalRevenueToday / 1000, 0, ',', '.') }}K</div>
                <div class="stat-change">Total penjualan selesai</div>
            </div>
        </div>

        <!-- Charts Section -->
        <h3 class="section-title"><i class="fa fa-chart-bar"></i> Grafik Penjualan Mingguan</h3>
        <div class="chart-container">
            <div class="chart-wrapper">
                <canvas id="salesChart"></canvas>
            </div>
        </div>

        <!-- Activity Log -->
        <h3 class="section-title"><i class="fa fa-clock"></i> Log Aktivitas Terbaru</h3>
        <div class="activity-container">
            <div class="activity-list">
                @forelse($recentActivities as $activity)
                    <div class="activity-item">
                        <div class="activity-icon">
                            <i class="fa fa-{{ $activity['type'] == 'login' ? 'user-check' : 'utensils' }}"></i>
                        </div>
                        <div class="activity-content">
                            <div class="activity-message">{{ $activity['message'] }}</div>
                            <div class="activity-time">{{ $activity['time'] }}</div>
                        </div>
                    </div>
                @empty
                    <div class="activity-item">
                        <p style="color: #999; margin: 0;">Belum ada aktivitas</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const ctx = document.getElementById('salesChart').getContext('2d');
        const labels = {!! json_encode($weeklySales->pluck('date')) !!};
        const data = {!! json_encode($weeklySales->pluck('total')) !!};

        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Penjualan (Rp)',
                    data: data,
                    backgroundColor: 'rgba(30, 60, 114, 0.8)',
                    borderColor: 'rgba(30, 60, 114, 1)',
                    borderRadius: 8,
                    borderWidth: 2
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: true,
                        position: 'top'
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: { color: '#f0f0f0' }
                    },
                    x: {
                        grid: { display: false }
                    }
                }
            }
        });
    </script>
</body>
</html>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const ctx = document.getElementById('salesChart').getContext('2d');
        const salesChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: @json($weeklySales->pluck('date')),
                datasets: [{
                    label: 'Penjualan Harian (Rp)',
                    data: @json($weeklySales->pluck('total')),
                    borderColor: 'rgb(75, 192, 192)',
                    tension: 0.1
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return 'Rp ' + value.toLocaleString('id-ID');
                            }
                        }
                    }
                }
            }
        });
    </script>
</body>
</html>