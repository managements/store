<?php

use App\Storage\TradeStorage;
use Illuminate\Database\Seeder;

class TradeSeeder extends Seeder
{
    public function run()
    {
        // form
        // gaz
        // consign
        // foreign
        // defective
        $data = [
            'payments'      => [
                ['price' => '150', 'mode_id' => 1],
                ['price' => '50','operation' => '342654','mode_id' => 2],
                ['price' => '200','operation' => '8635456','mode_id' => 3],
                ['price' => '100','operation' => '342654','mode_id' => 4]
            ],
            'partner'       => 1,
            'products'      => [
                'buy'           => [
                    'consign'       => [9 => 10],
                ],
                'sale'          => [
                    'gaz'           => [5 => 10],
                    'consign'       => [6 => 10],
                ],
            ],

        ];
        // sale
        $trade= new TradeStorage();
        $trade->trade($data);
    }
}
