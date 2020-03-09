<?php

namespace App;


class City extends IdUuid
{
    protected $fillable = ['city'];

    public $timestamps = false;

    public function addresses()
    {
        return $this->hasMany(Address::class);
    }
}
