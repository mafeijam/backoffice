<?php

namespace App\Modules\Client\Events;

use Spatie\EventSourcing\StoredEvents\ShouldBeStored;

class PendingClientCorrected extends ShouldBeStored
{
    public array $data;

    public array $meta;

    public function __construct(array $data, array $meta)
    {
        $this->data = $data;
        $this->meta = $meta;
    }
}
