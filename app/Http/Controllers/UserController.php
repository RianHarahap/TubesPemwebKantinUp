<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Vendor;
use App\Models\Menu;
use App\Models\Favorite;
use App\Models\Order;
use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    /**
     * Tampilkan halaman Home user (Daftar Kantin)
     */
    public function index()
    {
        // 1. Ambil data vendor REAL dari database agar sinkron dengan Admin & Penjual
        // Hanya vendor yang statusnya BUKA (is_open = true)
        $vendors = Vendor::where('is_open', true)->get();

        // 2. Ambil data Menu REAL (bukan dummy array) untuk Slider
        // Ambil menu yang tersedia, beserta relasi vendornya
        $menus = Menu::where('tersedia', true)
                     ->with('vendor') 
                     ->latest()
                     ->take(8)
                     ->get();

        return view('user.dashboard', compact('vendors', 'menus'));
    }

    /**
     * Detail Kantin (Menu List)
     */
    public function detail($id)
    {
        $vendor = Vendor::with('menus')->findOrFail($id);
        $menus = $vendor->menus()->where('tersedia', true)->get();
        // Cek apakah user sudah memfavoritkan kantin ini
        $isFavorite = Favorite::where('user_id', Auth::id())
                              ->where('vendor_id', $id)
                              ->exists();

        return view('user.detail_kantin', compact('vendor', 'menus', 'isFavorite'));
    }

    public function favorit()
    {
        $favorites = Favorite::where('user_id', Auth::id())->with('vendor')->get();
        return view('user.favorit', compact('favorites'));
    }

    public function toggleFavorite(Request $request)
    {
        $request->validate(['vendor_id' => 'required|exists:vendors,id']);
        
        $fav = Favorite::where('user_id', Auth::id())
                       ->where('vendor_id', $request->vendor_id)
                       ->first();

        if ($fav) {
            $fav->delete();
            return back()->with('success', 'Dihapus dari favorit.');
        } else {
            Favorite::create([
                'user_id' => Auth::id(),
                'vendor_id' => $request->vendor_id
            ]);
            return back()->with('success', 'Ditambahkan ke favorit.');
        }
    }
}
