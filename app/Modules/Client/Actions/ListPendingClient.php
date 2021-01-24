<?php

namespace App\Modules\Client\Actions;

use App\Modules\Client\ClientStatus;
use App\Modules\Client\Models\PendingClient;
use App\Modules\HasPaginate;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Lorisleiva\Actions\Concerns\AsAction;

class ListPendingClient
{
    use AsAction, HasPaginate;

    public function asController(Request $request)
    {
        [$perPage, $sort, $desc] = $this->parseHeader($request);

        $map = [
            'client' => 'data->name',
            'accounts' => 'data->accounts',
            'status' => 'status',
            'submitted_at' => 'updated_at',
            'created_by' => 'users.name'
        ];

        $data = PendingClient::select('pending_clients.*', 'users.name as uname')
            ->leftjoin('users', 'users.id', '=', 'pending_clients.meta_data->created_by')
            ->whereIn('status', [ClientStatus::NEW, ClientStatus::UPDATE])
            ->orderBy($map[$sort], $desc)
            ->paginate($perPage);

        return Inertia::render('Client/Approve', compact('data'));
    }
}
