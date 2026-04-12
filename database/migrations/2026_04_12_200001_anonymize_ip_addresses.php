<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * Renames ip_address to ip_hash and converts existing plain IPs
     * to HMAC-SHA256 hashes for UU PDP compliance.
     */
    public function up(): void
    {
        $appKey = config('app.key');

        // --- Analytics table ---
        Schema::table('analytics', function (Blueprint $table) {
            $table->dropUnique(['user_id', 'type', 'date', 'ip_address']);
        });

        Schema::table('analytics', function (Blueprint $table) {
            $table->renameColumn('ip_address', 'ip_hash');
        });

        Schema::table('analytics', function (Blueprint $table) {
            $table->string('ip_hash', 64)->nullable()->change();
            $table->unique(['user_id', 'type', 'date', 'ip_hash']);
        });

        // Hash existing analytics IPs
        DB::table('analytics')
            ->whereNotNull('ip_hash')
            ->where('ip_hash', '!=', '')
            ->whereRaw('LENGTH(ip_hash) < 64')
            ->orderBy('id')
            ->chunk(500, function ($records) use ($appKey) {
                foreach ($records as $record) {
                    DB::table('analytics')
                        ->where('id', $record->id)
                        ->update(['ip_hash' => hash_hmac('sha256', $record->ip_hash, $appKey)]);
                }
            });

        // --- Post Likes table ---
        Schema::table('post_likes', function (Blueprint $table) {
            $table->dropUnique(['post_id', 'ip_address']);
        });

        Schema::table('post_likes', function (Blueprint $table) {
            $table->renameColumn('ip_address', 'ip_hash');
        });

        Schema::table('post_likes', function (Blueprint $table) {
            $table->string('ip_hash', 64)->change();
            $table->unique(['post_id', 'ip_hash']);
        });

        // Hash existing post_likes IPs
        DB::table('post_likes')
            ->whereNotNull('ip_hash')
            ->where('ip_hash', '!=', '')
            ->whereRaw('LENGTH(ip_hash) < 64')
            ->orderBy('id')
            ->chunk(500, function ($records) use ($appKey) {
                foreach ($records as $record) {
                    DB::table('post_likes')
                        ->where('id', $record->id)
                        ->update(['ip_hash' => hash_hmac('sha256', $record->ip_hash, $appKey)]);
                }
            });

        // --- Site Visits table ---
        Schema::table('site_visits', function (Blueprint $table) {
            $table->renameColumn('ip_address', 'ip_hash');
        });

        Schema::table('site_visits', function (Blueprint $table) {
            $table->string('ip_hash', 64)->nullable()->change();
        });

        // Hash existing site_visits IPs
        DB::table('site_visits')
            ->whereNotNull('ip_hash')
            ->where('ip_hash', '!=', '')
            ->whereRaw('LENGTH(ip_hash) < 64')
            ->orderBy('id')
            ->chunk(500, function ($records) use ($appKey) {
                foreach ($records as $record) {
                    DB::table('site_visits')
                        ->where('id', $record->id)
                        ->update(['ip_hash' => hash_hmac('sha256', $record->ip_hash, $appKey)]);
                }
            });
    }

    /**
     * Reverse the migrations.
     * 
     * WARNING: Reversing this migration will NOT restore original IPs.
     * The hashed values will remain as-is (irreversible by design).
     */
    public function down(): void
    {
        // Analytics
        Schema::table('analytics', function (Blueprint $table) {
            $table->dropUnique(['user_id', 'type', 'date', 'ip_hash']);
        });

        Schema::table('analytics', function (Blueprint $table) {
            $table->renameColumn('ip_hash', 'ip_address');
        });

        Schema::table('analytics', function (Blueprint $table) {
            $table->string('ip_address', 45)->nullable()->change();
            $table->unique(['user_id', 'type', 'date', 'ip_address']);
        });

        // Post Likes
        Schema::table('post_likes', function (Blueprint $table) {
            $table->dropUnique(['post_id', 'ip_hash']);
        });

        Schema::table('post_likes', function (Blueprint $table) {
            $table->renameColumn('ip_hash', 'ip_address');
        });

        Schema::table('post_likes', function (Blueprint $table) {
            $table->string('ip_address')->change();
            $table->unique(['post_id', 'ip_address']);
        });

        // Site Visits
        Schema::table('site_visits', function (Blueprint $table) {
            $table->renameColumn('ip_hash', 'ip_address');
        });

        Schema::table('site_visits', function (Blueprint $table) {
            $table->string('ip_address', 45)->nullable()->change();
        });
    }
};
