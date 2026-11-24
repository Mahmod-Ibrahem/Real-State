<template>
    <!-- Inline 3+-value field in a table row (ADMIN_UI.md §5 "Quick edits"): a badge-like pill that opens a list -->
    <Listbox :model-value="modelValue" :disabled="loading" @update:model-value="emit('update:modelValue', $event)">
        <ListboxButton ref="anchorRef" :aria-label="label"
                       :class="['inline-flex h-7 items-center gap-1 whitespace-nowrap rounded-full pl-2.5 pr-2 text-xs font-medium transition-colors',
                                'focus:outline-none focus-visible:ring-2 focus-visible:ring-indigo-500/30 disabled:cursor-wait',
                                TONES[tone], TONE_HOVERS[tone]]"
                       @click="updatePosition" @keydown="updatePosition">
            {{ currentLabel }}
            <svg v-if="loading" class="h-3 w-3 animate-spin" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"/>
            </svg>
            <ChevronDownIcon v-else class="h-3.5 w-3.5 opacity-60" aria-hidden="true"/>
        </ListboxButton>

        <Portal>
            <transition leave-active-class="transition duration-75 ease-in" leave-from-class="opacity-100"
                        leave-to-class="opacity-0">
                <ListboxOptions :style="panelStyle"
                                class="fixed z-[60] max-h-64 overflow-auto rounded-lg border border-slate-200 bg-white p-1 text-sm shadow-lg focus:outline-none">
                    <ListboxOption v-for="option in options" :key="option[optionValue]" :value="option[optionValue]"
                                   :disabled="option.disabled" v-slot="{active, selected, disabled}" as="template">
                        <li class="flex select-none items-center gap-2 rounded-md px-2 py-1.5"
                            :class="disabled ? 'cursor-not-allowed text-slate-400'
                                             : ['cursor-pointer', active ? 'bg-slate-100 text-slate-900' : 'text-slate-700']">
                            <span class="flex-1 truncate" :class="{'font-medium text-slate-900': selected}">
                                {{ option[optionLabel] }}
                            </span>
                            <CheckIcon v-if="selected" class="h-4 w-4 shrink-0 text-indigo-600" aria-hidden="true"/>
                        </li>
                    </ListboxOption>
                </ListboxOptions>
            </transition>
        </Portal>
    </Listbox>
</template>

<script setup>
import {computed} from 'vue';
import {Listbox, ListboxButton, ListboxOption, ListboxOptions, Portal} from '@headlessui/vue';
import {CheckIcon, ChevronDownIcon} from '@heroicons/vue/20/solid';
import {TONES, TONE_HOVERS} from './tones.js';
import {useFloatingPanel} from '@/Composables/useFloatingPanel.js';

const props = defineProps({
    modelValue: {type: [String, Number], required: true},
    // [{id, name, disabled?}] by default
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

const {anchorRef, panelStyle, updatePosition} = useFloatingPanel({minWidth: 144});

const currentLabel = computed(
    () => props.options.find((option) => option[props.optionValue] === props.modelValue)?.[props.optionLabel] ?? props.modelValue
);
</script>
