<template>
    <!-- Public bundle: Tailwind only, Bootstrap-collision safe (FRONTEND.md). Finishing and delivery chips on cards. -->
    <span class="flex flex-wrap gap-1">
        <span v-if="property.finishing" :class="[CHIP, 'bg-slate-100 text-slate-700']">{{ property.finishing.name }}</span>
        <span v-if="property.delivery"
              :class="[CHIP, property.delivery.ready ? 'bg-green-50 text-green-700' : 'bg-amber-50 text-amber-700']">
            {{ property.delivery.ready ? 'Ready to move' : `Delivery ${property.delivery.year}` }}
        </span>
    </span>
</template>

<script setup>
const CHIP = 'inline-flex items-center rounded-full px-2 py-0.5 text-xs font-medium';

defineProps({
    // Public property resource: finishing {id, name}, delivery {ready, year}
    property: {type: Object, required: true},
});
</script>
