<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SupplierMaterial extends Model
{
    // Karena primary key adalah composite (supplier_id + material_id)
    protected $primaryKey = null;
    public $incrementing = false;

    protected $fillable = [
        'supplier_id',
        'material_id',
        'default_price',
        'lead_time_days',
    ];

    public $timestamps = false; // Tabel supplier_materials tidak punya timestamps

    // Relationships
    public function supplier()
    {
        return $this->belongsTo(Supplier::class, 'supplier_id');
    }

    public function material()
    {
        return $this->belongsTo(RawMaterial::class, 'material_id');
    }
}
