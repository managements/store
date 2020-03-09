<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Truck extends Model
{
    protected $fillable = [
        "registered", "transporter",
        "lat", "lang", "cash", "cheque",
        "assurance", "visit_technique",
        'account_caisse_id', 'account_charge_id', 'account_stock_id',
        "creator_id"
    ];

    public function getDriverAttribute()
    {
        if($driver = $this->drivers()->where('to', null)->first()) {
            return $driver->staff->full_name;
        }
        return null;
    }

    public function getAssistantAttribute()
    {
        if($assistant = $this->assistants()->where('to', null)->first()) {
            return $assistant->staff->full_name;
        }
        return null;
    }

    public function trades()
    {
        return $this->hasMany(Trade::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class,'creator_id');
    }

    public function drivers()
    {
        return $this->hasMany(Driver::class);
    }

    public function assistants()
    {
        return $this->hasMany(Assistant::class);
    }

    public function account_caisse()
    {
        return $this->belongsTo(Account::class,'account_caisse_id');
    }

    public function account_charge()
    {
        return $this->belongsTo(Account::class,'account_charge_id');
    }

    public function account_stock()
    {
        return $this->belongsTo(Account::class,'account_stock_id');
    }

    public function charge_trucks()
    {
        return $this->hasMany(ChargeTruck::class,'truck_id');
    }

    public function stocks()
    {
        return $this->hasMany(Stock::class);
    }

    public function loadings()
    {
        return $this->hasMany(Loading::class);
    }
}
