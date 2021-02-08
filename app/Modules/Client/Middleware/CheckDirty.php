<?php

namespace App\Modules\Client\Middleware;

use App\Modules\Client\Models\PendingClient;
use Closure;
use Illuminate\Http\Request;

class CheckDirty
{
    public function handle(Request $request, Closure $next)
    {
        $client = $request->route('client');

        [$before, $after] = PendingClient::checkDiff($client, $request->all());

        if (count($before) || count($after)) {
            return $next($request);
        }

        return back()->with('error', 'nothing changed');
    }
}
