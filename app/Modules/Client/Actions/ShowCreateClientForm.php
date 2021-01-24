<?php

namespace App\Modules\Client\Actions;

use App\Modules\Client\Models\Client;
use Inertia\Inertia;
use Lorisleiva\Actions\Concerns\AsAction;

class ShowCreateClientForm
{
    use AsAction;

    public function asController()
    {
        return Inertia::render('Client/Create', [
            'formSchema' => Client::toForm()
        ]);
    }
}
