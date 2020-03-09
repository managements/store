<?php

use App\Storage\StoreStorage;
use Illuminate\Database\Seeder;

class StoreSeeder extends Seeder
{
    public function run()
    {
        $storage = new StoreStorage();
        $storage->add();
    }
}
