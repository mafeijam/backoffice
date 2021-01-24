<?php

namespace App\Modules\Client\Actions;

use App\Modules\Client\ClientAggregateRoot;
use App\Modules\Client\Models\PendingClient;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Lorisleiva\Actions\Concerns\AsAction;

class ApproveOrRejectPendingClient
{
    use AsAction;

    public function asController(Request $request, PendingClient $client)
    {
        $hasReason = $request->has('reason');

        $message = $hasReason ? 'rejected: ' : 'approved: ';
        $message .= $client->data['name'];

        DB::beginTransaction();

        try {
            $meta = array_merge($client->meta_data, ['approved_by' => auth()->id()]);
            $aggregate = ClientAggregateRoot::retrieve($client->uuid);

            $hasReason
                ? $aggregate->reject(array_merge($meta, $request->only('reason')))
                : $aggregate->approve($meta, $client);

            $aggregate->persist();

            DB::commit();

            return redirect('client/pending')->with('success', $message);
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function rules()
    {
        return [
            'reason' => ['sometimes', 'required'],
        ];
    }
}
