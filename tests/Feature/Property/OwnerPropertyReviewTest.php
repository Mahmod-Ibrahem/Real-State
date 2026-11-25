<?php

namespace Tests\Feature\Property;

use App\Enums\NotificationPermission;
use App\Models\User;
use App\Services\OwnerRegistrationService;
use Database\Seeders\PermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Notification;
use Modules\Location\Models\Area;
use Modules\Property\Enums\PropertyPermission;
use Modules\Property\Enums\ReviewStatus;
use Modules\Property\Models\Feature;
use Modules\Property\Models\Property;
use Modules\Property\Models\PropertyClassifications;
use Modules\Property\Models\PropertyType;
use Modules\Property\Notifications\PropertyApprovedNotification;
use Modules\Property\Notifications\PropertySubmittedNotification;
use Tests\TestCase;

class OwnerPropertyReviewTest extends TestCase
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
        Notification::fake();
    }

    protected function tearDown(): void
    {
        File::deleteDirectory($this->storagePath);

        parent::tearDown();
    }

    public function test_owners_register_on_their_own_page_and_land_on_the_owner_dashboard(): void
    {
        $response = $this->post(route('supplier.register'), [
            'name' => 'Sara Owner',
            'email' => 'sara@example.com',
            'phone_country' => 'EG',
            'phone' => '01001234567',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $response->assertRedirect(route('supplier.dashboard'));
        $owner = User::firstWhere('email', 'sara@example.com');
        $this->assertTrue($owner->can(PropertyPermission::CreateOwn->value));
        $this->assertFalse($owner->can(PropertyPermission::View->value));
    }

    public function test_a_submitted_property_is_pending_and_mails_admins_who_receive_submissions(): void
    {
        $owner = $this->owner();
        $admin = $this->admin();
        $reviewerWithoutAlerts = User::factory()->create()->givePermissionTo(PropertyPermission::Review->value);

        $this->actingAs($owner)->post(route('supplier.properties.store'), $this->payload())
            ->assertRedirect(route('supplier.properties.index'));

        $property = Property::sole();
        $this->assertTrue($property->owner()->is($owner));
        $this->assertSame(ReviewStatus::Pending, $property->review_status);
        $this->assertFalse($property->is_published);
        Notification::assertSentTo(
            $admin,
            PropertySubmittedNotification::class,
            fn (PropertySubmittedNotification $notification, array $channels) => $channels === ['mail', 'database'],
        );
        Notification::assertNotSentTo($reviewerWithoutAlerts, PropertySubmittedNotification::class);
    }

    public function test_an_owner_cannot_touch_another_owners_property(): void
    {
        $property = Property::factory()->ownedBy($this->owner())->create();

        $this->actingAs($this->owner())->get(route('supplier.properties.edit', $property))->assertForbidden();
        $this->actingAs($this->owner())->patch(route('supplier.properties.publish', $property))->assertForbidden();
    }

    public function test_only_approved_properties_can_be_published(): void
    {
        $owner = $this->owner();
        $pending = Property::factory()->ownedBy($owner)->pending()->create();
        $approved = Property::factory()->ownedBy($owner)->create(['is_published' => false]);

        $this->actingAs($owner)->patch(route('supplier.properties.publish', $pending))->assertSessionHasErrors('database');
        $this->actingAs($owner)->patch(route('supplier.properties.publish', $approved))->assertSessionHasNoErrors();

        $this->assertFalse($pending->fresh()->is_published);
        $this->assertTrue($approved->fresh()->is_published);
    }

    public function test_approving_notifies_the_owner_by_mail_and_in_the_dashboard(): void
    {
        $owner = $this->owner();
        $property = Property::factory()->ownedBy($owner)->pending()->create();

        $this->actingAs($this->admin())->patch(route('admin.properties.approve', $property))->assertSessionHasNoErrors();

        $this->assertSame(ReviewStatus::Approved, $property->fresh()->review_status);
        Notification::assertSentTo(
            $owner,
            PropertyApprovedNotification::class,
            fn (PropertyApprovedNotification $notification, array $channels) => $channels === ['mail', 'database'],
        );
    }

    public function test_rejecting_stores_the_reason_without_notifying_the_owner(): void
    {
        $owner = $this->owner();
        $property = Property::factory()->ownedBy($owner)->pending()->create();

        $this->actingAs($this->admin())
            ->patch(route('admin.properties.reject', $property), ['reason' => 'Photos are blurry.'])
            ->assertSessionHasNoErrors();

        $property->refresh();
        $this->assertSame(ReviewStatus::Rejected, $property->review_status);
        $this->assertSame('Photos are blurry.', $property->rejection_reason);
        Notification::assertNothingSentTo($owner);
    }

    public function test_editing_an_approved_property_sends_it_back_to_review(): void
    {
        $owner = $this->owner();
        $admin = $this->admin();
        $property = Property::factory()->ownedBy($owner)->withGallery()->create(['is_published' => true]);
        $cover = $property->image;

        $this->actingAs($owner)
            ->put(route('supplier.properties.update', $property), [...$this->payload(), 'image' => null, 'gallery_images' => []])
            ->assertRedirect(route('supplier.properties.index'));

        $property->refresh();
        $this->assertSame($cover, $property->image);
        $this->assertSame(ReviewStatus::Pending, $property->review_status);
        $this->assertFalse($property->is_published);
        Notification::assertSentTo($admin, PropertySubmittedNotification::class);
    }

    public function test_changing_only_the_name_sends_an_approved_property_back_to_review(): void
    {
        $owner = $this->owner();
        $admin = $this->admin();
        $property = $this->liveListingOf($owner);

        $this->actingAs($owner)
            ->put(route('supplier.properties.update', $property), [...$this->payloadFor($property), 'name' => 'Renamed villa'])
            ->assertSessionHasNoErrors();

        $property->refresh();
        $this->assertSame('Renamed villa', $property->name);
        $this->assertSame(ReviewStatus::Pending, $property->review_status);
        $this->assertFalse($property->is_published);
        Notification::assertSentTo($admin, PropertySubmittedNotification::class);
    }

    public function test_saving_without_changes_still_sends_an_approved_property_back_to_review(): void
    {
        $owner = $this->owner();
        $admin = $this->admin();
        $property = $this->liveListingOf($owner);

        $this->actingAs($owner)
            ->put(route('supplier.properties.update', $property), $this->payloadFor($property))
            ->assertSessionHasNoErrors();

        $property->refresh();
        $this->assertSame(ReviewStatus::Pending, $property->review_status);
        $this->assertFalse($property->is_published);
        Notification::assertSentTo($admin, PropertySubmittedNotification::class);
    }

    public function test_editing_a_pending_property_keeps_it_pending_without_a_new_mail(): void
    {
        $owner = $this->owner();
        $admin = $this->admin();
        $property = Property::factory()->ownedBy($owner)->pending()->withGallery()->create();
        $property->features()->attach(Feature::factory()->create());

        $this->actingAs($owner)
            ->put(route('supplier.properties.update', $property), $this->payloadFor($property->load('features')))
            ->assertSessionHasNoErrors();

        $this->assertSame(ReviewStatus::Pending, $property->fresh()->review_status);
        Notification::assertNotSentTo($admin, PropertySubmittedNotification::class);
    }

    public function test_a_property_keeps_at_least_five_gallery_photos(): void
    {
        $owner = $this->owner();
        $message = __('Add at least :count gallery photos.', ['count' => Property::MIN_GALLERY_IMAGES]);

        $this->actingAs($owner)
            ->post(route('supplier.properties.store'), [...$this->payload(), 'gallery_images' => $this->photos(4)])
            ->assertSessionHasErrors(['gallery_images' => $message]);
        $this->assertSame(0, Property::count());

        $property = $this->liveListingOf($owner);
        $firstPhotoId = $property->propertyImages()->value('id');

        $this->actingAs($owner)
            ->put(route('supplier.properties.update', $property), [...$this->payloadFor($property), 'removed_image_ids' => [$firstPhotoId]])
            ->assertSessionHasErrors(['gallery_images' => $message]);
        $this->assertSame(Property::MIN_GALLERY_IMAGES, $property->propertyImages()->count());

        $this->actingAs($owner)
            ->put(route('supplier.properties.update', $property), [
                ...$this->payloadFor($property),
                'removed_image_ids' => [$firstPhotoId],
                'gallery_images' => $this->photos(1),
            ])
            ->assertSessionHasNoErrors();
        $this->assertSame(Property::MIN_GALLERY_IMAGES, $property->propertyImages()->count());
    }

    public function test_owners_are_kept_out_of_the_admin_and_guests_go_to_the_owner_login(): void
    {
        $this->actingAs($this->owner())->get(route('admin.properties.index'))->assertForbidden();
        $this->post(route('logout'));

        $this->get(route('supplier.dashboard'))->assertRedirect(route('supplier.login'));
    }

    public function test_owner_and_admin_pages_render(): void
    {
        $owner = $this->owner();
        $property = Property::factory()->ownedBy($owner)->rejected('Blurry photos.')->create();

        $this->get(route('supplier.login'))->assertOk();
        $this->get(route('supplier.register'))->assertOk();

        $this->actingAs($owner);
        $this->get(route('supplier.dashboard'))->assertOk();
        $this->get(route('supplier.properties.index'))->assertOk();
        $this->get(route('supplier.properties.create'))->assertOk();
        $this->get(route('supplier.properties.edit', $property))->assertOk();

        $this->actingAs($this->admin());
        $this->get(route('admin.dashboard'))->assertOk();
        $this->get(route('admin.properties.index', ['review' => 'rejected', 'search' => 'a']))->assertOk();
        $this->get(route('admin.properties.create'))->assertOk();
        $this->get(route('admin.properties.edit', $property))->assertOk();
    }

    public function test_the_public_site_only_shows_approved_and_published_properties(): void
    {
        $visible = Property::factory()->create(['is_published' => true]);
        Property::factory()->create(['is_published' => false]);
        Property::factory()->pending()->create();

        $this->assertSame([$visible->id], Property::active()->pluck('id')->all());
    }

    /** @return list<UploadedFile> */
    private function photos(int $count): array
    {
        return array_map(fn (int $i) => UploadedFile::fake()->image("room-{$i}.jpg", 800, 600), range(1, $count));
    }

    private function owner(): User
    {
        return User::factory()->create()->assignRole(OwnerRegistrationService::ROLE);
    }

    private function admin(): User
    {
        return User::factory()->create()->assignRole(PermissionSeeder::SUPER_ADMIN);
    }

    private function liveListingOf(User $owner): Property
    {
        $property = Property::factory()->ownedBy($owner)->withGallery()->create(['is_published' => true]);
        $property->features()->attach(Feature::factory()->create());

        return $property->load('features');
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
            'feature_ids' => [Feature::factory()->create()->id],
            'image' => UploadedFile::fake()->image('cover.jpg', 800, 600),
            'gallery_images' => $this->photos(Property::MIN_GALLERY_IMAGES),
        ];
    }
}
