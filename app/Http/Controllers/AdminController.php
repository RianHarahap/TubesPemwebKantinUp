<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Vendor;
use App\Models\Order;
use App\Models\Menu;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    public function dashboard()
    {
        // Total Vendor Aktif
        $totalVendors = Vendor::where('is_open', true)->count();

        // Total Transaksi Hari Ini
        $totalTransactionsToday = Order::whereDate('created_at', today())->count();

        // Total Revenue Hari Ini
        $totalRevenueToday = Order::whereDate('created_at', today())
                                 ->where('status', 'selesai')
                                 ->sum('total_harga');

        // Data untuk grafik penjualan mingguan (7 hari terakhir)
        $weeklySales = Order::select(
            DB::raw('DATE(created_at) as date'),
            DB::raw('SUM(total_harga) as total')
        )
        ->where('created_at', '>=', now()->subDays(7))
        ->where('status', 'selesai')
        ->groupBy('date')
        ->orderBy('date')
        ->get();

        // Log Aktivitas Terbaru (ASLI dari database)
        $recentActivities = ActivityLog::with('user')
                                      ->orderBy('created_at', 'desc')
                                      ->take(10)
                                      ->get()
                                      ->map(function($log) {
                                          return [
                                              'type' => $log->action,
                                              'message' => $log->description,
                                              'time' => $log->created_at->diffForHumans(),
                                              'icon' => $log->getIcon(),
                                              'badge' => $log->getBadgeClass()
                                          ];
                                      });

        return view('admin.dashboard', compact(
            'totalVendors',
            'totalTransactionsToday',
            'totalRevenueToday',
            'weeklySales',
            'recentActivities'
        ));
    }

    public function kelolaVendor()
    {
        $vendors = Vendor::with('user')->get();

        return view('admin.kelola-vendor', compact('vendors'));
    }

    public function showVendor(Vendor $vendor)
    {
        $vendor->load('user', 'menus', 'orders');

        // Data penjualan untuk grafik mingguan
        $weeklySales = Order::select(
            DB::raw('DATE(created_at) as date'),
            DB::raw('SUM(total_harga) as total')
        )
        ->where('vendor_id', $vendor->id)
        ->where('created_at', '>=', now()->subDays(7))
        ->where('status', 'selesai')
        ->groupBy('date')
        ->orderBy('date')
        ->get();

        // --- LOGIKA PENDETEKSI DATABASE (AGAR BISA JALAN DI MYSQL & SQLITE) ---
        $driver = DB::connection()->getDriverName();
        
        if ($driver == 'sqlite') {
            $monthQuery = "strftime('%m', created_at)";
            $yearQuery = "strftime('%Y', created_at)";
        } else {
            // Untuk MySQL / Laragon kamu
            $monthQuery = "MONTH(created_at)";
            $yearQuery = "YEAR(created_at)";
        }

        $monthlySales = Order::select(
            DB::raw("$monthQuery as month"),
            DB::raw("$yearQuery as year"),
            DB::raw('SUM(total_harga) as total')
        )
        ->where('vendor_id', $vendor->id)
        ->where('created_at', '>=', now()->subMonth())
        ->where('status', 'selesai')
        ->groupBy('month', 'year')
        ->orderBy('year')
        ->orderBy('month')
        ->get();
        // ---------------------------------------------------------------------

        // Statistik vendor
        $totalMenu = $vendor->menus()->count();
        $totalOrders = $vendor->orders()->count();
        $totalRevenue = $vendor->orders()->where('status', 'selesai')->sum('total_harga');

        // Pesanan terbaru
        $recentOrders = $vendor->orders()
                            ->with('user')
                            ->orderBy('created_at', 'desc')
                            ->take(5)
                            ->get();

        return view('admin.show-vendor', compact(
            'vendor',
            'weeklySales',
            'monthlySales',
            'totalMenu',
            'totalOrders',
            'totalRevenue',
            'recentOrders'
        ));
    }

    public function laporanTransaksi()
    {
        // Data transaksi untuk tabel
        $transactions = Order::with(['user', 'vendor'])
                        ->orderBy('created_at', 'desc')
                        ->get();

        // --- LOGIKA PENDETEKSI DATABASE (AGAR BISA JALAN DI MYSQL & SQLITE) ---
        $driver = DB::connection()->getDriverName();
        
        if ($driver == 'sqlite') {
            // Untuk teman kamu (SQLite)
            $monthQuery = "strftime('%m', created_at)";
            $yearQuery = "strftime('%Y', created_at)";
        } else {
            // Untuk kamu di Laragon (MySQL)
            $monthQuery = "MONTH(created_at)";
            $yearQuery = "YEAR(created_at)";
        }

        // Data untuk grafik bulanan
        $monthlySales = Order::select(
            DB::raw("$monthQuery as month"),
            DB::raw("$yearQuery as year"),
            DB::raw('SUM(total_harga) as total')
        )
        ->where('status', 'selesai')
        ->groupBy('month', 'year')
        ->orderBy('year', 'asc')
        ->orderBy('month', 'asc')
        ->get();
        // ---------------------------------------------------------------------

        return view('admin.laporan-transaksi', compact('transactions', 'monthlySales'));
    }

    // Vendor CRUD Methods
    public function createVendor()
    {
        return view('admin.create-vendor');
    }

    public function storeVendor(Request $request)
    {
        $request->validate([
            'nama_kantin' => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'name' => 'required|string|max:255|unique:users',
            'email' => 'required|email|unique:users',
            'password' => 'required|string|min:8',
        ]);

        // Create vendor
        $vendor = Vendor::create([
            'nama_kantin' => $request->nama_kantin,
            'deskripsi' => $request->deskripsi,
            'is_open' => $request->has('is_open') ? true : false,
        ]);

        // Create user for vendor
        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'penjual',
            'vendor_id' => $vendor->id,
        ]);

        // Log aktivitas
        ActivityLog::log('create_vendor', "Admin menambahkan vendor baru: {$vendor->nama_kantin}");

        return redirect()->route('admin.kelola-vendor')->with('success', 'Vendor berhasil ditambahkan!');
    }

    public function editVendor(Vendor $vendor)
    {
        return view('admin.edit-vendor', compact('vendor'));
    }

    public function updateVendor(Request $request, Vendor $vendor)
    {
        $request->validate([
            'nama_kantin' => 'required|string|max:255',
            'deskripsi' => 'required|string',
        ]);

        $vendor->update([
            'nama_kantin' => $request->nama_kantin,
            'deskripsi' => $request->deskripsi,
            'is_open' => $request->has('is_open') ? true : false,
        ]);

        // Log aktivitas
        ActivityLog::log('update_vendor', "Admin memperbarui vendor: {$vendor->nama_kantin}");

        return redirect()->route('admin.kelola-vendor')->with('success', 'Vendor berhasil diperbarui!');
    }

    public function toggleVendorStatus(Vendor $vendor)
    {
        $vendor->update([
            'is_open' => !$vendor->is_open
        ]);

        $status = $vendor->is_open ? 'dibuka' : 'ditutup';
        
        // Log aktivitas
        ActivityLog::log('toggle_status', "Admin mengubah status vendor {$vendor->nama_kantin} menjadi {$status}");
        
        return redirect()->back()->with('success', "Status vendor {$vendor->nama_kantin} berhasil {$status}!");
    }

    public function destroyVendor(Vendor $vendor)
    {
        $vendorName = $vendor->nama_kantin;
        
        // Delete associated user first
        if ($vendor->user) {
            $vendor->user->delete();
        }

        // Delete vendor
        $vendor->delete();

        // Log aktivitas
        ActivityLog::log('delete_vendor', "Admin menghapus vendor: {$vendorName}");

        return redirect()->route('admin.kelola-vendor')->with('success', 'Vendor berhasil dihapus!');
    }

    // API untuk mendapatkan statistik vendor
    public function getVendorStats(Vendor $vendor, Request $request)
    {
        $period = $request->query('period', 'minggu');
        
        switch ($period) {
            case 'minggu':
                $data = $this->getWeeklyStats($vendor);
                break;
            case 'bulan':
                $data = $this->getMonthlyStats($vendor);
                break;
            case 'tahun':
                $data = $this->getYearlyStats($vendor);
                break;
            default:
                $data = $this->getWeeklyStats($vendor);
        }

        return response()->json($data);
    }

    private function getWeeklyStats(Vendor $vendor)
    {
        $orders = Order::where('vendor_id', $vendor->id)
                      ->where('created_at', '>=', now()->subDays(7))
                      ->where('status', 'selesai')
                      ->get();

        $labels = [];
        $sales = [];
        $orderCounts = [];

        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i)->format('Y-m-d');
            $labels[] = now()->subDays($i)->format('d M');
            
            $daySales = $orders->filter(function($order) use ($date) {
                return $order->created_at->format('Y-m-d') == $date;
            })->sum('total_harga');
            
            $dayOrders = $orders->filter(function($order) use ($date) {
                return $order->created_at->format('Y-m-d') == $date;
            })->count();
            
            $sales[] = $daySales;
            $orderCounts[] = $dayOrders;
        }

        $totalSales = $orders->sum('total_harga');
        $totalOrders = $orders->count();
        $averageSales = $totalOrders > 0 ? $totalSales / 7 : 0;

        return [
            'labels' => $labels,
            'sales' => $sales,
            'orders' => $orderCounts,
            'total_sales' => (int)$totalSales,
            'total_orders' => $totalOrders,
            'average_sales' => (int)$averageSales
        ];
    }

    private function getMonthlyStats(Vendor $vendor)
    {
        $orders = Order::where('vendor_id', $vendor->id)
                      ->where('created_at', '>=', now()->subMonth())
                      ->where('status', 'selesai')
                      ->get();

        $labels = [];
        $sales = [];
        $orderCounts = [];

        for ($i = 29; $i >= 0; $i -= 3) {
            $date = now()->subDays($i)->format('Y-m-d');
            $labels[] = now()->subDays($i)->format('d M');
            
            $daySales = $orders->filter(function($order) use ($date) {
                return $order->created_at->format('Y-m-d') == $date;
            })->sum('total_harga');
            
            $dayOrders = $orders->filter(function($order) use ($date) {
                return $order->created_at->format('Y-m-d') == $date;
            })->count();
            
            $sales[] = $daySales;
            $orderCounts[] = $dayOrders;
        }

        $totalSales = $orders->sum('total_harga');
        $totalOrders = $orders->count();
        $averageSales = $totalOrders > 0 ? $totalSales / 30 : 0;

        return [
            'labels' => $labels,
            'sales' => $sales,
            'orders' => $orderCounts,
            'total_sales' => (int)$totalSales,
            'total_orders' => $totalOrders,
            'average_sales' => (int)$averageSales
        ];
    }

    private function getYearlyStats(Vendor $vendor)
    {
        $orders = Order::where('vendor_id', $vendor->id)
                      ->where('created_at', '>=', now()->subYear())
                      ->where('status', 'selesai')
                      ->get();

        $labels = [];
        $sales = [];
        $orderCounts = [];

        for ($i = 11; $i >= 0; $i--) {
            $month = now()->subMonth($i);
            $labels[] = $month->format('M');
            
            $monthSales = $orders->filter(function($order) use ($month) {
                return $order->created_at->format('m-Y') == $month->format('m-Y');
            })->sum('total_harga');
            
            $monthOrders = $orders->filter(function($order) use ($month) {
                return $order->created_at->format('m-Y') == $month->format('m-Y');
            })->count();
            
            $sales[] = $monthSales;
            $orderCounts[] = $monthOrders;
        }

        $totalSales = $orders->sum('total_harga');
        $totalOrders = $orders->count();
        $averageSales = $totalOrders > 0 ? $totalSales / 12 : 0;

        return [
            'labels' => $labels,
            'sales' => $sales,
            'orders' => $orderCounts,
            'total_sales' => (int)$totalSales,
            'total_orders' => $totalOrders,
            'average_sales' => (int)$averageSales
        ];
    }
}