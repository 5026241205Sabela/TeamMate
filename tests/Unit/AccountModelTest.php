<?php

use App\Models\Account;

test('account model uses account_id as primary key and hides sensitive attributes', function () {
    $account = new Account([
        'full_name' => 'Sabela Putri',
        'email' => 'sabela@student.test',
    ]);

    expect($account->getKeyName())->toBe('account_id')
        ->and($account->timestamps)->toBeFalse()
        ->and($account->getHidden())->toContain('password', 'remember_token');
});
