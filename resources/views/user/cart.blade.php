<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Keranjang Belanja - SIUP</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        :root { --primary: #0047ba; --secondary: #00a1e4; --success: #28a745; --danger: #dc3545; }
        body { font-family: 'Poppins', sans-serif; margin: 0; background-color: #f8f9fa; color: #333; }
        
        .container { max-width: 800px; margin: 30px auto; padding: 20px; }
        
        .cart-header { display: flex; align-items: center; justify-content: space-between; margin-bottom: 20px; }
        .cart-header h1 { margin: 0; font-size: 24px; color: var(--primary); }

        .cart-card { background: white; border-radius: 15px; box-shadow: 0 4px 10px rgba(0,0,0,0.05); overflow: hidden; margin-bottom: 20px; }
        
        .cart-item { display: flex; padding: 20px; border-bottom: 1px solid #eee; align-items: center; }
        .cart-item:last-child { border-bottom: none; }
        
        .item-img { width: 80px; height: 80px; background: #eee; border-radius: 10px; margin-right: 20px; object-fit: cover; }
        
        .item-details { flex: 1; }
        .item-name { font-weight: 600; font-size: 16px; margin-bottom: 5px; }
        .item-vendor { font-size: 13px; color: #666; margin-bottom: 5px; }
        .item-price { color: var(--primary); font-weight: 700; }

        .item-actions { display: flex; align-items: center; gap: 15px; }
        
        .qty-control { display: flex; align-items: center; border: 1px solid #ddd; border-radius: 8px; overflow: hidden; }
        .qty-btn { background: #f8f9fa; border: none; width: 30px; height: 30px; cursor: pointer; font-weight: bold; }
        .qty-input { width: 40px; text-align: center; border: none; height: 30px; font-weight: 600; }
        
        .btn-delete { color: var(--danger); text-decoration: none; font-size: 14px; }
        .btn-delete:hover { text-decoration: underline; }

        .cart-summary { background: white; border-radius: 15px; padding: 20px; box-shadow: 0 4px 10px rgba(0,0,0,0.05); }
        .summary-row { display: flex; justify-content: space-between; margin-bottom: 15px; font-size: 16px; }
        .summary-total { font-weight: 700; font-size: 20px; color: var(--primary); border-top: 1px solid #eee; padding-top: 15px; margin-top: 10px; }

        .btn-checkout { background: var(--primary); color: white; width: 100%; padding: 15px; border: none; border-radius: 10px; font-size: 16px; font-weight: 600; cursor: pointer; transition: 0.3s; margin-top: 20px; }
        .btn-checkout:hover { background: var(--secondary); }

        .empty-cart { text-align: center; padding: 50px; }
        .empty-cart i { font-size: 60px; color: #ccc; margin-bottom: 20px; }
        .btn-back { display: inline-block; padding: 10px 20px; background: var(--secondary); color: white; text-decoration: none; border-radius: 8px; margin-top: 20px; }
    </style>
</head>
<body>

<div class="container">
    <div class="cart-header">
        <h1><i class="fa fa-shopping-cart"></i> Keranjang Belanja</h1>
        <a href="{{ route('user.dashboard') }}" style="color: #666; text-decoration: none;"><i class="fa fa-arrow-left"></i> Kembali</a>
    </div>

    @if(session('success'))
        <div style="background: #d4edda; color: #155724; padding: 15px; border-radius: 10px; margin-bottom: 20px;">
            <i class="fa fa-check-circle"></i> {{ session('success') }}
        </div>
    @endif
    
    @if(session('error'))
        <div style="background: #f8d7da; color: #721c24; padding: 15px; border-radius: 10px; margin-bottom: 20px;">
            <i class="fa fa-exclamation-triangle"></i> {{ session('error') }}
        </div>
    @endif

    @if($cartItems->isEmpty())
        <div class="empty-cart">
            <i class="fa fa-shopping-basket"></i>
            <h3>Keranjang Anda Kosong</h3>
            <p>Yuk cari makanan enak di kantin!</p>
            <a href="{{ route('user.dashboard') }}" class="btn-back">Mulai Belanja</a>
        </div>
    @else
        <div class="cart-card">
            @php $total = 0; @endphp
            @foreach($cartItems as $item)
                @php 
                    $subtotal = $item->menu->harga * $item->quantity; 
                    $total += $subtotal;
                    $foto = $item->menu->foto ? asset('storage/'.$item->menu->foto) : asset('img/menus/dgeprek-menu-1.jpg'); // Fallback image
                @endphp
                <div class="cart-item">
                    <img src="{{ $foto }}" class="item-img" alt="{{ $item->menu->nama_makanan }}">
                    <div class="item-details">
                        <div class="item-name">{{ $item->menu->nama_makanan }}</div>
                        <div class="item-vendor"><i class="fa fa-store"></i> {{ $item->menu->vendor->nama_kantin ?? 'Kantin' }}</div>
                        <div class="item-price">Rp {{ number_format($item->menu->harga, 0, ',', '.') }}</div>
                    </div>
                    <div class="item-actions">
                        <form action="{{ route('user.cart.update', $item->id) }}" method="POST" style="display: flex; align-items: center;">
                            @csrf
                            <div class="qty-control">
                                <!-- Simple submit on change implementation could be better with JS, but Form submit is reliable -->
                                <button type="submit" name="quantity" value="{{ $item->quantity - 1 }}" class="qty-btn">-</button>
                                <input type="text" class="qty-input" value="{{ $item->quantity }}" readonly>
                                <button type="submit" name="quantity" value="{{ $item->quantity + 1 }}" class="qty-btn">+</button>
                            </div>
                        </form>
                        <a href="{{ route('user.cart.remove', $item->id) }}" class="btn-delete"><i class="fa fa-trash"></i></a>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="cart-summary">
            <div class="summary-row">
                <span>Total Item</span>
                <span>{{ $cartItems->sum('quantity') }} porsi</span>
            </div>
            <div class="summary-row summary-total">
                <span>Total Pembayaran</span>
                <span>Rp {{ number_format($total, 0, ',', '.') }}</span>
            </div>

            <form action="{{ route('user.cart.checkout') }}" method="POST">
                @csrf
                <button type="submit" class="btn-checkout">
                    <i class="fa fa-wallet"></i> Bayar Semua (QRIS)
                </button>
            </form>
        </div>
    @endif
</div>

</body>
</html>
