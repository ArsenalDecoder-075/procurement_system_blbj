<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Stock extends Model
{
    // Karena primary key adalah composite (warehouse_id + material_id)
    protected $primaryKey = null;
    public $incrementing = false;

    protected $fillable = [
        'warehouse_id',
        'material_id',
        'quantity',
    ];

    public $timestamps = false; // Tabel stocks tidak punya timestamps

    // Relationships
    public function material()
    {
        return $this->belongsTo(RawMaterial::class, 'material_id');
    }

    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class, 'warehouse_id');
    }

    // Method untuk update stock
    public static function updateStock($warehouseId, $materialId, $quantity, $operation = 'add')
    {
        // Cek apakah stock sudah ada
        $existing = self::where('warehouse_id', $warehouseId)
            ->where('material_id', $materialId)
            ->first();

        if ($existing) {
            // Update existing stock
            if ($operation === 'add') {
                $existing->quantity += $quantity;
            } elseif ($operation === 'subtract') {
                $existing->quantity -= $quantity;
            } elseif ($operation === 'set') {
                $existing->quantity = $quantity;
            }

            // Pastikan tidak negatif
            if ($existing->quantity < 0) {
                $existing->quantity = 0;
            }

            return $existing->save();
        } else {
            // Insert new stock (hanya jika quantity > 0)
            if ($quantity > 0) {
                return self::create([
                    'warehouse_id' => $warehouseId,
                    'material_id' => $materialId,
                    'quantity' => $quantity,
                ]);
            }
            return false;
        }
    }

    // Scope untuk material tertentu
    public function scopeForMaterial($query, $materialId)
    {
        return $query->where('material_id', $materialId);
    }

    // Scope untuk warehouse tertentu
    public function scopeForWarehouse($query, $warehouseId)
    {
        return $query->where('warehouse_id', $warehouseId);
    }

    // Get total stock untuk material tertentu (semua warehouse)
    public static function getTotalStockForMaterial($materialId)
    {
        return self::where('material_id', $materialId)->sum('quantity');
    }

    // Get stock summary per warehouse
    public static function getStockSummary()
    {
        return self::select(
            'warehouse_id',
            'material_id',
            'quantity',
            DB::raw('(SELECT warehouse_name FROM warehouses WHERE warehouse_id = stocks.warehouse_id) as warehouse_name'),
            DB::raw('(SELECT material_name FROM raw_materials WHERE material_id = stocks.material_id) as material_name'),
            DB::raw('(SELECT unit FROM raw_materials WHERE material_id = stocks.material_id) as unit')
        )
        ->orderBy('warehouse_name')
        ->orderBy('material_name')
        ->get();
    }

    // Check low stock
    public static function getLowStockMaterials($threshold = 10)
    {
        return self::where('quantity', '<', $threshold)
            ->with(['material', 'warehouse'])
            ->get();
    }
}
