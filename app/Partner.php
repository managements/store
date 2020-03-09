<?php

namespace App;


class Partner extends IdUuid
{
    protected $fillable = [
        "provider", "account", "name", "speaker", "rc", "patent", "ice", "loss", "gain", "creator_id", 'account_id'
    ];

    public function trades()
    {
        return $this->hasMany(Trade::class);
    }

    public function qr()
    {
        return $this->hasOne(Qr::class);
    }
    public function creator()
    {
        return $this->belongsTo(User::class,'creator_id');
    }

    public function compte()
    {
        return $this->belongsTo(Account::class,'account_id');
    }

    public function products()
    {
        return $this->hasMany(Product::class);
    }

    public function remises()
    {
        return $this->hasMany(Remise::class);
    }

    public function stocks()
    {
        return $this->hasMany(Stock::class);
    }

    public function loadings()
    {
        return $this->hasMany(Loading::class);
    }

    public function claims()
    {
        return $this->hasMany(Claim::class);
    }

    public function address()
    {
        return $this->hasOne(Address::class);
    }

    public function account_details()
    {
        return $this->belongsToMany(AccountDetail::class,'account_detail_partner');
    }
}
