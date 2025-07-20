<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OtherTransaction extends Model
{
    protected $table = 'other_transactions';

    protected $fillable = [
        'kurir_id',
        'name',
        'description',
        'price',
    ];

    public function transaction()
    {
        return $this->belongsTo(Kurir::class, 'kurir_id');
    }
}
