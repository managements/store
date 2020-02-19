<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Qr extends Model
{
    protected $fillable = ['img', 'code', 'min_lat', 'max_lat', 'min_lang', 'max_lang', 'partner_id'];

    public function partner()
    {
        return $this->belongsTo(Partner::class);
    }
}
