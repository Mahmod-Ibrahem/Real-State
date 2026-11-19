<template>
    <AuthenticatedLayout title="Property leads" description="Enquiries sent from a property page.">
        <TableCard noun="leads" :meta="propertyLeads" :searchable="false" :empty="!propertyLeads.data.length"
                   empty-text="No enquiries yet. They appear here when someone contacts you about a property.">
            <thead class="border-b border-slate-200">
            <tr>
                <TableHeadingCell>From</TableHeadingCell>
                <TableHeadingCell>Phone</TableHeadingCell>
                <TableHeadingCell>Property</TableHeadingCell>
                <TableHeadingCell>Message</TableHeadingCell>
                <TableHeadingCell>Received</TableHeadingCell>
                <TableHeadingCell align="right"><span class="sr-only">Actions</span></TableHeadingCell>
            </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
            <tr v-for="lead in propertyLeads.data" :key="lead.id" class="transition-colors hover:bg-slate-50">
                <TableCell>
                    <p class="font-medium text-slate-900">{{ lead.full_name }}</p>
                    <p class="text-xs text-slate-500">{{ lead.email }}</p>
                </TableCell>
                <TableCell class="whitespace-nowrap tabular-nums">{{ lead.phone }}</TableCell>
                <TableCell>
                    <Link :href="route('properties.edit', lead.property_id)" class="font-medium text-indigo-600 hover:text-indigo-700">
                        #{{ lead.property_id }}
                    </Link>
                </TableCell>
                <TableCell>
                    <p class="max-w-xs truncate" :title="lead.message">{{ lead.message }}</p>
                </TableCell>
                <TableCell variant="muted">{{ formatDate(lead.created_at) }}</TableCell>
                <TableCell align="right">
                    <RowActions :edit-href="route('propertyLeads.edit', lead.id)"
                                @delete="confirmDelete('propertyLeads.destroy', lead.id, 'lead')">
                        <ContactActions :phone="lead.phone" :email="lead.email"/>
                    </RowActions>
                </TableCell>
            </tr>
            </tbody>
        </TableCard>
    </AuthenticatedLayout>
</template>

<script setup>
import {Link} from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/Admin/AuthenticatedLayout.vue';
import ContactActions from '@/Components/Admin/ContactActions.vue';
import TableCard from '@/Components/Admin/ui/TableCard.vue';
import TableHeadingCell from '@/Components/Admin/ui/TableHeadingCell.vue';
import TableCell from '@/Components/Admin/ui/TableCell.vue';
import RowActions from '@/Components/Admin/ui/RowActions.vue';
import {confirmDelete} from '@/Composables/useConfirmDelete.js';
import {formatDate} from '@/Composables/useFormatters.js';

defineProps({
    // Raw Laravel paginator
    propertyLeads: {type: Object, required: true},
});
</script>
