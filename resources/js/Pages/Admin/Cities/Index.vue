<template>
    <AuthenticatedLayout title="Cities" description="Each city belongs to a country.">
        <TableCard noun="cities" :meta="cities.meta" :empty="!cities.data.length"
                   empty-text="No cities yet. Add your first city."
                   creatable create-label="Add city" @create="modal.openCreate()">
            <thead class="border-b border-slate-200">
            <tr>
                <TableHeadingCell>Name</TableHeadingCell>
                <TableHeadingCell>Country</TableHeadingCell>
                <TableHeadingCell align="right"><span class="sr-only">Actions</span></TableHeadingCell>
            </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
            <tr v-for="city in cities.data" :key="city.id" class="transition-colors hover:bg-slate-50">
                <TableCell>
                    <RowTitle @click="modal.openEdit(city)">{{ city.name }}</RowTitle>
                </TableCell>
                <TableCell>{{ city.country }}</TableCell>
                <TableCell align="right">
                    <RowActions editable @edit="modal.openEdit(city)"
                                @delete="confirmDelete('admin.cities.destroy', city.id, 'city')"/>
                </TableCell>
            </tr>
            </tbody>
        </TableCard>

        <CityForm :open="modal.isOpen.value" :city="modal.record.value" :countries="countries.data" @close="modal.close()"/>
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
import {useResourceModal} from '@/Composables/useResourceModal.js';
import CityForm from './CityForm.vue';

defineProps({
    cities: {type: Object, required: true},
    // Options for the form's country select
    countries: {type: Object, required: true},
});

const modal = useResourceModal();
</script>
