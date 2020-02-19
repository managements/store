<?php

use App\Size;
use Illuminate\Database\Seeder;

class SizeSeeder extends Seeder
{
    public function run()
    {
        $sizes = ['3kg', '6kg', '12kg', '35kg'];

        foreach ($sizes as $size) {
            Size::create([
                'size'      => $size
            ]);
        }
    }
}
