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
        Schema::table('analytics', function (Blueprint $table) {
            $table->string('ip_address', 45)->nullable()->after('type'); // Support IPv6
            
            // Drop old unique constraint
            $table->dropUnique(['user_id', 'type', 'date']);
            
            // Add new unique constraint including IP
            $table->unique(['user_id', 'type', 'date', 'ip_address']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('analytics', function (Blueprint $table) {
            $table->dropUnique(['user_id', 'type', 'date', 'ip_address']);
            $table->unique(['user_id', 'type', 'date']);
            $table->dropColumn('ip_address');
        });
    }
};
