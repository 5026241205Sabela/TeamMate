<?php

beforeEach(function () {
    $this->withoutVite();
});

test('landing page displays hero, one-page login and register tabs, features and about sections for guests', function () {
    $this->get(route('landing'))
        ->assertOk()
        ->assertSee('Find Your Perfect Teammates')
        ->assertSee('Get Started')
        ->assertSee('Explore Opportunities')
        ->assertSee('Masuk ke Akunmu')
        ->assertSee('Buat Akun Mahasiswa')
        ->assertSee(['Find Teammates', 'Join Competitions', 'Build Your Team', 'Connect with Students'])
        ->assertSee('About TeamMate');
});

test('landing page links to the register and login routes', function () {
    $this->get(route('landing'))
        ->assertOk()
        ->assertSee(route('register.store'), false)
        ->assertSee(route('login.store'), false);
});

test('authenticated accounts visiting the landing URL are redirected to the homepage', function () {
    $this->actingAs(makeAccount())
        ->get(route('landing'))
        ->assertRedirect(route('home'));
});
