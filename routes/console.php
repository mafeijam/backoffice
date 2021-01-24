<?php

use Illuminate\Support\Facades\Artisan;

Artisan::command('init', function () {
    Artisan::call('migrate:fresh');
    App\Models\User::create([
        'name' => 'admin',
        'email' => 'admin@abc.com',
        'password' => bcrypt(123456)
    ]);
});
