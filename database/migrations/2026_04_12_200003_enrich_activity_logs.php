<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Enrich activity_logs with forensic context (Who/What/Where/When).
     */
    public function up(): void
    {
        Schema::table('activity_logs', function (Blueprint $table) {
            $table->string('ip_hash', 64)->nullable()->after('properties');
            $table->string('user_agent', 255)->nullable()->after('ip_hash');
            $table->string('url', 500)->nullable()->after('user_agent');
            $table->json('old_values')->nullable()->after('url');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('activity_logs', function (Blueprint $table) {
            $table->dropColumn(['ip_hash', 'user_agent', 'url', 'old_values']);
        });
    }
};
