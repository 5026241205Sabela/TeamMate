<?php

use App\Models\Account;
use App\Models\Category;
use App\Models\JoinRequest;
use App\Models\Notification;
use App\Models\Post;
use App\Models\Report;
use App\Models\RequirementFile;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

beforeEach(function () {
    $this->withoutVite();
    Storage::fake('local');
    Storage::fake('public');
});

function createCategory(string $name = 'Teknologi'): Category
{
    return Category::firstOrCreate(['category_name' => $name]);
}

/**
 * @param  array<string, mixed>  $attributes
 */
function createPostForAccount(Account $account, array $attributes = []): Post
{
    $category = $attributes['category'] ?? createCategory();

    return Post::create(array_merge([
        'account_id' => $account->getKey(),
        'category_id' => $category->getKey(),
        'title' => 'National Hackathon 2026',
        'description' => 'Mencari backend developer dan UI designer.',
        'member_criteria' => 'Mahasiswa aktif minimal semester 3.',
        'post_status' => 'active',
        'date_created' => now(),
    ], collect($attributes)->except(['category'])->all()));
}

test('F3: verified student can open post detail page showing author full name and role-appropriate buttons', function () {
    $owner = makeAccount(['full_name' => 'Rina Amelia', 'email' => 'rina@student.test', 'nim' => '5026240001']);
    $viewer = makeAccount(['full_name' => 'Budi Santoso', 'email' => 'budi@student.test', 'nim' => '5026240002']);
    $post = createPostForAccount($owner, ['title' => 'Gemastik Software Engineering']);

    $this->actingAs($viewer)
        ->get(route('posts.show', $post->post_id))
        ->assertOk()
        ->assertSee('Gemastik Software Engineering')
        ->assertSee('Rina Amelia')
        ->assertSee('Apply')
        ->assertSee('Report')
        ->assertDontSee('Confirm Delete');

    $this->actingAs($owner)
        ->get(route('posts.show', $post->post_id))
        ->assertOk()
        ->assertSee('Edit Status')
        ->assertSee('Requests')
        ->assertSee('Delete');
});

test('F4: creating a competition post saves the poster and redirects to the new post detail page', function () {
    $owner = makeAccount();
    $category = createCategory('Bisnis');

    $response = $this->actingAs($owner)
        ->post(route('posts.store'), [
            'title' => 'Business Case Competition 2026',
            'category_id' => $category->category_id,
            'description' => 'Dicari analyst dan presenter.',
            'member_criteria' => 'Terbuka untuk jurusan manajemen dan TI.',
            'poster' => UploadedFile::fake()->image('poster.jpg', 600, 400),
        ]);

    $post = Post::where('title', 'Business Case Competition 2026')->first();
    expect($post)->not->toBeNull();
    Storage::disk('public')->assertExists($post->poster);

    $response->assertRedirect(route('posts.show', $post->post_id))
        ->assertSessionHas('success');
});

test('F5: post owner can delete their own post including related join requests and reports, while non-owners cannot', function () {
    $owner = makeAccount(['email' => 'owner@student.test', 'nim' => '5026240011']);
    $other = makeAccount(['email' => 'other@student.test', 'nim' => '5026240012']);

    $posterPath = UploadedFile::fake()->image('poster.png')->store('posters', 'public');
    $reqPath = UploadedFile::fake()->create('cv.pdf', 100, 'application/pdf')->store('requirements', 'local');

    $post = createPostForAccount($owner, ['poster' => $posterPath]);
    $joinRequest = JoinRequest::create([
        'account_id' => $other->account_id,
        'post_id' => $post->post_id,
        'request_status' => 'pending',
    ]);
    RequirementFile::create([
        'request_id' => $joinRequest->request_id,
        'file_name' => 'cv.pdf',
        'file_type' => 'pdf',
        'file_location' => $reqPath,
    ]);
    Report::create([
        'post_id' => $post->post_id,
        'reason' => 'Other',
        'description' => 'Sample report',
        'report_status' => 'pending',
    ]);

    $this->actingAs($other)
        ->delete(route('posts.destroy', $post->post_id))
        ->assertNotFound();

    expect(Post::find($post->post_id))->not->toBeNull();

    $this->actingAs($owner)
        ->delete(route('posts.destroy', $post->post_id))
        ->assertRedirect(route('posts.index'))
        ->assertSessionHas('success');

    expect(Post::find($post->post_id))->toBeNull()
        ->and(JoinRequest::find($joinRequest->request_id))->toBeNull()
        ->and(Report::where('post_id', $post->post_id)->count())->toBe(0);

    Storage::disk('public')->assertMissing($posterPath);
    Storage::disk('local')->assertMissing($reqPath);
});

