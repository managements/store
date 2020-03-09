<?php

use App\Intermediate;
use Illuminate\Database\Seeder;

class IntermediateSeeder extends Seeder
{
    public function run()
    {
        Intermediate::create(['name'    => 'ZIZ']);
    }
}
