<?php

namespace App\Modules\MasterTable\Models;

use App\Models\BaseModel as Model;

class AE extends Model
{
    protected $table = 'ae';

    protected $casts = [
        'data' => 'json'
    ];
}
