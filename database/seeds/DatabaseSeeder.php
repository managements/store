<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        $this->call([
            CitySeeder::class,
            CategorySeeder::class,
            ChargeSeeder::class,
            ModeSeeder::class,
            AccountTypeSeeder::class,
            SizeSeeder::class,
            StoreSeeder::class,
            ProductTypeSeeder::class,
            ClientSeeder::class,
            OtherProviderSeeder::class
        ]);

        $this->call([
            StaffSeeder::class,
            TruckSeeder::class,
            ChargeTruckSeeder::class,
            PartnerSeeder::class,
        ]);
        // TODO:: delete before production
        $this->call([
            StockSeeder::class,
            PriceSeeder::class,
            IntermediateSeeder::class
        ]);
    }
}
