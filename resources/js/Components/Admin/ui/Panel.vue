<template>
    <section class="rounded-xl border border-slate-200 bg-white">
        <header v-if="title || $slots.action" class="flex items-start justify-between gap-3 px-6 pt-5">
            <div class="min-w-0">
                <h2 class="text-sm font-semibold text-slate-900">{{ title }}</h2>
                <p v-if="hint" class="mt-0.5 text-xs text-slate-500">{{ hint }}</p>
            </div>
            <slot name="action"/>
        </header>
        <div :class="title || $slots.action ? 'px-6 pb-6 pt-4' : 'p-6'">
            <slot/>
        </div>
    </section>
</template>

<script setup>
defineProps({
    title: {type: String, default: ''},
    hint: {type: String, default: ''},
});
</script>
