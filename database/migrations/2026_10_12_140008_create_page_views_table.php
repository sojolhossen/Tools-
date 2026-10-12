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
        Schema::create('page_views', function (Blueprint $table): void {
            $table->id();
            $table->string('session_hash', 64)->index();
            $table->string('url', 255)->index();
            $table->string('path', 255)->index();
            $table->string('route_name', 100)->nullable()->index();
            $table->string('tool_slug', 100)->nullable()->index();
            $table->string('category', 50)->nullable()->index();
            $table->string('locale', 10)->default('en')->index();
            $table->string('referrer', 255)->nullable();
            $table->string('referrer_host', 100)->nullable()->index();
            $table->string('referrer_source', 50)->nullable()->index();
            $table->string('device_type', 20)->default('desktop')->index();
            $table->string('browser', 50)->nullable()->index();
            $table->string('os', 50)->nullable()->index();
            $table->boolean('is_bot')->default(false)->index();
            $table->date('viewed_date')->index();
            $table->timestamp('created_at')->nullable()->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('page_views');
    }
};
