
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reports', function (Blueprint $table) {

            // Primary Key (UUID)
            $table->string('report_id', 36)
                ->default(DB::raw('(gen_random_uuid())::text'))
                ->primary();

            // Foreign Key
            $table->integer('post_id');

            // Report Information
            $table->string('reason', 255);

            $table->text('description')
                ->nullable();

            $table->timestamp('report_date')
                ->useCurrent();

            $table->string('report_status', 50)
                ->default('keep');

            // Relationship with Post
            $table->foreign('post_id', 'fk_reports_post')
                ->references('post_id')
                ->on('posts');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reports');
    }
};
