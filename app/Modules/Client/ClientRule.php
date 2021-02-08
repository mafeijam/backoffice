<?php

namespace App\Modules\Client;

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
}
