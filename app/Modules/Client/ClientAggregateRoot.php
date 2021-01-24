<?php

namespace App\Modules\Client;

use App\Modules\Client\Events\PendingClientApproved;
use App\Modules\Client\Events\PendingClientCorrected;
use App\Modules\Client\Events\PendingClientCreated;
use App\Modules\Client\Events\PendingClientRejected;
use App\Modules\Client\Models\PendingClient;
use Spatie\EventSourcing\AggregateRoots\AggregateRoot;

class ClientAggregateRoot extends AggregateRoot
{
    public function add(array $data, array $meta)
    {
        $this->recordThat(new PendingClientCreated($data, $meta));

        return $this;
    }

    public function approve(array $meta, PendingClient $client)
    {
        $this->recordThat(new PendingClientApproved($meta, $client));

        return $this;
    }

    public function reject(array $meta)
    {
        $this->recordThat(new PendingClientRejected($meta));

        return $this;
    }

    public function correct(array $data, array $meta)
    {
        $this->recordThat(new PendingClientCorrected($data, $meta));

        return $this;
    }
}
