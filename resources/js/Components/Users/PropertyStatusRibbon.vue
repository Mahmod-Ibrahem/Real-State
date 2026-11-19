<template>
    <span v-if="status" class="status-ribbon" :class="'status-ribbon--' + status.value">
        {{ status.label }}
    </span>
</template>

<script setup>
// status: null while the property is available, otherwise {value: 'sold' | 'rented', label: 'Sold'}
defineProps({
    status: {type: Object, default: null},
});
</script>

<style scoped>
.status-ribbon {
    position: absolute;
    top: 16px;
    inset-inline-start: -36px;
    z-index: 2;
    width: 140px;
    padding: 4px 0;
    transform: rotate(-45deg);
    color: #fff;
    font-size: 13px;
    font-weight: 600;
    letter-spacing: .5px;
    text-align: center;
    text-transform: uppercase;
    box-shadow: 0 2px 6px rgba(0, 0, 0, .25);
    pointer-events: none;
}

[dir="rtl"] .status-ribbon {
    transform: rotate(45deg);
}

.status-ribbon--sold {
    background: #dc3545;
}

.status-ribbon--rented {
    background: #0d6efd;
}
</style>
