<?php

namespace App\Modules\Client\Actions;

use App\Modules\Client\Models\PendingClient;
use Inertia\Inertia;
use Lorisleiva\Actions\Concerns\AsAction;

class GetPendingClientInfo
{
    use AsAction;

    public function asController(PendingClient $client)
    {
        return Inertia::render('Client/View', [
            'client' => $client->toData(),
            'type' => 'approve'
        ]);
    }
}
