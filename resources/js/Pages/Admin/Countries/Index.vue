<template>
    <AuthenticatedLayout title="Countries" description="Top level of the location tree: Country → City → Area.">
        <TableCard noun="countries" :meta="countries.meta" :empty="!countries.data.length"
                   empty-text="No countries yet. Add your first country."
                   creatable create-label="Add country" @create="modal.openCreate()">
            <thead class="border-b border-slate-200">
            <tr>
                <TableHeadingCell>Name</TableHeadingCell>
                <TableHeadingCell align="right"><span class="sr-only">Actions</span></TableHeadingCell>
            </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
            <tr v-for="country in countries.data" :key="country.id" class="transition-colors hover:bg-slate-50">
                <TableCell>
                    <RowTitle @click="modal.openEdit(country)">{{ country.name }}</RowTitle>
                </TableCell>
                <TableCell align="right">
                    <RowActions editable @edit="modal.openEdit(country)"
                                @delete="confirmDelete('countries.destroy', country.id, 'country')"/>
                </TableCell>
            </tr>
            </tbody>
        </TableCard>

        <CountryForm :open="modal.isOpen.value" :country="modal.record.value" @close="modal.close()"/>
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
import CountryForm from './CountryForm.vue';

defineProps({
    countries: {type: Object, required: true},
});

const modal = useResourceModal();
</script>
