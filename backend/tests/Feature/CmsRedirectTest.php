<?php

namespace Tests\Feature;

use App\Enums\UserRole;
use App\Filament\Resources\Products\Pages\CreateProduct;
use App\Filament\Resources\Products\Pages\EditProduct;
use App\Filament\Resources\Products\ProductResource;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class CmsRedirectTest extends TestCase
{
    use RefreshDatabase;

    public function test_create_and_edit_actions_redirect_to_the_feature_index(): void
    {
        $admin = User::factory()->create([
            'role' => UserRole::SuperAdmin,
            'is_active' => true,
        ]);
        $admin->app_authentication_secret = 'local-test-secret';
        $admin->save();

        $this->actingAs($admin);

        Livewire::test(CreateProduct::class)
            ->fillForm([
                'name' => 'Redirect Test Product',
                'slug' => 'redirect-test-product',
                'category' => 'GRC',
                'short_description' => 'Product created by the CMS redirect test.',
                'is_published' => false,
                'is_featured' => false,
                'sort_order' => 0,
            ])
            ->call('create')
            ->assertHasNoFormErrors()
            ->assertRedirect(ProductResource::getUrl('index'));

        $product = Product::query()->where('slug', 'redirect-test-product')->firstOrFail();

        Livewire::test(EditProduct::class, ['record' => $product->getRouteKey()])
            ->fillForm([
                'name' => 'Updated Redirect Test Product',
            ])
            ->call('save')
            ->assertHasNoFormErrors()
            ->assertRedirect(ProductResource::getUrl('index'));

        $this->assertDatabaseHas('products', [
            'id' => $product->getKey(),
            'name' => 'Updated Redirect Test Product',
        ]);
    }
}
