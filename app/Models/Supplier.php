<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    protected $primaryKey = 'supplier_id';

    protected $fillable = [
        'supplier_code',
        'supplier_name',
        'address',
        'phone',
        'email',
    ];

    public $timestamps = true;

    // Relationships
    public function purchaseOrders()
    {
        return $this->hasMany(PurchaseOrder::class, 'supplier_id');
    }

    public function supplierMaterials()
    {
        return $this->hasMany(SupplierMaterial::class, 'supplier_id');
    }

    // Accessor untuk jumlah material
    public function getMaterialCountAttribute()
    {
        return $this->supplierMaterials()->count();
    }

    // Scope untuk supplier aktif (yang punya material)
    public function scopeWithMaterials($query)
    {
        return $query->whereHas('supplierMaterials');
    }
}
