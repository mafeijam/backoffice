<?php

namespace App\Modules\Client\Actions;

use App\Modules\Client\ClientAggregateRoot;
use App\Modules\Client\ClientRule;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Lorisleiva\Actions\Concerns\AsAction;

class CreatePendingClient
{
    use AsAction, ClientRule;

    public function asController(Request $request)
    {
        DB::beginTransaction();

        try {
            ClientAggregateRoot::retrieve(Str::uuid()->toString())
                ->add($request->all(), ['created_by' => auth()->id()])
                ->persist();

            DB::commit();

            return redirect('client/list')->with('success', 'created');
        } catch (Exception $e) {
            throw $e;
        }
    }
}
