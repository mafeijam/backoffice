<?php

namespace App\Modules;

use Illuminate\Http\Request;

trait HasPaginate
{
    public function parseHeader(Request $request, $sort = 'submitted_at')
    {
        return [
            $request->header('X-PerPage', 30),
            $request->header('X-Sort', $sort),
            $request->header('X-Desc', 'asc')
        ];
    }
}
