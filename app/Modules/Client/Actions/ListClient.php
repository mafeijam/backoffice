<?php

namespace App\Modules\Client\Actions;

use App\Modules\Client\Models\Account;
use App\Modules\HasPaginate;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Lorisleiva\Actions\Concerns\AsAction;

class ListClient
{
    use AsAction, HasPaginate;

    public function asController(Request $request)
    {
        [$perPage, $sort, $desc] = $this->parseHeader($request, 'client');

        $map = [
            'client' => 'clients.data->name',
            'account' => 'number',
            'type' => 'type',
            'status' => 'status'
        ];

        $data = Account::select('accounts.*', 'clients.data->name as cname', 'pending_clients.status as pstatus')
            ->leftJoin('clients', 'clients.uuid', '=', 'accounts.client_uuid')
            ->leftJoin('pending_clients', function ($join) {
                $join->on('pending_clients.uuid', '=', 'clients.uuid')
                    ->whereNull('pending_clients.deleted_at');
            })
            ->orderBy($map[$sort], $desc)
            ->paginate($perPage);

        return Inertia::render('Client/List', compact('data'));
    }
}
