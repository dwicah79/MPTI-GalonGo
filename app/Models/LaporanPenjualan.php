<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LaporanPenjualan extends Model
{
    use HasFactory;

    protected $fillable = [
        'item_id',
        'jumlah',
        'harga_satuan',
        'total_harga'
    ];

    public function item()
    {
        return $this->belongsTo(Item::class);
    }
}
