
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('posts', function (Blueprint $table) {

            // Primary Key
            $table->integer('post_id')
                ->generatedAs()
                ->primary();

            // Foreign Keys
            $table->integer('account_id');
            $table->integer('category_id');

            // Post Information
            $table->string('title', 255);

            $table->text('description')->nullable();

            $table->string('poster', 255)->nullable();

            $table->text('member_criteria')->nullable();

            $table->string('post_status', 50)
                ->default('active');

            // Date Information
            $table->timestamp('date_created')
                ->useCurrent();

            $table->timestamp('status_change_time')
                ->nullable();

            $table->timestamp('deletion_schedule')
                ->nullable();

            // Relationship with Account
            $table->foreign('account_id', 'fk_posts_account')
                ->references('account_id')
                ->on('accounts');

            // Relationship with Category
            $table->foreign('category_id', 'fk_posts_category')
                ->references('category_id')
                ->on('categories');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('posts');
    }
};
