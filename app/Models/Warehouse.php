<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB; // Tambahkan ini

class Warehouse extends Model
{
    protected $primaryKey = 'warehouse_id';

    protected $fillable = [
        'warehouse_code',
        'warehouse_name',
        'location',
    ];

    public $timestamps = true;

    // Relationships
    public function stocks()
    {
        return $this->hasMany(Stock::class, 'warehouse_id');
    }

    public function receivings()
    {
        return $this->hasMany(Receiving::class, 'warehouse_id');
    }

    // Method untuk mendapatkan total kapasitas (jika ada field capacity)
    public function getUsedCapacity()
    {
        // Jika ada field capacity di table warehouses
        // Anda bisa menambahkan logic di sini
        return 0;
    }

    // Method untuk mendapatkan stock summary di warehouse ini
    public function getStockSummary()
    {
        return $this->stocks()
            ->with('material')
            ->select(
                'material_id',
                DB::raw('SUM(quantity) as total_quantity'),
                DB::raw('COUNT(*) as item_count')
            )
            ->groupBy('material_id')
            ->get();
    }

    // Method untuk mendapatkan total value stock di warehouse
    public function getTotalStockValue()
    {
        $total = 0;
        $stocks = $this->stocks()->with('material.supplierMaterials')->get();

        foreach ($stocks as $stock) {
            // Ambil harga terakhir dari supplier
            $price = $stock->material->supplierMaterials
                ->sortByDesc('created_at')
                ->first()
                ->default_price ?? 0;

            $total += $stock->quantity * $price;
        }

        return $total;
    }

    // Scope untuk warehouse aktif
    public function scopeActive($query)
    {
        // Jika ada field status di table warehouses
        // return $query->where('status', 'ACTIVE');
        return $query;
    }

    // Method untuk mendapatkan warehouse dengan stock tertentu
    public function hasMaterial($materialId)
    {
        return $this->stocks()
            ->where('material_id', $materialId)
            ->exists();
    }

    // Method untuk mendapatkan quantity material tertentu
    public function getMaterialQuantity($materialId)
    {
        $stock = $this->stocks()
            ->where('material_id', $materialId)
            ->first();

        return $stock ? $stock->quantity : 0;
    }

    // Method untuk transfer stock ke warehouse lain
    public function transferStock($materialId, $quantity, $toWarehouseId)
    {
        // Validasi stock cukup
        $currentStock = $this->getMaterialQuantity($materialId);

        if ($currentStock < $quantity) {
            return [
                'success' => false,
                'message' => 'Stock tidak cukup untuk transfer'
            ];
        }

        // Kurangi stock dari warehouse ini
        Stock::updateStock($this->warehouse_id, $materialId, -$quantity, 'add');

        // Tambah stock ke warehouse tujuan
        Stock::updateStock($toWarehouseId, $materialId, $quantity, 'add');

        // Anda bisa menambahkan log transfer di sini

        return [
            'success' => true,
            'message' => 'Stock berhasil ditransfer'
        ];
    }

    // Method untuk mendapatkan low stock items
    public function getLowStockItems($threshold = 10)
    {
        return $this->stocks()
            ->where('quantity', '<', $threshold)
            ->with('material')
            ->get();
    }
}
