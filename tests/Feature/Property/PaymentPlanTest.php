<?php

namespace Tests\Feature\Property;

use App\Models\User;
use App\Services\OwnerRegistrationService;
use Database\Seeders\PermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Notification;
use Modules\Location\Models\Area;
use Modules\Property\Enums\ReviewStatus;
use Modules\Property\Models\Feature;
use Modules\Property\Models\Property;
use Modules\Property\Models\PropertyClassifications;
use Modules\Property\Models\PropertyPaymentPlan;
use Modules\Property\Models\PropertyType;
use Modules\Property\Services\PropertyPaymentPlanService;
use Tests\TestCase;

class PaymentPlanTest extends TestCase
{
    use RefreshDatabase;

    private const QUARTERLY_8_YEARS = ['down_payment_percent' => 10, 'installment_years' => 8, 'installment_frequency' => 'quarterly'];
    private const MONTHLY_5_YEARS = ['down_payment_percent' => 0, 'installment_years' => 5, 'installment_frequency' => 'monthly'];

    private string $storagePath;

    protected function setUp(): void
    {
        parent::setUp();

        $this->withoutVite();
        $this->storagePath = sys_get_temp_dir() . '/qeima-test-storage-' . uniqid();
        $this->app->useStoragePath($this->storagePath);
        $this->seed(PermissionSeeder::class);
        Notification::fake();
    }

    protected function tearDown(): void
    {
        File::deleteDirectory($this->storagePath);

        parent::tearDown();
    }

    public function test_only_the_plan_terms_are_stored_and_the_amounts_follow_the_price(): void
    {
        $admin = $this->admin();

        $this->actingAs($admin)->post(route('admin.properties.store'), [
            ...$this->payload(),
            'price' => 6000000,
            'maintenance_deposit_percent' => 8,
            'payment_plans' => [self::QUARTERLY_8_YEARS, self::MONTHLY_5_YEARS],
        ])->assertSessionHasNoErrors();

        $property = Property::sole();
        $this->assertSame(8, (int) $property->maintenance_deposit_percent);
        $this->assertEquals(
            [self::QUARTERLY_8_YEARS, self::MONTHLY_5_YEARS],
            $property->paymentPlans->map(fn (PropertyPaymentPlan $plan) => [
                ...$plan->only(['down_payment_percent', 'installment_years']),
                'installment_frequency' => $plan->installment_frequency->value,
            ])->all(),
        );
        $this->assertSame(
            [[600000, 168750, 56250], [0, 100000, 100000]],
            $this->amountsOf($property),
        );

        $this->actingAs($admin)->put(route('admin.properties.update', $property), [
            ...$this->payloadFor($property->load('features')),
            'price' => 3000000,
            'payment_plans' => [self::QUARTERLY_8_YEARS, self::MONTHLY_5_YEARS],
        ])->assertSessionHasNoErrors();

        $this->assertSame([[300000, 84375, 28125], [0, 50000, 50000]], $this->amountsOf($property));
    }

    public function test_plans_are_validated_and_kept_only_for_sale_properties(): void
    {
        $admin = $this->admin();

        $this->actingAs($admin)->post(route('admin.properties.store'), [
            ...$this->payload(),
            'payment_plans' => array_fill(0, 4, self::MONTHLY_5_YEARS),
        ])->assertSessionHasErrors(['payment_plans' => __('A property can have up to 3 payment plans.')]);
        $this->actingAs($admin)->post(route('admin.properties.store'), [
            ...$this->payload(),
            'payment_plans' => [[...self::MONTHLY_5_YEARS, 'down_payment_percent' => 95, 'installment_frequency' => 'weekly']],
        ])->assertSessionHasErrors(['payment_plans.0.down_payment_percent', 'payment_plans.0.installment_frequency']);

        $this->actingAs($admin)->post(route('admin.properties.store'), [
            ...$this->payload(),
            'purpose' => 'rent',
            'maintenance_deposit_percent' => 8,
            'payment_plans' => [self::MONTHLY_5_YEARS],
        ])->assertSessionHasNoErrors();
        $rental = Property::sole();
        $this->assertNull($rental->maintenance_deposit_percent);
        $this->assertSame(0, PropertyPaymentPlan::count());

        $sale = $this->saleListing(6000000, [self::MONTHLY_5_YEARS]);
        $sale->update(['maintenance_deposit_percent' => 8]);
        $this->actingAs($admin)->patch(route('admin.properties.listing', $sale), ['purpose' => 'rent'])->assertSessionHasNoErrors();

        $this->assertSame(0, $sale->paymentPlans()->count());
        $this->assertNull($sale->fresh()->maintenance_deposit_percent);
    }

    public function test_an_owner_saving_plans_replaces_them_and_sends_the_property_back_to_review(): void
    {
        $owner = User::factory()->create()->assignRole(OwnerRegistrationService::ROLE);
        $property = $this->saleListing(6000000, [self::MONTHLY_5_YEARS], $owner);
        $property->features()->attach(Feature::factory()->create());

        $this->actingAs($owner)->put(route('supplier.properties.update', $property), [
            ...$this->payloadFor($property->load('features')),
            'payment_plans' => [self::QUARTERLY_8_YEARS],
        ])->assertSessionHasNoErrors();

        $this->assertSame([[600000, 168750, 56250]], $this->amountsOf($property));
        $this->assertSame(ReviewStatus::Pending, $property->fresh()->review_status);
        $this->assertFalse($property->fresh()->is_published);
    }

