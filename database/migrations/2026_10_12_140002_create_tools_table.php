<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('tools', function (Blueprint $table) {
            $table->id();
            $table->string('slug', 100)->unique();
            $table->string('category_slug', 100)->index();
            $table->string('name_en');
            $table->string('name_bn');
            $table->text('description_en')->nullable();
            $table->text('description_bn')->nullable();
            $table->string('meta_title_en')->nullable();
            $table->string('meta_title_bn')->nullable();
            $table->text('meta_description_en')->nullable();
            $table->text('meta_description_bn')->nullable();
            $table->string('icon', 100)->default('wrench');
            $table->string('view_handler', 100); // Allowlisted blade view template
            $table->string('route_name', 100)->default('tools.show');
            $table->string('status', 50)->default('active')->index(); // 'active' or 'inactive'
            $table->boolean('is_featured')->default(false)->index();
            $table->boolean('is_popular')->default(false)->index();
            $table->integer('sort_order')->default(0)->index();
            $table->json('tags')->nullable();
            $table->json('supported_locales')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tools');
    }
};
