@extends('penjual.dashboard')

@section('content')
    <style>
        .page-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px; }
        .menu-card { background: white; border-radius: 12px; box-shadow: 0 2px 8px rgba(0,0,0,0.08); overflow: hidden; transition: all 0.3s ease; margin-bottom: 20px; }
        .menu-card:hover { transform: translateY(-4px); box-shadow: 0 4px 12px rgba(0,0,0,0.12); }
        .menu-card-img { width: 100%; height: 180px; object-fit: cover; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); }
        .menu-card-body { padding: 20px; }
        .menu-card-title { font-size: 18px; font-weight: 600; color: #0047ba; margin-bottom: 8px; }
        .menu-price { font-size: 20px; font-weight: bold; color: #28a745; margin: 10px 0; }
        .menu-vendor { font-size: 12px; color: #888; text-transform: uppercase; letter-spacing: 0.5px; }
        .status-badge { display: inline-block; padding: 4px 12px; border-radius: 20px; font-size: 11px; font-weight: 600; }
        .status-available { background: #d4edda; color: #155724; }
        .status-unavailable { background: #f8d7da; color: #721c24; }
        .menu-actions { display: flex; gap: 8px; margin-top: 15px; }
        .menu-actions a, .menu-actions button { flex: 1; font-size: 12px; }
        .add-btn { display: inline-flex; align-items: center; gap: 8px; padding: 12px 24px; background: linear-gradient(to right, #0047ba, #00a1e4); color: white; text-decoration: none; border-radius: 8px; border: none; cursor: pointer; font-weight: 600; transition: 0.3s; }
        .add-btn:hover { transform: translateY(-2px); box-shadow: 0 4px 12px rgba(0,71,186,0.3); }
        .empty-state { text-align: center; padding: 60px 20px; }
        .empty-state-icon { font-size: 48px; color: #ccc; margin-bottom: 15px; }
        .empty-state-text { color: #999; font-size: 16px; }
        .vendor-info { background: #f0f7ff; padding: 15px 20px; border-radius: 8px; margin-bottom: 25px; border-left: 4px solid #0047ba; }
        .vendor-info strong { color: #0047ba; }
    </style>

    <div class="page-header">
        <div>
            <h2 style="margin:0; color:#0047ba"><i class="fa fa-utensils" style="margin-right:10px"></i>Kelola Menu</h2>
            @if(Auth::user()->vendor)
                <div class="vendor-info" style="margin-top:15px;margin-bottom:0">
                    <i class="fa fa-store"></i> <strong>{{ Auth::user()->vendor->nama_kantin }}</strong>
                </div>
            @endif
        </div>
        <a href="{{ url('/penjual/menus/create') }}" class="add-btn">
            <i class="fa fa-plus-circle"></i> Tambah Menu
        </a>
    </div>

    @if(session('success'))
        <div style="background:#d4edda; border:1px solid #c3e6cb; color:#155724; padding:15px; border-radius:8px; margin-bottom:20px; display:flex; align-items:center; gap:10px">
            <i class="fa fa-check-circle"></i> {{ session('success') }}
        </div>
    @endif

    <div style="display:grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap:24px; margin-top:30px">
        @forelse($menus ?? [] as $menu)
            <div class="menu-card">
                @if(!empty($menu->foto))
                    <img src="{{ asset('storage/'.$menu->foto) }}" alt="{{ $menu->nama_makanan }}" class="menu-card-img">
                @else
                    <div class="menu-card-img" style="display:flex; align-items:center; justify-content:center; background:linear-gradient(135deg, #667eea 0%, #764ba2 100%)">
                        <i class="fa fa-utensils" style="font-size:48px; color:white; opacity:0.6"></i>
                    </div>
                @endif
                <div class="menu-card-body">
                    <p class="menu-vendor">{{ $menu->vendor?->nama_kantin ?? '-' }}</p>
                    <h3 class="menu-card-title">{{ $menu->nama_makanan }}</h3>
                    <p class="menu-price">Rp {{ isset($menu->harga) ? number_format($menu->harga,0,',','.') : '-' }}</p>
                    <p style="font-size:13px; color:#666; margin:10px 0">{{ Str::limit($menu->deskripsi, 60, '...') }}</p>
                    <span class="status-badge {{ !empty($menu->tersedia) ? 'status-available' : 'status-unavailable' }}">
                        <i class="fa {{ !empty($menu->tersedia) ? 'fa-check-circle' : 'fa-ban' }}"></i>
                        {{ !empty($menu->tersedia) ? 'Tersedia' : 'Tidak Tersedia' }}
                    </span>
                    <div class="menu-actions">
                        <a href="{{ url('/penjual/menus/'.$menu->id) }}" class="btn btn-sm" style="background:#e7f3ff; color:#0047ba; border:1px solid #b3d9ff">
                            <i class="fa fa-eye"></i> Lihat
                        </a>
                        <a href="{{ url('/penjual/menus/'.$menu->id.'/edit') }}" class="btn btn-sm" style="background:#fff3cd; color:#856404; border:1px solid #ffeaa7">
                            <i class="fa fa-edit"></i> Edit
                        </a>
                        <form action="{{ url('/penjual/menus/'.$menu->id) }}" method="POST" style="flex:1">
                            @csrf
                            @method('DELETE')
                            <button type="button" class="btn btn-sm" style="width:100%; background:#ffe6e6; color:#c0392b; border:1px solid #ffcccc" onclick="if(confirm('Hapus menu ini?')) this.form.submit()">
                                <i class="fa fa-trash"></i> Hapus
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        @empty
            <div style="grid-column:1/-1">
                <div class="empty-state">
                    <div class="empty-state-icon"><i class="fa fa-inbox"></i></div>
                    <div class="empty-state-text">Belum ada menu, silakan <a href="{{ url('/penjual/menus/create') }}" style="color:#0047ba; font-weight:600">tambah menu</a></div>
                </div>
            </div>
        @endforelse
    </div>
@endsection
