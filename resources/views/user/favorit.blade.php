<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kantin Favorit - SIUP</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        :root { --primary: #0047ba; --secondary: #00a1e4; --success: #28a745; }
        body { font-family: 'Poppins', sans-serif; margin: 0; background-color: #f8f9fa; color: #333; }
        
        .sidebar { 
            width: 280px; position: fixed; height: 100vh; background: white; 
            border-right: 1px solid #eee; padding: 20px; box-sizing: border-box; 
            display: flex; flex-direction: column; 
        }
        .sidebar-nav { flex-grow: 1; margin-top: 20px; }
        .menu-item { display: flex; align-items: center; padding: 12px 15px; border-radius: 10px; text-decoration: none; color: #555; margin-bottom: 5px; transition: 0.3s; }
        .menu-item:hover, .menu-item.active { background: var(--primary); color: white; }
        .menu-item i { margin-right: 15px; width: 20px; }
        .btn-logout { background: none; border: none; color: #dc3545; cursor: pointer; font-family: inherit; font-weight: 600; padding: 15px 10px; width: 100%; text-align: left; display: flex; align-items: center; gap: 15px; }
        
        .main-content { margin-left: 280px; padding: 30px; }
        
        .canteen-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 20px; }
        .canteen-card { background: white; border-radius: 15px; overflow: hidden; box-shadow: 0 4px 15px rgba(0,0,0,0.05); transition: 0.3s; cursor: pointer; border: 1px solid transparent; position: relative; }
        .canteen-card:hover { transform: translateY(-5px); border-color: var(--secondary); }
        .canteen-img { height: 160px; background: #ddd; background-size: cover; background-position: center; }
        .canteen-info { padding: 20px; }
        .canteen-info h3 { margin: 0 0 10px 0; font-size: 18px; }
        
        .remove-fav {
            position: absolute; top: 10px; right: 10px; background: rgba(255,255,255,0.9);
            width: 35px; height: 35px; border-radius: 50%; display: flex; align-items: center; justify-content: center;
            color: #dc3545; cursor: pointer; border: none; font-size: 16px;
        }
    </style>
</head>
<body>

    <div class="sidebar">
        <div style="text-align: center; margin-bottom: 30px;">
            <img src="https://sso.universitaspertamina.ac.id/images/logo.png" alt="Logo" style="width: 180px;">
        </div>
        <div class="sidebar-nav">
            <a href="{{ route('user.dashboard') }}" class="menu-item"><i class="fa fa-home"></i> Beranda</a>
            <a href="{{ route('user.cart') }}" class="menu-item"><i class="fa fa-shopping-cart"></i> Keranjang</a>
            <a href="{{ route('user.favorit') }}" class="menu-item active"><i class="fa fa-utensils"></i> Kantin Favorit</a>
            <a href="{{ route('user.history') }}" class="menu-item"><i class="fa fa-history"></i> Riwayat Pesanan</a>
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
        <h2 style="margin-bottom: 30px;">Kantin Favorit Anda</h2>

        @if(session('success'))
            <div style="background: #d4edda; color: #155724; padding: 15px; border-radius: 10px; margin-bottom: 20px;">
                <i class="fa fa-check-circle"></i> {{ session('success') }}
            </div>
        @endif

        @if($favorites->isEmpty())
            <div style="text-align: center; padding: 50px; color: #888;">
                <i class="fa fa-heart-broken fa-4x" style="margin-bottom: 20px; opacity: 0.2;"></i>
                <p>Belum ada kantin favorit. Jelajahi kantin dan tekan ikon hati!</p>
                <a href="{{ route('user.dashboard') }}" style="color: var(--primary); text-decoration: none; font-weight: 600;">Cari Kantin</a>
            </div>
        @else
            <div class="canteen-grid">
                @foreach($favorites as $fav)
                    @php 
                        $v = $fav->vendor; 
                        
                        $filename = \Illuminate\Support\Str::slug($v->nama_kantin) . '.jpg';
                        $manualPath = 'img/' . $filename;
                        
                        if($v->foto) {
                            $bgImage = asset('storage/' . $v->foto);
                        } elseif(file_exists(public_path($manualPath))) {
                            $bgImage = asset($manualPath);
                        } else {
                            $bgImage = 'https://loremflickr.com/400/200/restaurant,kitchen?lock=' . $v->id;
                        }
                    @endphp
                    <div class="canteen-card" onclick="location.href='{{ route('user.kantin', $v->id) }}'">
                        <div class="canteen-img" style="background-image: url('{{ $bgImage }}')"></div>
                        
                        <!-- Tombol Hapus Favorit -->
                        <form action="{{ route('user.toggle-favorite') }}" method="POST" onclick="event.stopPropagation();">
                            @csrf
                            <input type="hidden" name="vendor_id" value="{{ $v->id }}">
                            <button type="submit" class="remove-fav" title="Hapus dari favorit">
                                <i class="fa fa-heart"></i>
                            </button>
                        </form>

                        <div class="canteen-info">
                            <h3>{{ $v->nama_kantin }}</h3>
                            <p style="color: #777; font-size: 14px; margin-bottom: 15px;">{{ $v->deskripsi }}</p>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>

</body>
</html>
