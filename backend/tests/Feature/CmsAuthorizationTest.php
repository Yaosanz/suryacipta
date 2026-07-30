<?php

namespace Tests\Feature;

use App\Enums\PublicationStatus;
use App\Enums\UserRole;
use App\Models\BlogPost;
use App\Models\Inquiry;
use App\Models\Product;
use App\Models\User;
use App\Policies\ActivityPolicy;
use App\Policies\BlogPostPolicy;
use App\Policies\FaqPolicy;
use App\Policies\InquiryPolicy;
use App\Policies\MediaAssetPolicy;
use App\Policies\PageSectionPolicy;
use App\Policies\PortfolioProjectPolicy;
use App\Policies\ProductPolicy;
use App\Policies\SiteSettingPolicy;
use App\Policies\TestimonialPolicy;
use App\Policies\UserPolicy;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CmsAuthorizationTest extends TestCase
{
    use RefreshDatabase;

    public function test_panel_requires_authentication_and_password_reset_is_opt_in(): void
    {
        $this->get('/admin')->assertRedirect('/admin/login');
        $this->get('/admin/login')->assertOk()
            ->assertHeader('X-Frame-Options', 'DENY')
            ->assertHeader('X-Content-Type-Options', 'nosniff');
        $this->get('/admin/password-reset/request')->assertNotFound();
    }

    public function test_editor_cannot_edit_live_content_or_delete_content(): void
    {
        $editor = User::factory()->create(['role' => UserRole::Editor]);
        $published = Product::query()->create([
            'slug' => 'live',
            'name' => 'Live',
            'category' => 'GRC',
            'short_description' => 'Published content',
            'is_published' => true,
        ]);
        $draft = Product::query()->create([
            'slug' => 'draft',
            'name' => 'Draft',
            'category' => 'GRC',
            'short_description' => 'Draft content',
        ]);

        $policy = new ProductPolicy;
        $this->assertFalse($policy->update($editor, $published));
        $this->assertTrue($policy->update($editor, $draft));
        $this->assertFalse($policy->delete($editor, $draft));
    }

    public function test_editor_cannot_view_inquiries_and_manager_can(): void
    {
        $editor = User::factory()->create(['role' => UserRole::Editor]);
        $manager = User::factory()->create(['role' => UserRole::ContentManager]);
        $policy = new InquiryPolicy;

        $this->assertFalse($policy->viewAny($editor));
        $this->assertTrue($policy->viewAny($manager));
    }

    public function test_editor_cannot_edit_published_blog_post(): void
    {
        $editor = User::factory()->create(['role' => UserRole::Editor]);
        $post = new BlogPost(['status' => PublicationStatus::Published]);

        $this->assertFalse((new BlogPostPolicy)->update($editor, $post));
    }

    public function test_all_cms_policies_define_every_ability_required_by_strict_authorization(): void
    {
        $abilities = [
            'viewAny',
            'view',
            'create',
            'update',
            'delete',
            'deleteAny',
            'restore',
            'restoreAny',
            'forceDelete',
            'forceDeleteAny',
            'replicate',
            'reorder',
        ];
        $policies = [
            ActivityPolicy::class,
            BlogPostPolicy::class,
            FaqPolicy::class,
            InquiryPolicy::class,
            MediaAssetPolicy::class,
            PageSectionPolicy::class,
            PortfolioProjectPolicy::class,
            ProductPolicy::class,
            SiteSettingPolicy::class,
            TestimonialPolicy::class,
            UserPolicy::class,
        ];

        foreach ($policies as $policy) {
            foreach ($abilities as $ability) {
                $this->assertTrue(
                    method_exists($policy, $ability),
                    "{$policy} must define or inherit {$ability}().",
                );
            }
        }
    }

    public function test_super_admin_can_render_every_cms_list_without_authorization_errors(): void
    {
        $admin = User::factory()->create([
            'role' => UserRole::SuperAdmin,
            'is_active' => true,
        ]);
        $admin->app_authentication_secret = 'local-test-secret';
        $admin->save();

        $this->actingAs($admin);

        foreach ([
            '/admin/activities',
            '/admin/blog-posts',
            '/admin/faqs',
            '/admin/inquiries',
            '/admin/media-assets',
            '/admin/page-sections',
            '/admin/portfolio-projects',
            '/admin/products',
            '/admin/site-settings',
            '/admin/testimonials',
            '/admin/users',
        ] as $path) {
            $this->get($path)->assertOk();
        }

        foreach ([
            '/admin/blog-posts/create',
            '/admin/faqs/create',
            '/admin/media-assets/create',
            '/admin/page-sections/create',
            '/admin/portfolio-projects/create',
            '/admin/products/create',
            '/admin/site-settings/create',
            '/admin/testimonials/create',
            '/admin/users/create',
        ] as $path) {
            $this->get($path)->assertOk();
        }

        $this->get('/admin/activities/create')->assertNotFound();
        $this->get('/admin/inquiries/create')->assertNotFound();
    }

    public function test_inquiry_audit_does_not_store_customer_message(): void
    {
        $inquiry = Inquiry::query()->create([
            'name' => 'Customer',
            'email' => 'customer@example.com',
            'phone' => '081234567890',
            'project_type' => 'Lainnya',
            'message' => 'Sensitive project details',
            'status' => 'new',
        ]);

        $inquiry->update(['status' => 'contacted']);

        $activity = $inquiry->activitiesAsSubject()->where('event', 'updated')->firstOrFail();
        $changes = $activity->attribute_changes->get('attributes', []);
        $this->assertArrayNotHasKey('message', $changes);
        $this->assertSame('contacted', $changes['status']);
    }
}
