<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\BlogPost;
use App\Models\Faq;
use App\Models\PageSection;
use App\Models\PortfolioProject;
use App\Models\Product;
use App\Models\SiteSetting;
use App\Models\Testimonial;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class PublicContentController extends Controller
{
    public function index(): JsonResponse
    {
        $content = Cache::remember('cms.public.v1', now()->addMinutes(5), fn (): array => [
            'site' => $this->site(),
            'sections' => PageSection::query()
                ->where('is_enabled', true)->orderBy('sort_order')->get()
                ->map(fn (PageSection $section): array => $this->section($section))->values()->all(),
            'products' => Product::query()->published()->orderBy('sort_order')->get()
                ->map(fn (Product $product): array => $this->transformProduct($product))->values()->all(),
            'portfolio' => PortfolioProject::query()->published()->orderBy('sort_order')->get()
                ->map(fn (PortfolioProject $project): array => $this->project($project))->values()->all(),
            'posts' => BlogPost::query()->published()->latest('published_at')->get()
                ->map(fn (BlogPost $post): array => $this->transformPost($post, false))->values()->all(),
            'faqs' => Faq::query()->published()->orderBy('sort_order')->get()
                ->map(fn (Faq $faq): array => [
                    'category' => $faq->category,
                    'question' => $faq->question,
                    'answer' => $faq->answer,
                ])->values()->all(),
            'testimonials' => Testimonial::query()->published()->orderBy('sort_order')->get()
                ->map(fn (Testimonial $testimonial): array => [
                    'name' => $testimonial->name,
                    'companyRole' => $testimonial->company_role,
                    'quote' => $testimonial->quote,
                    'rating' => $testimonial->rating,
                ])->values()->all(),
        ]);

        return $this->respond($content);
    }

    public function product(string $slug): JsonResponse
    {
        $product = Product::query()->published()->where('slug', $slug)->firstOrFail();

        return $this->respond($this->transformProduct($product));
    }

    public function post(string $slug): JsonResponse
    {
        $post = BlogPost::query()->published()->where('slug', $slug)->firstOrFail();

        return $this->respond($this->transformPost($post, true));
    }

    private function site(): ?array
    {
        $settings = SiteSetting::query()->first();

        if ($settings === null) {
            return null;
        }

        return [
            'companyName' => $settings->company_name,
            'shortName' => $settings->short_name,
            'tagline' => $settings->tagline,
            'description' => $settings->description,
            'phone' => $settings->phone,
            'whatsapp' => $settings->whatsapp,
            'email' => $settings->email,
            'address' => $settings->address,
            'businessHours' => $settings->business_hours,
            'socialLinks' => $this->safeLinks($settings->social_links ?? []),
            'statistics' => $settings->statistics ?? [],
            'seo' => [
                'title' => $settings->default_seo_title,
                'description' => $settings->default_seo_description,
            ],
            'logo' => $this->assetUrl($settings->logo_path),
            'logoAlt' => $settings->logo_alt,
        ];
    }

    private function section(PageSection $section): array
    {
        return [
            'page' => $section->page,
            'key' => $section->section_key,
            'eyebrow' => $section->eyebrow,
            'title' => $section->title,
            'description' => $section->description,
            'content' => $section->content ?? [],
            'image' => $this->assetUrl($section->image_path),
            'imageAlt' => $section->image_alt,
            'cta' => [
                'label' => $section->primary_cta_label,
                'url' => $this->safeLink($section->primary_cta_url),
            ],
        ];
    }

    private function transformProduct(Product $product): array
    {
        return [
            'slug' => $product->slug,
            'name' => $product->name,
            'category' => $product->category,
            'shortDescription' => $product->short_description,
            'description' => $product->description,
            'features' => $product->features ?? [],
            'image' => $this->assetUrl($product->image_path),
            'imageAlt' => $product->image_alt,
            'featured' => $product->is_featured,
            'seo' => ['title' => $product->seo_title, 'description' => $product->seo_description],
        ];
    }

    private function project(PortfolioProject $project): array
    {
        return [
            'slug' => $project->slug,
            'title' => $project->title,
            'location' => $project->location,
            'category' => $project->category,
            'year' => $project->year,
            'description' => $project->description,
            'image' => $this->assetUrl($project->image_path),
            'imageAlt' => $project->image_alt,
            'featured' => $project->is_featured,
        ];
    }

    private function transformPost(BlogPost $post, bool $includeContent): array
    {
        $data = [
            'slug' => $post->slug,
            'title' => $post->title,
            'excerpt' => $post->excerpt,
            'image' => $this->assetUrl($post->featured_image_path),
            'imageAlt' => $post->featured_image_alt,
            'publishedAt' => $post->published_at?->toAtomString(),
            'seo' => ['title' => $post->seo_title, 'description' => $post->seo_description],
        ];

        if ($includeContent) {
            $data['contentHtml'] = Str::markdown($post->content, [
                'html_input' => 'strip',
                'allow_unsafe_links' => false,
            ]);
        }

        return $data;
    }

    private function assetUrl(?string $path): ?string
    {
        if (blank($path) || Str::contains($path, ['..', '\\'])) {
            return null;
        }

        return url(Storage::disk('public')->url($path));
    }

    private function safeLinks(array $links): array
    {
        return collect($links)->map(fn ($url) => $this->safeLink(is_string($url) ? $url : null))
            ->filter()->all();
    }

    private function safeLink(?string $url): ?string
    {
        if (blank($url)) {
            return null;
        }

        if (Str::startsWith($url, '/') && ! Str::startsWith($url, '//')) {
            return $url;
        }

        return filter_var($url, FILTER_VALIDATE_URL) && parse_url($url, PHP_URL_SCHEME) === 'https'
            ? $url
            : null;
    }

    private function respond(array $data): JsonResponse
    {
        return response()->json(['data' => $data])
            ->header('Cache-Control', 'public, max-age=60, stale-while-revalidate=300')
            ->header('X-Content-Type-Options', 'nosniff');
    }
}
