<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Status Pesanan</title>
</head>
<body>

<h1>Status Pesanan</h1>
<a href="/">← Kembali ke Vendor</a>
<br><br>

@if($orders->count() == 0)
    <p>Belum ada pesanan.</p>
@else
    <table border="1" cellpadding="10">
        <tr>
            <th>Nama Pembeli</th>
            <th>Menu</th>
            <th>Jumlah</th>
            <th>Status</th>
        </tr>

        @foreach($orders as $o)
        <tr>
            <td>{{ $o->nama_pembeli }}</td>
            <td>{{ $o->menu->nama_menu }}</td>
            <td>{{ $o->jumlah }}</td>
            <td>{{ $o->status }}</td>
        </tr>
        @endforeach
    </table>
@endif

</body>
</html>
