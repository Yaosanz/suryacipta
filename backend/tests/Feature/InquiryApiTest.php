<?php

namespace Tests\Feature;

use App\Enums\UserRole;
use App\Models\Inquiry;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class InquiryApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_valid_inquiry_is_stored_without_raw_ip(): void
    {
        Storage::fake('local');

        $response = $this->post('/api/inquiries', [
            'name' => 'Budi Santoso',
            'company' => 'PT Contoh Konstruksi',
            'email' => 'budi@example.com',
            'phone' => '0812 3456 7890',
            'projectType' => 'Perkantoran / Komersial',
            'estimatedArea' => '240 m2',
            'budgetRange' => 'Rp150 - 500 juta',
            'message' => 'Kami membutuhkan panel fasad untuk proyek di Bogor.',
            'attachment' => UploadedFile::fake()->create('gambar-kerja.pdf', 200, 'application/pdf'),
            'website' => '',
        ], ['Accept' => 'application/json']);

        $response->assertCreated()->assertExactJson([
            'message' => 'Permintaan telah diterima.',
        ]);

        $inquiry = Inquiry::query()->firstOrFail();
        $this->assertSame('budi@example.com', $inquiry->email);
        $this->assertSame(64, strlen((string) $inquiry->ip_hash));
        $this->assertNotSame('gambar-kerja.pdf', $inquiry->attachment_path);
        Storage::disk('local')->assertExists((string) $inquiry->attachment_path);
        $this->assertDatabaseMissing('inquiries', ['ip_hash' => '127.0.0.1']);
    }

    public function test_new_inquiry_notifies_only_active_authorized_cms_users(): void
    {
        $manager = User::factory()->create([
            'role' => UserRole::ContentManager,
            'is_active' => true,
        ]);
        $editor = User::factory()->create([
            'role' => UserRole::Editor,
            'is_active' => true,
        ]);
        $inactiveAdmin = User::factory()->create([
            'role' => UserRole::SuperAdmin,
            'is_active' => false,
        ]);

        $this->postJson('/api/inquiries', [
            'name' => 'Budi Santoso',
            'email' => 'notification@example.com',
            'phone' => '0812 3456 7890',
            'projectType' => 'Lainnya',
            'message' => 'Pesan valid untuk menguji notifikasi dashboard CMS.',
            'website' => '',
        ])->assertCreated();

        $this->assertCount(1, $manager->fresh()->notifications);
        $this->assertCount(0, $editor->fresh()->notifications);
        $this->assertCount(0, $inactiveAdmin->fresh()->notifications);

        $notificationData = json_encode($manager->fresh()->notifications->firstOrFail()->data);
        $this->assertStringContainsString('Permintaan penawaran baru', $notificationData);
        $this->assertStringNotContainsString('notification@example.com', $notificationData);
        $this->assertStringNotContainsString('0812 3456 7890', $notificationData);
    }

    public function test_honeypot_and_invalid_payload_are_rejected(): void
    {
        $response = $this->postJson('/api/inquiries', [
            'name' => 'X',
            'email' => 'not-an-email',
            'phone' => '123',
            'projectType' => 'invalid',
            'message' => 'short',
            'website' => 'spam.example',
        ]);

        $response->assertUnprocessable()
            ->assertJsonValidationErrors([
                'name',
                'email',
                'phone',
                'projectType',
                'message',
                'website',
            ]);
        $this->assertDatabaseCount('inquiries', 0);
    }

    public function test_inquiry_endpoint_is_rate_limited(): void
    {
        $payload = [
            'name' => 'Budi Santoso',
            'email' => 'rate@example.com',
            'phone' => '0812 3456 7890',
            'projectType' => 'Lainnya',
            'message' => 'Pesan valid dengan panjang lebih dari dua puluh karakter.',
            'website' => '',
        ];

        for ($attempt = 0; $attempt < 3; $attempt++) {
            $this->postJson('/api/inquiries', $payload)->assertCreated();
        }

        $this->postJson('/api/inquiries', $payload)->assertTooManyRequests();
    }

    public function test_active_html_is_removed_before_storage(): void
    {
        $response = $this->postJson('/api/inquiries', [
            'name' => '<script>alert(1)</script>Budi Santoso',
            'company' => '<b>PT Contoh</b>',
            'email' => 'xss@example.com',
            'phone' => '0812 3456 7890',
            'projectType' => 'Lainnya',
            'message' => '<img src=x onerror=alert(1)>Pesan pengujian yang cukup panjang untuk disimpan.',
            'website' => '',
        ]);

        $response->assertCreated();
        $this->assertDatabaseHas('inquiries', [
            'name' => 'Budi Santoso',
            'company' => 'PT Contoh',
            'message' => 'Pesan pengujian yang cukup panjang untuk disimpan.',
        ]);
    }

    public function test_production_origin_policy_rejects_cross_site_posts(): void
    {
        config()->set('security.require_origin_header', true);
        config()->set('cors.allowed_origins', ['https://frontend.example']);

        $payload = [
            'name' => 'Budi Santoso',
            'email' => 'origin@example.com',
            'phone' => '0812 3456 7890',
            'projectType' => 'Lainnya',
            'message' => 'Pesan valid dengan panjang lebih dari dua puluh karakter.',
            'website' => '',
        ];

        $this->withHeader('Origin', 'https://evil.example')
            ->postJson('/api/inquiries', $payload)
            ->assertForbidden()
            ->assertHeader(
                'Content-Security-Policy',
                "default-src 'none'; frame-ancestors 'none'; base-uri 'none'; form-action 'none'"
            );

        $this->withHeader('Origin', 'https://frontend.example')
            ->postJson('/api/inquiries', $payload)
            ->assertCreated();
    }
}
