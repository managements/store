<?php

namespace App;


class Intermediate extends IdUuid
{
    protected $fillable = ['name'];

    public function trades()
    {
        return $this->hasMany(Trade::class);
    }
}
