<?php

namespace App\Storage;


use App\Partner;

class ClientStorage
{
    // update
    public function update(array $data,Partner $client)
    {
        $client->address->update([
            'address'   => $data['address'],
            'city_id'   => $data['city']
        ]);
        $client->update($data);
    }
}