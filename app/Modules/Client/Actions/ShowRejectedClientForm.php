<?php

namespace App\Modules\Client\Actions;

use App\Modules\Client\Models\Client;
use App\Modules\Client\Models\PendingClient;
use Inertia\Inertia;
use Lorisleiva\Actions\Concerns\AsAction;

class ShowRejectedClientForm
{
    use AsAction;

    public function asController(PendingClient $client)
    {
        return Inertia::render('Client/Edit', [
            'client' => $client->toEdit(),
            'formSchema' => Client::toForm(),
            'reason' => $client->meta_data['reason']
        ]);
    }
}
