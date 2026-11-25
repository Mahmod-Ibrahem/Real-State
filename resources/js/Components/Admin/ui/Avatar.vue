<template>
    <img v-if="src" :src="src" :alt="name" :class="[SIZES[size], 'shrink-0 rounded-full object-cover']">
    <span v-else :class="[SIZES[size], 'grid shrink-0 place-content-center rounded-full bg-indigo-50 font-semibold text-indigo-700']"
          aria-hidden="true">{{ initials }}</span>
</template>

<script setup>
import {computed} from 'vue';

const SIZES = {
    sm: 'h-7 w-7 text-xs',
    md: 'h-10 w-10 text-sm',
    lg: 'h-16 w-16 text-lg',
};

const props = defineProps({
    src: {type: String, default: null},
    name: {type: String, required: true},
    size: {type: String, default: 'md'},
});

const initials = computed(() =>
    props.name.split(' ').filter(Boolean).slice(0, 2).map((word) => word[0].toUpperCase()).join('')
);
</script>
