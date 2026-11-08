
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('categories', function (Blueprint $table) {

            // Primary Key
            $table->integer('category_id')
                ->generatedAs()
                ->primary();

            // Category Information
            $table->string('category_name', 100);

            // Unique Constraint
            $table->unique(
                'category_name',
                'categories_category_name_key'
            );
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('categories');
    }
};
