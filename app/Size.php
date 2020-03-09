<?php

namespace App;


class Size extends IdUuid
{
    protected $fillable = ['size'];

    public $timestamps = false;

    public function products()
    {
        return $this->hasMany(Product::class);
    }
}
