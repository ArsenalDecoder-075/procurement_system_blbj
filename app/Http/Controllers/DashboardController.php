<?php

namespace App\Http\Controllers;

use App\Models\PurchaseOrder;
use App\Models\Receiving;
use App\Models\Supplier;
use App\Models\RawMaterial;
use App\Models\Stock;
use App\Models\Warehouse;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        // Hitung total stok dari semua gudang
        $totalStock = Stock::sum('quantity');

        // Hitung jumlah bahan baku
        $totalMaterials = RawMaterial::count();

        // Hitung jumlah supplier
        $totalSuppliers = Supplier::count();

        // Hitung jumlah purchase order
        $totalPurchaseOrders = PurchaseOrder::count();

        // Hitung jumlah gudang
        $totalWarehouses = Warehouse::count();

        // Ambil 10 purchase order terbaru dengan supplier
        $recentPurchaseOrders = PurchaseOrder::with('supplier')
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get();

        // Ambil 10 penerimaan barang terbaru
        $recentReceivings = Receiving::with(['purchaseOrder', 'warehouse'])
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get();

        // Ambil bahan baku dengan stok rendah (kurang dari 10)
        $lowStockMaterials = Stock::with('material')
            ->where('quantity', '<', 10)
            ->get();

        return view('dashboard', [
            'totalStock' => $totalStock,
            'totalMaterials' => $totalMaterials,
            'totalSuppliers' => $totalSuppliers,
            'totalPurchaseOrders' => $totalPurchaseOrders,
            'totalWarehouses' => $totalWarehouses,
            'recentPurchaseOrders' => $recentPurchaseOrders,
            'recentReceivings' => $recentReceivings,
            'lowStockMaterials' => $lowStockMaterials,
        ]);
    }
}
