<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Add persistent status cache columns to homelab_services.
     * Fallback when Proxmox API is unreachable.
     */
    public function up(): void
    {
        Schema::table('homelab_services', function (Blueprint $table) {
            $table->string('cached_status', 20)->default('unknown')->after('sort_order');
            $table->string('cached_cpu', 10)->nullable()->after('cached_status');
            $table->string('cached_memory', 10)->nullable()->after('cached_cpu');
            $table->string('cached_uptime', 30)->nullable()->after('cached_memory');
            $table->timestamp('last_status_check')->nullable()->after('cached_uptime');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('homelab_services', function (Blueprint $table) {
            $table->dropColumn([
                'cached_status',
                'cached_cpu',
                'cached_memory',
                'cached_uptime',
                'last_status_check',
            ]);
        });
    }
};
