<template>
    <!-- Year calendar (ADMIN_UI.md §4): pages through future years, no fixed range -->
    <FieldFrame :input-id="id" :label="label" :error="error" :hint="hint" :required="required" :class="$attrs.class">
        <Popover v-slot="{close}">
            <PopoverButton :id="id" ref="anchorRef" :aria-invalid="Boolean(error)"
                           :aria-describedby="error ? `${id}-error` : undefined"
                           :class="[controlClasses(error), 'flex h-10 items-center gap-2 text-left']" @click="onOpen">
                <span class="min-w-0 flex-1 truncate text-slate-900">{{ hasYear ? modelValue : emptyLabel }}</span>
                <CalendarDaysIcon class="h-4 w-4 shrink-0 text-slate-400" aria-hidden="true"/>
            </PopoverButton>

            <Portal>
                <transition leave-active-class="transition duration-75 ease-in" leave-from-class="opacity-100"
                            leave-to-class="opacity-0">
                    <PopoverPanel :style="panelStyle"
                                  class="fixed z-[60] rounded-lg border border-slate-200 bg-white p-2 text-sm shadow-lg focus:outline-none">
                        <div class="mb-2 flex items-center justify-between">
                            <button type="button" :disabled="pageStart <= minYear" aria-label="Earlier years"
                                    class="grid h-8 w-8 place-content-center rounded-md text-slate-500 transition-colors hover:bg-slate-100 disabled:opacity-30 disabled:hover:bg-transparent focus:outline-none focus-visible:ring-2 focus-visible:ring-indigo-500/30"
                                    @click="pageStart -= PAGE_SIZE">
                                <ChevronLeftIcon class="h-4 w-4 rtl:rotate-180" aria-hidden="true"/>
                            </button>
                            <span class="text-sm font-semibold text-slate-900 tabular-nums">
                                {{ pageStart }} – {{ pageStart + PAGE_SIZE - 1 }}
                            </span>
                            <button type="button" aria-label="Later years"
                                    class="grid h-8 w-8 place-content-center rounded-md text-slate-500 transition-colors hover:bg-slate-100 focus:outline-none focus-visible:ring-2 focus-visible:ring-indigo-500/30"
                                    @click="pageStart += PAGE_SIZE">
                                <ChevronRightIcon class="h-4 w-4 rtl:rotate-180" aria-hidden="true"/>
                            </button>
                        </div>

                        <div class="grid grid-cols-3 gap-1">
                            <button v-for="year in pageYears" :key="year" type="button" :aria-pressed="isChosen(year)"
                                    class="h-9 rounded-md text-sm tabular-nums transition-colors focus:outline-none focus-visible:ring-2 focus-visible:ring-indigo-500/30"
                                    :class="isChosen(year)
                                        ? 'bg-indigo-600 font-semibold text-white'
                                        : ['text-slate-700 hover:bg-slate-100', year === CURRENT_YEAR ? 'ring-1 ring-inset ring-indigo-200' : '']"
                                    @click="choose(year, close)">
                                {{ year }}
                            </button>
                        </div>

                        <button v-if="emptyLabel" type="button"
                                class="mt-2 h-8 w-full rounded-md border-t border-slate-100 text-sm font-medium transition-colors hover:bg-slate-100 focus:outline-none focus-visible:ring-2 focus-visible:ring-indigo-500/30"
                                :class="hasYear ? 'text-slate-600' : 'text-indigo-700'" @click="choose('', close)">
                            {{ emptyLabel }}
                        </button>
                    </PopoverPanel>
                </transition>
            </Portal>
        </Popover>
    </FieldFrame>
</template>

<script setup>
import {computed, ref} from 'vue';
import {Popover, PopoverButton, PopoverPanel, Portal} from '@headlessui/vue';
import {CalendarDaysIcon, ChevronLeftIcon, ChevronRightIcon} from '@heroicons/vue/20/solid';
import FieldFrame from './FieldFrame.vue';
import {controlClasses, useFieldId} from '@/Composables/useFieldId.js';
import {useFloatingPanel} from '@/Composables/useFloatingPanel.js';

defineOptions({inheritAttrs: false});

const CURRENT_YEAR = new Date().getFullYear();
const PAGE_SIZE = 12;

const props = defineProps({
    // The chosen year, or '' for none (empty-label)
    modelValue: {type: [Number, String], default: ''},
    // The first year that can be picked (this year by default)
    minYear: {type: Number, default: () => new Date().getFullYear()},
    // What "no year" means, e.g. "Ready to move"; also the clear button
    emptyLabel: {type: String, default: ''},
    label: {type: String, default: ''},
    hint: {type: String, default: ''},
    error: {type: String, default: ''},
    required: {type: Boolean, default: false},
});
const emit = defineEmits(['update:modelValue']);

const id = useFieldId('year');
const {anchorRef, panelStyle, updatePosition} = useFloatingPanel({minWidth: 288, maxHeight: 260});

const hasYear = computed(() => props.modelValue !== '' && props.modelValue !== null);
const pageStart = ref(props.minYear);
const pageYears = computed(() => Array.from({length: PAGE_SIZE}, (_, i) => pageStart.value + i));

const isChosen = (year) => hasYear.value && Number(props.modelValue) === year;

// Open on the page that holds the chosen year
function onOpen() {
    const year = hasYear.value ? Number(props.modelValue) : props.minYear;
    pageStart.value = props.minYear + Math.max(0, Math.floor((year - props.minYear) / PAGE_SIZE)) * PAGE_SIZE;
    updatePosition();
}

function choose(year, close) {
    emit('update:modelValue', year);
    close();
}
</script>
