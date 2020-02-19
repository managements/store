<?php

namespace App\Storage;


use App\Intermediate;

class IntermediateStorage
{
    public function add(array $data)
    {
        return Intermediate::create($data);
    }

    public function update(array $data, Intermediate $intermediate)
    {
        return $intermediate->update($data);
    }
}