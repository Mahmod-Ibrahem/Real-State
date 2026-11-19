<template>
    <!-- Inline two-value field: shows the current value, a click flips it (ADMIN_UI.md §5 "Quick edits") -->
    <button type="button" :disabled="loading" :aria-pressed="pressed" :title="`Click to change to ${nextLabel}`"
            :class="['inline-flex items-center gap-1 whitespace-nowrap rounded-full px-2 py-0.5 text-xs font-medium transition-colors',
                     'focus:outline-none focus-visible:ring-2 focus-visible:ring-indigo-500/30 disabled:cursor-wait',
                     TONES[tone], TONE_HOVERS[tone]]"
            @click="emit('toggle')">
        {{ label }}
        <svg v-if="loading" class="h-3 w-3 animate-spin" viewBox="0 0 24 24" fill="none" aria-hidden="true">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"/>
        </svg>
        <ArrowsRightLeftIcon v-else class="h-3 w-3 opacity-60" aria-hidden="true"/>
    </button>
</template>

<script setup>
import {ArrowsRightLeftIcon} from '@heroicons/vue/20/solid';
import {TONES, TONE_HOVERS} from './tones.js';

defineProps({
    // The current value's label, e.g. "Published"
    label: {type: String, required: true},
    // What a click switches to, e.g. "Draft"
    nextLabel: {type: String, required: true},
    // StatusBadge tone of the current value
    tone: {type: String, default: 'neutral'},
    // For assistive tech: is the "on" value (e.g. Published) the current one?
    pressed: {type: Boolean, default: false},
    loading: {type: Boolean, default: false},
});
const emit = defineEmits(['toggle']);
</script>
