<?php

namespace App\Http\Controllers\Penjual;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Support\Facades\Auth;

class SaldoController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:penjual']);
    }

    public function index()
    {
        $vendorId = Auth::user()?->vendor_id;
        
        if (!$vendorId) {
            return back()->withErrors(['vendor' => 'Akun penjual belum terhubung dengan vendor.']);
        }

        // Total Pendapatan dari pesanan yang sudah selesai
        $totalPendapatan = Order::where('vendor_id', $vendorId)
                                ->where('status', 'selesai')
                                ->sum('total_harga');

        // Riwayat Pesanan yang Selesai
        $riwayatPesanan = Order::where('vendor_id', $vendorId)
                               ->where('status', 'selesai')
                               ->orderBy('updated_at', 'desc')
                               ->get();

        // Statistik Pendapatan
        $pendapatanHariIni = Order::where('vendor_id', $vendorId)
                                  ->where('status', 'selesai')
                                  ->whereDate('updated_at', today())
                                  ->sum('total_harga');

        $pendapatanBulanIni = Order::where('vendor_id', $vendorId)
                                   ->where('status', 'selesai')
                                   ->whereYear('updated_at', date('Y'))
                                   ->whereMonth('updated_at', date('m'))
                                   ->sum('total_harga');

        $totalTransaksi = Order::where('vendor_id', $vendorId)
                               ->where('status', 'selesai')
                               ->count();

        return view('penjual.saldo', compact(
            'totalPendapatan',
            'riwayatPesanan',
            'pendapatanHariIni',
            'pendapatanBulanIni',
            'totalTransaksi'
        ));
    }

    public function detail($orderId)
    {
        $vendorId = Auth::user()?->vendor_id;

        $order = Order::where('id', $orderId)
                      ->where('vendor_id', $vendorId)
                      ->where('status', 'selesai')
                      ->with(['user', 'menu'])
                      ->first();

        if (!$order) {
            return back()->withErrors(['order' => 'Pesanan tidak ditemukan.']);
        }

        return view('penjual.saldo-detail', compact('order'));
    }
}
