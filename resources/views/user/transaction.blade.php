<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Transaksi Pembayaran - SIUP</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        :root { 
            --primary: #0047ba; 
            --secondary: #00a1e4; 
            --success: #28a745; 
            --danger: #dc3545;
        }
        
        * { margin: 0; padding: 0; box-sizing: border-box; }

        body { 
            font-family: 'Poppins', sans-serif; 
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 20px;
        }

        .payment-container {
            max-width: 600px;
            margin: 0 auto;
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            overflow: hidden;
        }

        .payment-header {
            background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
            color: white;
            padding: 30px;
            text-align: center;
        }

        .timer {
            background: #fff3cd;
            color: #856404;
            padding: 15px;
            text-align: center;
            font-weight: 600;
        }

        .payment-body { padding: 30px; }

        .order-list {
            background: #f8f9fa;
            border-radius: 10px;
            padding: 15px;
            margin-bottom: 20px;
            max-height: 300px;
            overflow-y: auto;
        }

        .order-item {
            display: flex;
            justify-content: space-between;
            border-bottom: 1px solid #eee;
            padding: 10px 0;
            font-size: 14px;
        }
        .order-item:last-child { border-bottom: none; }
        .order-qty { margin-right: 10px; font-weight: 600; }

        .total-amount {
            text-align: center;
            font-size: 28px;
            font-weight: 700;
            color: var(--primary);
            margin: 20px 0;
        }

        .qris-box {
            text-align: center;
            margin-bottom: 20px;
        }
        
        .qris-image {
            background: white;
            padding: 10px;
            border: 2px dashed #ddd;
            display: inline-block;
            border-radius: 10px;
        }

        .btn-confirm {
            background: var(--success);
            color: white;
            width: 100%;
            padding: 15px;
            border: none;
            border-radius: 10px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            box-shadow: 0 4px 10px rgba(40, 167, 69, 0.3);
        }
        .btn-confirm:hover { background: #218838; }

    </style>
</head>
<body>

    <div class="payment-container">
        <div class="payment-header">
            <h1><i class="fas fa-money-bill-wave"></i> Bayar Pesanan</h1>
            <p>Selesaikan pembayaran untuk memproses pesanan</p>
        </div>

        <div class="timer" id="timer">
            <i class="fas fa-clock"></i> <span id="countdown">Loading...</span>
        </div>

        <div class="payment-body">
            <div class="order-list">
                @foreach($orders as $order)
                <div class="order-item">
                    <div>
                        <span class="order-qty">{{ $order->jumlah }}x</span>
                        {{ $order->menu_name }}
                        <div style="font-size: 11px; color: #666;">
                            {{ $order->vendor->nama_kantin ?? 'Vendor' }}
                            @if($order->status == 'selesai')
                                <span style="background:#d4edda; color:#155724; padding:2px 5px; border-radius:4px; font-weight:600; margin-left:5px;">Selesai</span>
                            @elseif($order->status == 'siap')
                                <span style="background:#d1ecf1; color:#0c5460; padding:2px 5px; border-radius:4px; font-weight:600; margin-left:5px;">Siap Diambil</span>
                            @endif
                        </div>
                    </div>
                    <div>Rp {{ number_format($order->total_harga, 0, ',', '.') }}</div>
                </div>
                @endforeach
            </div>

            @php
                $allCompleted = $orders->every(fn($o) => $o->status === 'selesai');
            @endphp
            @if($allCompleted && $transaction->payment_status === 'paid')
                <div style="text-align: center; margin-bottom: 20px; padding: 10px; background: #d4edda; color: #155724; border-radius: 8px; font-weight: 600;">
                    <i class="fas fa-check-circle"></i> Semua Pesanan Telah Selesai
                </div>
            @endif

            <div class="total-amount">
                Rp {{ number_format($transaction->total_amount, 0, ',', '.') }}
            </div>

            <div class="qris-box">
                <p style="margin-bottom: 10px; color: #666;">Scan QRIS di bawah ini:</p>
                <div class="qris-image">
                    <img src="https://upload.wikimedia.org/wikipedia/commons/d/d0/QR_code_for_mobile_English_Wikipedia.svg" alt="QRIS" width="200">
                    <p style="margin: 5px 0 0 0; font-weight: bold;">SCAN QRIS</p>
                </div>
            </div>

            <form action="{{ route('user.transaction.confirm', $transaction->id) }}" method="POST">
                @csrf
                <button type="submit" class="btn-confirm">
                    <i class="fas fa-check-circle"></i> Saya Sudah Bayar
                </button>
            </form>
            
            <a href="{{ route('user.dashboard') }}" style="display: block; text-align: center; margin-top: 15px; color: #666; text-decoration: none;">Batalkan Transaksi</a>
        </div>
    </div>

    <script>
        // Countdown Timer
        const expiryTime = new Date("{{ $transaction->expired_at->toIso8601String() }}").getTime();
        
        function updateTimer() {
            const now = new Date().getTime();
            const distance = expiryTime - now;

            if (distance < 0) {
                document.getElementById('timer').innerHTML = 'Pembayaran Kadaluarsa';
                document.getElementById('timer').style.background = '#f8d7da';
                document.getElementById('timer').style.color = '#721c24';
                document.querySelector('.btn-confirm').disabled = true;
                document.querySelector('.btn-confirm').style.opacity = 0.5;
                return;
            }

            const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
            const seconds = Math.floor((distance % (1000 * 60)) / 1000);

            document.getElementById('countdown').innerHTML = 
                `Sisa waktu: ${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`;
        }

        setInterval(updateTimer, 1000);
        updateTimer();
    </script>
</body>
</html>
