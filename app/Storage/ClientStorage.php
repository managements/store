<?php

namespace App\Storage;


use App\Partner;

class ClientStorage
{
    // update
    public function update(array $data,Partner $client)
    {
        $client->update($data);
    }
}