<?php

namespace App;


class Trade extends IdUuid
{
    protected $fillable = [
        'slug_inv', 'inv', 'ht', 'tva', 'ttc', 'partner_id',
        'intermediate_id', 'truck_id', 'creator_id', 'created_at'
    ];

    public function getTermAttribute()
    {
        return $this->payments()->where('mode_id',4)->sum('price');
    }


    public function account_details()
    {
        return $this->belongsToMany(AccountDetail::class,'account_detail_trade');
    }

    public function payments()
    {
        return $this->belongsToMany(Payment::class,'payment_trade');
    }

    public function invoices()
    {
        return $this->belongsToMany(Invoice::class,'invoice_trade');
    }

    public function partner()
    {
        return $this->belongsTo(Partner::class);
    }

    public function intermediate()
    {
        return $this->belongsTo(Intermediate::class);
    }

    public function truck()
    {
        return $this->belongsTo(Truck::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class,'creator_id');
    }

    public function bc()
    {
        return $this->hasOne(Bc::class);
    }

    public function bl()
    {
        return $this->hasOne(Bl::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function claim_details()
    {
        return $this->hasMany(ClaimDetail::class);
    }

    public function transaction_buy()
    {
        return $this->hasOne(Transaction::class,'buy_id');
    }

    public function transaction_sale()
    {
        return $this->hasOne(Transaction::class,'sale_id');
    }
}
