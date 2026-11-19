<template>
    <th scope="col" class="whitespace-nowrap bg-slate-50 px-4 py-2.5 text-xs font-medium text-slate-500"
        :class="align === 'right' ? 'text-right' : 'text-left'">
        <slot/>
    </th>
</template>

<script setup>
defineProps({
    align: {type: String, default: 'left'},
});
</script>
