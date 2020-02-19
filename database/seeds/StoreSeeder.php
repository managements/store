<?php

use App\Store;
use Illuminate\Database\Seeder;

class StoreSeeder extends Seeder
{
    public function run()
    {
        $storage = new \App\Storage\StoreStorage();
        $storage->add();
    }
}
