<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Mode extends Model
{
    protected $fillable = ['mode'];
    public $timestamps = false;

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }
}
