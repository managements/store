<?php

use App\Category;
use App\Storage\StaffStorage;
use Illuminate\Database\Seeder;

class StaffSeeder extends Seeder
{
    public function run()
    {
        $data = [
            "last_name" => "Last_name",
            "first_name" => "First_name",
            "mobile" => "0600000000",
            "cin" => "bh000000"
        ];
        $categories = Category::all();
        $storage = new StaffStorage();
        foreach ($categories as $category) {
            $data['category'] = $category->id;
            if ($category->category == 'assistant') {
                $data ['name'] = $category->category;
                $storage->add($data);
                $data ['name'] = $category->category . '1';
                $storage->add($data);
            }
            elseif ($category->category == 'driver') {
                $data ['name'] = $category->category;
                $data ['password'] = 'password';
                $storage->add($data);
                $data ['name'] = $category->category . '1';
                $storage->add($data);
            }
            else {
                $data ['name'] = $category->category;
                $data ['password'] = 'password';
                $storage->add($data);
            }
        }
    }
}
