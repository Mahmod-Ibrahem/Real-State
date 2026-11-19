<template>
    <AuthenticatedLayout title="Feature types" description="Groups for features on the property page, e.g. Interior, Outdoor, Security.">
        <TableCard noun="feature types" :count="featureTypes.length" :empty="!filtered.length"
                   empty-text="No feature types yet. Add your first type."
                   client-search v-model:search="search"
                   creatable create-label="Add feature type" @create="modal.openCreate()">
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
                    <RowActions editable @edit="modal.openEdit(featureType)"
                                @delete="confirmDelete('feature-types.destroy', featureType.id, 'feature type')"/>
                </TableCell>
            </tr>
            </tbody>
        </TableCard>

        <FeatureTypeForm :open="modal.isOpen.value" :feature-type="modal.record.value" @close="modal.close()"/>
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
import {useResourceModal} from '@/Composables/useResourceModal.js';
import FeatureTypeForm from './FeatureTypeForm.vue';

const props = defineProps({
    // Plain array of {id, name}
    featureTypes: {type: Array, required: true},
});

const {search, filtered} = useClientSearch(() => props.featureTypes, ['name']);
const modal = useResourceModal();
</script>
