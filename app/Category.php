<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $fillable = ['category'];

    public $timestamps = false;

    public function staffs()
    {
        return $this->hasMany(Staff::class);
    }
}
