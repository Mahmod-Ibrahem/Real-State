<template>
    <AuthenticatedLayout title="Areas" description="Neighbourhoods inside a city; every property is placed in one.">
        <TableCard noun="areas" :meta="areas.meta" :empty="!areas.data.length"
                   empty-text="No areas yet. Add your first area."
                   creatable create-label="Add area" @create="modal.openCreate()">
            <thead class="border-b border-slate-200">
            <tr>
                <TableHeadingCell>ID</TableHeadingCell>
                <TableHeadingCell>Name</TableHeadingCell>
                <TableHeadingCell>City</TableHeadingCell>
                <TableHeadingCell align="right"><span class="sr-only">Actions</span></TableHeadingCell>
            </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
            <tr v-for="area in areas.data" :key="area.id" class="transition-colors hover:bg-slate-50">
                <TableCell variant="muted">#{{ area.id }}</TableCell>
                <TableCell variant="primary">{{ area.name }}</TableCell>
                <TableCell>{{ area.city }}</TableCell>
                <TableCell align="right">
                    <RowActions editable @edit="modal.openEdit(area)"
                                @delete="confirmDelete('areas.destroy', area.id, 'area')"/>
                </TableCell>
            </tr>
            </tbody>
        </TableCard>

        <AreaForm :open="modal.isOpen.value" :area="modal.record.value" :cities="cities.data" @close="modal.close()"/>
    </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from '@/Layouts/Admin/AuthenticatedLayout.vue';
import TableCard from '@/Components/Admin/ui/TableCard.vue';
import TableHeadingCell from '@/Components/Admin/ui/TableHeadingCell.vue';
import TableCell from '@/Components/Admin/ui/TableCell.vue';
import RowActions from '@/Components/Admin/ui/RowActions.vue';
import {confirmDelete} from '@/Composables/useConfirmDelete.js';
import {useResourceModal} from '@/Composables/useResourceModal.js';
import AreaForm from './AreaForm.vue';

defineProps({
    areas: {type: Object, required: true},
    // Options for the form's city select
    cities: {type: Object, required: true},
});

const modal = useResourceModal();
</script>
