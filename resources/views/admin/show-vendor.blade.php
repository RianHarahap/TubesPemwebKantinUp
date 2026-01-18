<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Vendor - Admin Dashboard</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
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
        body { font-family: 'Poppins', sans-serif; background-color: #f4f7f6; display: flex; }

        /* Sidebar */
        .sidebar { 
            width: 280px; position: fixed; height: 100vh; background: var(--primary); 
            color: white; padding: 20px; display: flex; flex-direction: column; z-index: 1000;
        }
        .logo-section { text-align: center; margin-bottom: 30px; background: white; padding: 10px; border-radius: 10px; }
        .logo-section img { width: 140px; }
        .menu-item { 
            display: flex; align-items: center; padding: 12px 15px; border-radius: 10px; 
            text-decoration: none; color: #a0aec0; margin-bottom: 5px; transition: 0.3s; 
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
            margin-bottom: 30px; border-bottom: 2px solid #ddd; padding-bottom: 15px; 
        }

        /* Info Cards */
        .info-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .info-card {
            background: white;
            padding: 20px;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
            text-align: center;
        }

        .info-card-icon {
            width: 50px;
            height: 50px;
            background: linear-gradient(135deg, var(--blue) 0%, var(--blue-light) 100%);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 24px;
            margin: 0 auto 15px;
        }

        .info-card-title {
            color: #999;
            font-size: 13px;
            margin-bottom: 8px;
        }

        .info-card-value {
            color: var(--blue);
            font-size: 24px;
            font-weight: bold;
        }

        .vendor-details {
            background: white;
            padding: 25px;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
            margin-bottom: 30px;
        }

        .vendor-details h3 {
            color: var(--blue);
            margin-bottom: 15px;
            font-size: 18px;
        }

        .detail-item {
            display: grid;
            grid-template-columns: 150px 1fr;
            margin-bottom: 15px;
            padding-bottom: 15px;
            border-bottom: 1px solid #f0f0f0;
        }

        .detail-item:last-child {
            border-bottom: none;
        }

        .detail-label {
            font-weight: 600;
            color: #666;
        }

        .detail-value {
            color: #333;
        }

        .status-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
        }

        .status-badge.open {
            background: #d4edda;
            color: #155724;
        }

        .status-badge.closed {
            background: #f8d7da;
            color: #721c24;
        }

        .btn {
            padding: 10px 20px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            font-weight: 600;
            transition: all 0.2s;
        }

        .btn-primary {
            background: var(--blue);
            color: white;
        }

        .btn-primary:hover {
            background: #163157;
        }

        .btn-secondary {
            background: #6c757d;
            color: white;
        }

        .btn-secondary:hover {
            background: #5a6268;
        }

        .btn-danger {
            background: #dc3545;
            color: white;
        }

        .btn-danger:hover {
            background: #c82333;
        }

        .action-buttons {
            display: flex;
            gap: 10px;
            margin-top: 25px;
        }

        .chart-section {
            background: white;
            padding: 25px;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
            margin-bottom: 30px;
        }

        .chart-section h3 {
            color: var(--blue);
            margin-bottom: 15px;
            font-size: 18px;
        }

        .chart-container {
            position: relative;
            height: 300px;
        }

        @media (max-width: 768px) {
            .sidebar { width: 100%; height: auto; position: static; padding: 15px; }
            .main-content { margin-left: 0; padding: 15px; }
            .info-grid { grid-template-columns: repeat(2, 1fr); }
            .action-buttons { flex-direction: column; }
        }
    </style>
