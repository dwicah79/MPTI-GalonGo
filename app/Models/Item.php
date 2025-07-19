<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    protected $fillable = [
        'name',
        'type',
        'satuan',
        'stok',
        'price',
    ];

    public function typeGalon()
    {
        return $this->belongsTo(TypeGalon::class, 'type_galon_id');
    }


}
