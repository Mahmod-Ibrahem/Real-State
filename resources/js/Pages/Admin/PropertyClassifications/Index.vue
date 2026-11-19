<template>
    <AuthenticatedLayout title="Classifications" description="Price tiers such as Affordable, Moderate and Luxury.">
        <TableCard noun="classifications" :count="propertyClassifications.data.length" :empty="!filtered.length"
                   empty-text="No classifications yet. Add your first classification."
                   client-search v-model:search="search"
                   creatable create-label="Add classification" @create="modal.openCreate()">
            <thead class="border-b border-slate-200">
            <tr>
                <TableHeadingCell>Name</TableHeadingCell>
                <TableHeadingCell align="right"><span class="sr-only">Actions</span></TableHeadingCell>
            </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
            <tr v-for="classification in filtered" :key="classification.id" class="transition-colors hover:bg-slate-50">
                <TableCell>
                    <RowTitle @click="modal.openEdit(classification)">{{ classification.name }}</RowTitle>
                </TableCell>
                <TableCell align="right">
                    <RowActions editable @edit="modal.openEdit(classification)"
                                @delete="confirmDelete('admin.property-classifications.destroy', classification.id, 'classification')"/>
                </TableCell>
            </tr>
            </tbody>
        </TableCard>

        <ClassificationForm :open="modal.isOpen.value" :classification="modal.record.value" @close="modal.close()"/>
    </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from '@/Layouts/Admin/AuthenticatedLayout.vue';
import TableCard from '@/Components/Admin/ui/TableCard.vue';
import TableHeadingCell from '@/Components/Admin/ui/TableHeadingCell.vue';
import TableCell from '@/Components/Admin/ui/TableCell.vue';
import RowActions from '@/Components/Admin/ui/RowActions.vue';
import RowTitle from '@/Components/Admin/ui/RowTitle.vue';
import {confirmDelete} from '@/Composables/useConfirmDelete.js';
import {useClientSearch} from '@/Composables/useClientSearch.js';
import {useResourceModal} from '@/Composables/useResourceModal.js';
import ClassificationForm from './ClassificationForm.vue';

const props = defineProps({
    propertyClassifications: {type: Object, required: true},
});

const {search, filtered} = useClientSearch(() => props.propertyClassifications.data, ['name']);
const modal = useResourceModal();
</script>
