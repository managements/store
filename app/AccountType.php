<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AccountType extends Model
{
    protected $fillable = ['type'];

    public $timestamps = false;

    public function accounts()
    {
        return $this->hasMany(Account::class);
    }
}
