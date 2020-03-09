<?php

use App\Stock;
use Illuminate\Database\Seeder;

class StockSeeder extends Seeder
{
    public function run()
    {
        $stocks = Stock::all();
        foreach ($stocks as $stock) {
            $stock->update([
                'qt'    => 100
            ]);
        }
    }
}
