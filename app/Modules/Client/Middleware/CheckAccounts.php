<?php

namespace App\Modules\Client\Middleware;

use App\Modules\Client\ClientStatus;
use App\Modules\Client\Models\Account;
use App\Modules\Client\Models\PendingClient;
use Closure;
use Illuminate\Http\Request;

class CheckAccounts
{
    public function handle(Request $request, Closure $next)
    {
        $messages = $this->checkRequestAccounts($request);

        if ($messages->count()) {
            return back()->with('error', $messages->implode('<br>'));
        }

        $isDuplicated = $this->checkDuplicate($request);

        if ($isDuplicated->count()) {
            return back()->with('error', 'duplicate account number: '.$isDuplicated->implode(', '));
        }

        return $next($request);
    }

    protected function checkRequestAccounts(Request $request)
    {
        $collectAccounts = collect($request->accounts);

        $combination = $collectAccounts->groupBy(fn ($a) => $a['accountNo'].'@'.$a['type'])
            ->map->count()->filter(fn ($a) => $a > 1);

        $active = $collectAccounts->filter(fn ($a) => $a['status'] === ClientStatus::ACTIVE)
            ->groupBy('accountNo')->map->count()->filter(fn ($a) => $a > 1);

        $messages = collect([]);

        if ($combination->count()) {
            $messages[] = 'duplicate account + type combination: '. $combination->keys()->implode(', ');
        }

        if ($active->count()) {
            $messages[] = 'duplicate active account: '.$active->keys()->implode(', ');
        }

        return $messages;
    }

    protected function checkDuplicate($request)
    {
        $requestAccounts = $request->input('accounts.*.accountNo');
        $client = $request->route('client');

        $isDuplicated = $this->checkDuplicateAccounts($requestAccounts, $client);
        $isDuplicatedPending = $this->checkDuplicatePendingAccounts($requestAccounts, $client);

        return $isDuplicated->merge($isDuplicatedPending);
    }

    protected function checkDuplicateAccounts($requestAccounts, $client = null)
    {
        $accounts = Account::getAccountNumbers($requestAccounts, $client);

        return $accounts->intersect($requestAccounts)->unique();
    }

    protected function checkDuplicatePendingAccounts($requestAccounts, $client = null)
    {
        $pendingAccounts = PendingClient::getAccountNumbers($client);

        return $pendingAccounts->intersect($requestAccounts)->unique();
    }
}
