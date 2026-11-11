<?php

use App\Models\Account;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Storage;

beforeEach(function () {
    $this->withoutVite();
});

/**
 * @return array<string, mixed>
 */
function validRegistrationData(array $overrides = []): array
{
    return array_merge([
        'name' => 'Budi Santoso',
        'email' => 'budi@example.com',
        'phone' => '081234567890',
        'university' => 'Universitas Indonesia',
        'student_id' => '2201234567',
        'ktm' => UploadedFile::fake()->create('ktm.pdf', 500, 'application/pdf'),
        'password' => 'password123',
        'password_confirmation' => 'password123',
    ], $overrides);
}

test('register page can be rendered', function () {
    $this->get(route('register'))
        ->assertOk()
        ->assertSee(['Full Name', 'Email Address', 'Phone Number', 'University', 'Student ID (NIM)', 'KTM Upload', 'Confirm Password'])
        ->assertSee(route('login'), false);
});

test('valid registration creates a pending account, stores the ktm, and waits for admin verification before sending email', function () {
    Storage::fake('local');
    Notification::fake();

    $this->post(route('register.store'), validRegistrationData())
        ->assertRedirect(route('verification.notice'))
        ->assertSessionHas('success');

    $account = Account::firstWhere('email', 'budi@example.com');

    expect($account)->not->toBeNull()
        ->and($account->full_name)->toBe('Budi Santoso')
        ->and($account->nim)->toBe('2201234567')
        ->and($account->role)->toBe('student')
        ->and($account->verification_status)->toBe('pending')
        ->and($account->hasVerifiedEmail())->toBeFalse()
        ->and(Hash::check('password123', $account->password))->toBeTrue();

    Storage::disk('local')->assertExists($account->ktm);
    Notification::assertNothingSent();
    $this->assertAuthenticatedAs($account);

    $this->get(route('home'))->assertRedirect(route('verification.notice'));
});

test('pending student sees waiting for admin verification screen on verification notice', function () {
    Storage::fake('local');

    $this->post(route('register.store'), validRegistrationData());

    $this->get(route('verification.notice'))
        ->assertOk()
        ->assertSee('Menunggu Verifikasi Admin')
        ->assertSee('Budi Santoso');
});

test('email and student id must be unique', function () {
    makeAccount(['email' => 'budi@example.com', 'nim' => '2201234567']);

    $this->post(route('register.store'), validRegistrationData())
        ->assertSessionHasErrors(['email', 'student_id']);
});

test('email uniqueness ignores letter case', function () {
    makeAccount(['email' => 'budi@example.com', 'nim' => '1111111111']);

    $this->post(route('register.store'), validRegistrationData(['email' => 'BUDI@Example.com']))
        ->assertSessionHasErrors('email');
});

test('authenticated accounts cannot open the register page', function () {
    $this->actingAs(makeAccount())
        ->get(route('register'))
        ->assertRedirect();
});

test('ktm accepts jpg and png images', function (string $filename) {
    Storage::fake('local');

    $this->post(route('register.store'), validRegistrationData([
        'ktm' => UploadedFile::fake()->image($filename),
    ]))->assertSessionHasNoErrors();
})->with(['ktm.jpg', 'ktm.png']);

test('all registration fields are required', function () {
    $this->post(route('register.store'), [])
        ->assertSessionHasErrors(['name', 'email', 'phone', 'university', 'student_id', 'ktm', 'password']);
});

test('registration requires a valid email format', function () {
    $this->post(route('register.store'), validRegistrationData(['email' => 'not-an-email']))
        ->assertSessionHasErrors('email');
});

test('registration requires matching password confirmation', function () {
    $this->post(route('register.store'), validRegistrationData(['password_confirmation' => 'different123']))
        ->assertSessionHasErrors('password');
});

test('ktm must be a pdf, jpg or png file', function () {
    $this->post(route('register.store'), validRegistrationData([
        'ktm' => UploadedFile::fake()->create('ktm.docx', 100),
    ]))->assertSessionHasErrors('ktm');
});

test('ktm may not be larger than 2 MB', function () {
    $this->post(route('register.store'), validRegistrationData([
        'ktm' => UploadedFile::fake()->create('ktm.pdf', 3000, 'application/pdf'),
    ]))->assertSessionHasErrors('ktm');
});

test('an account rejected by admin can register again with the same email and nim', function () {
    $rejected = makeAccount([
        'full_name' => 'Ilham',
        'email' => 'ilham@student.test',
        'nim' => '283993884',
        'verification_status' => 'rejected',
        'rejection_reason' => 'NIM pada formulir tidak sesuai dengan foto KTM.',
    ], verified: false);

    $this->post(route('register.store'), validRegistrationData([
        'name' => 'Ilham Perbaikan',
        'email' => 'ilham@student.test',
        'student_id' => '221220001',
    ]))->assertRedirect(route('verification.notice'));

    $updated = $rejected->fresh();
    expect($updated->full_name)->toBe('Ilham Perbaikan')
        ->and($updated->nim)->toBe('221220001')
        ->and($updated->verification_status)->toBe('pending')
        ->and($updated->rejection_reason)->toBeNull();
});
