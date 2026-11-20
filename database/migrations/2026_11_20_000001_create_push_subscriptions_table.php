<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('push_subscriptions', function (Blueprint $table) {
            $table->id();
            $table->string('endpoint', 500)->unique();
            $table->string('public_key');
            $table->string('auth_token');
            $table->string('content_encoding', 32)->nullable();
            $table->timestamps();
        });

        Schema::table('posts', function (Blueprint $table) {
            $table->timestamp('notified_at')->nullable()->after('published_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('push_subscriptions');

        Schema::table('posts', function (Blueprint $table) {
            $table->dropColumn('notified_at');
        });
    }
};
