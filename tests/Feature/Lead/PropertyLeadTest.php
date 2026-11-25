<?php

namespace Tests\Feature\Lead;

use App\Enums\NotificationPermission;
use App\Models\User;
use App\Services\OwnerRegistrationService;
use Database\Seeders\PermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Modules\Dashboard\Enums\DashboardPermission;
use Modules\Lead\Enums\LeadPermission;
use Modules\Lead\Enums\LeadSource;
use Modules\Lead\Enums\LeadStatus;
use Modules\Lead\Models\PropertyLead;
use Modules\Lead\Notifications\NewPropertyLeadNotification;
use Modules\Property\Enums\PropertyPermission;
use Modules\Property\Models\Property;
use Tests\TestCase;

class PropertyLeadTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->withoutVite();
        $this->seed(PermissionSeeder::class);
        Notification::fake();
    }

    public function test_a_visitor_sends_an_enquiry_to_the_owner_of_a_listed_property(): void
    {
        $owner = $this->owner();
        $property = $this->listedPropertyOf($owner);
        $admin = User::factory()->create()->givePermissionTo(NotificationPermission::PropertyLeads->value);

        $this->post(route('listing.leads.store', $property->slug), $this->enquiry())
            ->assertSessionHasNoErrors()
            ->assertSessionHas('message', __('Your message was sent to the owner.'))
            ->assertSessionMissing('contact_url');

        $lead = PropertyLead::sole();
        $this->assertTrue($lead->owner->is($owner));
        $this->assertTrue($lead->property->is($property));
        $this->assertSame(LeadSource::Form, $lead->source);
        $this->assertSame(LeadStatus::New, $lead->status);
        $this->assertSame('+201001234567', $lead->phone);
        Notification::assertSentTo($owner, NewPropertyLeadNotification::class,
            fn ($notification, array $channels) => $channels === ['mail', 'database']);
        Notification::assertSentTo($admin, NewPropertyLeadNotification::class);
    }

    public function test_call_and_whatsapp_leads_reveal_the_owners_number_only_after_saving(): void
    {
        $property = $this->listedPropertyOf($this->owner('+201009998887'));

        $this->get(route('listing.show', $property->slug))
            ->assertOk()
            ->assertDontSee('201009998887')
            ->assertInertia(fn ($page) => $page->where('property.owner_reachable', true));

        $this->post(route('listing.leads.store', $property->slug), ['source' => 'call', 'full_name' => 'Omar', 'phone_country' => 'EG', 'phone' => '01001234567'])
            ->assertSessionHasNoErrors()
            ->assertSessionHas('contact_url', 'tel:+201009998887');

        $this->post(route('listing.leads.store', $property->slug), ['source' => 'whatsapp', 'full_name' => 'Omar', 'phone_country' => 'GB', 'phone' => '07400 123456'])
            ->assertSessionHasNoErrors()
            ->assertSessionHas('contact_url', fn (string $url) => str_starts_with($url, 'https://wa.me/201009998887?text='));

        $this->assertEqualsCanonicalizing(
            [LeadSource::Call, LeadSource::WhatsApp],
            PropertyLead::pluck('source')->all(),
        );
        $this->assertSame('+447400123456', PropertyLead::firstWhere('source', LeadSource::WhatsApp)->phone);
    }

    public function test_an_owner_without_a_phone_gets_no_call_or_whatsapp_buttons(): void
    {
        $owner = User::factory()->create(['phone' => null])->assignRole(OwnerRegistrationService::ROLE);
        $property = $this->listedPropertyOf($owner);

        $this->get(route('listing.show', $property->slug))
            ->assertInertia(fn ($page) => $page->where('property.owner_reachable', false));
    }

    public function test_leads_need_a_listed_property_valid_details_and_are_rate_limited(): void
    {
        $owner = $this->owner();
        $pending = Property::factory()->ownedBy($owner)->pending()->create();
        $listed = $this->listedPropertyOf($owner);

        $this->post(route('listing.leads.store', $pending->slug), $this->enquiry())->assertNotFound();
        $this->post(route('listing.leads.store', $listed->slug), [...$this->enquiry(), 'email' => ''])->assertSessionHasErrors('email');
        $this->post(route('listing.leads.store', $listed->slug), [...$this->enquiry(), 'phone' => '123'])->assertSessionHasErrors('phone');
        $this->post(route('listing.leads.store', $listed->slug), [...$this->enquiry(), 'phone_country' => ''])->assertSessionHasErrors('phone_country');

        $this->post(route('listing.leads.store', $listed->slug), $this->enquiry())->assertSessionHasNoErrors();
        $this->post(route('listing.leads.store', $listed->slug), $this->enquiry())->assertSessionHasErrors('database');

        $this->assertSame(1, PropertyLead::count());
    }

    public function test_owners_see_and_move_only_their_own_leads(): void
    {
        $owner = $this->owner();
        $mine = PropertyLead::factory()->forProperty($this->listedPropertyOf($owner))->create();
        $theirs = PropertyLead::factory()->forProperty($this->listedPropertyOf($this->owner()))->create();

        $this->actingAs($owner)->get(route('supplier.leads.index'))
            ->assertInertia(fn ($page) => $page->component('Owner/Leads/Index')
                ->where('board.data.0.id', 'new')
                ->where('board.data.0.items', fn ($items) => collect($items)->pluck('id')->all() === [$mine->id]));
        $this->actingAs($owner)->get(route('supplier.leads.index', ['view' => 'table']))
            ->assertInertia(fn ($page) => $page->where('board', null)
                ->where('leads.data', fn ($rows) => collect($rows)->pluck('id')->all() === [$mine->id]));

        $this->actingAs($owner)->patch(route('supplier.leads.status', $theirs), ['status' => 'contacted'])->assertForbidden();
        $this->actingAs($owner)->patch(route('supplier.leads.status', $mine), ['status' => 'contacted'])->assertSessionHasNoErrors();

        $this->assertSame(LeadStatus::Contacted, $mine->fresh()->status);
        $this->assertSame(LeadStatus::New, $theirs->fresh()->status);
    }

    public function test_admins_see_every_lead_and_filter_them(): void
    {
        $admin = User::factory()->create()->givePermissionTo([
            DashboardPermission::View->value,
            LeadPermission::ViewPropertyLeads->value,
            LeadPermission::EditPropertyLeads->value,
        ]);
        $call = PropertyLead::factory()->forProperty($this->listedPropertyOf($this->owner()))->create(['source' => LeadSource::Call]);
        $form = PropertyLead::factory()->forProperty($this->listedPropertyOf($this->owner()))->withStatus(LeadStatus::Closed)->create();

        $this->actingAs($admin)->get(route('admin.property-leads.index', ['view' => 'table']))
            ->assertInertia(fn ($page) => $page->has('leads.data', 2)->where('leads.data.0.owner_name', fn ($name) => filled($name)));
        $this->actingAs($admin)->get(route('admin.property-leads.index', ['view' => 'table', 'source' => 'call']))
            ->assertInertia(fn ($page) => $page->where('leads.data', fn ($rows) => collect($rows)->pluck('id')->all() === [$call->id]));
        $this->actingAs($admin)->get(route('admin.property-leads.index', ['view' => 'table', 'status' => 'closed']))
            ->assertInertia(fn ($page) => $page->where('leads.data', fn ($rows) => collect($rows)->pluck('id')->all() === [$form->id]));
        $this->actingAs($admin)->get(route('admin.property-leads.index'))
            ->assertInertia(fn ($page) => $page->where('board.data.0.count', 1)->where('board.data.2.count', 1));

        $this->actingAs($admin)->patch(route('admin.property-leads.status', $call), ['status' => 'closed'])->assertSessionHasNoErrors();
        $this->assertSame(LeadStatus::Closed, $call->fresh()->status);
    }

    public function test_a_property_with_leads_cannot_be_deleted(): void
    {
        $admin = User::factory()->create()->givePermissionTo(PropertyPermission::Delete->value);
        $property = $this->listedPropertyOf($this->owner());
        PropertyLead::factory()->forProperty($property)->create();

        $this->actingAs($admin)->delete(route('admin.properties.destroy', $property))
            ->assertSessionHasErrors(['database' => __('This property still has leads.')]);
        $this->assertModelExists($property);
    }

    public function test_the_owner_dashboard_counts_new_leads(): void
    {
        $owner = $this->owner();
        $property = $this->listedPropertyOf($owner);
        PropertyLead::factory()->forProperty($property)->count(2)->create();
        PropertyLead::factory()->forProperty($property)->withStatus(LeadStatus::Contacted)->create();

        $this->actingAs($owner)->get(route('supplier.dashboard'))
            ->assertInertia(fn ($page) => $page->where('new_leads', 2));
    }

    private function owner(?string $phone = null): User
    {
        return User::factory()->create(['phone' => $phone ?? '+2010' . fake()->unique()->numerify('########'), 'phone_country' => 'EG'])
            ->assignRole(OwnerRegistrationService::ROLE);
    }

    private function listedPropertyOf(User $owner): Property
    {
        return Property::factory()->ownedBy($owner)->create(['is_published' => true]);
    }

    private function enquiry(): array
    {
        return [
            'source' => 'form',
            'full_name' => 'Sara Visitor',
            'email' => 'sara@example.com',
            'phone_country' => 'EG',
            'phone' => '0100 123 4567',
            'message' => 'Is it still available?',
        ];
    }
}
