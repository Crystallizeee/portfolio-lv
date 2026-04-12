<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Add spam protection fields to comments table.
     */
    public function up(): void
    {
        Schema::table('comments', function (Blueprint $table) {
            $table->boolean('is_approved')->default(false)->after('content');
            $table->string('ip_hash', 64)->nullable()->after('is_approved');
            $table->tinyInteger('spam_score')->default(0)->after('ip_hash');
            $table->boolean('honeypot_triggered')->default(false)->after('spam_score');
            
            $table->index('is_approved');
            $table->index('ip_hash');
        });

        // Auto-approve all existing comments
        \Illuminate\Support\Facades\DB::table('comments')
            ->update(['is_approved' => true]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('comments', function (Blueprint $table) {
            $table->dropIndex(['is_approved']);
            $table->dropIndex(['ip_hash']);
            $table->dropColumn(['is_approved', 'ip_hash', 'spam_score', 'honeypot_triggered']);
        });
    }
};
