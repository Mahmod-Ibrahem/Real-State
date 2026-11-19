<template>
    <AuthenticatedLayout title="FAQs" description="Questions and answers shown on the FAQ page.">
        <TableCard noun="FAQs" :meta="faqs.meta" :searchable="false" :empty="!faqs.data.length"
                   empty-text="No FAQs yet. Add your first question."
                   creatable create-label="Add FAQ" @create="modal.openCreate()">
            <thead class="border-b border-slate-200">
            <tr>
                <TableHeadingCell>Question</TableHeadingCell>
                <TableHeadingCell>Answer</TableHeadingCell>
                <TableHeadingCell align="right"><span class="sr-only">Actions</span></TableHeadingCell>
            </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
            <tr v-for="faq in faqs.data" :key="faq.id" class="transition-colors hover:bg-slate-50">
                <TableCell>
                    <div class="max-w-xs">
                        <RowTitle :title="faq.question" @click="modal.openEdit(faq)">{{ faq.question }}</RowTitle>
                    </div>
                </TableCell>
                <TableCell>
                    <p class="max-w-md truncate" :title="faq.answer">{{ faq.answer }}</p>
                </TableCell>
                <TableCell align="right">
                    <RowActions editable @edit="modal.openEdit(faq)" @delete="confirmDelete('admin.faqs.destroy', faq.id, 'FAQ')"/>
                </TableCell>
            </tr>
            </tbody>
        </TableCard>

        <FaqForm :open="modal.isOpen.value" :faq="modal.record.value" @close="modal.close()"/>
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
import FaqForm from './FaqForm.vue';

defineProps({
    faqs: {type: Object, required: true},
});

const modal = useResourceModal();
</script>
