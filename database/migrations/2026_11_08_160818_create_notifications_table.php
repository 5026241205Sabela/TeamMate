
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('notifications', function (Blueprint $table) {

            // Primary Key
            $table->integer('notification_id')
                ->generatedAs()
                ->primary();

            // Foreign Key
            $table->integer('account_id');

            // Notification Information
            $table->text('message');

            $table->timestamp('time_created')
                ->useCurrent();

            // Relationship with Account
            $table->foreign('account_id', 'fk_notifications_account')
                ->references('account_id')
                ->on('accounts');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('notifications');
    }
};
