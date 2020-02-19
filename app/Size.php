<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Size extends Model
{
    protected $fillable = ['size'];

    public $timestamps = false;

    public function products()
    {
        return $this->hasMany(Product::class);
    }
}
