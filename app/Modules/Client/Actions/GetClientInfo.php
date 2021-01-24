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
        $type = 'view';
        $client->load('accounts');
        $client = [
            'data' => $client->toEdit()
        ];
        return Inertia::render('Client/View', compact('client', 'type'));
    }
}