test('F6: marking a post as completed schedules deletion in 2 days and delete-completed command cleans up expired posts with relations', function () {
    $owner = makeAccount(['email' => 'owner6@student.test', 'nim' => '5026240061']);
    $applicant = makeAccount(['email' => 'app6@student.test', 'nim' => '5026240062']);
    $post = createPostForAccount($owner);

    $this->actingAs($owner)
        ->patch(route('posts.update-status', $post->post_id), ['status' => 'completed'])
        ->assertRedirect(route('posts.index'))
        ->assertSessionHas('success', 'Status successfully updated');

    $post->refresh();
    expect($post->post_status)->toBe('completed')
        ->and($post->status_change_time)->not->toBeNull()
        ->and($post->deletion_schedule)->not->toBeNull()
        ->and((int) round($post->status_change_time->diffInHours($post->deletion_schedule)))->toBe(48);

    $post->deletion_schedule = now()->subMinute();
    $post->save();
    JoinRequest::create([
        'account_id' => $applicant->account_id,
        'post_id' => $post->post_id,
        'request_status' => 'pending',
    ]);

    $this->artisan('posts:delete-completed')->assertSuccessful();

    expect(Post::find($post->post_id))->toBeNull();
});

test('F8: non-owner student can apply with requirement file, sees PRD confirmation, and owner receives notification', function () {
    $owner = makeAccount(['email' => 'owner8@student.test', 'nim' => '5026240081']);
    $applicant = makeAccount(['full_name' => 'Andi Pelamar', 'email' => 'app8@student.test', 'nim' => '5026240082']);
    $post = createPostForAccount($owner);

    $this->actingAs($applicant)
        ->get(route('join-requests.create', $post->post_id))
        ->assertOk()
        ->assertSee('Join Request Form')
        ->assertSee($post->title);

    $this->actingAs($applicant)
        ->post(route('join-requests.store', $post->post_id), [
            'requirement_file' => UploadedFile::fake()->create('cv_andi.pdf', 200, 'application/pdf'),
        ])
        ->assertRedirect(route('home'))
        ->assertSessionHas('success', 'Application is successfully submitted and recorded');

    $joinRequest = JoinRequest::where('post_id', $post->post_id)->where('account_id', $applicant->account_id)->first();
    expect($joinRequest)->not->toBeNull()
        ->and($joinRequest->request_status)->toBe('pending')
        ->and($joinRequest->requirementFile)->not->toBeNull()
        ->and($joinRequest->requirementFile->file_name)->toBe('cv_andi.pdf');

    Storage::disk('local')->assertExists($joinRequest->requirementFile->file_location);

    expect(Notification::where('account_id', $owner->account_id)->count())->toBe(1);

    $this->actingAs($applicant)
        ->get(route('join-requests.create', $post->post_id))
        ->assertRedirect(route('posts.show', $post->post_id));

    $this->actingAs($owner)
        ->get(route('join-requests.create', $post->post_id))
        ->assertRedirect(route('posts.show', $post->post_id));
});

