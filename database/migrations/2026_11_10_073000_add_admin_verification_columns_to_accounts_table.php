<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Add admin verification status and rejection reason to accounts.
     */
    public function up(): void
    {
        Schema::table('accounts', function (Blueprint $table) {
            $table->string('verification_status', 30)->default('approved')->after('role');
            $table->text('rejection_reason')->nullable()->after('verification_status');
        });
    }

    public function down(): void
    {
        Schema::table('accounts', function (Blueprint $table) {
            $table->dropColumn(['verification_status', 'rejection_reason']);
        });
    }
};
