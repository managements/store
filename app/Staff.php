<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Staff extends Model
{
    use SoftDeletes;

    protected $fillable = ["last_name", "first_name", "mobile", "cin", "category_id"];

    public function getFullNameAttribute()
    {
        return $this->last_name . ' ' . $this->first_name;
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function user()
    {
        return $this->hasOne(User::class);
    }

    public function driven_trucks()
    {
        return $this->hasMany(Driver::class);
    }

    public function assisted_truck()
    {
        return $this->hasMany(Assistant::class);
    }
}
