<?php

namespace App\Storage;


use App\Category;
use App\Staff;
use Illuminate\Support\Str;

class StaffStorage
{
    public function add(array $data)
    {
        $staff = Staff::create([
            "last_name"     => $data['last_name'],
            "first_name"    => $data['first_name'],
            "mobile"        => $data['mobile'],
            "cin"           => $data['cin'],
            "category_id"   => $data['category']
        ]);
        $category = Category::find($staff->category_id);
        if ($category->category != 'assistant') {
            $staff->user()->create([
                'name'              => $data['name'],
                'password'          => bcrypt($data['password']),
                'remember_token'    => Str::random(10)
            ]);
        }
        return $staff;
    }

    public function sub(Staff $staff)
    {
        if ($driver = $staff->driven_trucks()->where('to',null)->first()) {
            $driver->update([
                'to'    => now()
            ]);
        }
        if ($assistant = $staff->assisted_truck()->where('to',null)->first()) {
            $driver->update([
                'to'    => now()
            ]);
        }
        if ($staff->user) {
            $staff->user()->delete();
        }
        return $staff->delete();
    }

    public function update(Staff $staff,array $data)
    {
        return $staff->update([
            "last_name"         => $data['last_name'],
            "first_name"        => $data['first_name'],
            "mobile"            => $data['mobile'],
            "cin"               => $data['cin']
        ]);
    }

}