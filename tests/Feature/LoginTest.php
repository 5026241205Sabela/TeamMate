<?php

beforeEach(function () {
    $this->withoutVite();
});

test('login page can be rendered', function () {
    $this->get(route('login'))
        ->assertOk()
        ->assertSee(['Email Address', 'Password', 'Remember Me', 'Forgot Password?'])
        ->assertSee(route('register'), false)
        ->assertSee(route('password.request'), false);
});

test('login requires email and password', function () {
    $this->post(route('login.store'), [])
        ->assertSessionHasErrors(['email', 'password']);
});

test('login requires a valid email format', function () {
    $this->post(route('login.store'), ['email' => 'invalid', 'password' => 'secret'])
        ->assertSessionHasErrors('email');
});

test('valid login input returns an informational message', function () {
    $this->from(route('login'))
        ->post(route('login.store'), ['email' => 'budi@example.com', 'password' => 'secret', 'remember' => '1'])
        ->assertRedirect(route('login'))
        ->assertSessionHasNoErrors()
        ->assertSessionHas('info');
});

test('forgot password page can be rendered', function () {
    $this->get(route('password.request'))
        ->assertOk()
        ->assertSee('Back to Login');
});
