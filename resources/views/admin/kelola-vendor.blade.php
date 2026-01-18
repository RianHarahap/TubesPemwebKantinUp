<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Vendor - Admin Dashboard</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        :root { 
            --primary: #0b132b; 
            --secondary: #1c2541; 
            --highlight: #3a506b;
            --success: #28a745;
            --danger: #dc3545;
            --warning: #ffc107;
            --info: #17a2b8;
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

        /* Vendor Grid */
        .vendor-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 20px;
        }

        .vendor-card-container {
            background: white;
            border-radius: 15px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
            overflow: hidden;
            transition: all 0.3s ease;
            cursor: pointer;
        }

        .vendor-card-container:hover {
            box-shadow: 0 8px 16px rgba(0,0,0,0.15);
            transform: translateY(-5px);
        }

        .vendor-card-header {
            background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);
            padding: 20px;
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .vendor-avatar {
            width: 60px;
            height: 60px;
            border-radius: 12px;
            background: rgba(255,255,255,0.2);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 28px;
            font-weight: bold;
        }

        .vendor-title-section h3 {
            color: white;
            margin: 0 0 5px 0;
            font-size: 18px;
        }

        .vendor-title-section p {
            color: rgba(255,255,255,0.9);
            margin: 0;
            font-size: 12px;
        }

        .vendor-card-body {
            padding: 20px;
        }

        .vendor-description {
            color: #666;
            font-size: 13px;
            margin-bottom: 12px;
            line-height: 1.4;
        }

        .vendor-meta {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
            padding-bottom: 15px;
            border-bottom: 1px solid #eee;
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

        .vendor-stats {
            display: flex;
            justify-content: space-between;
            gap: 10px;
            margin-bottom: 15px;
        }

        .stat-item {
            flex: 1;
            text-align: center;
            padding: 10px;
            background: #f8f9fa;
            border-radius: 8px;
        }

        .stat-number {
            display: block;
            font-size: 20px;
            font-weight: bold;
            color: var(--primary);
        }

        .stat-label {
            display: block;
            font-size: 11px;
            color: #999;
            margin-top: 3px;
        }

        .vendor-actions {
            display: flex;
            gap: 8px;
        }

        .btn {
            padding: 8px 14px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            font-size: 12px;
            font-weight: 600;
            transition: all 0.2s;
            flex: 1;
            justify-content: center;
        }

        .btn-primary {
            background: var(--primary);
            color: white;
        }

        .btn-primary:hover {
            background: #081b2d;
            transform: scale(1.02);
        }

        .btn-secondary {
            background: #6c757d;
            color: white;
        }

        .btn-secondary:hover {
            background: #5a6268;
        }

        .btn-add {
            background: var(--success);
            color: white;
            padding: 10px 20px;
        }

        .btn-add:hover {
            background: #218838;
        }

        /* Modal Styles */
        .modal {
            display: none;
            position: fixed;
            z-index: 2000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0,0,0,0.5);
            animation: fadeIn 0.3s;
        }

        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        .modal-content {
            background-color: #fefefe;
            margin: 50px auto;
            padding: 0;
            border-radius: 15px;
            width: 90%;
            max-width: 900px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.3);
            animation: slideIn 0.3s;
        }

        @keyframes slideIn {
            from { transform: translateY(-50px); opacity: 0; }
            to { transform: translateY(0); opacity: 1; }
        }

        .modal-header {
            padding: 20px;
            background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);
            color: white;
            border-radius: 15px 15px 0 0;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .modal-header h2 {
            margin: 0;
            font-size: 22px;
        }

        .close-btn {
            background: none;
            border: none;
            color: white;
            font-size: 28px;
            cursor: pointer;
            line-height: 1;
        }

        .modal-body {
            padding: 30px;
        }

        .period-toggle {
            display: flex;
            gap: 10px;
            margin-bottom: 25px;
            border-bottom: 2px solid #eee;
            padding-bottom: 15px;
        }

        .period-btn {
            padding: 10px 20px;
            border: none;
            background: #f0f0f0;
            color: #666;
            cursor: pointer;
            border-radius: 8px;
            font-weight: 600;
            transition: all 0.2s;
        }

        .period-btn.active {
            background: var(--primary);
            color: white;
        }

        .period-btn:hover {
            background: var(--highlight);
            color: white;
        }

        .chart-container {
            position: relative;
            height: 300px;
            margin-bottom: 30px;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 15px;
            margin-top: 20px;
        }

        .stat-card {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 10px;
            text-align: center;
        }

        .stat-card-title {
            font-size: 12px;
            color: #999;
            margin-bottom: 8px;
        }

        .stat-card-value {
            font-size: 24px;
            font-weight: bold;
            color: var(--primary);
        }

        @media (max-width: 1200px) {
            .vendor-grid {
                grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            }
        }

        @media (max-width: 768px) {
            .sidebar { width: 100%; height: auto; position: static; padding: 15px; }
            .main-content { margin-left: 0; padding: 15px; }
            .vendor-grid { grid-template-columns: 1fr; }
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
            <h2 style="margin:0">Kelola Vendor</h2>
        </div>

        @if(session('success'))
            <div style="background: #d4edda; color: #155724; padding: 15px; border-radius: 8px; margin-bottom: 20px; border: 1px solid #c3e6cb;">
                {{ session('success') }}
            </div>
        @endif

        <div class="vendor-grid">
            @foreach($vendors as $vendor)
            <div class="vendor-card-container" onclick="openVendorModal({{ $vendor->id }}, '{{ $vendor->nama_kantin }}', '{{ $vendor->deskripsi }}', {{ $vendor->is_open ? 'true' : 'false' }})">
                <div class="vendor-card-header">
                    <div class="vendor-avatar">
                        <i class="fa fa-utensils"></i>
                    </div>
                    <div class="vendor-title-section">
                        <h3>{{ $vendor->nama_kantin }}</h3>
                        <p>Penjual: {{ $vendor->user ? $vendor->user->name : 'N/A' }}</p>
                    </div>
                </div>

                <div class="vendor-card-body">
                    <div class="vendor-description">
                        {{ Str::limit($vendor->deskripsi, 80) }}
                    </div>

                    <div class="vendor-meta">
                        <span class="status-badge {{ $vendor->is_open ? 'open' : 'closed' }}">
                            <i class="fa fa-{{ $vendor->is_open ? 'circle-check' : 'circle-xmark' }}"></i>
                            {{ $vendor->is_open ? 'Buka' : 'Tutup' }}
                        </span>
                    </div>

                    <div class="vendor-stats">
                        <div class="stat-item">
                            <span class="stat-number">{{ $vendor->menus()->count() }}</span>
                            <span class="stat-label">Menu</span>
                        </div>
                        <div class="stat-item">
                            <span class="stat-number">{{ $vendor->orders()->count() }}</span>
                            <span class="stat-label">Pesanan</span>
                        </div>
                        <div class="stat-item">
                            <span class="stat-number">Rp {{ number_format($vendor->orders()->where('status', 'selesai')->sum('total_harga') / 1000, 0, ',', '.') }}K</span>
                            <span class="stat-label">Revenue</span>
                        </div>
                    </div>

                    <div class="vendor-actions">
                        <a href="{{ route('admin.kelola-vendor.edit', $vendor) }}" class="btn btn-primary" onclick="event.stopPropagation();">
                            <i class="fa fa-edit"></i> Edit
                        </a>
                        <a href="{{ route('admin.kelola-vendor.show', $vendor) }}" class="btn btn-secondary" onclick="event.stopPropagation();">
                            <i class="fa fa-eye"></i> Detail
                        </a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <!-- Tombol Tambah Vendor -->
        <div style="margin-top: 30px; text-align: center;">
            <a href="{{ route('admin.kelola-vendor.create') }}" class="btn btn-add" style="padding: 10px 20px; font-size: 14px; width: auto; display: inline-flex;">
                <i class="fa fa-plus"></i> Tambah Vendor Baru
            </a>
        </div>
    </div>

    <!-- Modal untuk Grafik Penjualan -->
    <div id="vendorModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2 id="modalVendorName">Grafik Penjualan Vendor</h2>
                <button class="close-btn" onclick="closeVendorModal()">&times;</button>
            </div>
            <div class="modal-body">
                <div class="period-toggle">
                    <button class="period-btn active" onclick="changePeriod('minggu')">Per Minggu</button>
                    <button class="period-btn" onclick="changePeriod('bulan')">Per Bulan</button>
                    <button class="period-btn" onclick="changePeriod('tahun')">Per Tahun</button>
                </div>

                <div class="chart-container">
                    <canvas id="vendorChart"></canvas>
                </div>

                <div class="stats-grid">
                    <div class="stat-card">
                        <div class="stat-card-title">Total Pesanan</div>
                        <div class="stat-card-value" id="totalOrders">0</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-card-title">Total Penjualan</div>
                        <div class="stat-card-value" id="totalSales">Rp 0</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-card-title">Rata-rata per Hari</div>
                        <div class="stat-card-value" id="averageSales">Rp 0</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        let currentVendorId = null;
        let currentPeriod = 'minggu';
        let vendorChart = null;

        function openVendorModal(vendorId, vendorName, vendorDesc, isOpen) {
            currentVendorId = vendorId;
            document.getElementById('modalVendorName').textContent = vendorName;
            document.getElementById('vendorModal').style.display = 'block';
            
            // Load data untuk grafik
            loadChartData('minggu');
        }

        function closeVendorModal() {
            document.getElementById('vendorModal').style.display = 'none';
            if (vendorChart) {
                vendorChart.destroy();
            }
        }

        function changePeriod(period) {
            currentPeriod = period;
            
            // Update active button
            document.querySelectorAll('.period-btn').forEach(btn => {
                btn.classList.remove('active');
            });
            event.target.classList.add('active');
            
            loadChartData(period);
        }

        function loadChartData(period) {
            fetch(`/api/vendor/${currentVendorId}/stats?period=${period}`, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.json())
            .then(data => {
                updateChart(data);
                updateStats(data);
            })
            .catch(error => console.error('Error:', error));
        }

        function updateChart(data) {
            const ctx = document.getElementById('vendorChart').getContext('2d');
            
            if (vendorChart) {
                vendorChart.destroy();
            }

            vendorChart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: data.labels,
                    datasets: [
                        {
                            label: 'Penjualan (Rp)',
                            data: data.sales,
                            backgroundColor: '#667eea',
                            borderColor: '#667eea',
                            borderRadius: 8,
                            tension: 0.4
                        },
                        {
                            label: 'Pesanan (Unit)',
                            data: data.orders,
                            backgroundColor: '#764ba2',
                            borderColor: '#764ba2',
                            borderRadius: 8,
                            tension: 0.4
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: true,
                            position: 'top',
                            labels: {
                                usePointStyle: true,
                                padding: 15
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: {
                                color: '#f0f0f0'
                            }
                        },
                        x: {
                            grid: {
                                display: false
                            }
                        }
                    }
                }
            });
        }

        function updateStats(data) {
            document.getElementById('totalOrders').textContent = data.total_orders;
            document.getElementById('totalSales').textContent = 'Rp ' + new Intl.NumberFormat('id-ID').format(data.total_sales);
            document.getElementById('averageSales').textContent = 'Rp ' + new Intl.NumberFormat('id-ID').format(data.average_sales);
        }

        // Close modal when clicking outside
        window.onclick = function(event) {
            const modal = document.getElementById('vendorModal');
            if (event.target == modal) {
                modal.style.display = 'none';
            }
        }
    </script>
</body>
</html>