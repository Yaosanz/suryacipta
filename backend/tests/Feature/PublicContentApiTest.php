<?php

namespace Tests\Feature;

use App\Enums\PublicationStatus;
use App\Models\BlogPost;
use App\Models\PageSection;
use App\Models\Product;
use App\Models\SiteSetting;
use App\Models\TeamMember;
use App\Support\VideoMetadata;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Mockery;
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
            'description' => 'Complete public description',
            'features' => ['Custom size', 'Weather resistant'],
            'image_alt' => 'Installed GRC panel',
            'seo_title' => 'Published product SEO',
            'seo_description' => 'Published product search description',
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
        TeamMember::query()->create([
            'name' => 'Public Team',
            'role' => 'Engineering',
            'is_published' => true,
        ]);
        TeamMember::query()->create([
            'name' => 'Private Team',
            'role' => 'Internal',
            'is_published' => false,
        ]);

        $response = $this->getJson('/api/v1/content');

        $response->assertOk()
            ->assertJsonPath('data.products.0.slug', 'published-product')
            ->assertJsonPath('data.products.0.description', 'Complete public description')
            ->assertJsonPath('data.products.0.features.0', 'Custom size')
            ->assertJsonPath('data.products.0.imageAlt', 'Installed GRC panel')
            ->assertJsonPath('data.products.0.seo.title', 'Published product SEO')
            ->assertJsonPath('data.team.0.name', 'Public Team')
            ->assertJsonMissing(['slug' => 'secret-draft'])
            ->assertJsonMissing(['name' => 'Private Team'])
            ->assertHeader('X-Content-Type-Options', 'nosniff');

        $this->assertStringContainsString('no-store', (string) $response->headers->get('Cache-Control'));
    }

    public function test_content_changes_are_returned_immediately_without_a_stale_api_cache(): void
    {
        $product = Product::query()->create([
            'slug' => 'live-product',
            'name' => 'Original Name',
            'category' => 'GRC',
            'short_description' => 'Original description',
            'is_published' => true,
            'published_at' => now(),
        ]);

        $this->getJson('/api/v1/content')
            ->assertOk()
            ->assertJsonPath('data.products.0.name', 'Original Name');

        $product->update([
            'name' => 'Updated Name',
            'short_description' => 'Updated description',
        ]);

        $this->getJson('/api/v1/content')
            ->assertOk()
            ->assertJsonPath('data.products.0.name', 'Updated Name')
            ->assertJsonPath('data.products.0.shortDescription', 'Updated description')
            ->assertJsonMissing(['name' => 'Original Name']);
    }

    public function test_page_sections_remain_segmented_and_updates_are_returned_immediately(): void
    {
        PageSection::query()->create([
            'page' => 'home',
            'section_key' => 'hero',
            'title' => 'Home hero',
            'is_enabled' => true,
            'sort_order' => 1,
        ]);
        $productHero = PageSection::query()->create([
            'page' => 'products',
            'section_key' => 'hero',
            'title' => 'Product hero',
            'is_enabled' => true,
            'sort_order' => 1,
        ]);

        $sections = collect($this->getJson('/api/v1/content')->assertOk()->json('data.sections'));

        $this->assertSame('Home hero', $sections->firstWhere('page', 'home')['title']);
        $this->assertSame('Product hero', $sections->firstWhere('page', 'products')['title']);

        $productHero->update(['title' => 'Updated product hero']);

        $updatedSections = collect($this->getJson('/api/v1/content')->assertOk()->json('data.sections'));
        $this->assertSame(
            'Updated product hero',
            $updatedSections->first(fn (array $section): bool => $section['page'] === 'products' && $section['key'] === 'hero')['title'],
        );
    }

    public function test_updated_logo_is_exposed_immediately_and_the_previous_file_is_removed(): void
    {
        Storage::fake('public');
        Storage::disk('public')->put('cms/branding/old-logo.png', 'old-logo');
        Storage::disk('public')->put('cms/branding/new-logo.png', 'new-logo');

        $settings = SiteSetting::query()->create([
            'company_name' => 'Surya Cipta Estetica Mandiri',
            'short_name' => 'SCEMA',
            'tagline' => 'Spesialis GRC',
            'description' => 'Company profile',
            'phone' => '021000000',
            'whatsapp' => '628120000000',
            'email' => 'info@example.test',
            'address' => ['city' => 'Bogor'],
            'logo_path' => 'cms/branding/old-logo.png',
            'logo_alt' => 'Logo lama',
        ]);

        $this->getJson('/api/v1/content')
            ->assertOk()
            ->assertJsonPath('data.site.logo', url('/storage/cms/branding/old-logo.png'));

        $settings->update([
            'logo_path' => 'cms/branding/new-logo.png',
            'logo_alt' => 'Logo terbaru',
        ]);

        $this->getJson('/api/v1/content')
            ->assertOk()
            ->assertJsonPath('data.site.logo', url('/storage/cms/branding/new-logo.png'))
            ->assertJsonPath('data.site.logoAlt', 'Logo terbaru');

        Storage::disk('public')->assertMissing('cms/branding/old-logo.png');
        Storage::disk('public')->assertExists('cms/branding/new-logo.png');
    }

    public function test_video_metadata_and_public_url_are_exposed_for_published_content(): void
    {
        Storage::fake('public');
        Storage::disk('public')->put('cms/products/videos/demo.mp4', 'video-content');

        $analyzer = Mockery::mock(VideoMetadata::class);
        $analyzer->shouldReceive('analyze')->once()->andReturn([
            'width' => 1920,
            'height' => 1080,
            'duration' => 45.5,
            'size' => 13,
            'mime_type' => 'video/mp4',
        ]);
        $this->app->instance(VideoMetadata::class, $analyzer);

        Product::query()->create([
            'slug' => 'video-product',
            'name' => 'Video Product',
            'category' => 'GRC',
            'short_description' => 'Product with verified video.',
            'video_path' => 'cms/products/videos/demo.mp4',
            'video_alt' => 'Demonstration of the GRC product',
            'is_published' => true,
            'published_at' => now(),
        ]);

        $this->getJson('/api/v1/content')
            ->assertOk()
            ->assertJsonPath('data.products.0.video.alt', 'Demonstration of the GRC product')
            ->assertJsonPath('data.products.0.video.width', 1920)
            ->assertJsonPath('data.products.0.video.height', 1080)
            ->assertJsonPath('data.products.0.video.duration', 45.5)
            ->assertJsonPath('data.products.0.video.size', 13)
            ->assertJsonPath('data.products.0.video.url', url('/storage/cms/products/videos/demo.mp4'));
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

    public function test_section_links_are_sanitized_and_empty_content_is_an_object(): void
    {
        PageSection::query()->create([
            'page' => 'home',
            'section_key' => 'unsafe-links',
            'title' => 'Safe title',
            'content' => [
                'secondaryCtaUrl' => 'javascript:alert(1)',
                'mapUrl' => 'data:text/html,unsafe',
            ],
            'primary_cta_label' => 'Unsafe action',
            'primary_cta_url' => 'javascript:alert(1)',
            'is_enabled' => true,
        ]);

        PageSection::query()->create([
            'page' => 'home',
            'section_key' => 'empty-content',
            'title' => 'Empty content',
            'is_enabled' => true,
            'sort_order' => 1,
        ]);

        $response = $this->getJson('/api/v1/content')->assertOk();
        $sections = collect($response->json('data.sections'))->keyBy('key');

        $this->assertSame([], $sections->get('unsafe-links')['content']);
        $this->assertNull($sections->get('unsafe-links')['cta']['url']);
        $this->assertIsArray($sections->get('empty-content')['content']);
        $this->assertSame([], $sections->get('empty-content')['content']);
        $this->assertStringNotContainsString('javascript:', $response->getContent());
        $this->assertStringNotContainsString('data:text/html', $response->getContent());
    }
}
