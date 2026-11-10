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

test('valid credentials log the account in and redirect home', function () {
    $account = makeAccount();

    $this->post(route('login.store'), ['email' => 'sabela@student.test', 'password' => 'Password123'])
        ->assertRedirect(route('home'))
        ->assertSessionHasNoErrors();

    $this->assertAuthenticatedAs($account);
});

test('email is matched regardless of letter case', function () {
    $account = makeAccount();

    $this->post(route('login.store'), ['email' => 'Sabela@Student.Test', 'password' => 'Password123'])
        ->assertRedirect(route('home'));

    $this->assertAuthenticatedAs($account);
});

test('wrong password is rejected with a clear message', function () {
    makeAccount();

    $this->from(route('login'))
        ->post(route('login.store'), ['email' => 'sabela@student.test', 'password' => 'wrong-password'])
        ->assertRedirect(route('login'))
        ->assertSessionHasErrors('email');

    $this->assertGuest();
});

test('unknown email is rejected', function () {
    $this->post(route('login.store'), ['email' => 'tidakada@student.test', 'password' => 'Password123'])
        ->assertSessionHasErrors('email');

    $this->assertGuest();
});

test('remember me sets the remember token', function () {
    $account = makeAccount();

    $this->post(route('login.store'), ['email' => 'sabela@student.test', 'password' => 'Password123', 'remember' => '1']);

    expect($account->fresh()->remember_token)->not->toBeNull();
});

test('login is throttled after repeated failures', function () {
    makeAccount();

    foreach (range(1, 5) as $attempt) {
        $this->post(route('login.store'), ['email' => 'sabela@student.test', 'password' => 'wrong']);
    }

    $this->post(route('login.store'), ['email' => 'sabela@student.test', 'password' => 'Password123'])
        ->assertStatus(429);
});

test('logout ends the session and returns to the landing page', function () {
    $this->actingAs(makeAccount())
        ->post(route('logout'))
        ->assertRedirect(route('landing'));

    $this->assertGuest();
});

test('guests are sent to login when opening the homepage', function () {
    $this->get(route('home'))->assertRedirect(route('login'));
});

test('authenticated accounts can open the homepage and create post page', function () {
    $account = makeAccount();

    $this->actingAs($account)
        ->get(route('home'))
        ->assertOk();

    $this->actingAs($account)
        ->get(route('posts.create'))
        ->assertOk();
});

test('forgot password page can be rendered', function () {
    $this->get(route('password.request'))
        ->assertOk()
        ->assertSee('Back to Login');
});
