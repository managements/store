<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProductType extends Model
{
    protected $fillable = ['type'];
    public $timestamps = false;

    public function products()
    {
        return $this->hasMany(Product::class);
    }
}
