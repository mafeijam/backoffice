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
        $type = 'approve';
        return Inertia::render('Client/View', compact('client', 'type'));
    }
}
