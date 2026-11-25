<template>
    <div class="relative w-full md:w-64">
        <MagnifyingGlassIcon class="pointer-events-none absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-slate-400"
                             aria-hidden="true"/>
        <input v-model="search" type="search" :placeholder="`Search ${noun}…`" :aria-label="`Search ${noun}`"
               class="h-9 w-full rounded-lg border border-slate-300 bg-white pl-9 pr-3 text-sm text-slate-900 placeholder:text-slate-400 transition-colors hover:border-slate-400 focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500/15"/>
    </div>
</template>

<script setup>
import {watch} from 'vue';
import {router} from '@inertiajs/vue3';
import {MagnifyingGlassIcon} from '@heroicons/vue/20/solid';

const props = defineProps({
    noun: {type: String, required: true},
    // Unpaginated lists filter their own rows: only update the model instead of reloading with ?search=
    clientSearch: {type: Boolean, default: false},
});
const search = defineModel({type: String, default: ''});

if (!props.clientSearch) {
    search.value = new URLSearchParams(window.location.search).get('search') ?? '';
}

let timer = null;
watch(search, (value) => {
    if (props.clientSearch) return;

    clearTimeout(timer);
    timer = setTimeout(() => {
        const {page, search: previous, ...filters} = Object.fromEntries(new URLSearchParams(window.location.search));
        router.get(window.location.pathname, value ? {...filters, search: value} : filters, {
            preserveState: true,
            preserveScroll: true,
            replace: true,
        });
    }, 300);
});
</script>
