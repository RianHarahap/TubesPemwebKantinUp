@extends('penjual.dashboard')

@section('content')
    <style>
        .page-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px; }
        .order-table { background: white; border-radius: 12px; box-shadow: 0 2px 8px rgba(0,0,0,0.08); overflow: hidden; }
        .order-table table { width: 100%; border-collapse: collapse; }
        .order-table thead { background: linear-gradient(to right, #0047ba, #00a1e4); color: white; }
        .order-table th { padding: 16px; text-align: left; font-weight: 600; font-size: 13px; text-transform: uppercase; }
        .order-table td { padding: 16px; border-bottom: 1px solid #f0f0f0; }
        .order-table tbody tr:hover { background: #f8f9fa; }
        .order-row { display: flex; justify-content: space-between; align-items: center; }
        .order-nomor { font-weight: 600; color: #0047ba; font-size: 15px; }
        .order-user { color: #333; font-weight: 500; }
        .order-user small { display: block; color: #888; font-size: 12px; }
        .status-badge { display: inline-block; padding: 6px 14px; border-radius: 20px; font-size: 12px; font-weight: 600; }
        .status-menunggu { background: #fff3cd; color: #856404; }
        .status-dimasak { background: #cce5ff; color: #004085; }
        .status-siap { background: #d4edda; color: #155724; }
        .status-selesai { background: #d1ecf1; color: #0c5460; }
        .status-dibatalkan { background: #f8d7da; color: #721c24; }
        .status-dropdown { padding: 8px 12px; border: 1px solid #ddd; border-radius: 6px; font-size: 12px; cursor: pointer; }
        .action-btn { padding: 8px 16px; background: #f5f5f5; border: none; border-radius: 6px; cursor: pointer; font-weight: 600; font-size: 12px; transition: 0.3s; }
        .action-btn:hover { background: #0047ba; color: white; }
        .empty-state { text-align: center; padding: 60px 20px; }
        .empty-state-icon { font-size: 48px; color: #ccc; margin-bottom: 15px; }
        .empty-state-text { color: #999; font-size: 16px; }
        .success-msg { background: #d4edda; border-left: 4px solid #28a745; color: #155724; padding: 15px 20px; border-radius: 8px; margin-bottom: 20px; }
        .filter-bar { display: flex; gap: 12px; margin-bottom: 20px; }
        .filter-btn { padding: 10px 16px; background: #f5f5f5; border: 1px solid #ddd; border-radius: 8px; cursor: pointer; font-weight: 600; font-size: 13px; transition: 0.3s; }
        .filter-btn.active { background: #0047ba; color: white; border-color: #0047ba; }
        .price-highlight { font-weight: 600; color: #28a745; }
        @media (max-width: 768px) {
            .order-table table { font-size: 12px; }
            .order-table th, .order-table td { padding: 12px; }
        }
    </style>

    <div class="page-header">
        <h2 style="margin:0; color:#0047ba"><i class="fa fa-inbox" style="margin-right:10px"></i>Pesanan Masuk</h2>
    </div>

    @if(session('success'))
        <div class="success-msg">
            <i class="fa fa-check-circle"></i> {{ session('success') }}
        </div>
    @endif

    <div class="order-table">
        @if(empty($groupedOrders) || $groupedOrders->isEmpty())
            <div class="empty-state">
                <div class="empty-state-icon"><i class="fa fa-inbox"></i></div>
                <div class="empty-state-text">Belum ada pesanan yang masuk</div>
            </div>
        @else
            <table style="width: 100%; border-collapse: separate; border-spacing: 0;">
                <thead>
                    <tr>
                        <th style="width: 15%;">Antrean</th>
                        <th style="width: 15%;">Pembeli</th>
                        <th style="width: 50%;">Menu & Status</th>
                        <th style="width: 10%;">Total</th>
                        <th style="width: 10%;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($groupedOrders as $groupKey => $items)
                        @php
                            $first = $items->first();
                            $isMulti = $items->count() > 1;
                            $totalGroup = $items->sum('total_harga');
                        @endphp
                        <tr style="border-bottom: 5px solid #f8f9fa;">
                            <!-- Kolom 1: Info Antrean -->
                            <td style="vertical-align: top; padding: 15px; border-bottom: 1px solid #eee;">
                                <div style="font-weight: 700; font-size: 16px; color: #0047ba;">{{ $first->nomor_antrean ?? '-' }}</div>
                                <div style="font-size: 11px; color: #888; margin-top: 4px;">{{ $first->created_at->format('d/m H:i') }}</div>
                                
                                @if($first->payment_status == 'paid')
                                    <div style="margin-top: 8px;"><span style="padding: 3px 8px; background: #e8f5e9; color: #2e7d32; border-radius: 12px; font-size: 11px; font-weight: 600;">Lunas</span></div>
                                @elseif($first->payment_status == 'pending')
                                    <div style="margin-top: 8px;"><span style="padding: 3px 8px; background: #fff3cd; color: #856404; border-radius: 12px; font-size: 11px; font-weight: 600;">Belum Lunas</span></div>
                                @elseif($first->payment_status == 'expired')
                                    <div style="margin-top: 8px;"><span style="padding: 3px 8px; background: #ffebee; color: #c62828; border-radius: 12px; font-size: 11px; font-weight: 600;">Expired</span></div>
                                @endif
                            </td>

                            <!-- Kolom 2: Pembeli -->
                            <td style="vertical-align: top; padding: 15px; border-bottom: 1px solid #eee;">
                                <div style="font-weight: 500;">{{ $first->nama_pembeli ?? ($first->user->name ?? 'Guest') }}</div>
                                @if($isMulti)
                                    <div style="margin-top: 5px; font-size: 11px; color: #666; background: #f0f0f0; display: inline-block; padding: 2px 6px; border-radius: 4px;">{{ $items->count() }} Item</div>
                                @endif
                            </td>

                            <!-- Kolom 3: Rincian Menu & Status (Layout Grid Rapi) -->
                            <td style="vertical-align: top; padding: 10px; border-bottom: 1px solid #eee;">
                                <div style="display: flex; flex-direction: column; gap: 8px;">
                                    @foreach($items as $item)
                                        <div style="display: flex; align-items: center; justify-content: space-between; background: #fdfdfd; padding: 8px 10px; border: 1px solid #f0f0f0; border-radius: 8px;">
                                            <div style="flex: 1;">
                                                <div style="font-weight: 600; font-size: 13px;">{{ $item->menu_name }}</div>
                                                <div style="font-size: 11px; color: #666; margin-top: 2px;">{{ $item->jumlah }} x Rp {{ number_format($item->harga_satuan, 0, ',', '.') }}</div>
                                            </div>
                                            
                                            <!-- Status Control -->
                                            <form action="{{ route('penjual.orders.updateStatus', $item->id) }}" method="POST" style="margin-left: 15px;">
                                                @csrf
                                                <select name="status" onchange="this.form.submit()" 
                                                    style="padding: 5px 8px; border: 1px solid #ddd; border-radius: 6px; font-size: 11px; cursor: pointer; outline: none; 
                                                    background-color: {{ $item->status == 'menunggu' ? '#fff3cd' : ($item->status == 'dimasak' ? '#e3f2fd' : ($item->status == 'selesai' ? '#e8f5e9' : '#fff')) }};
                                                    color: {{ $item->status == 'menunggu' ? '#856404' : ($item->status == 'dimasak' ? '#0d47a1' : ($item->status == 'selesai' ? '#1b5e20' : '#333')) }};
                                                    border-color: {{ $item->status == 'menunggu' ? '#ffeeba' : ($item->status == 'dimasak' ? '#bbdefb' : ($item->status == 'selesai' ? '#c8e6c9' : '#ddd')) }};">
                                                    
                                                    <option value="menunggu" {{ $item->status == 'menunggu' ? 'selected' : '' }}>⏳ Menunggu</option>
                                                    <option value="dimasak" {{ $item->status == 'dimasak' ? 'selected' : '' }}>🔥 Dimasak</option>
                                                    <option value="siap" {{ $item->status == 'siap' ? 'selected' : '' }}>✅ Siap</option>
                                                    <option value="selesai" {{ $item->status == 'selesai' ? 'selected' : '' }}>🎉 Selesai</option>
                                                    <option value="dibatalkan" {{ $item->status == 'dibatalkan' ? 'selected' : '' }}>❌ Batal</option>
                                                </select>
                                            </form>
                                        </div>
                                    @endforeach
                                </div>
                            </td>

                            <!-- Kolom 4: Total Group -->
                            <td style="vertical-align: top; padding: 15px; border-bottom: 1px solid #eee;">
                                <div style="font-weight: 700; color: #0047ba;">Rp {{ number_format($totalGroup, 0, ',', '.') }}</div>
                            </td>

                            <!-- Kolom 5: Aksi -->
                            <td style="vertical-align: top; padding: 15px; border-bottom: 1px solid #eee;">
                                <a href="{{ route('penjual.orders.show', $first->id) }}" class="action-btn" style="text-decoration:none;">
                                    <i class="fa fa-eye"></i>
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>
@endsection
