<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Transaksi - Admin Dashboard</title>
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
            grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .stat-card {
            background: white;
            padding: 20px;
            border-radius: 12px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.08);
            border-left: 4px solid var(--blue);
            text-align: center;
        }

        .stat-label {
            color: #999;
            font-size: 11px;
            font-weight: 600;
            text-transform: uppercase;
            margin-bottom: 8px;
        }

        .stat-value {
            color: var(--blue);
            font-size: 24px;
            font-weight: 700;
        }

        /* Table Container */
        .table-container {
            background: white;
            padding: 25px;
            border-radius: 15px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.08);
            margin-bottom: 30px;
        }

        .table-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 2px solid #f0f0f0;
        }

        .table-header h3 {
            color: #333;
            font-size: 18px;
            margin: 0;
        }

        .search-box {
            padding: 8px 15px;
            border: 1px solid #ddd;
            border-radius: 8px;
            width: 250px;
            font-family: 'Poppins', sans-serif;
        }

        .table {
            width: 100%;
            border-collapse: collapse;
        }

        .table th {
            background: linear-gradient(135deg, var(--blue) 0%, var(--blue-light) 100%);
            color: white;
            padding: 15px;
            text-align: left;
            font-weight: 600;
            border: none;
        }

        .table td {
            padding: 15px;
            border-bottom: 1px solid #f0f0f0;
            color: #333;
        }

        .table tbody tr:hover {
            background: #f9f9f9;
        }

        .table tbody tr:last-child td {
            border-bottom: none;
        }

        /* Status Badge */
        .status-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 600;
            text-transform: uppercase;
        }

        .status-selesai {
            background: #d4edda;
            color: #155724;
        }

        .status-menunggu {
            background: #fff3cd;
            color: #856404;
        }

        .status-dimasak {
            background: #cce5ff;
            color: #004085;
        }

        .status-siap {
            background: #d1ecf1;
            color: #0c5460;
        }

        /* Chart Section */
        .chart-container {
            background: white;
            padding: 25px;
            border-radius: 15px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.08);
            margin-bottom: 30px;
        }

        .chart-container h3 {
            color: #333;
            font-size: 18px;
            margin-bottom: 20px;
        }

        .chart-wrapper {
            position: relative;
            height: 300px;
        }

        /* Pagination */
        .pagination {
            display: flex;
            justify-content: center;
            gap: 8px;
            margin-top: 20px;
        }

        .pagination a, .pagination span {
            padding: 8px 12px;
            border-radius: 6px;
            border: 1px solid #ddd;
            text-decoration: none;
            color: var(--blue);
        }

        .pagination .active {
            background: var(--blue);
            color: white;
            border-color: var(--blue);
        }

        .pagination a:hover {
            background: #f0f0f0;
        }

        /* Modal Detail Transaksi */
        .modal-detail {
            display: none;
            position: fixed;
            z-index: 2000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow-y: auto; /* Enable scroll if content is long */
            background-color: rgba(0,0,0,0.5);
            animation: fadeIn 0.3s;
        }

        .modal-detail-content {
            background-color: #fefefe;
            margin: 50px auto;
            padding: 0;
            border-radius: 15px;
            width: 90%;
            max-width: 700px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.3);
            animation: slideIn 0.3s;
        }

        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        @keyframes slideIn {
            from { transform: translateY(-50px); opacity: 0; }
            to { transform: translateY(0); opacity: 1; }
        }

        .modal-header-detail {
            padding: 20px;
            background: linear-gradient(135deg, var(--blue) 0%, var(--blue-light) 100%);
            color: white;
            border-radius: 15px 15px 0 0;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .modal-header-detail h2 {
            margin: 0;
            font-size: 22px;
        }

        .close-detail {
            background: none;
            border: none;
            color: white;
            font-size: 28px;
            cursor: pointer;
            line-height: 1;
        }

        .modal-body-detail {
            padding: 30px;
        }

        .detail-row {
            display: grid;
            grid-template-columns: 150px 1fr;
            margin-bottom: 15px;
            padding-bottom: 15px;
            border-bottom: 1px solid #f0f0f0;
        }

        .detail-row:last-child {
            border-bottom: none;
        }

        .detail-label {
            font-weight: 600;
            color: #666;
            font-size: 12px;
            text-transform: uppercase;
        }

        .detail-value {
            color: #333;
            font-weight: 500;
        }

        .detail-section {
            margin-bottom: 25px;
            padding-bottom: 20px;
            border-bottom: 2px solid #f0f0f0;
        }

        .detail-section:last-child {
            border-bottom: none;
        }

        .detail-section-title {
            color: var(--blue);
            font-size: 14px;
            font-weight: 700;
            text-transform: uppercase;
            margin-bottom: 15px;
            letter-spacing: 1px;
        }

        .menu-item-detail {
            background: #f9f9f9;
            padding: 12px;
            border-radius: 8px;
            margin-bottom: 10px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .menu-item-name {
            font-weight: 600;
            color: #333;
        }

        .menu-item-qty {
            background: var(--blue);
            color: white;
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 12px;
            font-weight: 600;
        }

        .menu-deleted {
            color: #999;
            font-style: italic;
            background: #f5f5f5;
            padding: 8px 12px;
            border-radius: 6px;
            border-left: 3px solid #e74c3c;
        }

        .menu-deleted-icon {
            color: #e74c3c;
            margin-right: 8px;
        }

        .price-breakdown {
            background: #f0f5f9;
            padding: 15px;
            border-radius: 8px;
        }

        .price-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 8px;
            font-size: 14px;
        }

        .price-row.total {
            border-top: 2px solid var(--blue);
            padding-top: 8px;
            font-weight: 700;
            color: var(--blue);
            font-size: 16px;
        }

        @media (max-width: 768px) {
            .sidebar { width: 100%; height: auto; position: static; }
            .main-content { margin-left: 0; padding: 15px; }
            .stats-grid { grid-template-columns: repeat(2, 1fr); }
            .table-header { flex-direction: column; gap: 15px; }
            .search-box { width: 100%; }
            .table { font-size: 12px; }
            .table th, .table td { padding: 10px; }
            .modal-detail-content { width: 95%; margin: 30px auto; }
            .detail-row { grid-template-columns: 1fr; }
        }
    </style>
</head>
<body>

    <div class="sidebar">
        <div class="logo-section"><img src="https://sso.universitaspertamina.ac.id/images/logo.png" alt="Logo"></div>
        <a href="{{ route('admin.dashboard') }}" class="menu-item"><i class="fa fa-chart-line"></i> Ringkasan Sistem</a>
        <a href="{{ route('admin.kelola-vendor') }}" class="menu-item"><i class="fa fa-store"></i> Kelola Vendor</a>
        <a href="{{ route('admin.laporan-transaksi') }}" class="menu-item active"><i class="fa fa-file-invoice-dollar"></i> Laporan Transaksi</a>

        <form action="{{ route('logout') }}" method="POST" style="margin-top: auto;">
            @csrf
            <button type="submit" class="btn-logout"><i class="fa fa-power-off"></i> Shutdown Session</button>
        </form>
    </div>

    <div class="main-content">
        <div class="header">
            <div>
                <h1>Laporan Transaksi</h1>
                <p style="color: #999; margin-top: 5px;">Pantau semua transaksi penjualan di platform</p>
            </div>
        </div>

        <!-- Chart Section -->
        <div class="chart-container">
            <h3><i class="fa fa-chart-line"></i> Grafik Penjualan Bulanan</h3>
            <div class="chart-wrapper">
                <canvas id="monthlySalesChart"></canvas>
            </div>
        </div>

        <!-- Stats -->
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-label">Total Transaksi</div>
                <div class="stat-value">{{ $transactions->count() }}</div>
            </div>
            <div class="stat-card">
                <div class="stat-label">Total Revenue</div>
                <div class="stat-value">Rp {{ number_format($transactions->sum('total_harga') / 1000, 0, ',', '.') }}K</div>
            </div>
            <div class="stat-card">
                <div class="stat-label">Rata-rata Transaksi</div>
                <div class="stat-value">Rp {{ $transactions->count() > 0 ? number_format($transactions->sum('total_harga') / $transactions->count() / 1000, 0, ',', '.') : '0' }}K</div>
            </div>
        </div>

        <!-- Table Container -->
        <div class="table-container">
            <div class="table-header">
                <h3>Daftar Transaksi</h3>
                <input type="text" id="tableSearch" class="search-box" placeholder="Cari vendor atau pembeli...">
            </div>

            <div style="overflow-x: auto;">
                <table class="table">
                    <thead>
                        <tr>
                            <th width="15%">Tanggal</th>
                            <th width="20%">Pembeli</th>
                            <th width="20%">Vendor</th>
                            <th width="15%">Total</th>
                            <th width="15%">Status</th>
                            <th width="15%">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($transactions as $transaction)
                            <tr>
                                <td>
                                    <small>{{ $transaction->created_at->format('d M Y H:i') }}</small>
                                </td>
                                <td>
                                    <div style="font-weight: 500;">{{ $transaction->user->name ?? 'N/A' }}</div>
                                    <small style="color: #999;">{{ $transaction->user->email ?? 'N/A' }}</small>
                                </td>
                                <td>
                                    <div style="font-weight: 500;">{{ $transaction->vendor->nama_kantin ?? 'N/A' }}</div>
                                </td>
                                <td>
                                    <strong style="color: var(--blue);">Rp {{ number_format($transaction->total_harga, 0, ',', '.') }}</strong>
                                </td>
                                <td>
                                    <span class="status-badge status-{{ $transaction->status }}">
                                        <i class="fa fa-{{ $transaction->status == 'selesai' ? 'check-circle' : ($transaction->status == 'menunggu' ? 'clock' : ($transaction->status == 'dimasak' ? 'fire' : 'check')) }}"></i>
                                        {{ ucfirst($transaction->status) }}
                                    </span>
                                </td>
                                <td>
                                    <a href="#" onclick="openDetailModal({{ $transaction->id }}, '{{ $transaction->user ? $transaction->user->name : 'N/A' }}', '{{ $transaction->user ? $transaction->user->email : 'N/A' }}', '{{ $transaction->vendor ? $transaction->vendor->nama_kantin : 'N/A' }}', '{{ $transaction->menu_name ?? ($transaction->menu ? $transaction->menu->nama_makanan : 'Menu Tidak Diketahui') }}', {{ $transaction->jumlah ?? 1 }}, {{ $transaction->total_harga }}, '{{ $transaction->status }}', '{{ $transaction->created_at->format('d M Y H:i') }}')" style="color: var(--blue); text-decoration: none; font-weight: 600;">
                                        <i class="fa fa-eye"></i> Detail
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" style="text-align: center; padding: 40px; color: #999;">
                                    <i class="fa fa-inbox" style="font-size: 32px; margin-bottom: 10px;"></i>
                                    <p>Belum ada transaksi</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
        </div>
    </div>

    <!-- Modal Detail Transaksi -->
    <div id="detailModal" class="modal-detail">
        <div class="modal-detail-content">
            <div class="modal-header-detail">
                <h2>Detail Transaksi</h2>
                <button class="close-detail" onclick="closeDetailModal()">&times;</button>
            </div>
            <div class="modal-body-detail">
                <!-- Detail Pembeli -->
                <div class="detail-section">
                    <div class="detail-section-title"><i class="fa fa-user"></i> Detail Pembeli</div>
                    <div class="detail-row">
                        <div class="detail-label">Nama</div>
                        <div class="detail-value" id="buyerName">-</div>
                    </div>
                    <div class="detail-row">
                        <div class="detail-label">Email</div>
                        <div class="detail-value" id="buyerEmail">-</div>
                    </div>
                </div>

                <!-- Detail Vendor -->
                <div class="detail-section">
                    <div class="detail-section-title"><i class="fa fa-store"></i> Detail Vendor</div>
                    <div class="detail-row">
                        <div class="detail-label">Nama Kantin</div>
                        <div class="detail-value" id="vendorName">-</div>
                    </div>
                </div>

                <!-- Menu yang Dipesan -->
                <div class="detail-section">
                    <div class="detail-section-title"><i class="fa fa-utensils"></i> Menu yang Dipesan</div>
                    <div class="menu-item-detail">
                        <div style="flex: 1;">
                            <div class="menu-item-name" id="menuName">-</div>
                        </div>
                        <div class="menu-item-qty" id="menuQty">-</div>
                    </div>
                </div>

                <!-- Breakdown Harga -->
                <div class="detail-section">
                    <div class="detail-section-title"><i class="fa fa-receipt"></i> Rincian Harga</div>
                    <div class="price-breakdown">
                        <div class="price-row">
                            <span>Harga</span>
                            <span id="totalPrice">Rp 0</span>
                        </div>
                        <div class="price-row total">
                            <span>Total</span>
                            <span id="totalAmount">Rp 0</span>
                        </div>
                    </div>
                </div>

                <!-- Status & Waktu -->
                <div class="detail-section">
                    <div class="detail-section-title"><i class="fa fa-info-circle"></i> Status & Waktu</div>
                    <div class="detail-row">
                        <div class="detail-label">Status</div>
                        <div class="detail-value">
                            <span class="status-badge" id="statusBadge">-</span>
                        </div>
                    </div>
                    <div class="detail-row">
                        <div class="detail-label">Tanggal Pesanan</div>
                        <div class="detail-value" id="orderDate">-</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const ctx = document.getElementById('monthlySalesChart').getContext('2d');
        
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: {!! json_encode($monthlySales->map(function($item) { return $item->month . '/' . substr($item->year, -2); })) !!},
                datasets: [{
                    label: 'Penjualan Bulanan (Rp)',
                    data: {!! json_encode($monthlySales->pluck('total')) !!},
                    backgroundColor: 'rgba(54, 162, 235, 0.6)',
                    borderColor: 'rgba(54, 162, 235, 1)',
                    borderWidth: 1
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
                        ticks: {
                            callback: function(value) {
                                return 'Rp ' + new Intl.NumberFormat('id-ID').format(value);
                            }
                        },
                        grid: { color: '#f0f0f0' }
                    },
                    x: {
                        grid: { display: false }
                    }
                }
            }
        });

        // Modal Detail Transaksi Functions
        function openDetailModal(id, buyerName, buyerEmail, vendorName, menuName, qty, totalPrice, status, orderDate) {
            document.getElementById('buyerName').textContent = buyerName;
            document.getElementById('buyerEmail').textContent = buyerEmail;
            document.getElementById('vendorName').textContent = vendorName;
            
            // Display menu name from order record
            const menuNameEl = document.getElementById('menuName');
            menuNameEl.textContent = menuName;
            menuNameEl.classList.remove('menu-deleted');
            
            document.getElementById('menuQty').textContent = qty + 'x';
            document.getElementById('totalPrice').textContent = 'Rp ' + new Intl.NumberFormat('id-ID').format(totalPrice);
            document.getElementById('totalAmount').textContent = 'Rp ' + new Intl.NumberFormat('id-ID').format(totalPrice);
            
            // Set status badge color
            const statusBadgeEl = document.getElementById('statusBadge');
            statusBadgeEl.textContent = ucfirstStatus(status);
            statusBadgeEl.className = 'status-badge status-' + status;
            
            document.getElementById('orderDate').textContent = orderDate;
            
            // Prevent body scroll
            document.body.style.overflow = 'hidden';
            
            document.getElementById('detailModal').style.display = 'block';
        }

        function closeDetailModal() {
            document.getElementById('detailModal').style.display = 'none';
            // Restore body scroll
            document.body.style.overflow = 'auto';
        }

        function ucfirstStatus(str) {
            return str.charAt(0).toUpperCase() + str.slice(1);
        }

        // Close modal when clicking outside
        window.onclick = function(event) {
            const modal = document.getElementById('detailModal');
            if (event.target == modal) {
                closeDetailModal();
            }
        }

        // Simple Client-Side Search
        document.getElementById('tableSearch').addEventListener('keyup', function() {
            let filter = this.value.toLowerCase();
            let rows = document.querySelectorAll('.table tbody tr');

            rows.forEach(row => {
                let text = row.innerText.toLowerCase();
                if (text.includes(filter)) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        });
    </script>
</body>
</html>