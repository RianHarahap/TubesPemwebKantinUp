<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Menu Kantin</title>
</head>
<body>

<h1>Daftar Menu</h1>
<a href="/">← Kembali ke Vendor</a>
<br><br>

@if($menus->count() == 0)
    <p>Menu belum tersedia.</p>
@else
    @foreach($menus as $m)
        <div style="border:1px solid #000; padding:10px; margin-bottom:10px;">
            <h3>{{ $m->nama_menu }}</h3>
            <p>Harga: Rp {{ number_format($m->harga) }}</p>
            <p>Estimasi: {{ $m->estimasi_menit }} menit</p>

            <form method="POST" action="/pesan">
                @csrf

                <input type="hidden" name="menu_id" value="{{ $m->id }}">

                <label>Nama Pembeli:</label><br>
                <input type="text" name="nama_pembeli" required><br><br>

                <label>Jumlah:</label><br>
                <input type="number" name="jumlah" min="1" required><br><br>

                <button type="submit">Pesan Sekarang</button>
            </form>
        </div>
    @endforeach
@endif

</body>
</html>
