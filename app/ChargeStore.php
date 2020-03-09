<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ChargeStore extends Model
{
    protected $fillable = ['store_id', 'creator_id'];

    public function creator()
    {
        return $this->belongsTo(User::class, 'creator_id');
    }

    public function store()
    {
        return $this->belongsTo(Store::class);
    }

    public function charge_store_details()
    {
        return $this->hasMany(ChargeStoreDetails::class,'charge_store_id');
    }

    public function payments()
    {
        return $this->belongsToMany(Payment::class,'charge_store_payment');
    }
}
