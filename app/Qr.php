<?php

namespace App;


class Qr extends IdUuid
{
    protected $fillable = ['img', 'code', 'min_lat', 'max_lat', 'min_lang', 'max_lang', 'partner_id'];

    public function partner()
    {
        return $this->belongsTo(Partner::class);
    }
}
