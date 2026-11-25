<template>
    <AuthenticatedLayout title="Property leads" description="Every enquiry, call and WhatsApp request sent from a property page.">
        <template #actions>
            <ViewSwitcher :views="LEAD_VIEWS" :model-value="filters.view"/>
        </template>

        <LeadsWorkspace :board="board?.data ?? board" :leads="leads" :filters="filters" :statuses="statuses" :sources="sources"
                        :properties="properties.data" route-base="admin.property-leads" :can-create="can('create property leads')"
                        status-route="admin.property-leads.status" destroy-route="admin.property-leads.destroy" show-owner
                        empty-text="No leads yet. They appear here when a visitor contacts an owner from a property page."/>
    </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from '@/Layouts/Admin/AuthenticatedLayout.vue';
import ViewSwitcher from '@/Components/Admin/ui/ViewSwitcher.vue';
import {LEAD_VIEWS} from '@/Components/Dashboard/Leads/constants.js';
import LeadsWorkspace from '@/Components/Dashboard/Leads/LeadsWorkspace.vue';
import {can} from '@/Composables/usePermissions.js';

defineProps({
    board: {type: [Object, Array], default: null},
    leads: {type: Object, default: null},
    filters: {type: Object, required: true},
    statuses: {type: Array, required: true},
    sources: {type: Array, required: true},
    properties: {type: Object, required: true},
});
</script>
