<template>
    <div>
        <template v-if="board">
            <div class="mb-4 flex flex-col gap-3 rounded-xl border border-slate-200 bg-white px-4 py-3 md:flex-row md:items-center md:justify-between">
                <p class="text-sm text-slate-500">
                    Found <span class="font-semibold text-slate-900">{{ boardTotal }}</span> leads
                </p>
                <div class="flex flex-wrap items-center gap-2">
                    <SearchBox v-model="search" noun="leads"/>
                    <FilterSelect :options="sources" :model-value="filters.source" param="source" label="Source"/>
                    <Button v-if="isFiltered" variant="ghost" :icon="XMarkIcon" @click="clearFilters">Clear filters</Button>
                </div>
            </div>

            <KanbanBoard :columns="columns" empty-text="No leads" @move="(lead, status) => changeStatus(lead, status)">
                <template #card="{item}">
                    <LeadCard :lead="item" :show-owner="showOwner" :saving="Boolean(saving[item.id])" @open="openLead"/>
                </template>
            </KanbanBoard>
        </template>

        <LeadsTable v-else :leads="leads" :statuses="statuses" :saving="saving" :show-owner="showOwner"
                    :destroy-route="destroyRoute" :empty-text="emptyText" @open="openLead" @change-status="changeStatus">
            <template #filters>
                <FilterSelect :options="statuses" :model-value="filters.status" param="status" label="Status"/>
                <FilterSelect :options="sources" :model-value="filters.source" param="source" label="Source"/>
                <Button v-if="isFiltered" variant="ghost" :icon="XMarkIcon" @click="clearFilters">Clear filters</Button>
            </template>
        </LeadsTable>

        <LeadDetailsModal :lead="openedLead" :statuses="statuses" :saving="Boolean(openedLead && saving[openedLead.id])"
                          :show-owner="showOwner" @close="openedId = null" @change-status="changeStatus"/>
    </div>
</template>

<script setup>
import {computed, ref, watch} from 'vue';
import {router} from '@inertiajs/vue3';
import {XMarkIcon} from '@heroicons/vue/20/solid';
import Button from '@/Components/Admin/ui/Button.vue';
import FilterSelect from '@/Components/Admin/ui/FilterSelect.vue';
import KanbanBoard from '@/Components/Admin/ui/KanbanBoard.vue';
import SearchBox from '@/Components/Admin/ui/SearchBox.vue';
import {useLeadStatus} from '@/Composables/useLeadStatus.js';
import LeadCard from './LeadCard.vue';
import LeadDetailsModal from './LeadDetailsModal.vue';
import LeadsTable from './LeadsTable.vue';

const FILTER_PARAMS = ['search', 'status', 'source'];

const props = defineProps({
    // Board view: [{id, name, tone, count, items}]; null in the table view
    board: {type: Array, default: null},
    // Table view: paginated PropertyLeadListResource; null on the board
    leads: {type: Object, default: null},
    filters: {type: Object, required: true},
    statuses: {type: Array, required: true},
    sources: {type: Array, required: true},
    statusRoute: {type: String, required: true},
    destroyRoute: {type: String, default: null},
    showOwner: {type: Boolean, default: false},
    emptyText: {type: String, default: 'No leads yet.'},
});

const {saving, changeStatus} = useLeadStatus(props.statusRoute);
const search = ref('');

// The board moves cards between these arrays while dragging; a fresh server response replaces them
const copyBoard = (board) => (board ?? []).map((column) => ({...column, items: [...column.items]}));
const columns = ref(copyBoard(props.board));
watch(() => props.board, (board) => (columns.value = copyBoard(board)));

const boardTotal = computed(() => columns.value.reduce((total, column) => total + column.count, 0));
const isFiltered = computed(() => FILTER_PARAMS.some((param) => props.filters[param]));

const openedId = ref(null);
const visibleLeads = computed(() => (props.board ? columns.value.flatMap((column) => column.items) : props.leads?.data ?? []));
const openedLead = computed(() => visibleLeads.value.find((lead) => lead.id === openedId.value) ?? null);
const openLead = (lead) => (openedId.value = lead.id);

function clearFilters() {
    const params = Object.fromEntries(new URLSearchParams(window.location.search));
    [...FILTER_PARAMS, 'page'].forEach((param) => delete params[param]);
    search.value = '';

    router.get(window.location.pathname, params, {preserveState: true, preserveScroll: true, replace: true});
}
</script>
