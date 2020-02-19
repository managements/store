<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = ['qt', 'ht', 'tva', 'ttc', 'product_id', 'bl_id', 'bc_id'];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function bl()
    {
        return $this->belongsTo(Bl::class);
    }

    public function bc()
    {
        return $this->belongsTo(Bc::class);
    }

    public function account_details()
    {
        return $this->belongsToMany(AccountDetail::class,'account_detail_order');
    }
}
