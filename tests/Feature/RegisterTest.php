<?php

use Illuminate\Http\UploadedFile;

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

test('valid registration redirects to login with a success message', function () {
    $this->post(route('register.store'), validRegistrationData())
        ->assertRedirect(route('login'))
        ->assertSessionHas('success');
});

test('ktm accepts jpg and png images', function (string $filename) {
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
