<?php

namespace App\Modules\Client\Actions;

use App\Modules\Client\Models\Client;
use Inertia\Inertia;
use Lorisleiva\Actions\Concerns\AsAction;

class GetClientInfo
{
    use AsAction;

    public function asController(Client $client)
    {
        return Inertia::render('Client/View', [
            'client' => $client->toData(),
            'type' => 'view'
        ]);
    }
}
