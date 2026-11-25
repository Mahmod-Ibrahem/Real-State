<?php

namespace Tests\Feature;

use App\Models\User;
use App\Services\OwnerRegistrationService;
use Database\Seeders\PermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;
use Modules\Property\Models\Property;
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
        return User::factory()->create(['phone' => '+201001234567', 'phone_country' => 'EG', 'phone_verified_at' => now()])
            ->assignRole(OwnerRegistrationService::ROLE);
    }
}
