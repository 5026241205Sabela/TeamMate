<?php

use App\Models\Account;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

/*
|--------------------------------------------------------------------------
| Test Case
|--------------------------------------------------------------------------
*/

pest()->extend(TestCase::class)
    ->beforeEach(fn () => createTestSchema())
    ->in('Feature');

/*
|--------------------------------------------------------------------------
| Expectations
|--------------------------------------------------------------------------
*/

expect()->extend('toBeOne', function () {
    return $this->toBe(1);
});

/*
|--------------------------------------------------------------------------
| Functions
|--------------------------------------------------------------------------
*/

/**
 * Create the tables used by the feature tests in the in-memory SQLite database.
 *
 * The real migrations rely on PostgreSQL identity columns, so tests build an
 * equivalent schema instead and never touch the hosted database.
 */
function createTestSchema(): void
{
    if (Schema::hasTable('accounts')) {
        return;
    }

    Schema::create('accounts', function (Blueprint $table) {
        $table->increments('account_id');
        $table->string('full_name');
        $table->string('email')->unique();
        $table->timestamp('email_verified_at')->nullable();
        $table->string('phone_number', 20)->nullable();
        $table->string('university')->nullable();
        $table->string('nim', 30)->nullable()->unique();
        $table->string('ktm')->nullable();
        $table->string('password');
        $table->string('role', 30)->default('student');
        $table->string('verification_status', 30)->default('approved');
        $table->text('rejection_reason')->nullable();
        $table->rememberToken();
    });

    Schema::create('categories', function (Blueprint $table) {
        $table->increments('category_id');
        $table->string('category_name', 100)->unique();
    });

    Schema::create('posts', function (Blueprint $table) {
        $table->increments('post_id');
        $table->unsignedInteger('account_id');
        $table->unsignedInteger('category_id');
        $table->string('title');
        $table->text('description')->nullable();
        $table->string('poster')->nullable();
        $table->text('member_criteria')->nullable();
        $table->string('post_status', 50)->default('active');
        $table->timestamp('date_created')->useCurrent();
        $table->timestamp('status_change_time')->nullable();
        $table->timestamp('deletion_schedule')->nullable();
    });

    Schema::create('join_requests', function (Blueprint $table) {
        $table->increments('request_id');
        $table->unsignedInteger('post_id');
        $table->unsignedInteger('account_id');
        $table->string('request_status', 50)->default('pending');
        $table->timestamp('request_date')->useCurrent();
    });

    Schema::create('requirement_files', function (Blueprint $table) {
        $table->increments('file_id');
        $table->unsignedInteger('request_id');
        $table->string('file_type', 50);
        $table->string('storage_Location');
    });

    Schema::create('reports', function (Blueprint $table) {
        $table->increments('report_id');
        $table->unsignedInteger('post_id');
        $table->string('reason', 100);
        $table->text('description')->nullable();
        $table->timestamp('report_date')->useCurrent();
    });

    Schema::create('notifications', function (Blueprint $table) {
        $table->increments('notification_id');
        $table->unsignedInteger('account_id');
        $table->text('message');
        $table->boolean('read_status')->default(false);
        $table->timestamp('delivery_time')->useCurrent();
    });
}

/**
 * @param  array<string, mixed>  $attributes
 */
function makeAccount(array $attributes = [], bool $verified = true): Account
{
    $account = new Account(array_merge([
        'full_name' => 'Sabela Putri',
        'email' => 'sabela@student.test',
        'phone_number' => '081200000002',
        'university' => 'Institut Teknologi Sepuluh Nopember',
        'nim' => '5026241205',
        'password' => 'Password123',
    ], $attributes));

    $account->forceFill(['email_verified_at' => $verified ? now() : null])->save();

    return $account;
}
