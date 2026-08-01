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
use App\Policies\TeamMemberPolicy;
use App\Policies\TestimonialPolicy;
use App\Policies\UserPolicy;
use App\Support\ActivityPresenter;
use Illuminate\Auth\Events\Login;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Spatie\Activitylog\Models\Activity;
use Tests\TestCase;

class CmsAuthorizationTest extends TestCase
{
    use RefreshDatabase;

    public function test_panel_requires_authentication_and_password_reset_is_opt_in(): void
    {
        $this->get('/admin')->assertRedirect('/admin/login');
        $login = $this->get('/admin/login')->assertOk()
            ->assertHeader('X-Frame-Options', 'DENY')
            ->assertHeader('X-Content-Type-Options', 'nosniff');
        $csp = (string) $login->headers->get('Content-Security-Policy');
        $this->assertStringContainsString("media-src 'self' blob:", $csp);
        $this->assertStringContainsString("worker-src 'self' blob:", $csp);
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

    public function test_view_pages_follow_record_policies(): void
    {
        $editor = User::factory()->create(['role' => UserRole::Editor]);
        $manager = User::factory()->create(['role' => UserRole::ContentManager]);
        $admin = User::factory()->create(['role' => UserRole::SuperAdmin]);

        foreach ([$editor, $manager, $admin] as $user) {
            $user->app_authentication_secret = 'local-test-secret';
            $user->save();
        }

        $product = Product::query()->create([
            'slug' => 'read-only-product',
            'name' => 'Read-only Product',
            'category' => 'GRC',
            'short_description' => 'Detail content',
            'is_published' => true,
        ]);
        $inquiry = Inquiry::query()->create([
            'name' => 'Prospect',
            'email' => 'prospect@example.com',
            'phone' => '081234567890',
            'project_type' => 'Fasad',
            'message' => 'Project details',
            'status' => 'new',
        ]);

        $this->actingAs($editor)
            ->get("/admin/products/{$product->getKey()}")
            ->assertOk();
        $this->get("/admin/products/{$product->getKey()}/edit")
            ->assertForbidden();
        $this->get("/admin/inquiries/{$inquiry->getKey()}")
            ->assertForbidden();

        $this->actingAs($manager)
            ->get("/admin/inquiries/{$inquiry->getKey()}")
            ->assertOk();
        $this->get("/admin/users/{$editor->getKey()}")
            ->assertForbidden();

        $this->actingAs($admin)
            ->get("/admin/users/{$editor->getKey()}")
            ->assertOk();
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
            TeamMemberPolicy::class,
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

        $this->get('/admin')->assertOk();
        $this->get('/admin/profile')->assertOk();

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
            '/admin/team-members',
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
            '/admin/team-members/create',
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

    public function test_inquiry_handling_records_the_responsible_user(): void
    {
        $manager = User::factory()->create(['role' => UserRole::ContentManager]);
        $inquiry = Inquiry::query()->create([
            'name' => 'Customer',
            'email' => 'customer@example.com',
            'phone' => '081234567890',
            'project_type' => 'Fasad',
            'message' => 'Project details',
            'status' => 'new',
        ]);

        $this->actingAs($manager);
        $inquiry->update(['status' => 'contacted']);

        $this->assertTrue($inquiry->handledBy->is($manager));
        $this->assertNotNull($inquiry->handled_at);
    }

    public function test_user_avatar_uses_local_initials_and_public_uploads(): void
    {
        Storage::fake('public');
        $user = User::factory()->create([
            'name' => 'Sandy Admin',
            'avatar_path' => null,
        ]);

        $fallback = $user->getFilamentAvatarUrl();
        $this->assertStringStartsWith('data:image/svg+xml;base64,', $fallback);
        $this->assertStringContainsString('SA', base64_decode(str($fallback)->after(',')->toString()));

        Storage::disk('public')->put('cms/avatars/admin.webp', 'image-bytes');
        $user->update(['avatar_path' => 'cms/avatars/admin.webp']);

        $this->assertStringContainsString('/storage/cms/avatars/admin.webp', $user->getFilamentAvatarUrl());
    }

    public function test_activity_log_records_actor_context_and_readable_field_changes(): void
    {
        $admin = User::factory()->create([
            'name' => 'Audit Admin',
            'email' => 'audit@example.test',
            'role' => UserRole::SuperAdmin,
        ]);
        $product = Product::query()->create([
            'slug' => 'audit-product',
            'name' => 'Before Update',
            'category' => 'GRC',
            'short_description' => 'Audit test.',
            'is_published' => false,
        ]);

        $this->actingAs($admin);
        $product->update([
            'name' => 'After Update',
            'is_published' => true,
        ]);

        $activity = $product->activitiesAsSubject()->where('event', 'updated')->latest()->firstOrFail();
        $changes = collect(ActivityPresenter::changes($activity))->keyBy('field');

        $this->assertTrue($activity->causer->is($admin));
        $this->assertSame('audit@example.test', $activity->properties->get('actor_email'));
        $this->assertSame('super_admin', $activity->properties->get('actor_role'));
        $this->assertSame('After Update', $activity->properties->get('subject_label'));
        $this->assertSame('Before Update', $changes->get('Name')['old']);
        $this->assertSame('After Update', $changes->get('Name')['new']);
        $this->assertSame('Tidak', $changes->get('Status tayang')['old']);
        $this->assertSame('Ya', $changes->get('Status tayang')['new']);
    }

    public function test_login_event_is_recorded_with_user_and_exact_timestamp(): void
    {
        $admin = User::factory()->create([
            'email' => 'login-audit@example.test',
            'role' => UserRole::SuperAdmin,
        ]);

        event(new Login('web', $admin, false));

        $activity = Activity::query()
            ->where('log_name', 'authentication')
            ->where('event', 'login')
            ->latest()
            ->firstOrFail();

        $this->assertTrue($activity->causer->is($admin));
        $this->assertSame('login-audit@example.test', $activity->properties->get('actor_email'));
        $this->assertNotNull($activity->properties->get('occurred_at'));
        $this->assertNotNull($activity->created_at);
    }
}
