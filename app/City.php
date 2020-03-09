<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class City extends Model
{
    protected $fillable = ['city'];

    public $timestamps = false;

    public function addresses()
    {
        return $this->hasMany(Address::class);
    }
}
