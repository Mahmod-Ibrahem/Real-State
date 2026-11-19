<template>
    <AuthenticatedLayout title="Property types" description="House, apartment, villa… Shown as categories on the home page.">
        <TableCard noun="property types" :count="propertyTypes.data.length" :empty="!filtered.length"
                   empty-text="No property types yet. Add your first type."
                   client-search v-model:search="search"
                   :create-href="route('propertyTypes.create')" create-label="Add type">
            <thead class="border-b border-slate-200">
            <tr>
                <TableHeadingCell>ID</TableHeadingCell>
                <TableHeadingCell>Image</TableHeadingCell>
                <TableHeadingCell>Name</TableHeadingCell>
                <TableHeadingCell align="right"><span class="sr-only">Actions</span></TableHeadingCell>
            </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
            <tr v-for="type in filtered" :key="type.id" class="transition-colors hover:bg-slate-50">
                <TableCell variant="muted">#{{ type.id }}</TableCell>
                <TableCell>
                    <img :src="type.image" alt="" class="h-10 w-14 rounded-md border border-slate-200 object-cover"/>
                </TableCell>
                <TableCell variant="primary">{{ type.name }}</TableCell>
                <TableCell align="right">
                    <RowActions :edit-href="route('propertyTypes.edit', type.id)"
                                @delete="confirmDelete('propertyTypes.destroy', type.id, 'property type')"/>
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
import {useClientSearch} from '@/Composables/useClientSearch.js';

const props = defineProps({
    propertyTypes: {type: Object, required: true},
});

const {search, filtered} = useClientSearch(() => props.propertyTypes.data, ['name']);
</script>
