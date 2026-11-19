<template>
    <AuthenticatedLayout title="Countries" description="Top level of the location tree: Country → City → Area.">
        <TableCard noun="countries" :meta="countries.meta" :empty="!countries.data.length"
                   empty-text="No countries yet. Add your first country."
                   :create-href="route('countries.create')" create-label="Add country">
            <thead class="border-b border-slate-200">
            <tr>
                <TableHeadingCell>ID</TableHeadingCell>
                <TableHeadingCell>Name</TableHeadingCell>
                <TableHeadingCell align="right"><span class="sr-only">Actions</span></TableHeadingCell>
            </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
            <tr v-for="country in countries.data" :key="country.id" class="transition-colors hover:bg-slate-50">
                <TableCell variant="muted">#{{ country.id }}</TableCell>
                <TableCell variant="primary">{{ country.name }}</TableCell>
                <TableCell align="right">
                    <RowActions :edit-href="route('countries.edit', country.id)"
                                @delete="confirmDelete('countries.destroy', country.id, 'country')"/>
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

defineProps({
    countries: {type: Object, required: true},
});
</script>
