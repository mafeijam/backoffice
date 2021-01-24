<?php

use App\Modules\Client\Actions\ApproveOrRejectPendingClient;
use App\Modules\Client\Actions\CorrectClient;
use App\Modules\Client\Actions\CreatePendingClient;
use App\Modules\Client\Actions\EditClient;
use App\Modules\Client\Actions\GetClientInfo;
use App\Modules\Client\Actions\GetPendingClientInfo;
use App\Modules\Client\Actions\ListClient;
use App\Modules\Client\Actions\ListPendingClient;
use App\Modules\Client\Actions\ListRejectedClient;
use App\Modules\Client\Actions\ShowCreateClientForm;
use App\Modules\Client\Actions\ShowEditClientForm;
use App\Modules\Client\Actions\ShowRejectedClientForm;
use Illuminate\Support\Facades\Route;

Auth::loginUsingId(1);

Route::inertia('/', 'Index');

Route::prefix('/client')->group(function () {
    Route::get('/list', ListClient::class);
    Route::get('/pending', ListPendingClient::class);
    Route::get('/rejected', ListRejectedClient::class);
    Route::get('/create', ShowCreateClientForm::class);

    Route::get('/approve/{client:uuid}', GetPendingClientInfo::class);
    Route::get('/view/{client:uuid}', GetClientInfo::class);
    Route::get('/edit/{client:uuid}', ShowEditClientForm::class);
    Route::get('/correct/{client:uuid}', ShowRejectedClientForm::class);

    Route::post('/create', CreatePendingClient::class);
    Route::put('/edit/{client:uuid}', EditClient::class);
    Route::put('/correct/{client:uuid}', CorrectClient::class);
    Route::patch('/{client:uuid}', ApproveOrRejectPendingClient::class);
});

Route::inertia('/login', 'Login');
