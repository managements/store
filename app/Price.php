<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Price extends Model
{
    protected $fillable = ['buy', 'sale', 'product_id'];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
