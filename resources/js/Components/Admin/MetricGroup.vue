<template>
    <!-- A row of numbers inside one panel; as many columns as fit -->
    <div class="grid grid-cols-[repeat(auto-fit,minmax(8rem,1fr))] gap-4">
        <component :is="metric.href ? Link : 'div'" v-for="metric in metrics" :key="metric.label"
                   v-bind="metric.href ? {href: metric.href} : {}"
                   class="-m-2 min-w-0 rounded-lg p-2" :class="metric.href && 'transition-colors hover:bg-slate-50'">
            <p class="truncate text-sm text-slate-500" :title="metric.label">{{ metric.label }}</p>
            <p class="mt-1 text-3xl font-semibold tabular-nums tracking-tight text-slate-900">{{ formatNumber(metric.value) }}</p>
            <p v-if="metric.hint" class="mt-0.5 truncate text-xs text-slate-400">{{ metric.hint }}</p>
        </component>
    </div>
</template>

<script setup>
import {Link} from '@inertiajs/vue3';
import {formatNumber} from '@/Composables/useFormatters.js';

defineProps({
    // [{label, value, href?, hint?}]
    metrics: {type: Array, required: true},
});
</script>
