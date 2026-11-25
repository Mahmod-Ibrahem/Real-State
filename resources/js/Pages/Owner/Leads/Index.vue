<template>
    <AuthenticatedLayout :nav="OWNER_NAV" title="Leads" description="People who contacted you about your properties.">
        <template #actions>
            <ViewSwitcher :views="LEAD_VIEWS" :model-value="filters.view"/>
        </template>

        <LeadsWorkspace :board="board?.data ?? board" :leads="leads" :filters="filters" :statuses="statuses" :sources="sources"
                        :properties="properties.data" route-base="supplier.leads" :can-create="can('create own property leads')"
                        status-route="supplier.leads.status"
                        empty-text="No leads yet. They appear here when a visitor contacts you from one of your listed properties."/>
    </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from '@/Layouts/Admin/AuthenticatedLayout.vue';
import ViewSwitcher from '@/Components/Admin/ui/ViewSwitcher.vue';
import {LEAD_VIEWS} from '@/Components/Dashboard/Leads/constants.js';
import LeadsWorkspace from '@/Components/Dashboard/Leads/LeadsWorkspace.vue';
import {OWNER_NAV} from '@/Components/Owner/navigation.js';
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
