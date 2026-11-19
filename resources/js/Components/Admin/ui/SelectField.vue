<template>
    <!-- Custom select, ported from Mr Egypt Tours' CustomSelect (ADMIN_UI.md §4, "Custom inputs only") -->
    <FieldFrame :input-id="id" :label="label" :error="error" :hint="hint" :required="required" :class="$attrs.class">
        <Listbox :model-value="modelValue" :multiple="multiple" :disabled="disabled" :by="sameValue"
                 @update:model-value="choose">
            <ListboxButton :id="id" ref="anchorRef" :aria-invalid="Boolean(error)"
                           :aria-describedby="error ? `${id}-error` : undefined"
                           :class="[controlClasses(error), 'flex min-h-10 items-center gap-2 py-1.5 text-left']"
                           @click="onOpen" @keydown="onOpen">
                <span class="flex min-w-0 flex-1 flex-wrap items-center gap-1">
                    <!-- Multiple: chips for the first few, then a count -->
                    <template v-if="multiple && selectedOptions.length">
                        <span v-for="option in visibleChips" :key="valueOf(option)"
                              class="inline-flex max-w-[10rem] items-center rounded-md bg-slate-100 px-1.5 py-0.5 text-xs font-medium text-slate-700">
                            <span class="truncate">{{ labelOf(option) }}</span>
                        </span>
                        <span v-if="hiddenCount" class="text-xs font-medium text-slate-500">+{{ hiddenCount }} more</span>
                    </template>
                    <span v-else-if="!multiple && selectedOptions.length" class="truncate text-slate-900"
                          :title="labelOf(selectedOptions[0])">
                        {{ labelOf(selectedOptions[0]) }}
                    </span>
                    <span v-else class="truncate text-slate-400">{{ placeholder }}</span>
                </span>
                <ChevronUpDownIcon class="h-4 w-4 shrink-0 text-slate-400" aria-hidden="true"/>
            </ListboxButton>

            <!-- A Headless UI Portal (not Teleport) so it still works inside a Modal, and tables can't clip it -->
            <Portal>
                <transition leave-active-class="transition duration-75 ease-in" leave-from-class="opacity-100"
                            leave-to-class="opacity-0">
                    <ListboxOptions :style="panelStyle"
                                    class="fixed z-[60] max-h-64 overflow-auto rounded-lg border border-slate-200 bg-white p-1 text-sm shadow-lg focus:outline-none">
                        <li v-if="searchable" class="sticky -top-1 z-10 -mx-1 -mt-1 mb-1 border-b border-slate-100 bg-white p-1.5">
                            <div class="relative">
                                <MagnifyingGlassIcon class="pointer-events-none absolute left-2 top-1/2 h-4 w-4 -translate-y-1/2 text-slate-400"
                                                     aria-hidden="true"/>
                                <input ref="searchRef" v-model="query" type="text" :placeholder="searchPlaceholder"
                                       :aria-label="searchPlaceholder" @keydown="onSearchKeydown"
                                       class="h-8 w-full rounded-md border border-slate-200 pl-8 pr-2 text-sm text-slate-900 placeholder:text-slate-400 focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500/15"/>
                            </div>
                        </li>

                        <li v-if="multiple && selectedOptions.length" class="mb-1 border-b border-slate-100 pb-1">
                            <button type="button" @click="choose([])"
                                    class="w-full rounded-md px-2 py-1.5 text-left text-xs font-medium text-slate-500 hover:bg-slate-100 hover:text-slate-900">
                                Clear selection
                            </button>
                        </li>

                        <li v-if="!options.length" class="px-2 py-2 text-slate-400">{{ emptyMessage }}</li>
                        <li v-else-if="!visibleOptions.length" class="px-2 py-2 text-slate-400">No matches for “{{ query }}”</li>

                        <ListboxOption v-for="option in visibleOptions" :key="valueOf(option)" :value="valueOf(option)"
                                       :disabled="Boolean(option?.disabled)" v-slot="{active, selected}" as="template">
                            <li class="flex min-w-0 cursor-pointer select-none items-center gap-2 rounded-md px-2 py-1.5"
                                :title="labelOf(option)"
                                :class="[active ? 'bg-slate-100 text-slate-900' : 'text-slate-700', option?.disabled ? 'opacity-50' : '']">
                                <!-- Multiple: checkbox square; single: check mark -->
                                <span v-if="multiple"
                                      class="grid h-4 w-4 shrink-0 place-content-center rounded border transition-colors"
                                      :class="selected ? 'border-indigo-600 bg-indigo-600 text-white' : 'border-slate-300 bg-white'">
                                    <CheckIcon v-if="selected" class="h-3 w-3"/>
                                </span>
                                <span class="min-w-0 flex-1 truncate" :class="{'font-medium text-slate-900': selected}">
                                    {{ labelOf(option) }}
                                </span>
                                <CheckIcon v-if="!multiple && selected" class="h-4 w-4 shrink-0 text-indigo-600"/>
                            </li>
                        </ListboxOption>
                    </ListboxOptions>
                </transition>
            </Portal>
        </Listbox>
    </FieldFrame>
