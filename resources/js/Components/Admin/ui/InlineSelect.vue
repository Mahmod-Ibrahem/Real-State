<template>
    <!-- Inline 3+-value field in a table row (ADMIN_UI.md §5 "Quick edits") -->
    <div class="relative inline-flex">
        <select :value="modelValue" :disabled="loading" :aria-label="label"
                :class="['h-7 cursor-pointer appearance-none rounded-full border-0 bg-none py-0 pl-2.5 pr-7 text-xs font-medium transition-colors',
                         'focus:outline-none focus:ring-2 focus:ring-indigo-500/30 disabled:cursor-wait', TONES[tone], TONE_HOVERS[tone]]"
                @change="emit('update:modelValue', $event.target.value)">
            <option v-for="option in options" :key="option[optionValue]" :value="option[optionValue]">
                {{ option[optionLabel] }}
            </option>
        </select>
        <svg v-if="loading" class="pointer-events-none absolute right-2 top-1/2 h-3 w-3 -translate-y-1/2 animate-spin"
             viewBox="0 0 24 24" fill="none" aria-hidden="true">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"/>
        </svg>
        <ChevronDownIcon v-else class="pointer-events-none absolute right-2 top-1/2 h-3.5 w-3.5 -translate-y-1/2 opacity-60"
                         aria-hidden="true"/>
    </div>
</template>

<script setup>
import {ChevronDownIcon} from '@heroicons/vue/20/solid';
import {TONES, TONE_HOVERS} from './tones.js';

defineProps({
    modelValue: {type: [String, Number], required: true},
    // [{id, name}] by default
    options: {type: Array, required: true},
    optionValue: {type: String, default: 'id'},
    optionLabel: {type: String, default: 'name'},
    // Accessible name, e.g. "Status of Sea View Villa"
    label: {type: String, required: true},
    // StatusBadge tone of the current value
    tone: {type: String, default: 'neutral'},
    loading: {type: Boolean, default: false},
});
const emit = defineEmits(['update:modelValue']);
</script>
