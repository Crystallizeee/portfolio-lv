<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('job_profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('name'); // e.g. "Software Engineer Variant"
            $table->string('slug'); // for public sharing
            $table->string('professional_title')->nullable();
            $table->text('summary')->nullable();
            $table->json('about_grc_list')->nullable();
            $table->json('about_tech_list')->nullable();
            $table->boolean('is_landing_page')->default(false);
            $table->timestamps();
        });

        // Migrate existing user fields to a default JobProfile
        $user = DB::table('users')->first();
        if ($user) {
            $title = $user->professional_title ?? 'Default Profile';
            DB::table('job_profiles')->insert([
                'user_id' => $user->id,
                'name' => 'Default Profile',
                'slug' => Str::slug('Default Profile'),
                'professional_title' => $user->professional_title,
                'summary' => $user->summary,
                'about_grc_list' => $user->about_grc_list,
                'about_tech_list' => $user->about_tech_list,
                'is_landing_page' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('job_profiles');
    }
};
