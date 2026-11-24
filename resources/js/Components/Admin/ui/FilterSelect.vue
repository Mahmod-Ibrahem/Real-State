<template>
    <Listbox :model-value="modelValue" @update:model-value="apply">
        <ListboxButton ref="anchorRef"
                       :class="['inline-flex h-9 items-center gap-1.5 whitespace-nowrap rounded-lg border px-3 text-sm transition-colors',
                                'focus:outline-none focus-visible:ring-2 focus-visible:ring-indigo-500/30',
                                selected ? 'border-indigo-200 bg-indigo-50 text-indigo-700 hover:bg-indigo-100'
                                         : 'border-slate-300 bg-white text-slate-600 hover:bg-slate-50 hover:text-slate-900']"
                       @click="updatePosition" @keydown="updatePosition">
            <span>{{ label }}<template v-if="selected">: <span class="font-semibold">{{ selected.name }}</span></template></span>
            <ChevronDownIcon class="h-4 w-4 opacity-60" aria-hidden="true"/>
        </ListboxButton>

        <Portal>
            <transition leave-active-class="transition duration-75 ease-in" leave-from-class="opacity-100"
                        leave-to-class="opacity-0">
                <ListboxOptions :style="panelStyle" :aria-label="label"
                                class="fixed z-[60] max-h-64 overflow-auto rounded-lg border border-slate-200 bg-white p-1 text-sm shadow-lg focus:outline-none">
                    <ListboxOption v-for="option in choices" :key="option.id ?? 'all'" :value="option.id"
                                   v-slot="{active, selected: isChosen}" as="template">
                        <li class="flex cursor-pointer select-none items-center gap-2 rounded-md px-2 py-1.5"
                            :class="active ? 'bg-slate-100 text-slate-900' : 'text-slate-700'">
                            <span v-if="option.tone" class="h-1.5 w-1.5 shrink-0 rounded-full" :class="TONE_DOTS[option.tone]"
                                  aria-hidden="true"></span>
                            <span class="flex-1 truncate" :class="{'font-medium text-slate-900': isChosen}">{{ option.name }}</span>
                            <CheckIcon v-if="isChosen" class="h-4 w-4 shrink-0 text-indigo-600" aria-hidden="true"/>
                        </li>
                    </ListboxOption>
                </ListboxOptions>
            </transition>
        </Portal>
    </Listbox>
</template>

<script setup>
import {computed} from 'vue';
import {router} from '@inertiajs/vue3';
import {Listbox, ListboxButton, ListboxOption, ListboxOptions, Portal} from '@headlessui/vue';
import {CheckIcon, ChevronDownIcon} from '@heroicons/vue/20/solid';
import {TONE_DOTS} from './tones.js';
import {useFloatingPanel} from '@/Composables/useFloatingPanel.js';

const props = defineProps({
    // [{id, name, tone?}]
    options: {type: Array, required: true},
    modelValue: {type: [String, Number], default: null},
    // Query-string key this filter sets, e.g. "status"
    param: {type: String, required: true},
    label: {type: String, required: true},
    allLabel: {type: String, default: 'All'},
});

const {anchorRef, panelStyle, updatePosition} = useFloatingPanel({minWidth: 176});

const choices = computed(() => [{id: null, name: props.allLabel}, ...props.options]);
const selected = computed(() => props.options.find((option) => option.id === props.modelValue) ?? null);

function apply(value) {
    const params = Object.fromEntries(new URLSearchParams(window.location.search));
    delete params.page;
    delete params[props.param];
    if (value !== null) params[props.param] = value;

    router.get(window.location.pathname, params, {preserveState: true, preserveScroll: true, replace: true});
}
</script>
