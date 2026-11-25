<?php

namespace Tests\Feature\Property;

use App\Models\User;
use Database\Seeders\PermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;
use Modules\Location\Models\Area;
use Modules\Property\Enums\FinishingType;
use Modules\Property\Models\Feature;
use Modules\Property\Models\Property;
use Modules\Property\Models\PropertyClassifications;
use Modules\Property\Models\PropertyType;
use Tests\TestCase;

class FinishingDeliveryTest extends TestCase
{
    use RefreshDatabase;

    private string $storagePath;

    protected function setUp(): void
    {
        parent::setUp();

        $this->withoutVite();
        $this->storagePath = sys_get_temp_dir() . '/qeima-test-storage-' . uniqid();
        $this->app->useStoragePath($this->storagePath);
        $this->seed(PermissionSeeder::class);
    }

    protected function tearDown(): void
    {
        File::deleteDirectory($this->storagePath);

        parent::tearDown();
    }

    public function test_finishing_is_required_and_only_sale_properties_keep_a_delivery_year(): void
    {
        $admin = $this->admin();
        $nextYear = now()->year + 1;

        $this->actingAs($admin)->post(route('admin.properties.store'), [...$this->payload(), 'finishing' => null])
            ->assertSessionHasErrors('finishing');
        $this->post(route('admin.properties.store'), [...$this->payload(), 'delivery_year' => 'soon'])
            ->assertSessionHasErrors('delivery_year');

        $this->post(route('admin.properties.store'), [...$this->payload(), 'delivery_year' => $nextYear, 'year_built' => null])
            ->assertSessionHasNoErrors();
        $sale = Property::sole();
        $this->assertSame(FinishingType::SemiFinished, $sale->finishing);
        $this->assertSame($nextYear, $sale->delivery_year);
        $this->assertNull($sale->year_built);

        $this->patch(route('admin.properties.listing', $sale), ['purpose' => 'rent'])->assertSessionHasNoErrors();
        $this->assertNull($sale->fresh()->delivery_year);

        $this->post(route('admin.properties.store'), [...$this->payload(), 'purpose' => 'rent', 'delivery_year' => $nextYear])
            ->assertSessionHasNoErrors();
        $this->assertNull(Property::latest('id')->first()->delivery_year);
    }

    public function test_the_listing_filters_by_finishing_and_delivery(): void
    {
        $ready = $this->listed(['finishing' => FinishingType::Furnished, 'delivery_year' => null]);
        $deliveredThisYear = $this->listed(['finishing' => FinishingType::FullyFinished, 'delivery_year' => now()->year]);
        $soon = $this->listed(['finishing' => FinishingType::CoreAndShell, 'delivery_year' => now()->year + 2]);
        $later = $this->listed(['finishing' => FinishingType::CoreAndShell, 'delivery_year' => now()->year + 4]);

        $this->assertListingIds(['finishing' => 'furnished'], [$ready]);
        $this->assertListingIds(['finishing' => 'core_and_shell'], [$soon, $later]);
        $this->assertListingIds(['delivery' => 'ready'], [$ready, $deliveredThisYear]);
        $this->assertListingIds(['delivery' => now()->year + 2], [$ready, $deliveredThisYear, $soon]);
        $this->assertListingIds(['delivery' => 'soon', 'finishing' => 'marble'], [$ready, $deliveredThisYear, $soon, $later]);

        $this->get(route('listing.index', ['delivery' => 'ready']))
            ->assertInertia(fn ($page) => $page->where('filters.delivery', 'ready'));
    }

    public function test_cards_and_the_property_page_show_finishing_and_delivery(): void
    {
        $offPlan = $this->listed(['finishing' => FinishingType::CoreAndShell, 'delivery_year' => now()->year + 2]);
        $arrived = $this->listed(['finishing' => FinishingType::Furnished, 'delivery_year' => now()->year]);

        $this->get(route('listing.show', $offPlan->slug))->assertInertia(fn ($page) => $page
            ->where('property.finishing', ['id' => 'core_and_shell', 'name' => 'Core & shell'])
            ->where('property.delivery', ['ready' => false, 'year' => now()->year + 2]));

        $this->get(route('listing.index'))->assertInertia(fn ($page) => $page->where('properties.data',
            fn ($rows) => collect($rows)->firstWhere('id', $arrived->id)['delivery'] === ['ready' => true, 'year' => null]));
    }

    private function assertListingIds(array $query, array $expected): void
    {
        $this->get(route('listing.index', $query))->assertInertia(fn ($page) => $page->where('properties.data',
            fn ($rows) => collect($rows)->pluck('id')->sort()->values()->all() === collect($expected)->pluck('id')->sort()->values()->all()));
    }

    private function listed(array $attributes): Property
    {
        return Property::factory()->create(['purpose' => 'sale', 'status' => 'available', 'is_published' => true, ...$attributes]);
    }

    private function admin(): User
    {
        return User::factory()->create()->assignRole(PermissionSeeder::SUPER_ADMIN);
    }

    private function payload(): array
    {
        $area = Area::factory()->create()->load('city');

        return [
            'name' => 'Sunny Apartment',
            'description' => '<p>Close to the metro.</p>',
            'price' => 1500000,
            'address' => '12 Nile Street',
            'year_built' => 2015,
            'sqft' => 1200,
            'locale' => 'en',
            'country_id' => $area->city->country_id,
            'city_id' => $area->city_id,
            'area_id' => $area->id,
            'property_type_id' => PropertyType::factory()->create()->id,
            'property_classification_id' => PropertyClassifications::factory()->create()->id,
            'purpose' => 'sale',
            'status' => 'available',
            'finishing' => 'semi_finished',
            'feature_ids' => [Feature::factory()->create()->id],
            'image' => UploadedFile::fake()->image('cover.jpg', 800, 600),
            'gallery_images' => array_map(
                fn (int $i) => UploadedFile::fake()->image("room-{$i}.jpg", 800, 600),
                range(1, Property::MIN_GALLERY_IMAGES),
            ),
        ];
    }
}
