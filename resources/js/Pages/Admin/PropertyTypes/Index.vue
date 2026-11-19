<template>
    <AuthenticatedLayout title="Property types" description="House, apartment, villa… Shown as categories on the home page.">
        <TableCard noun="property types" :count="propertyTypes.data.length" :empty="!filtered.length"
                   empty-text="No property types yet. Add your first type."
                   client-search v-model:search="search"
                   creatable create-label="Add type" @create="modal.openCreate()">
            <thead class="border-b border-slate-200">
            <tr>
                <TableHeadingCell>Name</TableHeadingCell>
                <TableHeadingCell align="right"><span class="sr-only">Actions</span></TableHeadingCell>
            </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
            <tr v-for="type in filtered" :key="type.id" class="transition-colors hover:bg-slate-50">
                <TableCell>
                    <div class="flex items-center gap-3">
                        <img :src="type.image" alt="" class="h-10 w-14 shrink-0 rounded-md border border-slate-200 object-cover"/>
                        <RowTitle @click="modal.openEdit(type)">{{ type.name }}</RowTitle>
                    </div>
                </TableCell>
                <TableCell align="right">
                    <RowActions editable @edit="modal.openEdit(type)"
                                @delete="confirmDelete('propertyTypes.destroy', type.id, 'property type')"/>
                </TableCell>
            </tr>
            </tbody>
        </TableCard>

        <PropertyTypeForm :open="modal.isOpen.value" :property-type="modal.record.value" @close="modal.close()"/>
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
import PropertyTypeForm from './PropertyTypeForm.vue';

const props = defineProps({
    propertyTypes: {type: Object, required: true},
});

const {search, filtered} = useClientSearch(() => props.propertyTypes.data, ['name']);
const modal = useResourceModal();
</script>
