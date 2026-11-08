
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('requirement_files', function (Blueprint $table) {

            // Primary Key
            $table->integer('file_id')
                ->generatedAs()
                ->primary();

            // Foreign Key
            $table->integer('request_id');

            // File Information
            $table->string('file_name', 255);

            $table->string('file_type', 100)
                ->nullable();

            $table->text('file_location');

            $table->timestamp('upload_date')
                ->useCurrent();

            // One-to-One Constraint
            $table->unique(
                'request_id',
                'uq_requirement_files_request'
            );

            // Relationship with Join Request
            $table->foreign(
                'request_id',
                'fk_requirement_files_request'
            )
                ->references('request_id')
                ->on('join_requests');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('requirement_files');
    }
};
