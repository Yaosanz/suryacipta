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
        Schema::create('site_settings', function (Blueprint $table) {
            $table->id();
            $table->string('company_name');
            $table->string('short_name');
            $table->string('tagline');
            $table->text('description');
            $table->string('phone', 32);
            $table->string('whatsapp', 32);
            $table->string('email');
            $table->json('address');
            $table->string('business_hours')->nullable();
            $table->json('social_links')->nullable();
            $table->json('statistics')->nullable();
            $table->string('default_seo_title')->nullable();
            $table->text('default_seo_description')->nullable();
            $table->string('logo_path')->nullable();
            $table->string('logo_alt')->nullable();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('site_settings');
    }
};
