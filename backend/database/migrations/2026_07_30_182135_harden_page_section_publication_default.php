<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('page_sections', function (Blueprint $table): void {
            $table->boolean('is_enabled')->default(false)->change();
        });
    }

    public function down(): void
    {
        Schema::table('page_sections', function (Blueprint $table): void {
            $table->boolean('is_enabled')->default(true)->change();
        });
    }
};
