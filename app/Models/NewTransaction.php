<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NewTransaction extends Model
{
    protected $guarded = [
        'id',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function item()
    {
        return $this->belongsTo(Item::class);
    }

    public function itemType()
    {
        return $this->belongsTo(TypeGalon::class, 'id_type_galon');
    }

}
