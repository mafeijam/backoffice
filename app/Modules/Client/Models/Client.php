<?php

namespace App\Modules\Client\Models;

use App\Models\BaseModel as Model;
use App\Modules\Client\ClientStatus;

class Client extends Model
{
    protected $table = 'clients';

    protected $casts = [
        'data' => 'json',
        'meta_data' => 'json'
    ];

    public function accounts()
    {
        return $this->hasMany(Account::class, 'client_uuid', 'uuid');
    }

    public function logs()
    {
        return $this->hasMany(ClientLog::class, 'client_uuid', 'uuid');
    }

    public static function uuid(string $uuid)
    {
        return static::firstWhere('uuid', $uuid);
    }

    public static function toForm()
    {
        return [
            'clientType' => 'INDIVIDUAL',
            'nonFace' => 'NO',
            'usTax' => 'NO',
            'name' => null,
            'email' => null,
            'phone' => null,
            'accounts' => [
                [
                    'openAt' => today()->format('Y-m-d'),
                    'accountNo' => null,
                    'type' => null,
                    'status' => ClientStatus::ACTIVE
                ]
            ]
        ];
    }

    public function toData()
    {
        $data = array_merge(static::toForm(), $this->data, [
            'accounts' => $this->accounts
                ->sort(fn ($a, $b) => [$a['number'], $a['type']] <=> [$b['number'], $b['type']])
                ->values()
                ->map(fn ($a) => array_merge($a->data, ['readonly' => true]))
                ->toArray()
        ]);

        return [
            'uuid' => $this->uuid,
            'status' => $this->status,
            'data' => $data
        ];
    }
}
