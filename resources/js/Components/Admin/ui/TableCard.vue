<template>
    <div class="overflow-hidden rounded-xl border border-slate-200 bg-white">
        <div class="flex flex-col gap-3 border-b border-slate-200 px-4 py-3 md:flex-row md:items-center md:justify-between">
            <p class="text-sm text-slate-500">
                Found <span class="font-semibold text-slate-900">{{ total }}</span> {{ noun }}
            </p>

            <div class="flex flex-wrap items-center gap-2">
                <div v-if="searchable" class="relative w-full md:w-64">
                    <MagnifyingGlassIcon class="pointer-events-none absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-slate-400"
                                         aria-hidden="true"/>
                    <input v-model="search" type="search" :placeholder="`Search ${noun}…`" :aria-label="`Search ${noun}`"
                           class="h-9 w-full rounded-lg border border-slate-300 bg-white pl-9 pr-3 text-sm text-slate-900 placeholder:text-slate-400 transition-colors hover:border-slate-400 focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500/15"/>
                </div>
                <slot name="actions"/>
                <Button v-if="createHref" :href="createHref" :icon="PlusIcon">{{ createLabel }}</Button>
                <Button v-else-if="creatable" :icon="PlusIcon" @click="emit('create')">{{ createLabel }}</Button>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <slot/>
            </table>
        </div>

        <p v-if="empty" class="px-4 py-16 text-center text-sm text-slate-500">
            {{ search ? `No ${noun} match “${search}”.` : emptyText }}
        </p>

        <Pagination v-if="meta" :meta="meta" class="border-t border-slate-200"/>
    </div>
</template>

<script setup>
import {computed, ref, watch} from 'vue';
import {router} from '@inertiajs/vue3';
import {MagnifyingGlassIcon, PlusIcon} from '@heroicons/vue/20/solid';
import Button from './Button.vue';
import Pagination from './Pagination.vue';

const props = defineProps({
    // Plural noun for the toolbar and messages: "cities"
    noun: {type: String, required: true},
    // Laravel paginator meta; omit for unpaginated lists and pass `count`
    meta: {type: Object, default: null},
    count: {type: Number, default: null},
    empty: {type: Boolean, default: false},
    emptyText: {type: String, default: 'Nothing here yet.'},
    // Create on its own page
    createHref: {type: String, default: null},
    // Create in a modal: the button emits `create`
    creatable: {type: Boolean, default: false},
    createLabel: {type: String, default: 'Add new'},
    searchable: {type: Boolean, default: true},
    // Unpaginated lists filter their own rows: bind v-model:search instead of hitting the server
    clientSearch: {type: Boolean, default: false},
});
const emit = defineEmits(['update:search', 'create']);

const total = computed(() => props.count ?? props.meta?.total ?? 0);

// Server mode filters with ?search=; keep the box in sync with the URL and debounce typing.
const search = ref(props.clientSearch ? '' : new URLSearchParams(window.location.search).get('search') ?? '');
let timer = null;
watch(search, (value) => {
    if (props.clientSearch) {
        emit('update:search', value);
        return;
    }
    clearTimeout(timer);
    timer = setTimeout(() => {
        router.get(window.location.pathname, value ? {search: value} : {}, {
            preserveState: true,
            preserveScroll: true,
            replace: true,
        });
    }, 300);
});
</script>
