<?php

use App\Models\Account;
use App\Notifications\AccountRejectedNotification;
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

test('admin can approve a pending account which sends the verification email, and clicking the email link logs the user into homepage', function () {
    Notification::fake();

    $admin = makeAccount(['email' => 'admin@teammate.test', 'nim' => '0000000001', 'role' => 'admin']);
    $student = makeAccount([
        'email' => 'baru@student.test',
        'nim' => '26019999',
        'verification_status' => 'pending',
    ], verified: false);

    $this->actingAs($admin)
        ->get(route('admin.verifications.index'))
        ->assertOk()
        ->assertSee($student->full_name);

    $this->actingAs($admin)
        ->patch(route('admin.verifications.approve', $student->account_id))
        ->assertRedirect()
        ->assertSessionHas('success');

    expect($student->fresh()->verification_status)->toBe('approved');
    Notification::assertSentTo($student, VerifyEmail::class);

    auth()->logout();

    $this->get(verificationUrl($student->fresh()))
        ->assertRedirect(route('home'))
        ->assertSessionHas('success');

    expect($student->fresh()->hasVerifiedEmail())->toBeTrue();
    $this->assertAuthenticatedAs($student->fresh());
});

test('admin can reject an invalid account and send a rejection email with the reason', function () {
    Notification::fake();

    $admin = makeAccount(['email' => 'admin@teammate.test', 'nim' => '0000000001', 'role' => 'admin']);
    $student = makeAccount([
        'email' => 'invalid@student.test',
        'nim' => '26018888',
        'verification_status' => 'pending',
    ], verified: false);

    $this->actingAs($admin)
        ->patch(route('admin.verifications.reject', $student->account_id), [
            'rejection_reason' => 'Foto KTM buram dan NIM tidak sesuai.',
        ])
        ->assertRedirect()
        ->assertSessionHas('success');

    expect($student->fresh()->verification_status)->toBe('rejected')
        ->and($student->fresh()->rejection_reason)->toBe('Foto KTM buram dan NIM tidak sesuai.');

    Notification::assertSentTo($student, AccountRejectedNotification::class);
});

test('non-admin accounts cannot access the admin verification portal', function () {
    $student = makeAccount();

    $this->actingAs($student)
        ->get(route('admin.verifications.index'))
        ->assertForbidden();
});
