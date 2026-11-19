<template>
    <div v-if="meta.last_page > 1" class="flex flex-col gap-3 px-4 py-3 sm:flex-row sm:items-center sm:justify-between">
        <p class="text-sm text-slate-500">
            Showing <span class="font-medium text-slate-900">{{ meta.from }}–{{ meta.to }}</span>
            of <span class="font-medium text-slate-900">{{ meta.total }}</span>
        </p>

        <nav class="flex items-center gap-1" aria-label="Pagination">
            <button type="button" :disabled="!prev" :class="[BASE, IDLE]" aria-label="Previous page" @click="go(prev)">
                <ChevronLeftIcon class="h-4 w-4" aria-hidden="true"/>
            </button>
            <template v-for="link in pages" :key="link.label">
                <span v-if="!link.url" class="px-1 text-sm text-slate-400">…</span>
                <button v-else type="button" :class="[BASE, link.active ? 'bg-indigo-600 text-white' : IDLE]"
                        :aria-current="link.active ? 'page' : undefined" @click="go(link.url)">
                    {{ link.label }}
                </button>
            </template>
            <button type="button" :disabled="!next" :class="[BASE, IDLE]" aria-label="Next page" @click="go(next)">
                <ChevronRightIcon class="h-4 w-4" aria-hidden="true"/>
            </button>
        </nav>
    </div>
</template>

<script setup>
import {computed} from 'vue';
import {router} from '@inertiajs/vue3';
import {ChevronLeftIcon, ChevronRightIcon} from '@heroicons/vue/20/solid';

const BASE = 'grid h-8 min-w-8 place-content-center rounded-md px-2 text-sm font-medium transition-colors disabled:cursor-not-allowed disabled:opacity-40';
const IDLE = 'text-slate-600 hover:bg-slate-100 hover:text-slate-900';

// A Laravel paginator's `meta` (from a resource collection)
const props = defineProps({
    meta: {type: Object, required: true},
});

// Laravel's links are [« Previous, 1, 2, …, Next »]
const pages = computed(() => props.meta.links.slice(1, -1));
const prev = computed(() => props.meta.links[0]?.url);
const next = computed(() => props.meta.links.at(-1)?.url);

const go = (url) => url && router.visit(url, {preserveScroll: true, preserveState: true});
</script>
