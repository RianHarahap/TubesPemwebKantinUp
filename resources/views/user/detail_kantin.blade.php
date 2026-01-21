<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Kantin - {{ $vendor->nama_kantin }}</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        :root { --primary: #0047ba; --secondary: #00a1e4; --success: #28a745; }
        body { font-family: 'Poppins', sans-serif; margin: 0; background-color: #f8f9fa; color: #333; }
        
        .container { max-width: 800px; margin: 0 auto; padding: 20px; }
        
        /* Header Kantin */
        .vendor-header {
            background: white;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 4px 15px rgba(0,0,0,0.05);
            margin-bottom: 25px;
        }
        .vendor-cover {
            height: 150px;
            background: linear-gradient(45deg, var(--primary), var(--secondary));
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 3rem;
        }
        .vendor-info { padding: 20px; }
        .vendor-info h1 { margin: 0 0 10px 0; font-size: 24px; }
        .vendor-info p { color: #666; margin: 0; }

        /* Grid Menu */
        .menu-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(160px, 1fr));
            gap: 15px;
        }
        .menu-card {
            background: white;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
            transition: 0.2s;
        }
        .menu-card:hover { transform: translateY(-3px); }
        .menu-img {
            height: 120px;
            background-color: #eee;
            background-size: cover;
            background-position: center;
        }
        .menu-body { padding: 12px; }
        .menu-title { font-weight: 600; font-size: 14px; margin-bottom: 5px; height: 40px; overflow: hidden; }
        .menu-price { color: var(--primary); font-weight: 700; margin-bottom: 10px; }
        
        .btn-add {
            display: block;
            width: 100%;
            background: var(--primary);
            color: white;
            border: none;
            padding: 8px;
            border-radius: 5px;
            cursor: pointer;
            font-weight: 600;
        }
        .btn-add:hover { background: var(--secondary); }

        .back-btn {
            display: inline-block;
            margin-bottom: 20px;
            color: #555;
            text-decoration: none;
            font-weight: 500;
        }

        /* Modal */
        .modal { display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); align-items: center; justify-content: center; z-index: 1000; }
        .modal-content { background: white; padding: 25px; border-radius: 10px; width: 90%; max-width: 400px; }
    </style>
</head>
<body>

<div class="container">
    <a href="{{ route('user.dashboard') }}" class="back-btn"><i class="fa fa-arrow-left"></i> Kembali</a>

    <div class="vendor-header">
        @if(session('error'))
            <div style="background: #f8d7da; color: #721c24; padding: 15px; border-bottom: 3px solid #f5c6cb;">
                <i class="fa fa-exclamation-circle"></i> {{ session('error') }}
            </div>
        @endif
        @if(session('success'))
            <div style="background: #d4edda; color: #155724; padding: 15px; border-bottom: 3px solid #c3e6cb;">
                <i class="fa fa-check-circle"></i> {{ session('success') }}
            </div>
        @endif
        <div class="vendor-cover" style="position: relative;">
            @php
                $filename = \Illuminate\Support\Str::slug($vendor->nama_kantin) . '.jpg';
                $manualPath = 'img/' . $filename;
            @endphp

            @if($vendor->foto)
                 <img src="{{ asset('storage/'.$vendor->foto) }}" alt="" style="width:100%; height:100%; object-fit:cover;">
            @elseif(file_exists(public_path($manualPath)))
                 <img src="{{ asset($manualPath) }}" alt="" style="width:100%; height:100%; object-fit:cover;">
            @else
                 <img src="https://loremflickr.com/800/300/restaurant,kitchen?lock={{ $vendor->id }}" alt="" style="width:100%; height:100%; object-fit:cover;">
            @endif
        </div>
        <div class="vendor-info">
            <div style="display: flex; justify-content: space-between; align-items: center;">
                <h1 style="margin: 0;">{{ $vendor->nama_kantin }}</h1>
                <form action="{{ route('user.toggle-favorite') }}" method="POST">
                    @csrf
                    <input type="hidden" name="vendor_id" value="{{ $vendor->id }}">
                    <button type="submit" style="background: none; border: none; cursor: pointer; font-size: 24px; color: {{ $isFavorite ? '#dc3545' : '#ccc' }}; transition: 0.3s;">
                        <i class="{{ $isFavorite ? 'fa' : 'far' }} fa-heart"></i>
                    </button>
                </form>
            </div>
            <p>{{ $vendor->deskripsi }}</p>
            <div style="margin-top: 10px;">
                <span style="background: #e8f5e9; color: #28a745; padding: 3px 10px; border-radius: 15px; font-size: 12px; font-weight: 600;">BUKA</span>
                <span style="font-size: 12px; color: #666; margin-left: 10px;"><i class="fa fa-star" style="color: #f39c12;"></i> 4.8 (120+ rating)</span>
            </div>
        </div>
    </div>

    <h3 style="margin-bottom: 15px;">Daftar Menu</h3>

    @if($menus->isEmpty())
        <div style="text-align: center; padding: 40px; color: #888;">
            <i class="fa fa-bowl-rice fa-3x" style="margin-bottom: 15px; opacity: 0.3;"></i>
            <p>Belum ada menu tersedia saat ini.</p>
        </div>
    @else
        <div class="menu-grid">
            @foreach($menus as $menu)
            @php
                 $filename = \Illuminate\Support\Str::slug($menu->nama_makanan) . '.jpg';
                 $manualPath = 'img/menus/' . $filename;
                 
                 if($menu->foto) {
                     $bgImage = asset('storage/' . $menu->foto);
                 } elseif(file_exists(public_path($manualPath))) {
                     $bgImage = asset($manualPath);
                 } else {
                     $bgImage = 'https://loremflickr.com/400/300/food?lock=' . $menu->id;
                 }
            @endphp
            <div class="menu-card">
                <div class="menu-img" style="background-image: url('{{ $bgImage }}')"></div>
                <div class="menu-body">
                    <div class="menu-title">{{ $menu->nama_makanan }}</div>
                    <div class="menu-price">Rp {{ number_format($menu->harga, 0, ',', '.') }}</div>
                    <button class="btn-add" onclick="openModal('{{ $menu->id }}', '{{ $menu->nama_makanan }}', {{ $menu->harga }})">
                        <i class="fa fa-plus"></i> Pesan
                    </button>
                </div>
            </div>
            @endforeach
        </div>
    @endif
