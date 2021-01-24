<?php

namespace App\Modules\Client\Events;

use Spatie\EventSourcing\StoredEvents\ShouldBeStored;

class PendingClientRejected extends ShouldBeStored
{
    public array $meta;

    public function __construct(array $meta)
    {
        $this->meta = $meta;
    }
}
