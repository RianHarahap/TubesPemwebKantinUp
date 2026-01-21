<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Menu;
use App\Models\CartItem;
use App\Models\Order;
use App\Models\Transaction;
use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class CartController extends Controller
{
    public function index()
    {
        $cartItems = CartItem::where('user_id', Auth::id())
                             ->with(['menu.vendor'])
                             ->get();
        
        return view('user.cart', compact('cartItems'));
    }

    public function addToCart(Request $request)
    {
        $request->validate([
            'menu_id' => 'required|exists:menus,id',
            'jumlah' => 'required|integer|min:1',
        ]);

        $item = CartItem::where('user_id', Auth::id())
                        ->where('menu_id', $request->menu_id)
                        ->first();

        if ($item) {
            $item->quantity += $request->jumlah;
            $item->save();
        } else {
            CartItem::create([
                'user_id' => Auth::id(),
                'menu_id' => $request->menu_id,
                'quantity' => $request->jumlah
            ]);
        }

        return redirect()->route('user.cart')->with('success', 'Menu berhasil ditambahkan ke keranjang.');
    }

    public function updateCart(Request $request, $id)
    {
        $request->validate(['quantity' => 'required|integer|min:1']);
        
        $item = CartItem::where('user_id', Auth::id())->findOrFail($id);
        $item->quantity = $request->quantity;
        $item->save();

        return back()->with('success', 'Keranjang diperbarui.');
    }

    public function removeFromCart($id)
    {
        CartItem::where('user_id', Auth::id())->where('id', $id)->delete();
        return back()->with('success', 'Menu dihapus dari keranjang.');
    }

    public function checkout()
    {
        $user = Auth::user();
        $cartItems = CartItem::where('user_id', $user->id)->with('menu')->get();

        if ($cartItems->isEmpty()) {
            return back()->with('error', 'Keranjang belanja kosong.');
        }

        // Generate Transaction Info
        $orderGroupId = 'TRX-' . strtoupper(uniqid()) . '-' . time();
        $qrisCode = 'QRIS-' . $orderGroupId;
        $expiredAt = now()->addMinutes(5);
        $totalAmount = 0;

        // Group items by Vendor
        $itemsByVendor = $cartItems->groupBy(function($item) {
            return $item->menu->vendor_id;
        });

        foreach ($itemsByVendor as $vendorId => $items) {
            foreach ($items as $item) {
                $subtotal = $item->menu->harga * $item->quantity;
                $totalAmount += $subtotal;

                // Create Order per Item (or per Vendor?)
                // Current system structure is per Item/Menu usually in simple order systems,
                // but let's see KantinController logic. 
                // User bought 1 menu -> 1 Order record.
                // So if User buys 3 menus from same vendor, it should be 3 Order records or 1 Order with many items?
                // The current `Order` table has `menu_id`, `menu_name`, `jumlah`.
                // This means `Order` = `OrderItem`.
                // So we create 1 Order record for EACH cart item.
                
                // Generate nomor antrean per ORDER/ITEM ???
                // Usually Queue Number is per Vendor per User session.
                // Let's simplify: Each Order record gets a queue number.

                $todayOrders = Order::whereDate('created_at', today())->count();
                $nomorAntrean = 'A-' . str_pad($todayOrders + 1, 3, '0', STR_PAD_LEFT);

                Order::create([
                    'user_id' => $user->id,
                    'order_group_id' => $orderGroupId,
                    'vendor_id' => $vendorId,
                    'menu_id' => $item->menu_id,
                    'menu_name' => $item->menu->nama_makanan,
                    'jumlah' => $item->quantity,
                    'harga_satuan' => $item->menu->harga,
                    'total_harga' => $subtotal,
                    'status' => 'menunggu',
                    'nomor_antrean' => $nomorAntrean,
                    'nama_pembeli' => $user->name,
                    'payment_status' => 'pending',
                     // All orders share the same QRIS info for now, or null if we use Transaction
                    'qris_code' => $qrisCode, 
                    'payment_expired_at' => $expiredAt
                ]);
            }
        }

        // Create Transaction Record
        $transaction = Transaction::create([
            'user_id' => $user->id,
            'order_group_id' => $orderGroupId,
            'total_amount' => $totalAmount,
            'qris_code' => $qrisCode,
            'payment_status' => 'pending',
            'expired_at' => $expiredAt
        ]);

        // Clear Cart
        CartItem::where('user_id', $user->id)->delete();

        ActivityLog::log('checkout', "User checkout keranjang dengan Total Rp " . number_format($totalAmount));

        return redirect()->route('user.transaction', $transaction->id);
    }
    
    public function showTransaction($id)
    {
        $transaction = Transaction::where('id', $id)
                                  ->where('user_id', Auth::id())
                                  ->firstOrFail();

        // Check expiry
        if ($transaction->payment_status === 'pending' && now()->greaterThan($transaction->expired_at)) {
            $transaction->update(['payment_status' => 'expired']);
            // Update all related orders
            Order::where('order_group_id', $transaction->order_group_id)->update(['payment_status' => 'expired']);
        }

        // Get linked orders for display
        $orders = Order::where('order_group_id', $transaction->order_group_id)->with('menu')->get();
        
        return view('user.transaction', compact('transaction', 'orders'));
    }

    public function confirmTransaction($id)
    {
        $transaction = Transaction::where('id', $id)
                                  ->where('user_id', Auth::id())
                                  ->firstOrFail();

        if ($transaction->payment_status !== 'pending') {
            return back()->with('error', 'Transaksi tidak dapat dikonfirmasi.');
        }

        $transaction->update(['payment_status' => 'paid']);
        
        // Update all related orders
        Order::where('order_group_id', $transaction->order_group_id)->update(['payment_status' => 'paid']);

        ActivityLog::log('bayar_transaksi', "Pembayaran transaksi #{$transaction->id} berhasil dikonfirmasi", Auth::id());

        return redirect()->route('user.history')->with('success', 'Pembayaran berhasil! Semua pesanan sedang diproses.');
    }
}
