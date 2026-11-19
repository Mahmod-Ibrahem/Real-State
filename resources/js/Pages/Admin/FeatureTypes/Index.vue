<template>
    <AuthenticatedLayout title="Feature types" description="Groups for features on the property page, e.g. Interior, Outdoor, Security.">
        <TableCard noun="feature types" :count="featureTypes.length" :empty="!filtered.length"
                   empty-text="No feature types yet. Add your first type."
                   client-search v-model:search="search"
                   :create-href="route('feature-types.create')" create-label="Add feature type">
            <thead class="border-b border-slate-200">
            <tr>
                <TableHeadingCell>ID</TableHeadingCell>
                <TableHeadingCell>Name</TableHeadingCell>
                <TableHeadingCell align="right"><span class="sr-only">Actions</span></TableHeadingCell>
            </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
            <tr v-for="featureType in filtered" :key="featureType.id" class="transition-colors hover:bg-slate-50">
                <TableCell variant="muted">#{{ featureType.id }}</TableCell>
                <TableCell variant="primary">{{ featureType.name }}</TableCell>
                <TableCell align="right">
                    <RowActions :edit-href="route('feature-types.edit', featureType.id)"
                                @delete="confirmDelete('feature-types.destroy', featureType.id, 'feature type')"/>
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
    // Plain array of {id, name}
    featureTypes: {type: Array, required: true},
});

const {search, filtered} = useClientSearch(() => props.featureTypes, ['name']);
</script>
