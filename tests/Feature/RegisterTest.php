<?php

use App\Models\Account;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Event;
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

test('valid registration creates an account, stores the ktm, sends a verification email and redirects to the homepage', function () {
    Storage::fake('local');
    Notification::fake();

    $this->post(route('register.store'), validRegistrationData())
        ->assertRedirect(route('home'))
        ->assertSessionHas('success');

    $account = Account::firstWhere('email', 'budi@example.com');

    expect($account)->not->toBeNull()
        ->and($account->full_name)->toBe('Budi Santoso')
        ->and($account->nim)->toBe('2201234567')
        ->and($account->role)->toBe('student')
        ->and($account->hasVerifiedEmail())->toBeFalse()
        ->and(Hash::check('password123', $account->password))->toBeTrue();

    Storage::disk('local')->assertExists($account->ktm);
    Notification::assertSentTo($account, VerifyEmail::class);
    $this->assertAuthenticatedAs($account);
});

test('registration still succeeds when the verification email cannot be sent', function () {
    Storage::fake('local');
    Event::listen(Registered::class, fn () => throw new RuntimeException('SMTP down'));

    $this->post(route('register.store'), validRegistrationData())
        ->assertRedirect(route('home'))
        ->assertSessionHas('info');

    expect(Account::where('email', 'budi@example.com')->exists())->toBeTrue();
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
