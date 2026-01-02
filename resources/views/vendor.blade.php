<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Vendor Kantin UP</title>
</head>
<body>

<h1>Daftar Vendor Kantin Universitas Pertamina</h1>

@if($vendors->count() == 0)
    <p>Belum ada vendor tersedia.</p>
@else
    <ul>
        @foreach($vendors as $v)
            <li>
                <strong>{{ $v->nama_vendor }}</strong> <br>
                Kategori: {{ $v->kategori }} <br>
                <a href="/menu/{{ $v->id }}">Lihat Menu</a>
            </li>
            <hr>
        @endforeach
    </ul>
@endif

</body>
</html>
