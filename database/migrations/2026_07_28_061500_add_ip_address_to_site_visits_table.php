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
        Schema::table('site_visits', function (Blueprint $table) {
            if (!Schema::hasColumn('site_visits', 'ip_address')) {
                $table->string('ip_address', 45)->nullable()->after('id');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('site_visits', function (Blueprint $table) {
            if (Schema::hasColumn('site_visits', 'ip_address')) {
                $table->dropColumn('ip_address');
            }
        });
    }
};
