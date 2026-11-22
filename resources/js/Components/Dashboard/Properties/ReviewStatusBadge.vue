<template>
    <StatusBadge :tone="option?.tone ?? 'neutral'">{{ option?.name ?? status }}</StatusBadge>
</template>

<script setup>
import {computed} from 'vue';
import StatusBadge from '@/Components/Admin/ui/StatusBadge.vue';

const props = defineProps({
    status: {type: String, required: true},
    statuses: {type: Array, required: true},
});

const option = computed(() => props.statuses.find((status) => status.id === props.status));
</script>
