<template>
    <AuthenticatedLayout title="Site content" description="Fixed blocks used across the site, such as the home and About Us videos.">
        <TableCard noun="content blocks" :count="contents.data.length" :searchable="false" :empty="!contents.data.length"
                   empty-text="No content blocks. They are created by the database seeder.">
            <thead class="border-b border-slate-200">
            <tr>
                <TableHeadingCell>Name</TableHeadingCell>
                <TableHeadingCell>Content</TableHeadingCell>
                <TableHeadingCell align="right"><span class="sr-only">Actions</span></TableHeadingCell>
            </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
            <tr v-for="content in contents.data" :key="content.id" class="transition-colors hover:bg-slate-50">
                <TableCell variant="primary" class="whitespace-nowrap">{{ content.name }}</TableCell>
                <TableCell>
                    <p class="max-w-xl truncate font-mono text-xs" :title="content.content">{{ content.content }}</p>
                </TableCell>
                <TableCell align="right">
                    <!-- Blocks are fixed slots: edit only -->
                    <RowActions :edit-href="route('content.edit', content.id)" :deletable="false"/>
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

defineProps({
    contents: {type: Object, required: true},
});
</script>
