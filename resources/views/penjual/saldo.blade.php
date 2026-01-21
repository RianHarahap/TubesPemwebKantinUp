@extends('penjual.dashboard')

@section('content')
    <style>
        .saldo-header { margin-bottom: 40px; }
        .saldo-title { font-size: 28px; font-weight: 700; color: #0047ba; margin-bottom: 30px; }
        
        /* Kartu Pendapatan Utama */
        .pendapatan-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); gap: 20px; margin-bottom: 40px; }
        .pendapatan-card { background: white; padding: 25px; border-radius: 12px; box-shadow: 0 2px 8px rgba(0,0,0,0.08); border-left: 4px solid #0047ba; transition: 0.3s; }
        .pendapatan-card:hover { transform: translateY(-4px); box-shadow: 0 4px 12px rgba(0,0,0,0.12); }
        .pendapatan-icon { font-size: 28px; margin-bottom: 12px; color: #0047ba; }
        .pendapatan-amount { font-size: 28px; font-weight: 700; color: #0047ba; margin-bottom: 5px; }
        .pendapatan-label { font-size: 12px; color: #888; text-transform: uppercase; font-weight: 600; letter-spacing: 0.5px; }
        
        .pendapatan-card.hari { border-left-color: #00a1e4; }
        .pendapatan-card.hari .pendapatan-amount { color: #00a1e4; }
        .pendapatan-card.hari .pendapatan-icon { color: #00a1e4; }
        
        .pendapatan-card.bulan { border-left-color: #f39c12; }
        .pendapatan-card.bulan .pendapatan-amount { color: #f39c12; }
        .pendapatan-card.bulan .pendapatan-icon { color: #f39c12; }
        
        .pendapatan-card.total { border-left-color: #27ae60; }
        .pendapatan-card.total .pendapatan-amount { color: #27ae60; }
        .pendapatan-card.total .pendapatan-icon { color: #27ae60; }
        
        .pendapatan-card.transaksi { border-left-color: #e74c3c; }
        .pendapatan-card.transaksi .pendapatan-amount { color: #e74c3c; }
        .pendapatan-card.transaksi .pendapatan-icon { color: #e74c3c; }
        
        /* Riwayat Pesanan */
        .section-title { font-size: 18px; font-weight: 700; color: #0047ba; margin-bottom: 20px; margin-top: 40px; }
        .riwayat-container { background: white; border-radius: 12px; box-shadow: 0 2px 8px rgba(0,0,0,0.08); overflow: hidden; }
        .riwayat-table { width: 100%; border-collapse: collapse; }
        .riwayat-table thead { background: linear-gradient(to right, #0047ba, #00a1e4); color: white; }
        .riwayat-table th { padding: 16px; text-align: left; font-weight: 600; font-size: 13px; }
        .riwayat-table td { padding: 14px 16px; border-bottom: 1px solid #f0f0f0; }
        .riwayat-table tbody tr:hover { background: #f8f9fa; }
        
        .order-no { font-weight: 600; color: #0047ba; }
        .menu-name { color: #333; font-weight: 500; }
        .customer-name { color: #666; font-size: 13px; }
        .price { font-weight: 600; color: #27ae60; }
        .payment-method { display: inline-block; padding: 4px 10px; border-radius: 4px; font-size: 11px; font-weight: 600; background: #cce5ff; color: #004085; }
        .status-selesai { display: inline-block; padding: 4px 12px; border-radius: 20px; font-size: 11px; font-weight: 600; background: #d1ecf1; color: #0c5460; }
        
        .transaction-time { color: #888; font-size: 12px; }
        .detail-btn { display: inline-block; padding: 6px 12px; background: #0047ba; color: white; border-radius: 6px; text-decoration: none; font-size: 12px; font-weight: 600; transition: 0.3s; cursor: pointer; border: none; }
        .detail-btn:hover { background: #00a1e4; }
        
        .empty-state { text-align: center; padding: 40px; color: #999; }
        .empty-state i { font-size: 48px; margin-bottom: 15px; opacity: 0.5; display: block; }
        
        /* Pagination */
        .pagination { display: flex; justify-content: center; gap: 5px; margin-top: 20px; padding: 20px 0 0 0; }
        .pagination a, .pagination span { padding: 8px 12px; border-radius: 4px; border: 1px solid #ddd; text-decoration: none; color: #0047ba; font-weight: 600; transition: 0.3s; }
        .pagination a:hover { background: #0047ba; color: white; }
        .pagination .active span { background: #0047ba; color: white; border-color: #0047ba; }
        
        /* Modal untuk detail resi */
        .modal { display: none; position: fixed; z-index: 1000; left: 0; top: 0; width: 100%; height: 100%; background-color: rgba(0,0,0,0.4); }
        .modal-content { background-color: white; margin: 5% auto; padding: 30px; border-radius: 12px; width: 90%; max-width: 600px; box-shadow: 0 4px 20px rgba(0,0,0,0.15); }
        .modal-header { font-size: 20px; font-weight: 700; color: #0047ba; margin-bottom: 20px; display: flex; justify-content: space-between; align-items: center; }
        .close-btn { background: none; border: none; font-size: 24px; cursor: pointer; color: #999; }
        .close-btn:hover { color: #333; }
        
        .resi-content { border: 2px dashed #0047ba; padding: 20px; border-radius: 8px; background: #f8faff; }
        .resi-section { margin-bottom: 20px; }
        .resi-label { font-size: 11px; color: #888; text-transform: uppercase; font-weight: 600; letter-spacing: 0.5px; }
        .resi-value { font-size: 14px; color: #333; font-weight: 600; margin-top: 5px; }
        
        .btn-print { background: #0047ba; color: white; padding: 10px 20px; border: none; border-radius: 6px; cursor: pointer; font-weight: 600; margin-top: 20px; }
        .btn-print:hover { background: #00a1e4; }
        
        @media print {
            body * { display: none; }
            .modal-content, .modal-content * { display: block; }
            .close-btn, .btn-print { display: none; }
        }
    </style>

    <div class="saldo-header">
        <div class="saldo-title">
            <i class="fa fa-wallet" style="margin-right: 12px;"></i>Saldo & Riwayat Pesanan
        </div>
    </div>

    <!-- Kartu Pendapatan -->
    <div class="pendapatan-grid">
        <div class="pendapatan-card hari">
            <div class="pendapatan-icon"><i class="fa fa-sun"></i></div>
            <div class="pendapatan-amount">Rp {{ number_format($pendapatanHariIni, 0, ',', '.') }}</div>
            <div class="pendapatan-label">Pendapatan Hari Ini</div>
        </div>

        <div class="pendapatan-card bulan">
            <div class="pendapatan-icon"><i class="fa fa-calendar"></i></div>
            <div class="pendapatan-amount">Rp {{ number_format($pendapatanBulanIni, 0, ',', '.') }}</div>
            <div class="pendapatan-label">Pendapatan Bulan Ini</div>
        </div>

        <div class="pendapatan-card total">
            <div class="pendapatan-icon"><i class="fa fa-money-bill-wave"></i></div>
            <div class="pendapatan-amount">Rp {{ number_format($totalPendapatan, 0, ',', '.') }}</div>
            <div class="pendapatan-label">Total Pendapatan</div>
        </div>

        <div class="pendapatan-card transaksi">
            <div class="pendapatan-icon"><i class="fa fa-receipt"></i></div>
            <div class="pendapatan-amount">{{ $totalTransaksi }}</div>
            <div class="pendapatan-label">Total Transaksi</div>
        </div>
    </div>

    <!-- Riwayat Pesanan Selesai -->
    <h3 class="section-title"><i class="fa fa-history" style="margin-right: 10px;"></i>Riwayat Pesanan Selesai</h3>
    <div class="riwayat-container">
        @if($riwayatPesanan->count() > 0)
            <table class="riwayat-table">
                <thead>
                    <tr>
                        <th>No. Antrean</th>
                        <th>Menu</th>
                        <th>Pembeli</th>
                        <th>Total Harga</th>
                        <th>Waktu Selesai</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($riwayatPesanan as $order)
                        <tr>
                            <td><span class="order-no">#{{ $order->nomor_antrean }}</span></td>
                            <td>
                                <div class="menu-name">{{ $order->menu->nama_makanan ?? 'Menu Tidak Ada' }}</div>
                                <div class="customer-name">Qty: {{ $order->jumlah }}</div>
                            </td>
                            <td class="customer-name">{{ $order->user->name ?? 'Guest' }}</td>
                            <td><span class="price">Rp {{ number_format($order->total_harga, 0, ',', '.') }}</span></td>
                            <td>
                                <div class="transaction-time">{{ $order->updated_at->format('d M Y') }}</div>
                                <div class="transaction-time">{{ $order->updated_at->format('H:i') }} WIB</div>
                            </td>
                            <td>
                                <button class="detail-btn" onclick="showDetail({{ $order->id }}, '{{ $order->nomor_antrean }}', '{{ $order->menu->nama_makanan ?? 'Menu' }}', '{{ $order->user->name ?? 'Guest' }}', {{ $order->total_harga }}, '{{ $order->updated_at->format('d M Y H:i') }}')">
                                    <i class="fa fa-receipt"></i> Lihat Resi
                                </button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <div class="empty-state">
                <i class="fa fa-inbox fa-2x"></i>
                <p>Belum ada pesanan yang selesai</p>
            </div>
        @endif
    </div>

    <!-- Modal Resi Detail -->
    <div id="resiModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <span><i class="fa fa-receipt"></i> Resi Transaksi</span>
                <button class="close-btn" onclick="closeResi()">&times;</button>
            </div>
            
            <div class="resi-content" id="resiContent">
                <!-- Konten resi akan diisi oleh JavaScript -->
            </div>
            
            <button class="btn-print" onclick="window.print()">
                <i class="fa fa-print"></i> Cetak Resi
            </button>
        </div>
    </div>

    <script>
        function showDetail(orderId, nomorAntrean, menuName, buyerName, totalPrice, waktuSelesai) {
            const resiContent = `
                <div class="resi-section">
                    <div class="resi-label">Nomor Transaksi</div>
                    <div class="resi-value">#${nomorAntrean}</div>
                </div>
                
                <div class="resi-section">
                    <div class="resi-label">Nama Menu</div>
                    <div class="resi-value">${menuName}</div>
                </div>
                
                <div class="resi-section">
                    <div class="resi-label">Nama Pembeli</div>
                    <div class="resi-value">${buyerName}</div>
                </div>
                
                <div class="resi-section">
                    <div class="resi-label">Total Harga</div>
                    <div class="resi-value">Rp ${new Intl.NumberFormat('id-ID').format(totalPrice)}</div>
                </div>
                
                <div class="resi-section">
                    <div class="resi-label">Waktu Transaksi Selesai</div>
                    <div class="resi-value">${waktuSelesai} WIB</div>
                </div>
                
                <div class="resi-section" style="border-top: 2px dashed #0047ba; padding-top: 15px; margin-top: 15px;">
                    <div class="resi-label">Metode Pembayaran</div>
                    <div class="resi-value" style="display: inline-block; padding: 6px 12px; border-radius: 4px; background: #cce5ff; color: #004085; font-weight: 600;">
                        TUNAI / Transfer
                    </div>
                </div>
                
                <div style="margin-top: 20px; padding-top: 15px; border-top: 2px dashed #0047ba; text-align: center; color: #888; font-size: 11px;">
                    <p>Terima kasih atas kepercayaan Anda</p>
                    <p style="margin: 5px 0;">Kantin Universitas Pertamina</p>
                </div>
            `;
            
            document.getElementById('resiContent').innerHTML = resiContent;
            document.getElementById('resiModal').style.display = 'block';
        }

        function closeResi() {
            document.getElementById('resiModal').style.display = 'none';
        }

        window.onclick = function(event) {
            const modal = document.getElementById('resiModal');
            if (event.target == modal) {
                modal.style.display = 'none';
            }
        }
    </script>
@endsection
