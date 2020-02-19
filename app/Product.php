<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = ['type_id', 'tva', 'size_id',  'partner_id'];

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function type()
    {
        return $this->belongsTo(ProductType::class);
    }

    public function stocks()
    {
        return $this->hasMany(Stock::class);
    }

    public function size()
    {
        return $this->belongsTo(Size::class);
    }

    public function partner()
    {
        return $this->belongsTo(Partner::class);
    }

    public function prices()
    {
        return $this->hasMany(Price::class);
    }

    public function remises()
    {
        return $this->hasMany(Product::class);
    }

    public function tmps()
    {
        return $this->hasMany(Tmp::class);
    }

}
