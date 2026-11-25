<template>
    <TableCard noun="leads" :meta="leads.meta" :empty="!leads.data.length" :empty-text="emptyText">
        <template #actions>
            <slot name="filters"/>
        </template>

        <thead class="border-b border-slate-200">
        <tr>
            <TableHeadingCell>From</TableHeadingCell>
            <TableHeadingCell>Phone</TableHeadingCell>
            <TableHeadingCell>Property</TableHeadingCell>
            <TableHeadingCell v-if="showOwner">Owner</TableHeadingCell>
            <TableHeadingCell>Source</TableHeadingCell>
            <TableHeadingCell>Status</TableHeadingCell>
            <TableHeadingCell>Received</TableHeadingCell>
            <TableHeadingCell align="right"><span class="sr-only">Actions</span></TableHeadingCell>
        </tr>
        </thead>
        <tbody class="divide-y divide-slate-100">
        <tr v-for="lead in leads.data" :key="lead.id" class="transition-colors hover:bg-slate-50">
            <TableCell>
                <RowTitle title="Open lead" @click="emit('open', lead)">{{ lead.full_name }}</RowTitle>
                <p v-if="lead.email" class="text-xs text-slate-500">{{ lead.email }}</p>
            </TableCell>
            <TableCell class="whitespace-nowrap tabular-nums"><span dir="ltr">{{ lead.phone }}</span></TableCell>
            <TableCell>
                <p class="max-w-[14rem] truncate" :title="lead.property_name">{{ lead.property_name ?? 'Deleted property' }}</p>
            </TableCell>
            <TableCell v-if="showOwner" class="whitespace-nowrap">{{ lead.owner_name }}</TableCell>
            <TableCell class="whitespace-nowrap">
                <span class="inline-flex items-center gap-1.5">
                    <component :is="SOURCE_ICONS[lead.source]" class="h-4 w-4 text-slate-400" aria-hidden="true"/>
                    {{ lead.source_label }}
                </span>
            </TableCell>
            <TableCell>
                <InlineSelect :model-value="lead.status" :options="statuses" :label="`Status of the lead from ${lead.full_name}`"
                              :tone="toneOf(lead.status)" :loading="Boolean(saving[lead.id])"
                              @update:model-value="(status) => emit('change-status', lead, status)"/>
            </TableCell>
            <TableCell variant="muted" class="whitespace-nowrap">{{ formatDate(lead.created_at) }}</TableCell>
            <TableCell align="right">
                <RowActions :deletable="Boolean(destroyRoute)" @delete="confirmDelete(destroyRoute, lead.id, 'lead')">
                    <ContactActions :phone="lead.phone" :email="lead.email"/>
                </RowActions>
            </TableCell>
        </tr>
        </tbody>
    </TableCard>
</template>

<script setup>
import ContactActions from '@/Components/Admin/ContactActions.vue';
import InlineSelect from '@/Components/Admin/ui/InlineSelect.vue';
import RowActions from '@/Components/Admin/ui/RowActions.vue';
import RowTitle from '@/Components/Admin/ui/RowTitle.vue';
import TableCard from '@/Components/Admin/ui/TableCard.vue';
import TableCell from '@/Components/Admin/ui/TableCell.vue';
import TableHeadingCell from '@/Components/Admin/ui/TableHeadingCell.vue';
import {confirmDelete} from '@/Composables/useConfirmDelete.js';
import {formatDate} from '@/Composables/useFormatters.js';
import {SOURCE_ICONS} from './constants.js';

const props = defineProps({
    // PropertyLeadListResource collection with paginator meta
    leads: {type: Object, required: true},
    statuses: {type: Array, required: true},
    saving: {type: Object, required: true},
    showOwner: {type: Boolean, default: false},
    // Admins delete leads; owners can't
    destroyRoute: {type: String, default: null},
    emptyText: {type: String, default: 'No leads yet.'},
});
const emit = defineEmits(['open', 'change-status']);

const toneOf = (status) => props.statuses.find((option) => option.id === status)?.tone ?? 'neutral';
</script>
