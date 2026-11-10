<?php

use App\Models\Category;
use App\Models\Post;

beforeEach(function () {
    $this->withoutVite();
});

function makePost(array $attributes = []): Post
{
    $account = $attributes['account'] ?? makeAccount(['email' => fake()->unique()->safeEmail(), 'nim' => fake()->unique()->numerify('##########')]);
    $category = $attributes['category'] ?? Category::firstOrCreate(['category_name' => 'Teknologi']);

    return Post::create(array_merge([
        'account_id' => $account->getKey(),
        'category_id' => $category->getKey(),
        'title' => 'Hackathon Nasional 2026',
        'description' => 'Mencari anggota tim untuk hackathon.',
        'member_criteria' => 'UI/UX Designer',
        'post_status' => 'active',
        'date_created' => now(),
    ], collect($attributes)->except(['account', 'category'])->all()));
}

test('guests are redirected to login when opening the homepage', function () {
    $this->get(route('home'))
        ->assertRedirect(route('login'));
});

test('authenticated accounts see the homepage with sidebar, filter and shimmer skeleton', function () {
    $account = makeAccount();

    $this->actingAs($account)
        ->get(route('home'))
        ->assertOk()
        ->assertSee('Find Your Competition Teammate')
        ->assertSee('Active Recruitment Posts')
        ->assertSee('data-feed-url="'.route('posts.latest').'"', false)
        ->assertSee('data-feed-skeleton', false)
        ->assertSee('skeleton', false)
        ->assertSee($account->full_name)
        ->assertDontSee('Verifikasi email kamu');
});

test('the homepage reminds unverified accounts to verify their email', function () {
    $this->actingAs(makeAccount(verified: false))
        ->get(route('home'))
        ->assertOk()
        ->assertSee('Verifikasi email kamu');
});

test('the feed lists only active posts, newest first', function () {
    makePost(['title' => 'Older team', 'date_created' => now()->subDays(2)]);
    makePost(['title' => 'Newest team', 'date_created' => now()]);
    makePost(['title' => 'Closed team', 'post_status' => 'closed']);

    $this->get(route('posts.latest'))
        ->assertOk()
        ->assertSeeInOrder(['Newest team', 'Older team'])
        ->assertDontSee('Closed team')
        ->assertSee('Teknologi')
        ->assertSee('Sabela');
});

test('the feed can be filtered by keyword and category', function () {
    $tech = Category::firstOrCreate(['category_name' => 'Teknologi']);
    $biz = Category::firstOrCreate(['category_name' => 'Bisnis']);

    makePost(['title' => 'Lomba AI Nasional', 'category' => $tech]);
    makePost(['title' => 'Business Case Competition', 'category' => $biz]);

    $this->get(route('posts.latest', ['q' => 'Business']))
        ->assertOk()
        ->assertSee('Business Case Competition')
        ->assertDontSee('Lomba AI Nasional');

    $this->get(route('posts.latest', ['category_id' => $tech->category_id]))
        ->assertOk()
        ->assertSee('Lomba AI Nasional')
        ->assertDontSee('Business Case Competition');
});

test('the feed shows an empty state with a call to action', function () {
    $this->get(route('posts.latest'))
        ->assertOk()
        ->assertSee('Tidak ada postingan kompetisi yang cocok')
        ->assertSee(route('posts.create'), false);
});

test('teammate pages (my posts, edit post status, join requests and report form) render without errors', function () {
    $account = makeAccount();
    $post = makePost(['account' => $account, 'title' => 'GEMASTIK XVII']);

    $this->actingAs($account)->get(route('posts.index'))->assertOk()->assertSee('GEMASTIK XVII');
    $this->actingAs($account)->get(route('posts.edit', $post->post_id))->assertOk()->assertSee('Post Status');
    $this->actingAs($account)->get(route('join-requests.index'))->assertOk()->assertSee('Join Requests');
    $this->actingAs($account)->get(route('reports.create', $post->post_id))->assertOk()->assertSee('Report Form');
});
