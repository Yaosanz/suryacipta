<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * @var array<int, string>
     */
    private array $contentTables = [
        'page_sections',
        'products',
        'portfolio_projects',
        'blog_posts',
    ];

    public function up(): void
    {
        foreach ($this->contentTables as $tableName) {
            Schema::table($tableName, function (Blueprint $table): void {
                $table->string('video_path')->nullable();
                $table->string('video_alt', 180)->nullable();
                $table->unsignedSmallInteger('video_width')->nullable();
                $table->unsignedSmallInteger('video_height')->nullable();
                $table->decimal('video_duration', 7, 2)->nullable();
                $table->unsignedBigInteger('video_size')->nullable();
            });
        }

        Schema::table('media_assets', function (Blueprint $table): void {
            $table->unsignedSmallInteger('width')->nullable()->after('size');
            $table->unsignedSmallInteger('height')->nullable()->after('width');
            $table->decimal('duration', 7, 2)->nullable()->after('height');
        });
    }

    public function down(): void
    {
        Schema::table('media_assets', function (Blueprint $table): void {
            $table->dropColumn(['width', 'height', 'duration']);
        });

        foreach (array_reverse($this->contentTables) as $tableName) {
            Schema::table($tableName, function (Blueprint $table): void {
                $table->dropColumn([
                    'video_path',
                    'video_alt',
                    'video_width',
                    'video_height',
                    'video_duration',
                    'video_size',
                ]);
            });
        }
    }
};
