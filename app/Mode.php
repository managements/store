<?php

namespace App;


class Mode extends IdUuid
{
    protected $fillable = ['mode'];
    public $timestamps = false;

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }
}
