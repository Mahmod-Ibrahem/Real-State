<template>
    <AuthenticatedLayout title="Brokers" description="The team shown on the About Us page.">
        <TableCard noun="brokers" :count="brokers.data.length" :empty="!filtered.length"
                   empty-text="No brokers yet. Add your first team member."
                   client-search v-model:search="search"
                   creatable create-label="Add broker" @create="modal.openCreate()">
            <thead class="border-b border-slate-200">
            <tr>
                <TableHeadingCell>Broker</TableHeadingCell>
                <TableHeadingCell>Social</TableHeadingCell>
                <TableHeadingCell align="right"><span class="sr-only">Actions</span></TableHeadingCell>
            </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
            <tr v-for="broker in filtered" :key="broker.id" class="transition-colors hover:bg-slate-50">
                <TableCell>
                    <div class="flex min-w-[14rem] items-center gap-3">
                        <img :src="broker.image" alt="" class="h-10 w-10 shrink-0 rounded-full border border-slate-200 object-cover"/>
                        <div class="min-w-0">
                            <RowTitle @click="modal.openEdit(broker)">{{ broker.name }}</RowTitle>
                            <p class="truncate text-xs text-slate-500">{{ broker.job_title }}</p>
                        </div>
                    </div>
                </TableCell>
                <TableCell>
                    <div class="flex flex-wrap gap-1">
                        <a v-for="link in socialLinks(broker)" :key="link.label" :href="link.url" target="_blank" rel="noopener"
                           class="rounded-full bg-slate-100 px-2 py-0.5 text-xs font-medium text-slate-600 transition-colors hover:bg-slate-200 hover:text-slate-900">
                            {{ link.label }}
                        </a>
                        <span v-if="!socialLinks(broker).length" class="text-slate-400">—</span>
                    </div>
                </TableCell>
                <TableCell align="right">
                    <RowActions editable @edit="modal.openEdit(broker)"
                                @delete="confirmDelete('admin.brokers.destroy', broker.id, 'broker')"/>
                </TableCell>
            </tr>
            </tbody>
        </TableCard>

        <BrokerForm :open="modal.isOpen.value" :broker="modal.record.value" @close="modal.close()"/>
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
import BrokerForm from './BrokerForm.vue';

const props = defineProps({
    brokers: {type: Object, required: true},
});

const {search, filtered} = useClientSearch(() => props.brokers.data, ['name', 'job_title']);
const modal = useResourceModal();

const SOCIAL = [['facebook', 'Facebook'], ['instagram', 'Instagram'], ['twitter', 'X / Twitter']];
const socialLinks = (broker) => SOCIAL.filter(([key]) => broker[key]).map(([key, label]) => ({label, url: broker[key]}));
</script>
