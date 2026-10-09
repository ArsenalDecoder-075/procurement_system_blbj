<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB; // Tambahkan ini

class PurchaseOrder extends Model
{
    protected $primaryKey = 'po_id';

    protected $fillable = [
        'po_number',
        'supplier_id',
        'po_date',
        'status',
    ];

    public $timestamps = true;

    // Relationships
    public function supplier()
    {
        return $this->belongsTo(Supplier::class, 'supplier_id');
    }

    // Method untuk mendapatkan details
    public function getDetailsAttribute()
    {
        // Gunakan DB facade dengan benar
        return DB::table('purchase_order_details')
            ->where('po_id', $this->po_id)
            ->get();
    }

    // Method untuk menambah detail
    public function addDetail($materialId, $quantity, $price)
    {
        return DB::table('purchase_order_details')->insert([
            'po_id' => $this->po_id,
            'material_id' => $materialId,
            'quantity' => $quantity,
            'price' => $price,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    // Method untuk menghapus semua details
    public function deleteDetails()
    {
        return DB::table('purchase_order_details')
            ->where('po_id', $this->po_id)
            ->delete();
    }

    // Method untuk menghitung total PO
    public function getTotalAttribute()
    {
        $total = DB::table('purchase_order_details')
            ->where('po_id', $this->po_id)
            ->select(DB::raw('SUM(quantity * price) as total'))
            ->first();

        return $total ? $total->total : 0;
    }

    // Scope untuk PO aktif
    public function scopeActive($query)
    {
        return $query->whereIn('status', ['DRAFT', 'APPROVED']);
    }

    // Scope untuk PO selesai
    public function scopeCompleted($query)
    {
        return $query->whereIn('status', ['CLOSED', 'CANCELLED']);
    }
}
