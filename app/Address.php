<?php

namespace App;


class Address extends IdUuid
{
    protected $fillable = ['address', 'partner_id', 'city_id'];

    public function partner()
    {
        return $this->belongsTo(Partner::class);
    }

    public function city()
    {
        return $this->belongsTo(City::class);
    }
}
