<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RawMaterial extends Model
{
    protected $primaryKey = 'material_id';

    protected $fillable = [
        'material_code',
        'material_name',
        'unit',
    ];

    public $timestamps = true;

    // Relationships yang ADA
    public function stocks()
    {
        return $this->hasMany(Stock::class, 'material_id');
    }
}
