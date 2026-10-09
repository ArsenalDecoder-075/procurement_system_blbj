<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Receiving extends Model
{
    // Mengatur Primary Key karena bukan 'id'
    protected $primaryKey = 'receiving_id';

    protected $fillable = [
        'receiving_number',
        'po_id',
        'receiving_date',
        'warehouse_id',
        'status',
    ];

    public $timestamps = true;

    // --- Relationships ---

    public function purchaseOrder()
    {
        return $this->belongsTo(PurchaseOrder::class, 'po_id');
    }

    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class, 'warehouse_id');
    }

    /**
     * PERBAIKAN: Gunakan Relationship HasMany jika Anda memiliki model ReceivingDetail.
     * Jika belum ada, gunakan DB Facade seperti di bawah ini namun lebih rapi.
     */
    public function details()
    {
        // Asumsi nama tabel detail adalah receiving_details
        return DB::table('receiving_details')->where('receiving_id', $this->receiving_id)->get();
    }

    // --- Accessors ---

    // Menghitung total quantity received dengan cara yang lebih simpel
    public function getTotalReceivedAttribute()
    {
        return DB::table('receiving_details')
            ->where('receiving_id', $this->receiving_id)
            ->sum('quantity_received') ?: 0; // Lebih ringkas daripada select raw
    }

    // --- Logic Update Stok ---

    protected static function booted()
    {
        static::created(function ($receiving) {
            if ($receiving->status === 'COMPLETE') {
                $receiving->updateStock();
            }
        });

        static::updated(function ($receiving) {
            // Hanya update jika status BERUBAH menjadi COMPLETE
            if ($receiving->isDirty('status') && $receiving->status === 'COMPLETE') {
                $receiving->updateStock();
            }
        });
    }

    public function updateStock()
    {
        $details = $this->details(); // Memanggil method details()

        foreach ($details as $detail) {
            // Gunakan updateOrInsert untuk mempersingkat kodingan (Cek & Update sekaligus)
            DB::table('stocks')->updateOrInsert(
                [
                    'warehouse_id' => $this->warehouse_id,
                    'material_id'  => $detail->material_id,
                ],
                [
                    // DB::raw digunakan untuk mencegah data lama tertimpa (ditambahkan)
                    'quantity'   => DB::raw("quantity + {$detail->quantity_received}"),
                    'updated_at' => now(),
                    // created_at hanya diisi jika data baru di-insert
                    'created_at' => DB::raw('IFNULL(created_at, NOW())') 
                ]
            );
        }
    }
}