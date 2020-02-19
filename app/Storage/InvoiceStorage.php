<?php

namespace App\Storage;


use App\Trade;
use Carbon\Carbon;

class InvoiceStorage
{
    public function increment(): array
    {
        // slug inv month-year-inv
        $trade = Trade::latest()->first();
        if ($trade) {
            $inc = explode('-',$trade->slug_inv)[2];
        }
        else{
            $inc = 0;
        }
        $inc = $inc + 1;
        $slug_inv = Carbon::now()->format('m') . '-' . Carbon::now()->format('y') . '-' . $inc;
        $inv = str_replace('-','',$slug_inv);
        return compact('inv','slug_inv');
    }
}