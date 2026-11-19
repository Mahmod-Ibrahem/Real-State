<template>
    <AuthenticatedLayout title="FAQs" description="Questions and answers shown on the FAQ page.">
        <TableCard noun="FAQs" :meta="faqs.meta" :searchable="false" :empty="!faqs.data.length"
                   empty-text="No FAQs yet. Add your first question."
                   :create-href="route('faq.create')" create-label="Add FAQ">
            <thead class="border-b border-slate-200">
            <tr>
                <TableHeadingCell>Question</TableHeadingCell>
                <TableHeadingCell>Answer</TableHeadingCell>
                <TableHeadingCell>Category</TableHeadingCell>
                <TableHeadingCell align="right"><span class="sr-only">Actions</span></TableHeadingCell>
            </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
            <tr v-for="faq in faqs.data" :key="faq.id" class="transition-colors hover:bg-slate-50">
                <TableCell variant="primary">
                    <p class="max-w-xs truncate" :title="faq.question">{{ faq.question }}</p>
                </TableCell>
                <TableCell>
                    <p class="max-w-md truncate" :title="faq.answer">{{ faq.answer }}</p>
                </TableCell>
                <TableCell>
                    <StatusBadge>{{ categoryLabel(faq.type) }}</StatusBadge>
                </TableCell>
                <TableCell align="right">
                    <RowActions :edit-href="route('faq.edit', faq.id)" @delete="confirmDelete('faq.destroy', faq.id, 'FAQ')"/>
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
import StatusBadge from '@/Components/Admin/ui/StatusBadge.vue';
import {confirmDelete} from '@/Composables/useConfirmDelete.js';
import {FAQ_CATEGORIES} from './categories.js';

defineProps({
    faqs: {type: Object, required: true},
});

const categoryLabel = (value) => FAQ_CATEGORIES.find((category) => category.value === value)?.name ?? value ?? '—';
</script>
