<template>
    <AuthenticatedLayout title="Features" description="Amenities a property can have, grouped by feature type.">
        <TableCard noun="features" :count="features.data.length" :empty="!filtered.length"
                   empty-text="No features yet. Add your first feature."
                   client-search v-model:search="search"
                   creatable create-label="Add feature" @create="modal.openCreate()">
            <thead class="border-b border-slate-200">
            <tr>
                <TableHeadingCell>Name</TableHeadingCell>
                <TableHeadingCell>Type</TableHeadingCell>
                <TableHeadingCell align="right"><span class="sr-only">Actions</span></TableHeadingCell>
            </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
            <tr v-for="feature in filtered" :key="feature.id" class="transition-colors hover:bg-slate-50">
                <TableCell>
                    <RowTitle @click="modal.openEdit(feature)">{{ feature.name }}</RowTitle>
                </TableCell>
                <TableCell>
                    <StatusBadge>{{ feature.featureTypeName }}</StatusBadge>
                </TableCell>
                <TableCell align="right">
                    <RowActions editable @edit="modal.openEdit(feature)"
                                @delete="confirmDelete('propertyFeatures.destroy', feature.id, 'feature')"/>
                </TableCell>
            </tr>
            </tbody>
        </TableCard>

        <FeatureForm :open="modal.isOpen.value" :feature="modal.record.value" :feature-types="featureTypes"
                     @close="modal.close()"/>
    </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from '@/Layouts/Admin/AuthenticatedLayout.vue';
import TableCard from '@/Components/Admin/ui/TableCard.vue';
import TableHeadingCell from '@/Components/Admin/ui/TableHeadingCell.vue';
import TableCell from '@/Components/Admin/ui/TableCell.vue';
import RowActions from '@/Components/Admin/ui/RowActions.vue';
import RowTitle from '@/Components/Admin/ui/RowTitle.vue';
import StatusBadge from '@/Components/Admin/ui/StatusBadge.vue';
import {confirmDelete} from '@/Composables/useConfirmDelete.js';
import {useClientSearch} from '@/Composables/useClientSearch.js';
import {useResourceModal} from '@/Composables/useResourceModal.js';
import FeatureForm from './FeatureForm.vue';

const props = defineProps({
    features: {type: Object, required: true},
    // Options for the form's type select: [{id, name}]
    featureTypes: {type: Array, required: true},
});

const {search, filtered} = useClientSearch(() => props.features.data, ['name', 'featureTypeName']);
const modal = useResourceModal();
</script>