</template>

<script setup>
import {computed, ref} from 'vue';
import {Listbox, ListboxButton, ListboxOption, ListboxOptions, Portal} from '@headlessui/vue';
import {CheckIcon, ChevronUpDownIcon, MagnifyingGlassIcon} from '@heroicons/vue/20/solid';
import FieldFrame from './FieldFrame.vue';
import {controlClasses, useFieldId} from '@/Composables/useFieldId.js';
import {useFloatingPanel} from '@/Composables/useFloatingPanel.js';

// Layout classes (e.g. sm:col-span-2) go on the field frame
defineOptions({inheritAttrs: false});

const props = defineProps({
    modelValue: {type: [String, Number, Boolean, Array], default: null},
    // [{id, name}] by default; see optionValue/optionLabel
    options: {type: Array, default: () => []},
    optionValue: {type: String, default: 'id'},
    optionLabel: {type: String, default: 'name'},
    label: {type: String, default: ''},
    placeholder: {type: String, default: 'Select…'},
    error: {type: String, default: ''},
    hint: {type: String, default: ''},
    required: {type: Boolean, default: false},
    disabled: {type: Boolean, default: false},
    // v-model is an array of values
    multiple: {type: Boolean, default: false},
    // Chips shown in the button before "+N more"
    maxChips: {type: Number, default: 4},
    // A filter box at the top of the list
    searchable: {type: Boolean, default: false},
    searchPlaceholder: {type: String, default: 'Search…'},
    emptyMessage: {type: String, default: 'Nothing to choose from yet'},
});
const emit = defineEmits(['update:modelValue', 'change']);

const id = useFieldId('select');
const {anchorRef, panelStyle, updatePosition} = useFloatingPanel();

const isObject = (value) => value !== null && typeof value === 'object';
const valueOf = (option) => (isObject(option) ? option[props.optionValue] : option);
const labelOf = (option) => (isObject(option) ? option[props.optionLabel] : String(option));
// Loose compare so "10" (query strings, old values) still matches 10
const sameValue = (a, b) => a === b || (a != null && b != null && String(a) === String(b));

const selectedOptions = computed(() => {
    const current = props.multiple ? (Array.isArray(props.modelValue) ? props.modelValue : []) : [props.modelValue];
    return props.options.filter((option) => current.some((value) => sameValue(value, valueOf(option))));
});
const visibleChips = computed(() => selectedOptions.value.slice(0, props.maxChips));
const hiddenCount = computed(() => Math.max(0, selectedOptions.value.length - props.maxChips));

const query = ref('');
const searchRef = ref(null);
const visibleOptions = computed(() => {
    const term = query.value.trim().toLowerCase();
    if (!props.searchable || !term) return props.options;
    return props.options.filter((option) => String(labelOf(option) ?? '').toLowerCase().includes(term));
});

function onOpen() {
    query.value = '';
    updatePosition();
    // Headless UI focuses the list when it opens; move focus into the search box after that
    if (props.searchable) setTimeout(() => searchRef.value?.focus(), 0);
}

// Arrows, Enter, Escape and Tab still drive the list; other keys stay in the search box
// instead of triggering the list's own type-ahead
function onSearchKeydown(event) {
    if (!['ArrowDown', 'ArrowUp', 'Enter', 'Escape', 'Tab'].includes(event.key)) event.stopPropagation();
}

function choose(value) {
    emit('update:modelValue', value);
    emit('change', value);
}
</script>
