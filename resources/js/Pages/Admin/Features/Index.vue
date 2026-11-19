<template>
    <AuthenticatedLayout title="Features" description="Amenities a property can have, grouped by feature type.">
        <TableCard noun="features" :count="features.data.length" :empty="!filtered.length"
                   empty-text="No features yet. Add your first feature."
                   client-search v-model:search="search"
                   :create-href="route('propertyFeatures.create')" create-label="Add feature">
            <thead class="border-b border-slate-200">
            <tr>
                <TableHeadingCell>ID</TableHeadingCell>
                <TableHeadingCell>Name</TableHeadingCell>
                <TableHeadingCell>Type</TableHeadingCell>
                <TableHeadingCell align="right"><span class="sr-only">Actions</span></TableHeadingCell>
            </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
            <tr v-for="feature in filtered" :key="feature.id" class="transition-colors hover:bg-slate-50">
                <TableCell variant="muted">#{{ feature.id }}</TableCell>
                <TableCell variant="primary">{{ feature.name }}</TableCell>
                <TableCell>
                    <StatusBadge>{{ feature.featureTypeName }}</StatusBadge>
                </TableCell>
                <TableCell align="right">
                    <RowActions :edit-href="route('propertyFeatures.edit', feature.id)"
                                @delete="confirmDelete('propertyFeatures.destroy', feature.id, 'feature')"/>
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
import StatusBadge from '@/Components/Admin/ui/StatusBadge.vue';
import {confirmDelete} from '@/Composables/useConfirmDelete.js';
import {useClientSearch} from '@/Composables/useClientSearch.js';

const props = defineProps({
    features: {type: Object, required: true},
});

const {search, filtered} = useClientSearch(() => props.features.data, ['name', 'featureTypeName']);
</script>
