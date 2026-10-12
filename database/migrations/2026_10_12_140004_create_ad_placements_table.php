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
        Schema::create('ad_placements', function (Blueprint $table) {
            $table->id();
            $table->string('identifier', 100)->unique(); // e.g. 'footer-banner', 'tool-top', 'tool-bottom', 'header-leaderboard'
            $table->string('name', 255);
            $table->string('page_location', 100)->default('all'); // 'all', 'home', 'tools', 'tool-view', 'content-page'
            $table->string('provider', 100)->default('adsense');
            $table->string('slot_id', 255)->nullable();
            $table->string('format', 50)->default('responsive');
            $table->boolean('is_active')->default(false)->index();
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ad_placements');
    }
};
