<?php

namespace App\Modules\MasterTable\Actions;

use Illuminate\Http\Request;
use Inertia\Inertia;
use Lorisleiva\Actions\Concerns\AsAction;

class ShowCreateAeForm
{
    use AsAction;

    public function asController(Request $request)
    {
        return Inertia::render('MasterTable/AE/Index', [
            'component' => 'MasterTable/AE/Create',
            'tabName' => 'create'
        ]);
    }
}
