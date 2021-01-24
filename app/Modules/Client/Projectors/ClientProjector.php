<?php

namespace App\Modules\Client\Projectors;

use App\Modules\Client\Events\PendingClientApproved;
use App\Modules\Client\Models\PendingClient;
use Spatie\EventSourcing\EventHandlers\Projectors\Projector;

class ClientProjector extends Projector
{
    public function onPendingClientApproved(PendingClientApproved $event)
    {
        PendingClient::approve($event->aggregateRootUuid(), $event->meta, $event->client);
    }
}
