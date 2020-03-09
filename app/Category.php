<?php

namespace App;


class Category extends IdUuid
{
    protected $fillable = ['category'];

    public $timestamps = false;

    public function staffs()
    {
        return $this->hasMany(Staff::class);
    }
}
