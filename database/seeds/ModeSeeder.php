<?php

use App\Mode;
use Illuminate\Database\Seeder;

class ModeSeeder extends Seeder
{
    public function run()
    {
        $modes = ['cash', 'cheque', 'transfer', 'term'];

        foreach ($modes as $mode) {
            Mode::create([
                'mode'      => $mode
            ]);
        }
    }
}
