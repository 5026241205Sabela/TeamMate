
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('accounts', function (Blueprint $table) {

            // Primary Key
            $table->integer('account_id')
                ->generatedAs()
                ->primary();

            // Account Information
            $table->string('full_name', 255);

            $table->string('email', 255);

            $table->string('phone_number', 20)->nullable();

            $table->string('university', 255)->nullable();

            $table->string('nim', 30)->nullable();

            $table->string('ktm', 255)->nullable();

            $table->string('password', 255);

            $table->string('role', 30);

            // Unique Constraints
            $table->unique('email', 'accounts_email_key');

            $table->unique('nim', 'accounts_nim_key');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('accounts');
    }
};