    public function test_the_listing_filters_by_payment_type_and_by_a_budget_one_plan_must_fit(): void
    {
        // 6M, 10% over 8 years monthly: 600,000 down, 56,250 a month
        $longPlan = $this->saleListing(6000000, [['down_payment_percent' => 10, 'installment_years' => 8, 'installment_frequency' => 'monthly']]);
        // 3M: 20% over 5 years quarterly (600,000 down, 40,000 a month) or 0% over 3 years (0 down, 83,333 a month)
        $twoPlans = $this->saleListing(3000000, [
            ['down_payment_percent' => 20, 'installment_years' => 5, 'installment_frequency' => 'quarterly'],
            ['down_payment_percent' => 0, 'installment_years' => 3, 'installment_frequency' => 'monthly'],
        ]);
        $cashOnly = $this->saleListing(2000000, []);

        $this->assertListingIds(['payment' => 'cash'], [$cashOnly]);
        $this->assertListingIds(['payment' => 'installments'], [$longPlan, $twoPlans]);
        $this->assertListingIds(['max_monthly' => 50000], [$twoPlans]);
        $this->assertListingIds(['max_monthly' => 56250], [$longPlan, $twoPlans]);
        $this->assertListingIds(['max_down_payment_percent' => 0], [$twoPlans]);
        $this->assertListingIds(['max_down_payment_percent' => 10], [$longPlan, $twoPlans]);
        $this->assertListingIds(['max_monthly' => 50000, 'max_down_payment_percent' => 0], []);
        $this->assertListingIds(['max_monthly' => 60000, 'max_down_payment_percent' => 10], [$longPlan]);
        $this->assertListingIds(['max_monthly' => 90000, 'max_down_payment_percent' => 10], [$longPlan, $twoPlans]);
        $this->assertListingIds(['max_down_payment_percent' => 'abc'], [$longPlan, $twoPlans, $cashOnly]);

        $this->get(route('listing.index', ['payment' => 'installments']))
            ->assertInertia(fn ($page) => $page->where('properties.data', fn ($rows) => collect($rows)
                ->pluck('lowest_monthly_installment', 'id')->all() == [$longPlan->id => 56250, $twoPlans->id => 40000]));
    }

    public function test_the_property_page_lists_the_plans_and_deleting_the_property_removes_them(): void
    {
        $property = $this->saleListing(6000000, [self::QUARTERLY_8_YEARS, self::MONTHLY_5_YEARS]);
        $property->update(['maintenance_deposit_percent' => 8]);

        $this->get(route('listing.show', $property->slug))
            ->assertInertia(fn ($page) => $page
                ->has('property.payment_plans', 2)
                ->where('property.payment_plans.0.installment_amount', 168750)
                ->where('property.payment_plans.0.installment_frequency', 'quarterly')
                ->where('property.maintenance_deposit_amount', 480000));

        $this->actingAs($this->admin())->delete(route('admin.properties.destroy', $property))->assertSessionHasNoErrors();

        $this->assertModelMissing($property);
        $this->assertSame(0, PropertyPaymentPlan::count());
    }

    private function assertListingIds(array $query, array $expected): void
    {
        $this->get(route('listing.index', $query))->assertInertia(fn ($page) => $page->where('properties.data',
            fn ($rows) => collect($rows)->pluck('id')->sort()->values()->all() === collect($expected)->pluck('id')->sort()->values()->all()));
    }

    /** @return list<array{int, int, int}> down payment, installment per period, monthly; in the order the plans were sent */
    private function amountsOf(Property $property): array
    {
        $price = (int) $property->fresh()->price;

        return PropertyPaymentPlan::whereBelongsTo($property)->orderBy('id')->get()
            ->map(fn (PropertyPaymentPlan $plan) => [
                $plan->downPaymentAmount($price), $plan->installmentAmount($price), $plan->monthlyInstallment($price),
            ])
            ->all();
    }

    private function saleListing(int $price, array $plans, ?User $owner = null): Property
    {
        $property = Property::factory()
            ->when($owner, fn ($factory) => $factory->ownedBy($owner))
            ->withGallery()
            ->create(['purpose' => 'sale', 'status' => 'available', 'price' => $price, 'is_published' => true]);
        app(PropertyPaymentPlanService::class)->sync($property, $plans);

        return $property;
    }

    private function admin(): User
    {
        return User::factory()->create()->assignRole(PermissionSeeder::SUPER_ADMIN);
    }

    private function payloadFor(Property $property): array
    {
        return [
            ...$property->only([
                'name', 'description', 'price', 'address', 'bedrooms', 'bathrooms', 'garages', 'floor', 'year_built',
                'sqft', 'google_map', 'country_id', 'city_id', 'area_id', 'property_type_id', 'property_classification_id',
            ]),
            'purpose' => $property->purpose->value,
            'status' => $property->status->value,
            'finishing' => $property->finishing->value,
            'delivery_year' => $property->delivery_year,
            'feature_ids' => $property->features->pluck('id')->all(),
            'locale' => 'en',
        ];
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
            'finishing' => 'fully_finished',
            'feature_ids' => [Feature::factory()->create()->id],
            'image' => UploadedFile::fake()->image('cover.jpg', 800, 600),
            'gallery_images' => array_map(
                fn (int $i) => UploadedFile::fake()->image("room-{$i}.jpg", 800, 600),
                range(1, Property::MIN_GALLERY_IMAGES),
            ),
        ];
    }
}
