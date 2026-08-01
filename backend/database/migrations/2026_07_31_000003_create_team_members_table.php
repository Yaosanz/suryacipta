<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('team_members', function (Blueprint $table): void {
            $table->id();
            $table->string('name', 160);
            $table->string('role', 160);
            $table->text('bio')->nullable();
            $table->string('photo_path')->nullable();
            $table->string('photo_alt', 180)->nullable();
            $table->string('linkedin_url')->nullable();
            $table->boolean('is_published')->default(false)->index();
            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('team_members');
    }
};
