<?php

namespace App\Http\Controllers\Penjual;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:penjual']);
    }

    protected function vendorId()
    {
        $user = Auth::user();
        return $user?->vendor_id;
    }

    // Tampilkan semua pesanan untuk vendor ini
    public function index()
    {
        $vendorId = $this->vendorId();
        if (!$vendorId) {
            return back()->withErrors(['vendor' => 'Akun penjual belum terhubung dengan vendor.']);
        }

        // Ambil semua order untuk vendor ini, diurutkan dari terbaru
        $orders = Order::where('vendor_id', $vendorId)
                    ->orderBy('created_at', 'desc')
                    ->get();

        // Grouping berdasarkan order_group_id atau id (jika single)
        // Kuncinya: order_group_id agar menu yang dibeli bersamaan jadi satu
        $groupedOrders = $orders->groupBy(function($item) {
             return $item->order_group_id ?? 'SINGLE-'.$item->id;
        });

        // Pagination manual jika diperlukan (Collection Pagination)
        // Untuk sederhananya kita kirim collection groups
        
        return view('penjual.orders.index', compact('groupedOrders'));
    }

    // Tampilkan detail pesanan
    public function show($id)
    {
        $vendorId = $this->vendorId();
        if (!$vendorId) {
            return back()->withErrors(['vendor' => 'Akun penjual belum terhubung dengan vendor.']);
        }

        $order = Order::find($id);
        
        if (!$order || $order->vendor_id !== $vendorId) {
            abort(403, 'Anda tidak berhak mengakses pesanan ini');
        }

        // Ambil teman-teman satu grup order (jika ada) yang juga milik vendor ini
        // Jika order_group_id null, maka hanya dia sendiri
        $groupItems = collect([$order]);

        if ($order->order_group_id) {
            $groupItems = Order::where('order_group_id', $order->order_group_id)
                               ->where('vendor_id', $vendorId)
                               ->get();
        }

        // Hitung total harga untuk item-item yang masuk ke vendor ini saja
        $totalVendorPrice = $groupItems->sum('total_harga');

        return view('penjual.orders.show', compact('order', 'groupItems', 'totalVendorPrice'));
    }

    // Update status pesanan
    public function updateStatus(Request $request, $id)
    {
        $vendorId = $this->vendorId();
        if (!$vendorId) {
            return back()->withErrors(['vendor' => 'Akun penjual belum terhubung dengan vendor.']);
        }

        $order = Order::find($id);
        
        if (!$order || $order->vendor_id !== $vendorId) {
            abort(403, 'Anda tidak berhak mengubah status pesanan ini');
        }

        $request->validate([
            'status' => 'required|in:menunggu,dimasak,siap,selesai,dibatalkan'
        ]);

        $order->status = $request->status;
        $order->save();

        // Log Aktivitas
        ActivityLog::log('update_status_pesanan', "Penjual memperbarui status pesanan #{$order->id} menjadi " . ucfirst($request->status));

        return back()->with('success', 'Status pesanan berhasil diperbarui menjadi ' . ucfirst($request->status));
    }
}