</head>
<body>

    <div class="sidebar">
        <div class="logo-section"><img src="https://sso.universitaspertamina.ac.id/images/logo.png" alt="Logo"></div>
        <a href="{{ route('admin.dashboard') }}" class="menu-item"><i class="fa fa-chart-line"></i> Ringkasan Sistem</a>
        <a href="{{ route('admin.kelola-vendor') }}" class="menu-item active"><i class="fa fa-store"></i> Kelola Vendor</a>
        <a href="{{ route('admin.laporan-transaksi') }}" class="menu-item"><i class="fa fa-file-invoice-dollar"></i> Laporan Transaksi</a>

        <form action="{{ route('logout') }}" method="POST" style="margin-top: auto;">
            @csrf
            <button type="submit" class="btn-logout"><i class="fa fa-power-off"></i> Shutdown Session</button>
        </form>
    </div>

    <div class="main-content">
        <div class="header">
            <h2 style="margin:0">Detail Vendor: {{ $vendor->nama_kantin }}</h2>
            <a href="{{ route('admin.kelola-vendor') }}" class="btn btn-secondary">
                <i class="fa fa-arrow-left"></i> Kembali
            </a>
        </div>

        <!-- Info Grid -->
        <div class="info-grid">
            <div class="info-card">
                <div class="info-card-icon">
                    <i class="fa fa-utensils"></i>
                </div>
                <div class="info-card-title">Total Menu</div>
                <div class="info-card-value">{{ $totalMenu }}</div>
            </div>

            <div class="info-card">
                <div class="info-card-icon">
                    <i class="fa fa-shopping-cart"></i>
                </div>
                <div class="info-card-title">Total Pesanan</div>
                <div class="info-card-value">{{ $totalOrders }}</div>
            </div>

            <div class="info-card">
                <div class="info-card-icon">
                    <i class="fa fa-dollar-sign"></i>
                </div>
                <div class="info-card-title">Total Penjualan</div>
                <div class="info-card-value">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</div>
            </div>

            <div class="info-card">
                <div class="info-card-icon">
                    <i class="fa fa-user"></i>
                </div>
                <div class="info-card-title">Penjual</div>
                <div class="info-card-value">{{ $vendor->user ? $vendor->user->name : 'N/A' }}</div>
            </div>
        </div>

        <!-- Vendor Details -->
        <div class="vendor-details">
            <h3><i class="fa fa-info-circle"></i> Informasi Vendor</h3>
            
            <div class="detail-item">
                <div class="detail-label">Nama Kantin</div>
                <div class="detail-value">{{ $vendor->nama_kantin }}</div>
            </div>

            <div class="detail-item">
                <div class="detail-label">Deskripsi</div>
                <div class="detail-value">{{ $vendor->deskripsi }}</div>
            </div>

            <div class="detail-item">
                <div class="detail-label">Email Penjual</div>
                <div class="detail-value">{{ $vendor->user ? $vendor->user->email : 'N/A' }}</div>
            </div>

            <div class="detail-item">
                <div class="detail-label">Status</div>
                <div class="detail-value">
                    <span class="status-badge {{ $vendor->is_open ? 'open' : 'closed' }}">
                        <i class="fa fa-{{ $vendor->is_open ? 'circle-check' : 'circle-xmark' }}"></i>
                        {{ $vendor->is_open ? 'Buka' : 'Tutup' }}
                    </span>
                </div>
            </div>

            <div class="action-buttons">
                <a href="{{ route('admin.kelola-vendor.edit', $vendor) }}" class="btn btn-primary">
                    <i class="fa fa-edit"></i> Edit Vendor
                </a>
                <form action="{{ route('admin.kelola-vendor.toggle-status', $vendor) }}" method="POST" style="display:inline;">
                    @csrf
                    @method('PATCH')
                    <button type="submit" class="btn btn-secondary">
                        <i class="fa fa-toggle-on"></i> 
                        {{ $vendor->is_open ? 'Tutup Kantin' : 'Buka Kantin' }}
                    </button>
                </form>
                <form action="{{ route('admin.kelola-vendor.destroy', $vendor) }}" method="POST" style="display:inline;" onsubmit="return confirm('Yakin ingin menghapus vendor ini?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">
                        <i class="fa fa-trash"></i> Hapus Vendor
                    </button>
                </form>
            </div>
        </div>

        <!-- Chart Section -->
        <div class="chart-section">
            <h3><i class="fa fa-chart-bar"></i> Grafik Penjualan Mingguan</h3>
            <div class="chart-container">
                <canvas id="weeklySalesChart"></canvas>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Weekly Sales Chart
        const weeklySalesCtx = document.getElementById('weeklySalesChart').getContext('2d');
        const weeklySalesChart = new Chart(weeklySalesCtx, {
            type: 'bar',
            data: {
                labels: {!! json_encode($weeklySales->pluck('date')) !!},
                datasets: [{
                    label: 'Penjualan (Rp)',
                    data: {!! json_encode($weeklySales->pluck('total')) !!},
                    backgroundColor: '#1e3c72',
                    borderColor: '#2a5298',
                    borderWidth: 2,
                    borderRadius: 8
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