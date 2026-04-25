<?php

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
        Schema::create('cybersec_profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('platform'); // 'tryhackme' or 'letsdefend'
            $table->string('username');
            $table->string('profile_url')->nullable();
            $table->string('rank')->nullable();
            $table->integer('rooms_completed')->default(0);
            $table->integer('badges_count')->default(0);
            $table->integer('streak')->default(0);
            $table->integer('points')->default(0);
            $table->string('top_percent')->nullable();
            $table->json('custom_stats')->nullable();
            $table->boolean('is_visible')->default(true);
            $table->integer('sort_order')->default(0);
            $table->timestamps();

            $table->unique(['user_id', 'platform']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cybersec_profiles');
    }
};
