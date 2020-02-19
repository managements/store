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
                'cash'      => ['price' => '180'],
                'cheque'    => ['price' => '400','operation' => '342654'],
                'term'      => ['price' => '100','operation' => '342654'],
                'transfer'  => ['price' => '200','operation' => '8635456']
            ],
            'partner'      => 1,
            'buy'           => [
                'consignees'    => [9 => 10],
            ],
            'sale'          => [
                'gazes'         => [5 => 10],
                'consignees'    => [6 => 10],
            ],
        ];
        // sale
        $trade= new TradeStorage();
        $trade->trade($data);
        // buy
        // bc
        // bl
    }
}
