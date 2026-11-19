<template>
    <AuthenticatedLayout title="Contact messages" description="Messages sent from the Contact Us page, newest first.">
        <TableCard noun="messages" :meta="contactLeads" :searchable="false" :empty="!contactLeads.data.length"
                   empty-text="No messages yet. They appear here when someone uses the contact form.">
            <thead class="border-b border-slate-200">
            <tr>
                <TableHeadingCell>From</TableHeadingCell>
                <TableHeadingCell>Phone</TableHeadingCell>
                <TableHeadingCell>Subject</TableHeadingCell>
                <TableHeadingCell>Message</TableHeadingCell>
                <TableHeadingCell>Received</TableHeadingCell>
                <TableHeadingCell align="right"><span class="sr-only">Actions</span></TableHeadingCell>
            </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
            <tr v-for="lead in contactLeads.data" :key="lead.id" class="transition-colors hover:bg-slate-50">
                <TableCell>
                    <p class="font-medium text-slate-900">{{ lead.full_name }}</p>
                    <p class="text-xs text-slate-500">{{ lead.email }}</p>
                </TableCell>
                <TableCell class="whitespace-nowrap tabular-nums">{{ lead.phone }}</TableCell>
                <TableCell variant="primary">{{ lead.subject }}</TableCell>
                <TableCell>
                    <p class="max-w-xs truncate" :title="lead.message">{{ lead.message }}</p>
                </TableCell>
                <TableCell variant="muted">{{ formatDate(lead.created_at) }}</TableCell>
                <TableCell align="right">
                    <RowActions :edit-href="route('contactLeads.edit', lead.id)"
                                @delete="confirmDelete('contactLeads.destroy', lead.id, 'message')">
                        <ContactActions :phone="lead.phone" :email="lead.email"/>
                    </RowActions>
                </TableCell>
            </tr>
            </tbody>
        </TableCard>
    </AuthenticatedLayout>
</template>

<script setup>
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
    contactLeads: {type: Object, required: true},
});
</script>
