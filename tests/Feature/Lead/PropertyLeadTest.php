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
            ->assertSessionHas('message', __('Your message was sent to the owner.'));

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

    public function test_one_tap_call_and_whatsapp_save_a_lead_then_redirect_to_the_owner(): void
    {
        $owner = $this->owner('+201009998887');
        $property = $this->listedPropertyOf($owner);

        $this->get(route('listing.show', $property->slug))
            ->assertOk()
            ->assertDontSee('201009998887')
            ->assertInertia(fn ($page) => $page->where('property.owner_reachable', true));

        $this->post(route('listing.contact', $property->slug), ['source' => 'call'])
            ->assertRedirect('tel:+201009998887');
        $whatsApp = $this->post(route('listing.contact', $property->slug), ['source' => 'whatsapp'])->assertRedirect()
            ->headers->get('Location');
        $this->assertStringStartsWith('https://wa.me/201009998887?text=', $whatsApp);
        $this->assertStringContainsString(route('listing.show', $property->slug), rawurldecode($whatsApp));

        $leads = PropertyLead::all();
        $this->assertEqualsCanonicalizing([LeadSource::Call, LeadSource::WhatsApp], $leads->pluck('source')->all());
        $this->assertTrue($leads->every(fn (PropertyLead $lead) => $lead->owner->is($owner)
            && $lead->status === LeadStatus::New && $lead->full_name === null && $lead->phone === null));
        Notification::assertSentToTimes($owner, NewPropertyLeadNotification::class, 2);
    }

    public function test_one_tap_contact_needs_a_reachable_owner_and_a_phone_source(): void
    {
        $property = $this->listedPropertyOf(User::factory()->create(['phone' => null])->assignRole(OwnerRegistrationService::ROLE));
        $reachable = $this->listedPropertyOf($this->owner());

        $this->get(route('listing.show', $property->slug))
            ->assertInertia(fn ($page) => $page->where('property.owner_reachable', false));
        $this->post(route('listing.contact', $property->slug), ['source' => 'call'])
            ->assertSessionHasErrors(['database' => __('This owner can\'t be reached by phone. Send them a message instead.')]);
        $this->post(route('listing.contact', $reachable->slug), ['source' => 'form'])->assertSessionHasErrors('source');
        $this->post(route('listing.contact', Property::factory()->pending()->create()->slug), ['source' => 'call'])->assertNotFound();

        $this->assertSame(0, PropertyLead::count());
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
        $call = PropertyLead::factory()->forProperty($this->listedPropertyOf($this->owner()))
            ->create(['source' => LeadSource::Call, 'full_name' => null, 'email' => null, 'phone' => null, 'message' => null]);
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

    public function test_owners_and_admins_fill_in_a_leads_contact_details_later(): void
    {
        $owner = $this->owner();
        $mine = PropertyLead::factory()->forProperty($this->listedPropertyOf($owner))
            ->create(['source' => LeadSource::WhatsApp, 'full_name' => null, 'email' => null, 'phone' => null, 'message' => null]);
        $theirs = PropertyLead::factory()->forProperty($this->listedPropertyOf($this->owner()))->create();
        $admin = User::factory()->create()->givePermissionTo(LeadPermission::EditPropertyLeads->value);

        $this->actingAs($owner)->put(route('supplier.leads.update', $mine), [
            'full_name' => 'Omar Caller', 'email' => '', 'phone_country' => 'EG', 'phone' => '10 01234567',
        ])->assertSessionHasNoErrors();
        $mine->refresh();
        $this->assertSame(['Omar Caller', null, '+201001234567'], [$mine->full_name, $mine->email, $mine->phone]);
        $this->assertSame(LeadSource::WhatsApp, $mine->source);

        $this->actingAs($owner)->put(route('supplier.leads.update', $theirs), ['full_name' => 'Hijack'])->assertForbidden();
        $this->actingAs($owner)->put(route('supplier.leads.update', $mine), ['phone_country' => 'EG', 'phone' => '123'])
            ->assertSessionHasErrors('phone');

        $this->actingAs($admin)->put(route('admin.property-leads.update', $theirs), [
            'full_name' => 'Sara', 'email' => 'sara@example.com', 'phone' => '',
        ])->assertSessionHasNoErrors();
        $this->assertSame(['Sara', 'sara@example.com', null], [$theirs->fresh()->full_name, $theirs->fresh()->email, $theirs->fresh()->phone]);
    }

    public function test_owners_add_leads_by_hand_only_to_their_own_properties(): void
    {
        $owner = $this->owner();
        $mine = $this->listedPropertyOf($owner);
        $theirs = $this->listedPropertyOf($this->owner());

        $this->actingAs($owner)->get(route('supplier.leads.index'))
            ->assertInertia(fn ($page) => $page->where('properties.data', fn ($options) => collect($options)->pluck('id')->all() === [$mine->id]));

        $this->actingAs($owner)->post(route('supplier.leads.store'), [
            'property_id' => $mine->id, 'full_name' => 'Walk-in Client', 'phone_country' => 'EG', 'phone' => '10 01234567',
            'message' => 'Called my own number.',
        ])->assertSessionHasNoErrors();
        $this->actingAs($owner)->post(route('supplier.leads.store'), ['property_id' => $theirs->id, 'full_name' => 'Hijack'])
            ->assertSessionHasErrors(['property_id' => __('Choose one of your properties.')]);
        $this->actingAs($owner)->post(route('supplier.leads.store'), ['property_id' => $mine->id])->assertSessionHasErrors('full_name');

        $lead = PropertyLead::sole();
        $this->assertSame(LeadSource::Manual, $lead->source);
        $this->assertSame(LeadStatus::New, $lead->status);
        $this->assertTrue($lead->owner->is($owner));
        $this->assertSame('+201001234567', $lead->phone);
        Notification::assertNothingSent();
    }

    public function test_admins_add_leads_by_hand_to_any_property_with_the_create_permission(): void
    {
        $owner = $this->owner();
        $property = $this->listedPropertyOf($owner);
        $viewer = User::factory()->create()->givePermissionTo(LeadPermission::ViewPropertyLeads->value);
        $admin = User::factory()->create()->givePermissionTo(LeadPermission::CreatePropertyLeads->value);

        $this->actingAs($viewer)->post(route('admin.property-leads.store'), ['property_id' => $property->id, 'full_name' => 'Sara'])
            ->assertForbidden();
        $this->actingAs($admin)->post(route('admin.property-leads.store'), ['property_id' => $property->id, 'full_name' => 'Sara'])
            ->assertSessionHasNoErrors();

        $this->assertTrue(PropertyLead::sole()->owner->is($owner));
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
            'full_name' => 'Sara Visitor',
            'email' => 'sara@example.com',
            'phone_country' => 'EG',
            'phone' => '0100 123 4567',
            'message' => 'Is it still available?',
        ];
    }
}
