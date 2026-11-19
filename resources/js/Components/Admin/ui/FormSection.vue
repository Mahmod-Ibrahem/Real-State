<template>
    <section class="border-slate-200 [&:not(:first-child)]:border-t [&:not(:first-child)]:pt-6">
        <div class="mb-4">
            <h3 class="text-sm font-semibold text-slate-900">{{ title }}</h3>
            <p v-if="description" class="mt-0.5 text-xs text-slate-500">{{ description }}</p>
        </div>
        <div class="grid gap-5 sm:grid-cols-2">
            <slot/>
        </div>
    </section>
</template>

<script setup>
defineProps({
    title: {type: String, required: true},
    description: {type: String, default: ''},
});
</script>
