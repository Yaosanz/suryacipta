<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('inquiries', function (Blueprint $table): void {
            $table->id();
            $table->string('name', 100);
            $table->string('company', 120)->nullable();
            $table->string('email', 150)->index();
            $table->string('phone', 24);
            $table->string('project_type', 60);
            $table->string('estimated_area', 30)->nullable();
            $table->string('budget_range', 60)->nullable();
            $table->text('message');
            $table->string('attachment_path')->nullable();
            $table->string('status', 24)->default('new')->index();
            $table->string('source', 32)->default('website');
            $table->char('ip_hash', 64)->nullable()->index();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('inquiries');
    }
};
