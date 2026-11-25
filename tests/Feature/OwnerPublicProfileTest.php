<?php

namespace Tests\Feature;

use App\Models\User;
use App\Services\OwnerRegistrationService;
use Database\Seeders\PermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;
use Modules\Property\Models\Property;
use Modules\Property\Models\PropertyClassifications;
use Tests\TestCase;

class OwnerPublicProfileTest extends TestCase
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

    public function test_a_supplier_adds_an_avatar_when_signing_up(): void
    {
        $this->post(route('supplier.register'), [
            'name' => 'Sara Owner',
            'email' => 'sara@example.com',
            'phone_country' => 'EG',
            'phone' => '01001234567',
            'password' => 'password',
            'password_confirmation' => 'password',
            'avatar' => UploadedFile::fake()->image('me.jpg', 900, 900),
        ])->assertRedirect(route('supplier.dashboard'));

        $avatar = User::firstWhere('email', 'sara@example.com')->avatar;
        $this->assertSame([400, 400], array_slice(getimagesize($this->storedFile($avatar)), 0, 2));
    }

    public function test_the_owner_profile_replaces_and_removes_the_avatar_without_touching_other_fields(): void
    {
        $owner = $this->owner();
        $profile = ['whatsapp_country' => 'EG', 'whatsapp_phone' => '0111 222 3334'];

        $this->actingAs($owner)
            ->patch(route('supplier.profile.update'), [...$profile, 'avatar' => UploadedFile::fake()->image('me.jpg', 900, 900)])
            ->assertSessionHasNoErrors();
        $first = $owner->fresh()->avatar;
        $this->assertFileExists($this->storedFile($first));

        $this->patch(route('supplier.profile.update'), [...$profile, 'avatar' => UploadedFile::fake()->image('again.png', 600, 600)])
            ->assertSessionHasNoErrors();
        $second = $owner->fresh()->avatar;
        $this->assertNotSame($first, $second);
        $this->assertFileDoesNotExist($this->storedFile($first));

        $this->patch(route('supplier.profile.update'), $profile)->assertSessionHasNoErrors();
        $this->assertSame($second, $owner->fresh()->avatar);

        $this->patch(route('supplier.profile.update'), [...$profile, 'remove_avatar' => true])->assertSessionHasNoErrors();
        $this->assertNull($owner->fresh()->avatar);
        $this->assertFileDoesNotExist($this->storedFile($second));
        $this->assertSame('+201112223334', $owner->fresh()->whatsapp_phone);

        $this->patch(route('supplier.profile.update'), [...$profile, 'avatar' => UploadedFile::fake()->create('notes.pdf', 10, 'application/pdf')])
            ->assertSessionHasErrors('avatar');
    }

    public function test_the_admin_profile_saves_the_avatar_with_the_name_and_email(): void
    {
        $admin = User::factory()->create()->assignRole(PermissionSeeder::SUPER_ADMIN);

        $this->actingAs($admin)->patch(route('admin.profile.update'), [
            'name' => 'New Name',
            'email' => $admin->email,
            'avatar' => UploadedFile::fake()->image('me.jpg', 500, 500),
        ])->assertSessionHasNoErrors();

        $admin->refresh();
        $this->assertSame('New Name', $admin->name);
        $this->assertFileExists($this->storedFile($admin->avatar));
        $this->assertNotNull($admin->email_verified_at);
    }

    public function test_an_owner_sets_and_clears_their_whatsapp_number(): void
    {
        $owner = $this->owner();

        $this->actingAs($owner)
            ->patch(route('supplier.profile.update'), ['whatsapp_country' => 'EG', 'whatsapp_phone' => '0111 222 3334'])
            ->assertSessionHasNoErrors();
        $this->assertSame('+201112223334', $owner->fresh()->whatsapp_phone);
        $this->assertSame('EG', $owner->fresh()->whatsapp_country);

        $this->patch(route('supplier.profile.update'), ['whatsapp_country' => 'EG', 'whatsapp_phone' => '0222 345 678'])
            ->assertSessionHasErrors('whatsapp_phone');

        $this->patch(route('supplier.profile.update'), ['whatsapp_country' => 'EG', 'whatsapp_phone' => null])
            ->assertSessionHasNoErrors();
        $this->assertNull($owner->fresh()->whatsapp_phone);
        $this->assertNull($owner->fresh()->whatsapp_country);
    }

    public function test_listings_and_the_property_page_show_the_owner_without_their_contact_details(): void
    {
        $owner = $this->owner();
        $owner->forceFill(['avatar' => 'https://example.com/owner.jpg', 'whatsapp_phone' => '+201112223334'])->save();
        $property = Property::factory()->ownedBy($owner)->create(['is_published' => true]);
        Property::factory()->ownedBy($owner)->create(['is_published' => true]);
        Property::factory()->ownedBy($owner)->pending()->create();
        $publicOwner = [
            'id' => $owner->id,
            'name' => $owner->name,
            'avatar' => 'https://example.com/owner.jpg',
            'verified' => true,
            'member_since' => $owner->created_at->year,
        ];

        $this->get(route('listing.show', $property->slug))
            ->assertDontSee($owner->email)
            ->assertDontSee('201112223334')
            ->assertInertia(fn ($page) => $page->where('property.owner', [...$publicOwner, 'listed_properties_count' => 2]));

        $this->get(route('listing.index'))
            ->assertDontSee($owner->email)
            ->assertInertia(fn ($page) => $page->where('properties.data.0.owner', $publicOwner));
    }

    public function test_the_home_page_cards_show_the_owner_and_only_listed_properties(): void
    {
        $owner = $this->owner();
        $luxury = PropertyClassifications::factory()->create(['name' => 'Luxury']);
        $listed = Property::factory()->ownedBy($owner)->create(['is_published' => true, 'property_classification_id' => $luxury->id]);
        Property::factory()->ownedBy($owner)->pending()->create(['property_classification_id' => $luxury->id]);
        Property::factory()->ownedBy($owner)->create(['is_published' => false, 'property_classification_id' => $luxury->id]);

        $this->get(route('home'))->assertInertia(fn ($page) => $page
            ->has('classifications.0.properties.data', 1)
            ->where('classifications.0.properties.data.0.id', $listed->id)
            ->where('classifications.0.properties.data.0.owner.name', $owner->name));

        $owner->forceFill(['avatar' => 'https://example.com/new.jpg'])->save();

        $this->get(route('home'))->assertInertia(fn ($page) => $page
            ->where('classifications.0.properties.data.0.owner.avatar', 'https://example.com/new.jpg'));
    }

    public function test_the_owner_page_lists_only_their_listed_properties(): void
    {
        $owner = $this->owner();
        $listed = Property::factory()->ownedBy($owner)->count(2)->create(['is_published' => true]);
        Property::factory()->ownedBy($owner)->pending()->create();
        Property::factory()->ownedBy($this->owner())->create(['is_published' => true]);

        $this->get(route('owners.show', $owner))
            ->assertOk()
            ->assertDontSee($owner->email)
            ->assertInertia(fn ($page) => $page->component('Users/OwnerProfile')
                ->where('owner.name', $owner->name)
                ->where('owner.listed_properties_count', 2)
                ->where('properties.data', fn ($rows) => collect($rows)->pluck('id')->sort()->values()->all()
                    === $listed->pluck('id')->sort()->values()->all()));
    }

    public function test_owners_with_nothing_listed_or_under_review_get_an_empty_page(): void
    {
        $nothingListed = $this->owner();
        Property::factory()->ownedBy($nothingListed)->pending()->create();
        $underReview = User::factory()->underReview()->create()->assignRole(OwnerRegistrationService::ROLE);
        Property::factory()->ownedBy($underReview)->create(['is_published' => true]);

        foreach ([$nothingListed, $underReview] as $owner) {
            $this->get(route('owners.show', $owner))
                ->assertOk()
                ->assertInertia(fn ($page) => $page
                    ->where('owner.name', $owner->name)
                    ->where('owner.listed_properties_count', 0)
                    ->has('properties.data', 0));
        }

        $this->get(route('owners.show', 999999))->assertNotFound();
    }

    public function test_the_profile_pages_render(): void
    {
        $this->actingAs($this->owner())->get(route('supplier.profile.show'))->assertOk();
        $this->actingAs(User::factory()->create()->assignRole(PermissionSeeder::SUPER_ADMIN))
            ->get(route('admin.profile.edit'))->assertOk();
    }

    private function storedFile(string $url): string
    {
        return storage_path('app/public' . str_replace(url('storage'), '', $url));
    }

    private function owner(): User
    {
        return User::factory()
            ->create(['phone' => '+2010' . fake()->unique()->numerify('########'), 'phone_country' => 'EG', 'phone_verified_at' => now()])
            ->assignRole(OwnerRegistrationService::ROLE);
    }
}
