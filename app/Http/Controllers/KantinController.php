<?php

namespace App\Http\Controllers;

use App\Models\Vendor;
use App\Models\Menu;
use App\Models\Order;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
        // Validasi input
        $request->validate([
            'menu_id' => 'required|exists:menus,id',
            'jumlah' => 'required|integer|min:1',
        ]);
        
        $menu = Menu::findOrFail($request->menu_id);
        $totalHarga = $menu->harga * $request->jumlah;
        $user = Auth::user();

        // Generate Nomor Antrean (Simple: A-001)
        $todayOrders = Order::whereDate('created_at', today())->count();
        $nomorAntrean = 'A-' . str_pad($todayOrders + 1, 3, '0', STR_PAD_LEFT);

        // Generate QRIS Code (simplified - bisa diganti dengan API QRIS sebenarnya)
        // Format: QRIS-ORDERID-TIMESTAMP
        $qrisCode = 'QRIS-' . strtoupper(uniqid()) . '-' . time();
        
        // QRIS expired dalam 5 menit
        $paymentExpiredAt = now()->addMinutes(5);

        $order = Order::create([
            'user_id' => $user->id, 
            'nama_pembeli' => $user->name, 
            'vendor_id' => $menu->vendor_id, 
            'menu_id' => $request->menu_id,
            'menu_name' => $menu->nama_makanan, 
            'jumlah' => $request->jumlah,
            'harga_satuan' => $menu->harga, 
            'total_harga' => $totalHarga,
            'status' => 'menunggu', 
            'nomor_antrean' => $nomorAntrean,
            'payment_status' => 'pending',
            'qris_code' => $qrisCode,
            'payment_expired_at' => $paymentExpiredAt
        ]);

        // Log Aktivitas: Mahasiswa membuat pesanan
        ActivityLog::log('buat_pesanan', "Mahasiswa membuat pesanan: {$menu->nama_makanan} (x{$request->jumlah})", $user->id);

        // Redirect ke halaman pembayaran QRIS
        return redirect()->route('user.payment-qris', $order->id)->with('success', 'Pesanan berhasil dibuat! Silakan selesaikan pembayaran.');
    }

    public function pesanan()
    {
        // Ambil semua order milik user
        $orders = Order::where('user_id', Auth::id())
                       ->with(['menu', 'vendor', 'transaction'])
                       ->orderBy('created_at', 'desc')
                       ->get();

        // Kelompokkan berdasarkan order_group_id
        // Jika order_group_id null (transaksi lama/single), gunakan ID order itu sendiri sebagai key
        $groupedOrders = $orders->groupBy(function ($item) {
            return $item->order_group_id ?? ('SINGLE-' . $item->id);
        });
                       
        return view('pesanan', compact('groupedOrders'));
    }

    public function showPaymentQris($orderId)
    {
        $order = Order::where('id', $orderId)
                      ->where('user_id', Auth::id())
                      ->with('vendor')
                      ->firstOrFail();

        // Cek jika sudah expired atau sudah dibayar
        if ($order->payment_status === 'paid') {
            return redirect()->route('user.history')->with('info', 'Pesanan ini sudah dibayar.');
        }

        if ($order->payment_status === 'expired' || now()->greaterThan($order->payment_expired_at)) {
            $order->update(['payment_status' => 'expired']);
            return redirect()->route('user.history')->with('error', 'Pembayaran QRIS telah kadaluarsa.');
        }

        return view('user.payment-qris', compact('order'));
    }

    public function confirmPayment($orderId)
    {
        $order = Order::where('id', $orderId)
                      ->where('user_id', Auth::id())
                      ->firstOrFail();

        if ($order->payment_status !== 'pending') {
            return back()->with('error', 'Pesanan ini tidak dapat dikonfirmasi.');
        }

        // Update payment status
        $order->update(['payment_status' => 'paid']);

        ActivityLog::log('konfirmasi_pembayaran', "Mahasiswa mengkonfirmasi pembayaran pesanan #{$order->id}", Auth::id());

        return redirect()->route('user.history')->with('success', 'Pembayaran berhasil dikonfirmasi! Pesanan Anda sedang diproses.');
    }
}
