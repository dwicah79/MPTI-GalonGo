<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kurir extends Model
{
    protected $fillable = [
        'name',
        'phone',
    ];

    public function transactions()
    {
        return $this->hasMany(NewTransaction::class, 'kurir_id');
    }
}
