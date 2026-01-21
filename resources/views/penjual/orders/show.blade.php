@extends('penjual.dashboard')

@section('content')
    <style>
        .detail-header { margin-bottom: 30px; }
        .detail-card { background: white; border-radius: 12px; box-shadow: 0 2px 8px rgba(0,0,0,0.08); padding: 30px; }
        .order-title { font-size: 28px; font-weight: 700; color: #0047ba; margin-bottom: 25px; }
        .detail-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px; margin-bottom: 30px; }
        .detail-box { background: #f8f9fa; padding: 20px; border-radius: 8px; border-left: 4px solid #0047ba; }
        .detail-label { font-size: 12px; color: #888; text-transform: uppercase; font-weight: 600; }
        .detail-value { font-size: 18px; font-weight: 600; color: #333; margin-top: 8px; }
        .status-large { display: inline-block; padding: 12px 24px; border-radius: 25px; font-size: 14px; font-weight: 700; }
        .status-menunggu { background: #fff3cd; color: #856404; }
        .status-dimasak { background: #cce5ff; color: #004085; }
        .status-siap { background: #d4edda; color: #155724; }
        .status-selesai { background: #d1ecf1; color: #0c5460; }
        .status-dibatalkan { background: #f8d7da; color: #721c24; }
        .section-title { font-size: 18px; font-weight: 700; color: #0047ba; margin-top: 30px; margin-bottom: 15px; }
        .menu-items { background: #f8f9fa; padding: 20px; border-radius: 8px; margin-top: 15px; }
        .menu-item { display: flex; justify-content: space-between; align-items: center; padding: 15px 0; border-bottom: 1px solid #ddd; }
        .menu-item:last-child { border-bottom: none; }
        .menu-name { font-weight: 600; color: #333; }
        .menu-price { font-weight: 700; color: #28a745; }
        .status-selector { display: flex; gap: 12px; align-items: center; margin-top: 20px; }
        .status-dropdown { padding: 12px 15px; border: 1px solid #ddd; border-radius: 8px; font-size: 15px; font-weight: 600; cursor: pointer; }
        .btn-update { padding: 12px 24px; background: linear-gradient(to right, #0047ba, #00a1e4); color: white; border: none; border-radius: 8px; font-weight: 600; cursor: pointer; transition: 0.3s; }
        .btn-update:hover { transform: translateY(-2px); box-shadow: 0 4px 12px rgba(0,71,186,0.3); }
        .btn-back { padding: 12px 24px; background: #f5f5f5; color: #666; border: none; border-radius: 8px; font-weight: 600; cursor: pointer; text-decoration: none; display: inline-block; transition: 0.3s; }
        .btn-back:hover { background: #e8e8e8; }
        .action-group { display: flex; gap: 12px; margin-top: 20px; }
    </style>

    <div class="detail-header">
        <a href="{{ route('penjual.orders.index') }}" style="color:#0047ba; text-decoration:none; font-weight:600; display:flex; align-items:center; gap:8px">
            <i class="fa fa-arrow-left"></i> Kembali ke Pesanan
        </a>
    </div>

    <div class="detail-card">
        <h1 class="order-title"><i class="fa fa-receipt" style="margin-right:12px"></i>Pesanan No. {{ $order->nomor_antrean }}</h1>

        <div class="detail-grid">
            <div class="detail-box">
                <div class="detail-label"><i class="fa fa-user" style="margin-right:8px; color:#0047ba"></i>Nama Pembeli</div>
                <div class="detail-value">{{ $order->user->name ?? ($order->nama_pembeli ?? 'Guest') }}</div>
            </div>
            <div class="detail-box">
                <div class="detail-label"><i class="fa fa-clock" style="margin-right:8px; color:#0047ba"></i>Waktu Pesan</div>
                <div class="detail-value">{{ $order->created_at->format('d M Y H:i') }}</div>
            </div>
            <div class="detail-box">
                <div class="detail-label"><i class="fa fa-hourglass-half" style="margin-right:8px; color:#0047ba"></i>Estimasi Siap</div>
                <div class="detail-value">{{ $order->estimasi_menit }} menit</div>
            </div>
            <div class="detail-box">
                <div class="detail-label"><i class="fa fa-wallet" style="margin-right:8px; color:#0047ba"></i>Status Pembayaran</div>
                <div class="detail-value">
                     @if($order->payment_status == 'paid')
                        <span style="color:#28a745"><i class="fa fa-check-circle"></i> Lunas</span>
                     @elseif($order->payment_status == 'expired')
                         <span style="color:#dc3545"><i class="fa fa-times-circle"></i> Kadaluarsa</span>
                     @else
                        <span style="color:#f39c12"><i class="fa fa-clock"></i> Belum Lunas</span>
                     @endif
                </div>
            </div>
        </div>

        <div style="background: #e7f3ff; border-left: 4px solid #0047ba; padding: 20px; border-radius: 8px; margin-bottom: 30px;">
            <div style="font-size: 14px; color: #0047ba; font-weight: 600;">
                <i class="fa fa-cash-register" style="margin-right:8px"></i>Total Pendapatan (Pesanan Ini)
            </div>
            <div style="font-size: 32px; font-weight: 700; color: #0047ba; margin-top: 10px;">
                Rp {{ number_format($totalVendorPrice, 0, ',', '.') }}
            </div>
        </div>

        <h3 class="section-title"><i class="fa fa-list" style="margin-right:10px"></i>Rincian Menu Dipesan</h3>
        
        <div class="menu-items">
            @foreach($groupItems as $item)
                <div class="menu-item">
                    <div style="flex: 1;">
                        <div class="menu-name" style="font-size: 16px;">
                            {{ $item->menu_name ?? ($item->menu->nama_makanan ?? '-') }}
                            <span style="color:#666; font-size:14px; font-weight:normal;"> (x{{ $item->jumlah }})</span>
                        </div>
                        <div style="margin-top:5px; font-size:13px; color:#555">
                            Status: <span class="status-badge status-{{ $item->status }}" style="padding:2px 8px; font-size:11px;">{{ $item->getStatusLabel() }}</span>
                        </div>
                    </div>
                    <div class="menu-price">Rp {{ number_format($item->total_harga, 0, ',', '.') }}</div>
                </div>
            @endforeach
        </div>
        
        <div style="margin-top: 30px; padding: 20px; background: #fff3cd; border: 1px solid #ffeeba; border-radius: 8px;">
            <i class="fa fa-info-circle"></i> <strong>Catatan:</strong> Status pesanan dapat diubah secara individual per item di halaman utama "Pesanan Masuk".
        </div>

        <!-- Hapus bagian form update status single di detail view karena sekarang multi-item
             Atau biarkan jika ingin mengubah item UTAMA yang sedang dilihat -->

        <form action="{{ route('penjual.orders.updateStatus', $order->id) }}" method="POST">
            @csrf
            <div class="status-selector">
                <select name="status" class="status-dropdown" required>
                    <option value="">-- Pilih Status --</option>
                    <option value="menunggu" {{ $order->status == 'menunggu' ? 'selected' : '' }}>Menunggu</option>
                    <option value="dimasak" {{ $order->status == 'dimasak' ? 'selected' : '' }}>Sedang Dimasak</option>
                    <option value="siap" {{ $order->status == 'siap' ? 'selected' : '' }}>Siap Diambil</option>
                    <option value="selesai" {{ $order->status == 'selesai' ? 'selected' : '' }}>Selesai</option>
                    <option value="dibatalkan" {{ $order->status == 'dibatalkan' ? 'selected' : '' }}>Dibatalkan</option>
                </select>
                <button type="submit" class="btn-update">
                    <i class="fa fa-save"></i> Perbarui Status
                </button>
            </div>
        </form>

        <div class="action-group" style="margin-top: 30px;">
            <a href="{{ route('penjual.orders.index') }}" class="btn-back">
                <i class="fa fa-arrow-left"></i> Kembali
            </a>
        </div>
    </div>
@endsection
