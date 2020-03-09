<?php

namespace App;


class AccountType extends IdUuid
{
    protected $fillable = ['type'];

    public $timestamps = false;

    public function accounts()
    {
        return $this->hasMany(Account::class);
    }
}
