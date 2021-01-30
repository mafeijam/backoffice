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

    public static function uuid(string $uuid)
    {
        return static::firstWhere('uuid', $uuid);
    }

    public static function getAccountNumbers($client = null)
    {
        return static::select('data->accounts as accounts')
            ->when($client, fn ($query, $client) => $query->where('uuid', '!=', $client->uuid))
            ->get()
            ->map(fn ($acc) => json_decode($acc['accounts'], true))
            ->flatten(1)
            ->filter(fn ($acc) => !isset($acc['readonly']))
            ->pluck('accountNo');
    }

    public static function add(string $uuid, array $data, array $meta)
    {
        if ($client = Client::uuid($uuid)) {
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

    public static function approve(string $uuid, array $meta, PendingClient $pending)
    {
        $accounts = collect($pending->data['accounts']);

        Client::updateOrCreate(
            ['uuid' => $uuid],
            [
                'data' => collect($pending->data)->except('accounts'),
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

        $pending->update([
            'status' => $pending->status === ClientStatus::NEW ? ClientStatus::NEW_APPROVED : ClientStatus::UPDATE_APPROVED
        ]);

        $pending->delete();
    }

    public static function reject(string $uuid, array $meta)
    {
        $client = static::uuid($uuid);
        $client->update([
            'status' => $client->status === ClientStatus::NEW ? ClientStatus::NEW_REJECTED : ClientStatus::UPDATE_REJECTED,
            'meta_data' => array_merge($client->meta_data, $meta)
        ]);
    }

    public static function correct(string $uuid, array $data, array $meta)
    {
        $pending = static::uuid($uuid) ;
        $client = Client::uuid($uuid) ?? $pending;

        [$before, $after] = static::checkDiff($client, $data);

        $pending->update([
            'data' => $data,
            'status' => $pending->status === ClientStatus::NEW_REJECTED ? ClientStatus::NEW : ClientStatus::UPDATE,
            'meta_data' => array_merge($pending->meta_data, $meta, compact('before', 'after'))
        ]);
    }

    public function toData()
    {
        $data = $this->data;
        [$old, $new] = collect($data['accounts'])->partition(fn ($a) => isset($a['readonly']));
        $old = $old->toArray();
        $new = $new->toArray();

        usort($old, static::usort());
        usort($new, static::usort());

        $data['accounts'] = array_merge($old, $new);

        return [
            'uuid' => $this->uuid,
            'status' => $this->status,
            'data' => array_merge(Client::toForm(), $data),
            'meta_data' => $this->meta_data
        ];
    }

    protected static function checkDiff($client, array $data)
    {
        $arrayDeep = new ArrayDiffDeep;
        $clientData = $client->toData()['data'];

        $keyBy = fn ($a) => $a['accountNo'].'@'.$a['type'];
        $data['accounts'] = collect($data['accounts'])->keyBy($keyBy)->toArray();
        $clientData['accounts'] = collect($clientData['accounts'])->keyBy($keyBy)->toArray();

        $before = $arrayDeep->diff($data, $clientData);
        $after = $arrayDeep->diff($clientData, $data);

        return [$before, $after];
    }

    protected static function usort()
    {
        return fn ($a, $b) => [$a['accountNo'], $a['type']] <=> [$b['accountNo'], $b['type']];
    }
}
