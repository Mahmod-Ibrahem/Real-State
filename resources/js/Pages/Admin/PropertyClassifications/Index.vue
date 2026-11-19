<template>
    <AuthenticatedLayout title="Classifications" description="Price tiers such as Affordable, Moderate and Luxury.">
        <TableCard noun="classifications" :count="propertyClassifications.data.length" :empty="!filtered.length"
                   empty-text="No classifications yet. Add your first classification."
                   client-search v-model:search="search"
                   :create-href="route('propertyClassifications.create')" create-label="Add classification">
            <thead class="border-b border-slate-200">
            <tr>
                <TableHeadingCell>ID</TableHeadingCell>
                <TableHeadingCell>Name</TableHeadingCell>
                <TableHeadingCell align="right"><span class="sr-only">Actions</span></TableHeadingCell>
            </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
            <tr v-for="classification in filtered" :key="classification.id" class="transition-colors hover:bg-slate-50">
                <TableCell variant="muted">#{{ classification.id }}</TableCell>
                <TableCell variant="primary">{{ classification.name }}</TableCell>
                <TableCell align="right">
                    <RowActions :edit-href="route('propertyClassifications.edit', classification.id)"
                                @delete="confirmDelete('propertyClassifications.destroy', classification.id, 'classification')"/>
                </TableCell>
            </tr>
            </tbody>
        </TableCard>
    </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from '@/Layouts/Admin/AuthenticatedLayout.vue';
import TableCard from '@/Components/Admin/ui/TableCard.vue';
import TableHeadingCell from '@/Components/Admin/ui/TableHeadingCell.vue';
import TableCell from '@/Components/Admin/ui/TableCell.vue';
import RowActions from '@/Components/Admin/ui/RowActions.vue';
import {confirmDelete} from '@/Composables/useConfirmDelete.js';
import {useClientSearch} from '@/Composables/useClientSearch.js';

const props = defineProps({
    propertyClassifications: {type: Object, required: true},
});

const {search, filtered} = useClientSearch(() => props.propertyClassifications.data, ['name']);
</script>
