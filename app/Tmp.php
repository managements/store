<?php

namespace App;


class Tmp extends IdUuid
{
    protected $fillable = ['qt', 'product_id', 'loading_id'];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function loading()
    {
        return $this->belongsTo(Loading::class);
    }

    public function account_details()
    {
        return $this->belongsToMany(AccountDetail::class,'account_detail_tmp');
    }
}