test('F9: join requests index only lists applicants for the logged-in owner and notifies applicant on accept/decline', function () {
    $ownerA = makeAccount(['full_name' => 'Owner A', 'email' => 'ownera@student.test', 'nim' => '5026240091']);
    $ownerB = makeAccount(['full_name' => 'Owner B', 'email' => 'ownerb@student.test', 'nim' => '5026240092']);
    $applicant = makeAccount(['full_name' => 'Pelamar A', 'email' => 'appla@student.test', 'nim' => '5026240093']);

    $postA = createPostForAccount($ownerA, ['title' => 'Postingan A']);
    $postB = createPostForAccount($ownerB, ['title' => 'Postingan B']);

    $reqA = JoinRequest::create(['account_id' => $applicant->account_id, 'post_id' => $postA->post_id, 'request_status' => 'pending']);
    $reqB = JoinRequest::create(['account_id' => $applicant->account_id, 'post_id' => $postB->post_id, 'request_status' => 'pending']);

    $this->actingAs($ownerA)
        ->get(route('join-requests.index'))
        ->assertOk()
        ->assertSee('Postingan A')
        ->assertDontSee('Postingan B');

    $this->actingAs($ownerA)
        ->patch(route('join-requests.update-status', $reqB->request_id), ['request_status' => 'accepted'])
        ->assertForbidden();

    $this->actingAs($ownerA)
        ->patch(route('join-requests.update-status', $reqA->request_id), ['request_status' => 'accepted'])
        ->assertRedirect(route('join-requests.index'))
        ->assertSessionHas('success', 'Join request berhasil diterima.');

    expect($reqA->fresh()->request_status)->toBe('accepted')
        ->and(Notification::where('account_id', $applicant->account_id)->where('message', 'like', '%diterima%')->count())->toBe(1);

    $this->actingAs($ownerB)
        ->patch(route('join-requests.update-status', $reqB->request_id), ['request_status' => 'declined'])
        ->assertRedirect(route('join-requests.index'))
        ->assertSessionHas('success', 'Join request berhasil ditolak.');

    expect($reqB->fresh()->request_status)->toBe('declined')
        ->and(Notification::where('account_id', $applicant->account_id)->where('message', 'like', '%ditolak%')->count())->toBe(1);
});

test('F10 & F11: student submits pending report with description check, admin can keep or remove post with owner notification', function () {
    $admin = makeAccount(['email' => 'admin@teammate.test', 'nim' => null, 'role' => 'admin']);
    $owner = makeAccount(['full_name' => 'Pemilik Post', 'email' => 'pemilik@student.test', 'nim' => '5026240111']);
    $reporter = makeAccount(['full_name' => 'Pelapor', 'email' => 'pelapor@student.test', 'nim' => '5026240112']);

    $postKeep = createPostForAccount($owner, ['title' => 'Postingan Aman']);
    $postRemove = createPostForAccount($owner, ['title' => 'Postingan Melanggar']);

    // F10
    $this->actingAs($reporter)
        ->get(route('reports.create', $postKeep->post_id))
        ->assertOk()
        ->assertSee('add a description first');

    $this->actingAs($reporter)
        ->post(route('reports.store', $postKeep->post_id), [
            'reason' => 'Spam or misleading',
            'description' => 'Terlihat duplikat.',
        ])
        ->assertRedirect(route('reports.create', $postKeep->post_id))
        ->assertSessionHas('success');

    $this->actingAs($reporter)
        ->post(route('reports.store', $postRemove->post_id), [
            'reason' => 'Inappropriate content',
            'description' => 'Mengandung informasi palsu.',
        ])
        ->assertRedirect(route('reports.create', $postRemove->post_id));

    expect(Report::where('post_id', $postKeep->post_id)->first()->report_status)->toBe('pending');

    $this->actingAs($reporter)
        ->get(route('admin.reports.index'))
        ->assertForbidden();

    $this->actingAs($admin)
        ->get(route('admin.reports.index'))
        ->assertOk()
        ->assertSee('Postingan Aman')
        ->assertSee('Postingan Melanggar');

    $this->actingAs($admin)
        ->patch(route('admin.reports.keep-post', $postKeep->post_id))
        ->assertRedirect(route('admin.reports.index'))
        ->assertSessionHas('success');

    expect(Post::find($postKeep->post_id))->not->toBeNull()
        ->and(Report::where('post_id', $postKeep->post_id)->first()->report_status)->toBe('dismissed');

    $this->actingAs($admin)
        ->delete(route('admin.reports.remove-post', $postRemove->post_id))
        ->assertRedirect(route('admin.reports.index'))
        ->assertSessionHas('success');

    expect(Post::find($postRemove->post_id))->toBeNull()
        ->and(Notification::where('account_id', $owner->account_id)->where('message', 'like', '%Postingan Melanggar%')->count())->toBe(1);

    $this->actingAs($owner)
        ->get(route('notifications.index'))
        ->assertOk()
        ->assertSee('Postingan Melanggar');

    $this->actingAs($owner)
        ->get(route('profile.show'))
        ->assertOk()
        ->assertSee($owner->full_name)
        ->assertSee($owner->nim);
});
