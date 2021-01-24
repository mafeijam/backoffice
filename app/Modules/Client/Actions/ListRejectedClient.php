<?php

namespace App\Modules\Client\Actions;

use App\Modules\Client\ClientStatus;
use App\Modules\Client\Models\PendingClient;
use App\Modules\HasPaginate;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Lorisleiva\Actions\Concerns\AsAction;

class ListRejectedClient
{
    use AsAction, HasPaginate;

    public function asController(Request $request)
    {
        [$perPage, $sort, $desc] = $this->parseHeader($request);

        $map = [
            'client' => 'data->name',
            'accounts' => 'data->accounts',
            'submitted_at' => 'updated_at',
            'created_by' => 'c.name',
            'rejected_by' => 'r.name'
        ];

        $data = PendingClient::select('pending_clients.*', 'c.name as cname', 'r.name as rname')
            ->leftjoin('users as c', 'c.id', '=', 'pending_clients.meta_data->created_by')
            ->leftjoin('users as r', 'r.id', '=', 'pending_clients.meta_data->approved_by')
            ->whereIn('status', [ClientStatus::NEW_REJECTED, ClientStatus::UPDATE_REJECTED])
            ->orderBy($map[$sort], $desc)
            ->paginate($perPage);

        return Inertia::render('Client/Rejected', compact('data'));
    }
}
