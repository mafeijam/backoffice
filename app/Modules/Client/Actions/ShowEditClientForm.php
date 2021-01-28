<?php

namespace App\Modules\Client\Actions;

use App\Modules\Client\Models\Client;
use Inertia\Inertia;
use Lorisleiva\Actions\Concerns\AsAction;

class ShowEditClientForm
{
    use AsAction;

    public function asController(Client $client)
    {
        return Inertia::render('Client/Edit', [
            'client' => $client->toData(),
            'formSchema' => Client::toForm()
        ]);
    }
}
