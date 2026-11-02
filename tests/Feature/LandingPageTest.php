<?php

beforeEach(function () {
    $this->withoutVite();
});

test('landing page displays hero, features and about sections', function () {
    $this->get(route('home'))
        ->assertOk()
        ->assertSee('Find Your Perfect Teammates')
        ->assertSee('Get Started')
        ->assertSee('Explore Opportunities')
        ->assertSee(['Find Teammates', 'Join Competitions', 'Build Your Team', 'Connect with Students'])
        ->assertSee('About TeamMate');
});

test('landing page links to the register and login pages', function () {
    $this->get(route('home'))
        ->assertOk()
        ->assertSee(route('register'), false)
        ->assertSee(route('login'), false);
});
