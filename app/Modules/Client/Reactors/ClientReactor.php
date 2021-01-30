<?php

namespace App\Modules\Client\Reactors;

use App\Modules\Client\ClientStatus;
use App\Modules\Client\Events\PendingClientApproved;
use App\Modules\Client\Events\PendingClientCorrected;
use App\Modules\Client\Events\PendingClientCreated;
use App\Modules\Client\Events\PendingClientRejected;
use App\Modules\Client\Models\ClientLog;
use App\Modules\Client\Models\PendingClient;
use Spatie\EventSourcing\EventHandlers\Reactors\Reactor;

class ClientReactor extends Reactor
{
    public function onPendingClientCreated(PendingClientCreated $event)
    {
        PendingClient::add($event->aggregateRootUuid(), $event->data, $event->meta);
    }

    public function onPendingClientRejected(PendingClientRejected $event)
    {
        PendingClient::reject($event->aggregateRootUuid(), $event->meta);
    }

    public function onPendingClientCorrected(PendingClientCorrected $event)
    {
        PendingClient::correct($event->aggregateRootUuid(), $event->data, $event->meta);
    }

    public function onPendingClientApproved(PendingClientApproved $event)
    {
        ClientLog::add($event->meta, $event->client);
    }
}
