<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Riwayat Pesanan - SIUP</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        :root { --primary: #0047ba; --secondary: #00a1e4; --success: #28a745; }
        body { font-family: 'Poppins', sans-serif; margin: 0; background-color: #f8f9fa; color: #333; }
        
        .sidebar { 
            width: 280px; 
            position: fixed; 
            height: 100vh; 
            background: white; 
            border-right: 1px solid #eee; 
            padding: 20px; 
            box-sizing: border-box; 
            display: flex; 
            flex-direction: column; 
        }

        .sidebar-nav { flex-grow: 1; margin-top: 20px; }
        
        .menu-item { display: flex; align-items: center; padding: 12px 15px; border-radius: 10px; text-decoration: none; color: #555; margin-bottom: 5px; transition: 0.3s; }
        .menu-item:hover, .menu-item.active { background: var(--primary); color: white; }
        .menu-item i { margin-right: 15px; width: 20px; }

        .btn-logout { background: none; border: none; color: #dc3545; cursor: pointer; font-family: inherit; font-weight: 600; padding: 15px 10px; width: 100%; text-align: left; display: flex; align-items: center; gap: 15px; }
        .btn-logout:hover { background-color: #fff5f5; border-radius: 10px; }

        .logo-section { text-align: center; margin-bottom: 30px; }
        .logo-section img { width: 180px; }

        .main-content { margin-left: 280px; padding: 30px; }
        
        .header { margin-bottom: 30px; }
        .header h2 { margin: 0; color: var(--primary); }

        .order-card { background: white; border-radius: 15px; padding: 20px; margin-bottom: 20px; box-shadow: 0 4px 6px rgba(0,0,0,0.05); border-left: 5px solid #ddd; }
        .order-pending { border-left-color: #f39c12; }
        .order-completed { border-left-color: #28a745; }
        .order-processing { border-left-color: #3498db; }

        .order-header { display: flex; justify-content: space-between; margin-bottom: 15px; border-bottom: 1px solid #eee; padding-bottom: 10px; }
        .order-date { font-size: 13px; color: #777; }
        .order-status { font-weight: 600; text-transform: uppercase; font-size: 12px; padding: 4px 10px; border-radius: 20px; }
        
        .status-menunggu { background: #fff3cd; color: #856404; }
        .status-dimasak { background: #d1ecf1; color: #0c5460; }
        .status-selesai { background: #d4edda; color: #155724; }
        
        .payment-badge { font-size: 11px; padding: 3px 8px; border-radius: 15px; margin-left: 10px; }
        .payment-pending { background: #fff3cd; color: #856404; }
        .payment-paid { background: #d4edda; color: #155724; }
        .payment-expired { background: #f8d7da; color: #721c24; }
        
        .btn-pay { background: var(--primary); color: white; padding: 8px 15px; border-radius: 8px; text-decoration: none; font-size: 12px; display: inline-block; margin-top: 10px; }
        .btn-pay:hover { background: var(--secondary); }
        
        .order-body h4 { margin: 0 0 5px 0; }
        .order-details { font-size: 14px; color: #555; }
        .order-price { font-weight: 700; color: var(--primary); font-size: 16px; margin-top: 10px; }
    </style>
</head>
<body>

    <div class="sidebar">
        <div class="logo-section">
            <img src="https://sso.universitaspertamina.ac.id/images/logo.png" alt="Logo SIUP">
        </div>

        <div class="sidebar-nav">
            <a href="{{ route('user.dashboard') }}" class="menu-item"><i class="fa fa-home"></i> Beranda</a>
            <a href="{{ route('user.cart') }}" class="menu-item"><i class="fa fa-shopping-cart"></i> Keranjang</a>
            <a href="{{ route('user.favorit') }}" class="menu-item"><i class="fa fa-utensils"></i> Kantin Favorit</a>
            <a href="{{ route('user.history') }}" class="menu-item active"><i class="fa fa-history"></i> Riwayat Pesanan</a>
        </div>

        <div class="logout-section">
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="btn-logout">
                    <i class="fa fa-sign-out-alt"></i> Keluar Sistem
                </button>
            </form>
        </div>
    </div>

    <div class="main-content">
        <div class="header">
            <h2>Riwayat Pesanan Anda</h2>
            <p style="color: #666;">Pantau status makananmu di sini</p>
        </div>

        @if(session('success'))
            <div style="background: #d4edda; color: #155724; padding: 15px; border-radius: 10px; margin-bottom: 20px;">
                <i class="fa fa-check-circle"></i> {{ session('success') }}
            </div>
        @endif

        @if($groupedOrders->isEmpty())
            <div style="text-align: center; padding: 50px; color: #888;">
                <i class="fa fa-receipt fa-4x" style="margin-bottom: 20px; opacity: 0.2;"></i>
                <p>Belum ada riwayat pesanan.</p>
                <a href="{{ route('user.dashboard') }}" style="color: var(--primary); text-decoration: none; font-weight: 600;">Pesan Sekarang</a>
            </div>
        @else
            @foreach($groupedOrders as $groupKey => $items)
                @php
                    $first = $items->first();
                    $isGroup = $items->count() > 1;
                    $totalGroupPrice = $items->sum('total_harga');

                    // Cek apakah semua item sudah selesai/diambil
                    // Status selesai atau siap bisa dianggap "Ready"
                    // Tapi user minta: "jika dua pesanan dalam satu keranjang selesai itu nanti digabung"
                    // Kita akan cek strict "selesai"
                    $allCompleted = $items->every(function ($item) {
                        return $item->status == 'selesai';
                    });
                    
                    // Juga cek siap
                    $allReady = $items->every(function ($item) {
                        return in_array($item->status, ['siap', 'selesai']);
                    });

                    // Payment Status Classes
                    $paymentClass = 'payment-pending';
                    $paymentLabel = 'Belum Bayar';
                    $paymentIcon = 'fa-clock';
                    
                    if($first->payment_status == 'paid') {
                        $paymentClass = 'payment-paid'; 
                        $paymentLabel = 'Lunas';
                        $paymentIcon = 'fa-check';
                    } elseif($first->payment_status == 'expired') {
                        $paymentClass = 'payment-expired'; 
                        $paymentLabel = 'Kadaluarsa';
                        $paymentIcon = 'fa-times';
                    }

                    // Tentukan border class
                    // Logic: Paid -> Green if completed, otherwise Yellow/Blue indicators
                    // Tapi user minta "Digabung jika selesai" -> Kartu jadi hijau
                    
                    if ($first->payment_status == 'paid') {
                         if ($allCompleted) {
                            $cardBorder = 'order-completed';
                            $borderColor = '#28a745'; // Green
                         } else {
                            $cardBorder = 'order-processing'; 
                            $borderColor = '#3498db'; // Processing
                         }
                    } elseif ($first->payment_status == 'expired') {
                         $cardBorder = 'payment-expired-border'; 
                         $borderColor = '#dc3545';
                    } else {
                         $cardBorder = 'order-pending';
                         $borderColor = '#f39c12';
                    }
                @endphp

                <div class="order-card {{ $cardBorder }}" style="padding-bottom: 10px; border-left: 5px solid {{ $borderColor }};">
                    <!-- Header Kartu -->
                    <div class="order-header">
                        <span class="order-date">
                            <i class="fa fa-calendar"></i> {{ $first->created_at->format('d M Y, H:i') }}
                            @if($isGroup) <span style="margin-left:5px; font-size:11px; color:#555; background:#eee; padding:2px 6px; border-radius:4px;">Multi-Order</span> @endif
                        </span>
                        <div>
                            @if($allCompleted)
                                <span style="background:#d4edda; color:#155724; border-radius:15px; padding:3px 8px; font-size:11px; margin-right:5px; font-weight:600;">
                                    <i class="fa fa-thumbs-up"></i> Semua Selesai
                                </span>
                            @elseif($allReady)
                                <span style="background:#d1ecf1; color:#0c5460; border-radius:15px; padding:3px 8px; font-size:11px; margin-right:5px; font-weight:600;">
                                    <i class="fa fa-bell"></i> Siap Diambil
                                </span>
                            @endif

                            <span class="payment-badge {{ $paymentClass }}">
                                <i class="fa {{ $paymentIcon }}"></i> {{ $paymentLabel }}
                            </span>
                        </div>
                    </div>

                    <!-- List Item -->
                    <div class="order-body">
                        @foreach($items as $item)
                            @php
                                $statusColor = '#856404'; // Menunggu
                                if($item->status == 'dimasak') $statusColor = '#0c5460';
                                if($item->status == 'selesai') $statusColor = '#155724';
                            @endphp
                            <div style="display: flex; justify-content: space-between; margin-bottom: 15px; border-bottom: 1px dashed #eee; padding-bottom: 10px;">
                                <div>
                                    <h4 style="margin: 0 0 5px 0; font-size: 15px;">{{ $item->menu_name ?? ($item->menu->nama_makanan ?? 'Menu Terhapus') }} <span style="font-size: 13px; color: #777; font-weight: normal;">(x{{ $item->jumlah }})</span></h4>
                                    <div class="order-details">
                                        <i class="fa fa-store" style="font-size: 11px;"></i> {{ $item->vendor->nama_kantin ?? 'Kantin' }}
                                        &nbsp;|&nbsp; 
                                        <span style="font-weight: 600; font-size: 11px; color: {{ $statusColor }}; text-transform: uppercase;">
                                            Status: {{ $item->status }}
                                        </span>
                                    </div>
                                    @if($item->nomor_antrean)
                                        <div style="font-size: 12px; margin-top: 3px;">
                                            Antrean: <strong>{{ $item->nomor_antrean }}</strong>
                                        </div>
                                    @endif
                                </div>
                                <div style="text-align: right;">
                                    <div style="font-weight: 600; font-size: 14px;">Rp {{ number_format($item->total_harga, 0, ',', '.') }}</div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Footer Kartu: Total & Aksi -->
                    <div style="display: flex; justify-content: space-between; align-items: center; padding-top: 5px;">
                        <div>
                            <span style="font-size: 13px; color: #555;">Total Transaksi:</span>
                            <div style="font-size: 18px; font-weight: 700; color: var(--primary);">Rp {{ number_format($totalGroupPrice, 0, ',', '.') }}</div>
                        </div>

                        @if($first->payment_status == 'pending')
                            @php
                                // Tentukan link pembayaran
                                $payLink = '#';
                                if($first->transaction) {
                                    $payLink = route('user.transaction', $first->transaction->id);
                                } elseif($first->id) {
                                    // Fallback legacy orders (single)
                                    $payLink = route('user.payment-qris', $first->id);
                                }
                            @endphp
                            <a href="{{ $payLink }}" class="btn-pay">
                                <i class="fa fa-qrcode"></i> Bayar Sekarang
                            </a>
                        @endif
                    </div>
                </div>
            @endforeach
        @endif
    </div>

</body>
</html>
