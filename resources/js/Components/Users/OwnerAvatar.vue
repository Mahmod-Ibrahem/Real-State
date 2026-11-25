<template>
    <!-- Public bundle: Tailwind only, Bootstrap-collision safe (FRONTEND.md) -->
    <img v-if="src" :src="src" :alt="name" :class="[SIZES[size], 'shrink-0 rounded-full object-cover']" loading="lazy">
    <span v-else :class="[SIZES[size], 'grid shrink-0 place-content-center rounded-full bg-sky-50 font-semibold text-sky-700']"
          aria-hidden="true">{{ initials }}</span>
</template>

<script setup>
import {computed} from 'vue';

const SIZES = {
    sm: 'h-8 w-8 text-xs',
    lg: 'h-14 w-14 text-lg',
    xl: 'h-20 w-20 text-2xl',
};

const props = defineProps({
    src: {type: String, default: null},
    name: {type: String, required: true},
    size: {type: String, default: 'sm'},
});

const initials = computed(() =>
    props.name.split(' ').filter(Boolean).slice(0, 2).map((word) => word[0].toUpperCase()).join('')
);
</script>
