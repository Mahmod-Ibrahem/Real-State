<template>
    <td class="px-4 py-3 text-sm" :class="[VARIANTS[variant], align === 'right' ? 'text-right' : '']">
        <slot/>
    </td>
</template>

<script setup>
const VARIANTS = {
    default: 'text-slate-600',
    primary: 'font-medium text-slate-900',
    muted: 'whitespace-nowrap text-slate-500',
};

defineProps({
    variant: {type: String, default: 'default'},
    align: {type: String, default: 'left'},
});
</script>
