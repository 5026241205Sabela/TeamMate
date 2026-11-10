<?php

use App\Models\Account;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\URL;

beforeEach(function () {
    $this->withoutVite();
});

function verificationUrl(Account $account, ?string $email = null): string
{
    return URL::temporarySignedRoute('verification.verify', now()->addMinutes(60), [
        'id' => $account->getKey(),
        'hash' => sha1($email ?? $account->getEmailForVerification()),
    ]);
}

test('guests cannot open the verification notice', function () {
    $this->get(route('verification.notice'))->assertRedirect(route('login'));
});

test('unverified accounts see the verification notice', function () {
    $this->actingAs(makeAccount(verified: false))
        ->get(route('verification.notice'))
        ->assertOk()
        ->assertSee('Cek email kamu')
        ->assertSee('sabela@student.test');
});

test('verified accounts skip the verification notice', function () {
    $this->actingAs(makeAccount())
        ->get(route('verification.notice'))
        ->assertRedirect(route('home'));
});

test('the signed link verifies the email', function () {
    $account = makeAccount(verified: false);

    $this->actingAs($account)
        ->get(verificationUrl($account))
        ->assertRedirect(route('home'))
        ->assertSessionHas('success');

    expect($account->fresh()->hasVerifiedEmail())->toBeTrue();
});

test('a link with the wrong hash does not verify the email', function () {
    $account = makeAccount(verified: false);

    $this->actingAs($account)
        ->get(verificationUrl($account, 'someone-else@student.test'))
        ->assertForbidden();

    expect($account->fresh()->hasVerifiedEmail())->toBeFalse();
});

test('an unsigned link is rejected', function () {
    $account = makeAccount(verified: false);

    $this->actingAs($account)
        ->get(route('verification.verify', ['id' => $account->getKey(), 'hash' => sha1($account->email)], false))
        ->assertForbidden();
});

test('a new verification link can be requested', function () {
    Notification::fake();
    $account = makeAccount(verified: false);

    $this->actingAs($account)
        ->from(route('verification.notice'))
        ->post(route('verification.send'))
        ->assertRedirect(route('verification.notice'))
        ->assertSessionHas('success');

    Notification::assertSentTo($account, VerifyEmail::class);
});

test('verified accounts are not sent another verification link', function () {
    Notification::fake();
    $account = makeAccount();

    $this->actingAs($account)
        ->post(route('verification.send'))
        ->assertRedirect(route('home'));

    Notification::assertNothingSent();
});

test('the verification email is written in Indonesian with a verify button', function () {
    $account = makeAccount(verified: false);

    $mail = (new VerifyEmail)->toMail($account);

    expect($mail->subject)->toBe('Verifikasi email akun TeamMate kamu')
        ->and($mail->actionText)->toBe('Verifikasi Email')
        ->and($mail->actionUrl)->toContain('/email/verify/'.$account->getKey());
});
