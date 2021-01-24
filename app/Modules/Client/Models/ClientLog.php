<?php

namespace App\Modules\Client\Models;

use App\Models\BaseModel as Model;
use App\Modules\Client\ClientStatus;

class ClientLog extends Model
{
    protected $table = 'client_logs';

    protected $casts = [
        'data' => 'json'
    ];

    public static function add(PendingClient $client, array $meta)
    {
        $data = $client->status === ClientStatus::NEW
            ? [
                'type' => 'new client approved',
                'details' => $client->data
            ]
            : [
                'type' => 'client edited',
                'details' => collect($client->meta_data)->only('before', 'after')
            ];

        ClientLog::create([
            'client_uuid' => $client->uuid,
            'created_by' => $meta['created_by'],
            'approved_by' => $meta['approved_by'],
            'data' => $data
        ]);
    }
}
