<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Transfer Bank via QRIS - SIUP</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        :root { 
            --primary: #0047ba; 
            --secondary: #00a1e4; 
            --success: #28a745; 
            --danger: #dc3545;
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body { 
            font-family: 'Poppins', sans-serif; 
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .payment-container {
            max-width: 500px;
            width: 100%;
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

        .payment-header h1 {
            font-size: 24px;
            margin-bottom: 10px;
        }

        .payment-header p {
            opacity: 0.9;
            font-size: 14px;
        }

        .payment-body {
            padding: 30px;
        }

        .order-info {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 10px;
            margin-bottom: 20px;
        }

        .order-info h3 {
            font-size: 18px;
            margin-bottom: 15px;
            color: var(--primary);
        }

        .info-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
            font-size: 14px;
        }

        .info-row .label {
            color: #666;
        }

        .info-row .value {
            font-weight: 600;
            color: #333;
        }

        .total-amount {
            border-top: 2px solid #dee2e6;
            padding-top: 15px;
            margin-top: 15px;
        }

        .total-amount .value {
            font-size: 28px;
            color: var(--danger);
        }

        .qris-section {
            text-align: center;
            margin: 30px 0;
        }

        .qris-box {
            background: white;
            border: 3px solid #dee2e6;
            border-radius: 15px;
            padding: 20px;
            display: inline-block;
            margin-bottom: 20px;
        }

        .qris-image {
            width: 250px;
            height: 250px;
            background: white;
            border: 2px solid #000;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto;
        }

        .qris-placeholder {
            width: 100%;
            height: 100%;
            background: 
                repeating-linear-gradient(45deg, #000, #000 10px, #fff 10px, #fff 20px),
                repeating-linear-gradient(-45deg, #000, #000 10px, #fff 10px, #fff 20px);
            background-size: 20px 20px;
            position: relative;
        }

        .qris-logo {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background: white;
            padding: 10px;
            border-radius: 8px;
            font-weight: bold;
            color: var(--danger);
            font-size: 18px;
        }

        .timer {
            background: #fff3cd;
            color: #856404;
            padding: 15px;
            border-radius: 10px;
            margin-bottom: 20px;
            text-align: center;
            font-weight: 600;
        }

        .timer i {
            margin-right: 8px;
        }

        .instructions {
            background: #e7f3ff;
            padding: 20px;
            border-radius: 10px;
            margin-bottom: 20px;
        }

        .instructions h4 {
            color: var(--primary);
            margin-bottom: 15px;
            font-size: 16px;
        }

        .instructions ol {
            padding-left: 20px;
            margin: 0;
        }

        .instructions li {
            margin-bottom: 8px;
            font-size: 14px;
            line-height: 1.6;
        }

        .action-buttons {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 10px;
            margin-top: 20px;
        }

        .btn {
            padding: 15px;
            border: none;
            border-radius: 10px;
            font-family: inherit;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            text-decoration: none;
        }

        .btn-download {
            background: var(--success);
            color: white;
        }

        .btn-download:hover {
            background: #218838;
            transform: translateY(-2px);
        }

        .btn-confirm {
            background: var(--primary);
            color: white;
        }

        .btn-confirm:hover {
            background: #003a9a;
            transform: translateY(-2px);
        }

        .btn-cancel {
            grid-column: 1 / -1;
            background: #6c757d;
            color: white;
        }

        .btn-cancel:hover {
            background: #5a6268;
        }

        .qris-code {
            font-family: monospace;
            font-size: 12px;
            color: #666;
            margin-top: 10px;
            word-break: break-all;
        }

        @media (max-width: 600px) {
            .payment-container {
                margin: 0;
            }

            .qris-image {
                width: 200px;
                height: 200px;
            }

            .action-buttons {
                grid-template-columns: 1fr;
            }

            .btn-cancel {
                grid-column: 1;
            }
        }
    </style>
</head>
<body>
    <div class="payment-container">
        <div class="payment-header">
            <h1><i class="fas fa-qrcode"></i> Transfer Bank via QRIS</h1>
            <p>Selesaikan pembayaran dalam waktu yang ditentukan</p>
        </div>

        <div class="payment-body">
            <!-- Timer -->
            <div class="timer" id="timer">
                <i class="fas fa-clock"></i>
                <span id="countdown">Memuat...</span>
            </div>

            <!-- Order Info -->
            <div class="order-info">
                <h3><i class="fas fa-receipt"></i> Detail Pesanan</h3>
                <div class="info-row">
                    <span class="label">Nomor Pesanan</span>
                    <span class="value">#{{ $order->id }}</span>
                </div>
                <div class="info-row">
                    <span class="label">Nomor Antrean</span>
                    <span class="value">{{ $order->nomor_antrean }}</span>
                </div>
                <div class="info-row">
                    <span class="label">Menu</span>
                    <span class="value">{{ $order->menu_name }}</span>
                </div>
                <div class="info-row">
                    <span class="label">Jumlah</span>
                    <span class="value">{{ $order->jumlah }}x</span>
                </div>
                <div class="info-row">
                    <span class="label">Kantin</span>
                    <span class="value">{{ $order->vendor->nama_vendor }}</span>
                </div>
                <div class="info-row total-amount">
                    <span class="label">Total Pembayaran</span>
                    <span class="value">Rp{{ number_format($order->total_harga, 0, ',', '.') }}</span>
                </div>
            </div>

            <!-- QRIS Code -->
            <div class="qris-section">
                <div class="qris-box">
                    <div class="qris-image">
                        <div class="qris-placeholder">
                            <div class="qris-logo">QRIS</div>
                        </div>
                    </div>
                    <div class="qris-code">
                        {{ $order->qris_code }}
                    </div>
                </div>
                <p style="font-size: 12px; color: #666;">Scan QR Code di atas dengan aplikasi pembayaran Anda</p>
            </div>

            <!-- Instructions -->
            <div class="instructions">
                <h4><i class="fas fa-info-circle"></i> Cara Pembayaran</h4>
                <ol>
                    <li>Screenshot atau unduh gambar QR Code di atas</li>
                    <li>Buka aplikasi e-wallet atau m-banking Anda</li>
                    <li>Pilih menu Scan QRIS atau Bayar dengan QRIS</li>
                    <li>Scan QR Code atau upload screenshot</li>
                    <li>Konfirmasi pembayaran di aplikasi Anda</li>
                    <li>Klik tombol "Saya Sudah Bayar" di bawah</li>
                </ol>
            </div>

            <!-- Action Buttons -->
            <div class="action-buttons">
                <button class="btn btn-download" onclick="downloadQRIS()">
                    <i class="fas fa-download"></i> Unduh QRIS
                </button>
                
                <form method="POST" action="{{ route('user.confirm-payment', $order->id) }}" style="display: contents;">
                    @csrf
                    <button type="submit" class="btn btn-confirm">
                        <i class="fas fa-check-circle"></i> Saya Sudah Bayar
                    </button>
                </form>

                <a href="{{ route('user.history') }}" class="btn btn-cancel">
                    <i class="fas fa-times"></i> Batalkan
                </a>
            </div>
        </div>
    </div>

    <script>
        // Countdown Timer
        // Menggunakan ISO string agar parsing date konsisten di JS
        const expiryTime = new Date("{{ $order->payment_expired_at->toIso8601String() }}").getTime();
        
        function updateTimer() {
            const now = new Date().getTime();
            const distance = expiryTime - now;

            if (distance < 0) {
                document.getElementById('timer').innerHTML = '<i class="fas fa-exclamation-triangle"></i> Pembayaran telah kadaluarsa';
                document.getElementById('timer').style.background = '#f8d7da';
                document.getElementById('timer').style.color = '#721c24';
                // Disable buttons
                document.querySelectorAll('.btn-download, .btn-confirm').forEach(btn => {
                    btn.disabled = true;
                    btn.style.opacity = '0.5';
                    btn.style.cursor = 'not-allowed';
                });
                return;
            }

            const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
            const seconds = Math.floor((distance % (1000 * 60)) / 1000);

            document.getElementById('countdown').innerHTML = 
                `Sisa waktu: ${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`;
        }

        // Update every second
        updateTimer();
        setInterval(updateTimer, 1000);

        // Download QRIS Function (simple screenshot simulation)
        function downloadQRIS() {
            // Dalam implementasi real, gunakan html2canvas atau library lain
            // Untuk sekarang, kita buat alert
            alert('Fitur unduh QRIS!\n\nSilakan screenshot halaman ini untuk menyimpan QR Code.\n\nAnda juga bisa salin kode: {{ $order->qris_code }}');
            
            // Optional: Copy code to clipboard
            const code = "{{ $order->qris_code }}";
            if (navigator.clipboard) {
                navigator.clipboard.writeText(code).then(() => {
                    console.log('QRIS code copied to clipboard');
                });
            }
        }
    </script>
</body>
</html>
