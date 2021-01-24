<?php

namespace App\Modules\Client\Events;

use App\Modules\Client\Models\PendingClient;
use Spatie\EventSourcing\StoredEvents\ShouldBeStored;

class PendingClientApproved extends ShouldBeStored
{
    public array $meta;

    public PendingClient $client;

    public function __construct(array $meta, PendingClient $client)
    {
        $this->meta = $meta;
        $this->client = $client;
    }
}
