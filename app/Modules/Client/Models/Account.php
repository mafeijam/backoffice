<?php

namespace App\Modules\Client\Models;

use App\Models\BaseModel as Model;

class Account extends Model
{
    protected $table = 'accounts';

    protected $casts = [
        'data' => 'json'
    ];

    public function client()
    {
        return $this->belongsTo(Client::class, 'client_uuid', 'uuid');
    }
}
