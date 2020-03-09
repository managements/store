<?php

use App\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        $this->call([
            CategorySeeder::class,
            CitySeeder::class,
            ChargeSeeder::class,
            ModeSeeder::class,
            AccountTypeSeeder::class,
            SizeSeeder::class,
            StoreSeeder::class,
            ProductTypeSeeder::class,
            StaffSeeder::class,
        ]);
        $user = User::first();
        auth()->setUser($user);
        $this->call([
            TruckSeeder::class,
            ClientSeeder::class,
            OtherProviderSeeder::class
        ]);
        $this->call([
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
