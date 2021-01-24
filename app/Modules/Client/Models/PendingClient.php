<?php

namespace App\Modules\Client\Models;

use App\Helpers\ArrayDiffDeep;
use App\Models\BaseModel as Model;
use App\Modules\Client\ClientStatus;

class PendingClient extends Model
{
    protected $table = 'pending_clients';

    protected $casts = [
        'data' => 'json',
        'meta_data' => 'json'
    ];

    public static function forUuid(string $uuid)
    {
        return static::firstWhere('uuid', $uuid);
    }

    public static function add(string $uuid, array $data, array $meta)
    {
        if ($client = Client::firstWhere('uuid', $uuid)) {
            [$before, $after] = static::checkDiff($client, $data);

            usort($data['accounts'], static::usort());

            static::create([
                'uuid' => $uuid,
                'status' => ClientStatus::UPDATE,
                'data' => $data,
                'meta_data' => array_merge($meta, compact('before', 'after'))
            ]);

            return;
        }

        usort($data['accounts'], static::usort());

        static::create([
            'uuid' => $uuid,
            'status' => ClientStatus::NEW,
            'data' => $data,
            'meta_data' => $meta
        ]);
    }

    public static function approve(string $uuid, array $meta, self $pending)
    {
        $client = static::forUuid($uuid);
        $accounts = collect($client->data['accounts']);

        Client::updateOrCreate(
            ['uuid' => $uuid],
            [
                'data' => collect($client->data)->except('accounts')->forget('uuid'),
                'meta_data' => $meta,
                'status' => $accounts->every(fn ($val) => $val['status'] === ClientStatus::INACTIVE)
                    ? ClientStatus::INACTIVE
                    : ClientStatus::ACTIVE
            ]
        );

        $accounts->each(function ($acc) use ($uuid) {
            Account::updateOrCreate(
                ['client_uuid' => $uuid, 'number' => $acc['accountNo'], 'type' => $acc['type']],
                ['data' => $acc, 'status' => $acc['status'] ?? ClientStatus::ACTIVE]
            );
        });

        $client->update([
            'status' => $pending->status === ClientStatus::NEW ? ClientStatus::NEW_APPROVED : ClientStatus::UPDATE_APPROVED
        ]);

        $client->delete();
    }

    public static function reject(string $uuid, array $meta)
    {
        $client = static::forUuid($uuid);
        $client->update([
            'status' => $client->status === ClientStatus::NEW ? ClientStatus::NEW_REJECTED : ClientStatus::UPDATE_REJECTED,
            'meta_data' => array_merge($client->meta_data, $meta)
        ]);
    }

    public static function correct(string $uuid, array $data, array $meta)
    {
        $client = static::forUuid($uuid);
        $original = Client::firstWhere('uuid', $uuid);

        [$before, $after] = static::checkDiff($original ?? $client, $data);

        $client->update([
            'data' => $data,
            'status' => $client->status === ClientStatus::NEW_REJECTED ? ClientStatus::NEW : ClientStatus::UPDATE,
            'meta_data' => array_merge($client->meta_data, $meta, compact('before', 'after'))
        ]);
    }

    public function toEdit()
    {
        $data = $this->data;
        [$old, $new] = collect($data['accounts'])->partition(fn ($a) => isset($a['readonly']));
        $old = $old->toArray();
        $new = $new->toArray();

        usort($old, static::usort());
        usort($new, static::usort());

        $data['accounts'] = array_merge($old, $new);

        $data['uuid'] = $this->uuid;
        return $data;
    }

    protected static function checkDiff($client, $data)
    {
        $arrayDeep = new ArrayDiffDeep;

        $before = $arrayDeep->diff($data, $client->toEdit());
        $after = $arrayDeep->diff($client->toEdit(), $data);

        unset($after['uuid']);
        unset($before['uuid']);

        return [$before, $after];
    }

    protected static function usort()
    {
        return fn ($a, $b) => [$a['accountNo'], $a['type']] <=> [$b['accountNo'], $b['type']];
    }
}
