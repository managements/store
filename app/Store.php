<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Store extends Model
{
    // caisse
    protected $fillable = ['name'];

    public function stocks()
    {
        return $this->hasMany(Stock::class);
    }
}