</div>

<!-- Modal Pemesanan -->
<div id="orderModal" class="modal">
    <div class="modal-content" style="max-width: 450px;">
        <div id="stepOrder">
            <h3 id="modalMenuName" style="border-bottom: 2px solid #eee; padding-bottom: 10px; margin-top: 0;">Nama Menu</h3>
            
            <div style="background: #f8f9fa; padding: 15px; border-radius: 10px; margin-bottom: 15px;">
                <p style="margin: 0; color: #666; font-size: 13px;">Harga Satuan</p>
                <p id="modalMenuPrice" style="color: var(--primary); font-weight: bold; margin: 0; font-size: 18px;">Rp 0</p>
            </div>
            
            <form action="{{ route('user.cart.add') }}" method="POST" id="orderForm">
                @csrf
                <input type="hidden" name="menu_id" id="modalMenuId">
                
                <div style="margin-bottom: 20px;">
                    <label style="display: block; margin-bottom: 8px; font-size: 14px; font-weight: 600;">Jumlah Porsi</label>
                    <div style="display: flex; align-items: center; gap: 10px;">
                        <button type="button" onclick="adjustQty(-1)" style="width: 40px; height: 40px; border: 1px solid #ddd; background: white; border-radius: 8px; font-size: 18px;">-</button>
                        <input type="number" name="jumlah" id="qtyInput" value="1" min="1" max="100" style="flex: 1; height: 40px; text-align: center; border: 1px solid #ddd; border-radius: 8px; font-weight: bold;" oninput="updateTotal()">
                        <button type="button" onclick="adjustQty(1)" style="width: 40px; height: 40px; border: 1px solid #ddd; background: white; border-radius: 8px; font-size: 18px;">+</button>
                    </div>
                </div>

                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; font-weight: bold;">
                    <span>Total Pembayaran:</span>
                    <span id="grandTotal" style="color: var(--primary); font-size: 20px;">Rp 0</span>
                </div>

                <div style="display: flex; gap: 10px;">
                    <button type="button" onclick="closeModal()" style="flex: 1; padding: 12px; background: #eee; border: none; border-radius: 8px; cursor: pointer; font-weight: 600;">Batal</button>
                    <button type="submit" style="flex: 1; padding: 12px; background: var(--primary); color: white; border: none; border-radius: 8px; cursor: pointer; font-weight: 600;">
                        <i class="fa fa-cart-plus"></i> Masuk Keranjang
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
        <div id="stepQris" style="display: none; text-align: center;">
            <h3 style="margin-top: 0;">Scan QRIS untuk Bayar</h3>
            <p style="color: #666; font-size: 13px;">Silakan scan QR Code di bawah ini menggunakan aplikasi e-wallet Anda.</p>
            
            <div style="background: white; padding: 20px; border: 2px dashed #ddd; border-radius: 15px; display: inline-block; margin: 10px 0;">
                <img src="https://upload.wikimedia.org/wikipedia/commons/d/d0/QR_code_for_mobile_English_Wikipedia.svg" alt="QRIS" style="width: 200px; height: 200px;">
                <p style="margin: 10px 0 0 0; font-weight: bold;">SCAN QRIS</p>
            </div>

            <p id="qrisTotal" style="font-size: 20px; font-weight: bold; color: var(--primary); margin: 10px 0;">Rp 0</p>

            <button type="button" onclick="submitOrder()" style="width: 100%; padding: 14px; background: #28a745; color: white; border: none; border-radius: 8px; cursor: pointer; font-weight: 600; font-size: 16px;">
                <i class="fa fa-check-circle"></i> Saya Sudah Bayar
            </button>
            <button type="button" onclick="backToOrder()" style="margin-top: 10px; background: none; border: none; color: #888; cursor: pointer; text-decoration: underline;">Kembali ke pesanan</button>
        </div>
    </div>
</div>

<script>
    let currentPrice = 0;

    function openModal(id, name, price) {
        document.getElementById('orderModal').style.display = 'flex';
        document.getElementById('modalMenuId').value = id;
        document.getElementById('modalMenuName').innerText = name;
        document.getElementById('modalMenuPrice').innerText = formatRupiah(price);
        
        document.getElementById('qtyInput').value = 1;
        currentPrice = price;
        updateTotal();
    }
    
    function closeModal() {
        document.getElementById('orderModal').style.display = 'none';
    }
    
    function adjustQty(change) {
        const input = document.getElementById('qtyInput');
        let newVal = parseInt(input.value) + change;
        if(newVal < 1) newVal = 1;
        if(newVal > 100) newVal = 100;
        input.value = newVal;
        updateTotal();
    }

    function updateTotal() {
        const qty = parseInt(document.getElementById('qtyInput').value);
        const total = qty * currentPrice;
        document.getElementById('grandTotal').innerText = formatRupiah(total);
    }

    function formatRupiah(num) {
        return 'Rp ' + new Intl.NumberFormat('id-ID').format(num);
    }
    
    // Close modal when clicking outside
    window.onclick = function(event) {
        if (event.target == document.getElementById('orderModal')) {
            closeModal();
        }
    }
</script>

</body>
</html>
