<?php

namespace Tests\Feature;

use App\Models\User;
use App\Services\OwnerRegistrationService;
use Database\Seeders\PermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Notifications\DatabaseNotification;
use Modules\Property\Models\Property;
use Modules\Property\Notifications\PropertyApprovedNotification;
use Modules\Property\Notifications\PropertySubmittedNotification;
use Tests\TestCase;

class NotificationBellTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->withoutVite();
        $this->seed(PermissionSeeder::class);
    }

    public function test_the_bell_is_filled_instantly_while_mail_waits_for_the_queue(): void
    {
        config(['queue.default' => 'database']);
        $supplier = $this->supplier();
        $property = Property::factory()->ownedBy($supplier)->pending()->create();

        $this->actingAs($this->admin())->patch(route('admin.properties.approve', $property))->assertSessionHasNoErrors();

        $this->assertSame(1, $supplier->notifications()->count());
        $this->assertDatabaseCount('jobs', 1);
    }

    public function test_opening_a_notification_marks_it_read_and_follows_its_link(): void
    {
        $admin = $this->admin();
        $property = Property::factory()->pending()->create();
        $admin->notify(new PropertySubmittedNotification($property));
        $notification = $admin->notifications()->sole();

        $this->actingAs($admin)->get(route('notifications.open', $notification->id))
            ->assertRedirect(route('admin.properties.edit', $property));

        $this->assertNotNull($notification->fresh()->read_at);
    }

    public function test_opening_a_notification_from_the_dashboard_stays_an_inertia_visit(): void
    {
        $admin = $this->admin();
        $admin->notify(new PropertySubmittedNotification(Property::factory()->pending()->create()));
        $version = $this->actingAs($admin)->get(route('admin.dashboard'))->viewData('page')['version'];

        $this->withHeaders(['X-Inertia' => 'true', 'X-Inertia-Version' => $version])
            ->get(route('notifications.open', $admin->notifications()->sole()->id))
            ->assertRedirect();
    }

    public function test_users_cannot_open_someone_elses_notification(): void
    {
        $supplier = $this->supplier();
        $supplier->notify(new PropertyApprovedNotification(Property::factory()->ownedBy($supplier)->create()));

        $this->actingAs($this->supplier())
            ->get(route('notifications.open', DatabaseNotification::sole()->id))
            ->assertNotFound();
    }

    public function test_mark_all_read_clears_the_unread_count(): void
    {
        $supplier = $this->supplier();
        $property = Property::factory()->ownedBy($supplier)->create();
        $supplier->notify(new PropertyApprovedNotification($property));
        $supplier->notify(new PropertyApprovedNotification($property));

        $this->actingAs($supplier)->patchJson(route('notifications.read-all'))->assertNoContent();

        $this->assertSame(0, $supplier->unreadNotifications()->count());
    }

    public function test_the_bell_fetches_its_feed_from_its_own_endpoint(): void
    {
        $supplier = $this->supplier();
        $supplier->notify(new PropertyApprovedNotification(Property::factory()->ownedBy($supplier)->create()));
        $this->supplier()->notify(new PropertyApprovedNotification(Property::factory()->create()));

        $this->actingAs($supplier)->getJson(route('notifications.index'))
            ->assertOk()
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('data.0.is_read', false)
            ->assertJsonPath('data.0.url', route('supplier.properties.index'))
            ->assertJsonPath('meta.unread', 1);
    }

    public function test_guests_cannot_fetch_the_feed(): void
    {
        $this->getJson(route('notifications.index'))->assertUnauthorized();
    }

    private function admin(): User
    {
        return User::factory()->create()->assignRole(PermissionSeeder::SUPER_ADMIN);
    }

    private function supplier(): User
    {
        return User::factory()->create()->assignRole(OwnerRegistrationService::ROLE);
    }
}
