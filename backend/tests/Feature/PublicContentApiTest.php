<?php

namespace Tests\Feature;

use App\Enums\PublicationStatus;
use App\Models\BlogPost;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PublicContentApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_only_published_content_is_exposed(): void
    {
        Product::query()->create([
            'slug' => 'published-product',
            'name' => 'Published',
            'category' => 'GRC',
            'short_description' => 'Public description',
            'is_published' => true,
            'published_at' => now(),
        ]);

        Product::query()->create([
            'slug' => 'secret-draft',
            'name' => 'Draft',
            'category' => 'GRC',
            'short_description' => 'Must stay private',
            'is_published' => false,
        ]);

        $response = $this->getJson('/api/v1/content');

        $response->assertOk()
            ->assertJsonPath('data.products.0.slug', 'published-product')
            ->assertJsonMissing(['slug' => 'secret-draft'])
            ->assertHeader('X-Content-Type-Options', 'nosniff');
    }

    public function test_future_and_draft_posts_return_not_found(): void
    {
        BlogPost::query()->create([
            'slug' => 'future-post',
            'title' => 'Future',
            'excerpt' => 'Not available yet.',
            'content' => 'Private',
            'status' => PublicationStatus::Published,
            'published_at' => now()->addDay(),
        ]);

        BlogPost::query()->create([
            'slug' => 'draft-post',
            'title' => 'Draft',
            'excerpt' => 'Not published.',
            'content' => 'Private',
            'status' => PublicationStatus::Draft,
        ]);

        $this->getJson('/api/v1/posts/future-post')->assertNotFound();
        $this->getJson('/api/v1/posts/draft-post')->assertNotFound();
    }

    public function test_markdown_output_strips_raw_html_and_unsafe_links(): void
    {
        BlogPost::query()->create([
            'slug' => 'safe-post',
            'title' => 'Safe',
            'excerpt' => 'Safe excerpt.',
            'content' => '<script>alert(1)</script> [bad](javascript:alert(1))',
            'status' => PublicationStatus::Published,
            'published_at' => now(),
        ]);

        $content = (string) $this->getJson('/api/v1/posts/safe-post')
            ->assertOk()->json('data.contentHtml');

        $this->assertStringNotContainsString('<script', $content);
        $this->assertStringNotContainsString('javascript:', $content);
    }
}
