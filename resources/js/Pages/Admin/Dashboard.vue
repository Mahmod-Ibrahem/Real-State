<template>
    <AuthenticatedLayout title="Dashboard" description="Listings, enquiries and the catalog at a glance.">
        <div class="grid gap-4">
            <Panel title="Listings" hint="Everything on the site">
                <MetricGroup :metrics="listingMetrics"/>
            </Panel>

            <div class="grid gap-4 xl:grid-cols-3">
                <Panel title="Enquiries" :hint="monthLabel">
                    <MetricGroup :metrics="leadMetrics"/>
                </Panel>
                <Panel title="Catalog" hint="What listings can be filed under" class="xl:col-span-2">
                    <MetricGroup :metrics="catalogMetrics"/>
                </Panel>
            </div>

            <div class="grid gap-4 lg:grid-cols-2">
                <Panel title="Latest property leads">
                    <template #action>
                        <Link :href="route('admin.property-leads.index')" class="text-sm font-medium text-indigo-600 hover:text-indigo-700">View all</Link>
                    </template>
                    <ul v-if="recent_property_leads.data.length" class="-mx-6 divide-y divide-slate-100">
                        <li v-for="lead in recent_property_leads.data" :key="lead.id">
                            <Link :href="route('admin.property-leads.edit', lead.id)"
                                  class="flex items-center justify-between gap-3 px-6 py-3 transition-colors hover:bg-slate-50">
                                <span class="min-w-0">
                                    <span class="block truncate text-sm font-medium text-slate-900">{{ lead.full_name }}</span>
                                    <span class="block truncate text-xs text-slate-500">{{ lead.property ?? 'Deleted property' }}</span>
                                </span>
                                <span class="shrink-0 text-xs text-slate-400">{{ formatDate(lead.created_at) }}</span>
                            </Link>
                        </li>
                    </ul>
                    <p v-else class="py-6 text-center text-sm text-slate-500">No property enquiries yet.</p>
                </Panel>

                <Panel title="Latest contact messages">
                    <template #action>
                        <Link :href="route('admin.contact-leads.index')" class="text-sm font-medium text-indigo-600 hover:text-indigo-700">View all</Link>
                    </template>
                    <ul v-if="recent_contact_leads.data.length" class="-mx-6 divide-y divide-slate-100">
                        <li v-for="lead in recent_contact_leads.data" :key="lead.id">
                            <Link :href="route('admin.contact-leads.edit', lead.id)"
                                  class="flex items-center justify-between gap-3 px-6 py-3 transition-colors hover:bg-slate-50">
                                <span class="min-w-0">
                                    <span class="block truncate text-sm font-medium text-slate-900">{{ lead.full_name }}</span>
                                    <span class="block truncate text-xs text-slate-500">{{ lead.subject }}</span>
                                </span>
                                <span class="shrink-0 text-xs text-slate-400">{{ formatDate(lead.created_at) }}</span>
                            </Link>
                        </li>
                    </ul>
                    <p v-else class="py-6 text-center text-sm text-slate-500">No contact messages yet.</p>
                </Panel>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<script setup>
import {computed} from 'vue';
import {Link} from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/Admin/AuthenticatedLayout.vue';
import Panel from '@/Components/Admin/ui/Panel.vue';
import MetricGroup from '@/Components/Admin/MetricGroup.vue';
import {formatDate} from '@/Composables/useFormatters.js';

const props = defineProps({
    listings: {type: Object, required: true},
    leads: {type: Object, required: true},
    catalog: {type: Object, required: true},
    recent_property_leads: {type: Object, required: true},
    recent_contact_leads: {type: Object, required: true},
});

const monthLabel = new Date().toLocaleDateString('en-GB', {month: 'long', year: 'numeric'});

const listingMetrics = computed(() => [
    {label: 'Total', value: props.listings.total, href: route('admin.properties.index')},
    {label: 'Published', value: props.listings.published, hint: `${props.listings.total - props.listings.published} not live`},
    {label: 'Pending review', value: props.listings.pending_review, href: route('admin.properties.index', {review: 'pending'})},
    {label: 'Available for sale', value: props.listings.for_sale},
    {label: 'Available for rent', value: props.listings.for_rent},
    {label: 'Sold', value: props.listings.sold},
    {label: 'Rented', value: props.listings.rented},
]);

const leadMetrics = computed(() => [
    {label: 'Property leads', value: props.leads.property_leads, href: route('admin.property-leads.index')},
    {label: 'Contact messages', value: props.leads.contact_leads, href: route('admin.contact-leads.index')},
]);

const catalogMetrics = computed(() => [
    {label: 'Property types', value: props.catalog.property_types, href: route('admin.property-types.index')},
    {label: 'Countries', value: props.catalog.countries, href: route('admin.countries.index')},
    {label: 'Cities', value: props.catalog.cities, href: route('admin.cities.index')},
    {label: 'Areas', value: props.catalog.areas, href: route('admin.areas.index')},
]);
</script>
