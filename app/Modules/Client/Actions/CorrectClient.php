<?php

namespace App\Modules\Client\Actions;

use App\Modules\Client\ClientAggregateRoot;
use App\Modules\Client\Models\PendingClient;
use App\Modules\Client\ClientRule;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Lorisleiva\Actions\Concerns\AsAction;

class CorrectClient
{
    use AsAction, ClientRule;

    public function asController(Request $request, PendingClient $client)
    {
        DB::beginTransaction();

        try {
            ClientAggregateRoot::retrieve($client->uuid)
                ->correct($request->all(), ['created_by' => auth()->id()])
                ->persist();

            DB::commit();

            return redirect('client/list')->with('success', 'submitted');
        } catch (Exception $e) {
            throw $e;
        }
    }
}
