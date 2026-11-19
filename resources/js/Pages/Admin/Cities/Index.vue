<template>
    <AuthenticatedLayout title="Cities" description="Each city belongs to a country.">
        <TableCard noun="cities" :meta="cities.meta" :empty="!cities.data.length"
                   empty-text="No cities yet. Add your first city."
                   :create-href="route('cities.create')" create-label="Add city">
            <thead class="border-b border-slate-200">
            <tr>
                <TableHeadingCell>ID</TableHeadingCell>
                <TableHeadingCell>Name</TableHeadingCell>
                <TableHeadingCell>Country</TableHeadingCell>
                <TableHeadingCell align="right"><span class="sr-only">Actions</span></TableHeadingCell>
            </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
            <tr v-for="city in cities.data" :key="city.id" class="transition-colors hover:bg-slate-50">
                <TableCell variant="muted">#{{ city.id }}</TableCell>
                <TableCell variant="primary">{{ city.name }}</TableCell>
                <TableCell>{{ city.country }}</TableCell>
                <TableCell align="right">
                    <RowActions :edit-href="route('cities.edit', city.id)"
                                @delete="confirmDelete('cities.destroy', city.id, 'city')"/>
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
    cities: {type: Object, required: true},
});
</script>
