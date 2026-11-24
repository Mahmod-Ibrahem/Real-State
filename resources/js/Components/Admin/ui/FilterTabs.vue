<template>
    <nav class="flex items-center gap-1 rounded-lg bg-slate-100 p-0.5" :aria-label="label">
        <Link v-for="tab in tabs" :key="tab.id ?? 'all'" :href="hrefOf(tab.id)" preserve-scroll
              class="rounded-md px-2.5 py-1 text-xs font-medium transition-colors focus:outline-none focus-visible:ring-2 focus-visible:ring-indigo-500/30"
              :class="tab.id === modelValue ? 'bg-white text-slate-900 shadow-sm' : 'text-slate-500 hover:text-slate-900'"
              :aria-current="tab.id === modelValue ? 'page' : undefined">
            {{ tab.name }}
        </Link>
    </nav>
</template>

<script setup>
import {computed} from 'vue';
import {Link} from '@inertiajs/vue3';

const props = defineProps({
    options: {type: Array, required: true},
    modelValue: {type: [String, Number], default: null},
    param: {type: String, required: true},
    allLabel: {type: String, default: 'All'},
    label: {type: String, required: true},
});

const tabs = computed(() => [{id: null, name: props.allLabel}, ...props.options]);

function hrefOf(value) {
    const params = Object.fromEntries(new URLSearchParams(window.location.search));
    delete params.page;
    delete params[props.param];
    if (value) params[props.param] = value;
    const query = new URLSearchParams(params).toString();
    return query ? `${window.location.pathname}?${query}` : window.location.pathname;
}
</script>
