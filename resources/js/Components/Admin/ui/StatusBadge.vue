<template>
    <span class="inline-flex items-center whitespace-nowrap rounded-full px-2 py-0.5 text-xs font-medium" :class="TONES[tone]">
        <slot/>
    </span>
</template>

<script setup>
const TONES = {
    success: 'bg-green-50 text-green-700',
    warning: 'bg-amber-50 text-amber-700',
    danger: 'bg-red-50 text-red-700',
    info: 'bg-blue-50 text-blue-700',
    neutral: 'bg-slate-100 text-slate-600',
};

defineProps({
    tone: {type: String, default: 'neutral'},
});
</script>
