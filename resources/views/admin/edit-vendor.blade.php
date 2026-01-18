<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Vendor - Admin Dashboard</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        :root { --primary: #0b132b; --secondary: #1c2541; --highlight: #3a506b; }
        body { font-family: 'Poppins', sans-serif; margin: 0; background-color: #f4f7f6; display: flex; }

        /* Sidebar */
        .sidebar { width: 280px; position: fixed; height: 100vh; background: var(--primary); color: white; padding: 20px; box-sizing: border-box; display: flex; flex-direction: column; }
        .logo-section { text-align: center; margin-bottom: 30px; background: white; padding: 10px; border-radius: 10px; }
        .logo-section img { width: 140px; }
        .menu-item { display: flex; align-items: center; padding: 12px 15px; border-radius: 10px; text-decoration: none; color: #a0aec0; margin-bottom: 5px; transition: 0.3s; }
        .menu-item:hover, .menu-item.active { background: var(--highlight); color: white; }
        .menu-item i { margin-right: 15px; width: 20px; }

        /* Main Content */
        .main-content { margin-left: 280px; flex: 1; padding: 30px; }
        .header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px; border-bottom: 2px solid #ddd; padding-bottom: 15px; }

        .form-container { background: white; padding: 30px; border-radius: 15px; box-shadow: 0 4px 6px rgba(0,0,0,0.05); max-width: 600px; }
        .form-group { margin-bottom: 20px; }
        .form-group label { display: block; margin-bottom: 5px; font-weight: 600; color: #333; }
        .form-control { width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 8px; font-size: 14px; }
        .form-control:focus { outline: none; border-color: #007bff; box-shadow: 0 0 0 2px rgba(0,123,255,0.25); }
        .checkbox-group { display: flex; align-items: center; }
        .checkbox-group input { margin-right: 10px; }
        .btn { padding: 12px 24px; border: none; border-radius: 8px; cursor: pointer; font-weight: 600; text-decoration: none; display: inline-block; }
        .btn-primary { background: #007bff; color: white; }
        .btn-secondary { background: #6c757d; color: white; margin-left: 10px; }
        .btn:hover { opacity: 0.9; }
        .alert { padding: 15px; border-radius: 8px; margin-bottom: 20px; }
        .alert-success { background: #d4edda; color: #155724; border: 1px solid #c3e6cb; }
        .alert-error { background: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; }
        .btn-logout { background: none; border: none; color: #ff4d4d; cursor: pointer; font-weight: 600; margin-top: auto; padding: 10px; text-align: left; }
    </style>
</head>
<body>

    <div class="sidebar">
        <div class="logo-section"><img src="https://sso.universitaspertamina.ac.id/images/logo.png" alt="Logo"></div>
        <a href="{{ route('admin.dashboard') }}" class="menu-item"><i class="fa fa-chart-line"></i> Ringkasan Sistem</a>
        <a href="{{ route('admin.kelola-vendor') }}" class="menu-item active"><i class="fa fa-store"></i> Kelola Vendor</a>
        <a href="{{ route('admin.laporan-transaksi') }}" class="menu-item"><i class="fa fa-file-invoice-dollar"></i> Laporan Transaksi</a>

        <form action="{{ route('logout') }}" method="POST" style="margin-top: auto;">
            @csrf
            <button type="submit" class="btn-logout"><i class="fa fa-power-off"></i> Shutdown Session</button>
        </form>
    </div>

    <div class="main-content">
        <div class="header">
            <h2 style="margin:0">Edit Vendor: {{ $vendor->nama_kantin }}</h2>
        </div>

        <div class="form-container">
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            @if($errors->any())
                <div class="alert alert-error">
                    <ul style="margin:0; padding-left:20px;">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('admin.kelola-vendor.update', $vendor) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="form-group">
                    <label for="nama_kantin">Nama Kantin</label>
                    <input type="text" class="form-control" id="nama_kantin" name="nama_kantin" value="{{ $vendor->nama_kantin }}" required>
                </div>

                <div class="form-group">
                    <label for="deskripsi">Deskripsi Kantin</label>
                    <textarea class="form-control" id="deskripsi" name="deskripsi" rows="3" required>{{ $vendor->deskripsi }}</textarea>
                </div>

                <div class="form-group">
                    <label class="checkbox-group">
                        <input type="checkbox" id="is_open" name="is_open" value="1" {{ $vendor->is_open ? 'checked' : '' }}>
                        Kantin sedang buka
                    </label>
                </div>

                <div style="margin-top: 30px;">
                    <button type="submit" class="btn btn-primary">
                        <i class="fa fa-save"></i> Update Vendor
                    </button>
                    <a href="{{ route('admin.kelola-vendor') }}" class="btn btn-secondary">
                        <i class="fa fa-arrow-left"></i> Kembali
                    </a>
                </div>
            </form>
        </div>
    </div>
</body>
</html>