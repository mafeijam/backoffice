<?php

namespace App\Modules\Client;

use App\Modules\Client\ClientStatus;
use App\Modules\Client\Models\Account;
use App\Modules\Client\Models\PendingClient;
use Illuminate\Http\Request;
use Lorisleiva\Actions\ActionRequest;

trait ClientRule
{
    public function rules()
    {
        return [
            'name' => ['required'],
            'accounts.*.accountNo' => ['required'],
            'accounts.*.type' => ['required'],
        ];
    }

    public function getValidationMessages()
    {
        return [
            'name.required' => 'Client name is required',
            'accounts.*.accountNo.required' => 'Each account required an account number',
            'accounts.*.type.required' => 'Each account required an account type',
        ];
    }

    public function prepareForValidation(ActionRequest $request)
    {
        $accounts = $request->input('accounts');

        $newAccounts = collect($accounts)->map(function ($acc) {
            $acc['accountNo'] = trim(strtoupper($acc['accountNo']));
            return $acc;
        })->toArray();

        $request->merge(['accounts' => $newAccounts]);
    }

    protected function checkRequestAccount(Request $request)
    {
        $collectAccounts = collect($request->accounts);
        $messages = collect([]);
        $combination = $collectAccounts->groupBy(fn ($a) => $a['accountNo'].'@'.$a['type'])
            ->map->count()->filter(fn ($a) => $a > 1);

        $active = $collectAccounts->filter(fn ($a) => $a['status'] === ClientStatus::ACTIVE)
            ->groupBy('accountNo')->map->count()->filter(fn ($a) => $a > 1);


        if ($combination->count()) {
            $messages[] = 'duplicate account + type combination: '. $combination->keys()->implode(', ');
        }

        if ($active->count()) {
            $messages[] = 'duplicate active account: '.$active->keys()->implode(', ');
        }

        return $messages;
    }

    protected function checkDuplicateInExistingAccounts($requestAccounts, $client = null)
    {
        $accounts = Account::whereIn('number', $requestAccounts)->get();
        if ($client) {
            $accounts = $accounts->where('client_uuid', '!=', $client->uuid);
        }

        return [
            $accounts->count(),
            $accounts->pluck('number')->intersect($requestAccounts)->unique()
        ];
    }

    protected function checkDuplicateInPendingAccounts($requestAccounts, $client = null)
    {
        $query = PendingClient::select('data->accounts as accounts');

        if ($client) {
            $query->where('uuid', '!=', $client->uuid);
        }

        $pendingAccounts = $query->get()
            ->map(fn ($acc) => json_decode($acc['accounts'], true))
            ->flatten(1)
            ->filter(fn ($acc) => !isset($acc['readonly']));

        $duplicatePending = $pendingAccounts->pluck('accountNo')->intersect($requestAccounts)->unique();

        return [
            $duplicatePending->count(),
            $duplicatePending
        ];
    }

    protected function checkDuplicate($requestAccounts, $client = null)
    {
        [$isDuplicated1, $duplicatedAccounts1] = $this->checkDuplicateInExistingAccounts($requestAccounts, $client);
        [$isDuplicated2, $duplicatedAccounts2] = $this->checkDuplicateInPendingAccounts($requestAccounts, $client);
        $allDuplicated = $duplicatedAccounts1->merge($duplicatedAccounts2)->implode(', ');

        return [
            $isDuplicated1 || $isDuplicated2,
            $allDuplicated
        ];
    }
}
