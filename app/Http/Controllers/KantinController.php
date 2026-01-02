<?php

namespace App\Http\Controllers;

use App\Models\Vendor;
use App\Models\Menu;
use App\Models\Order;
use Illuminate\Http\Request;

class KantinController extends Controller
{
    public function vendor()
    {
        $vendors = Vendor::all();
        return view('vendor', compact('vendors'));
    }

    public function menu($id)
    {
        $menus = Menu::where('vendor_id', $id)->get();
        return view('menu', compact('menus'));
    }

    public function pesan(Request $request)
    {
        Order::create([
            'nama_pembeli' => $request->nama_pembeli,
            'menu_id' => $request->menu_id,
            'jumlah' => $request->jumlah,
            'status' => 'Menunggu'
        ]);

        return redirect('/pesanan');
    }

    public function pesanan()
    {
        $orders = Order::all();
        return view('pesanan', compact('orders'));
    }
}
