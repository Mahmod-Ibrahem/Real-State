<template>
    <AuthenticatedLayout title="Areas" description="Neighbourhoods inside a city; every property is placed in one.">
        <TableCard noun="areas" :meta="areas.meta" :empty="!areas.data.length"
                   empty-text="No areas yet. Add your first area."
                   :create-href="route('areas.create')" create-label="Add area">
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
                    <RowActions :edit-href="route('areas.edit', area.id)"
                                @delete="confirmDelete('areas.destroy', area.id, 'area')"/>
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
    areas: {type: Object, required: true},
});
</script>
