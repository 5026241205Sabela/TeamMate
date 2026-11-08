
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('join_requests', function (Blueprint $table) {

            // Primary Key
            $table->integer('request_id')
                ->generatedAs()
                ->primary();

            // Foreign Keys
            $table->integer('account_id');
            $table->integer('post_id');

            // Join Request Information
            $table->timestamp('request_date')
                ->useCurrent();

            $table->string('request_status', 50)
                ->default('pending');

            // Relationship with Account
            $table->foreign('account_id', 'fk_join_requests_account')
                ->references('account_id')
                ->on('accounts');

            // Relationship with Post
            $table->foreign('post_id', 'fk_join_requests_post')
                ->references('post_id')
                ->on('posts');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('join_requests');
    }
};
