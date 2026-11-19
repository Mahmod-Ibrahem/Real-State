<template>
    <span class="inline-flex items-center whitespace-nowrap rounded-full px-2 py-0.5 text-xs font-medium" :class="TONES[tone]">
        <slot/>
    </span>
</template>

<script setup>
import {TONES} from './tones.js';

defineProps({
    tone: {type: String, default: 'neutral'},
});
</script>
