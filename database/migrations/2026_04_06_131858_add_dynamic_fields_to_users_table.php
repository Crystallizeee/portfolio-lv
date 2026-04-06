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
        Schema::table('users', function (Blueprint $table) {
            $table->string('contact_title')->nullable()->default('Get In Touch');
            $table->text('contact_subtitle')->nullable();
            $table->json('about_grc_list')->nullable();
            $table->json('about_tech_list')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['contact_title', 'contact_subtitle', 'about_grc_list', 'about_tech_list']);
        });
    }
};
