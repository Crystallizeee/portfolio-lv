<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('homelab_services', function (Blueprint $table) {
            $table->id();
            $table->integer('vmid')->unique();
            $table->string('name');
            $table->string('node_label')->default('Worker');
            $table->string('icon')->default('server');
            $table->enum('type', ['qemu', 'lxc', 'node'])->default('lxc');
            $table->boolean('is_visible')->default(true);
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('homelab_services');
    }
};
