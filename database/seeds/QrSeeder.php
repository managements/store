<?php

use App\Storage\QrStorage;
use Illuminate\Database\Seeder;

class QrSeeder extends Seeder
{
    public function run()
    {
        $qr = new QrStorage();
        $qr->create(15);
    }
}
