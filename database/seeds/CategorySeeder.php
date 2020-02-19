<?php

use App\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run()
    {
        $categories = ["admin", "store", "driver", "assistant"];

        foreach ($categories as $category){
            Category::create([
                "category"      => $category
            ]);
        }

    }
}
