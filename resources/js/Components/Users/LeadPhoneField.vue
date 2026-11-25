<template>
    <div>
        <div class="flex gap-2">
            <Listbox v-model="country" as="div" class="relative w-[8.5rem] shrink-0">
                <ListboxButton aria-label="Country code" :aria-invalid="Boolean(countryError)" @click="onOpen" @keydown="onOpen"
                               :class="[CONTROL, 'flex items-center gap-2 text-start', countryError ? 'border-red-400' : 'border-slate-300']">
                    <span class="min-w-0 flex-1 truncate">{{ codeLabel }}</span>
                    <ChevronUpDownIcon class="h-4 w-4 shrink-0 text-slate-400" aria-hidden="true"/>
                </ListboxButton>

                <ListboxOptions
                    class="absolute start-0 z-20 m-0 mt-1 max-h-64 w-64 list-none overflow-auto rounded-lg border-[1px] border-slate-200 bg-white p-1 text-sm shadow-[0_10px_25px_rgba(15,23,42,0.15)] focus:outline-none">
                    <li class="sticky -top-1 z-10 -mx-1 -mt-1 mb-1 border-b-[1px] border-slate-100 bg-white p-[6px]">
                        <div class="relative">
                            <MagnifyingGlassIcon class="pointer-events-none absolute start-2 top-1/2 h-4 w-4 -translate-y-1/2 text-slate-400"
                                                 aria-hidden="true"/>
                            <input ref="searchRef" v-model="query" type="text" placeholder="Search countries…"
                                   aria-label="Search countries" @keydown="onSearchKeydown"
                                   class="block h-9 w-full rounded-md border-[1px] border-slate-200 bg-white pe-2 ps-8 text-sm text-slate-900 placeholder:text-slate-400 focus:border-sky-400 focus:outline-none focus:ring-2 focus:ring-sky-400/20"/>
                        </div>
                    </li>
                    <li v-if="!visibleCountries.length" class="px-2 py-2 text-slate-400">No matches for “{{ query }}”</li>
                    <ListboxOption v-for="option in visibleCountries" :key="option.id" :value="option.id" as="template"
                                   v-slot="{active, selected}">
                        <li class="flex cursor-pointer select-none items-center gap-2 rounded-md px-2 py-[6px]"
                            :class="active ? 'bg-slate-100 text-slate-900' : 'text-slate-700'">
                            <span class="min-w-0 flex-1 truncate" :class="{'font-medium text-slate-900': selected}">{{ option.name }}</span>
                            <CheckIcon v-if="selected" class="h-4 w-4 shrink-0 text-sky-500" aria-hidden="true"/>
                        </li>
                    </ListboxOption>
                </ListboxOptions>
            </Listbox>

            <input v-model="phone" type="tel" inputmode="tel" autocomplete="tel-national" aria-label="Phone number"
                   :placeholder="examplePhone(country)" :aria-invalid="Boolean(error)" @blur="phone = toLocalPhone(phone, country)"
                   :class="[CONTROL, 'min-w-0 flex-1', error ? 'border-red-400' : 'border-slate-300']"/>
        </div>
        <p v-if="countryError || error" class="m-0 mt-1 text-xs text-red-600">{{ countryError || error }}</p>
    </div>
</template>

<script setup>
import {computed, ref} from 'vue';
import {Listbox, ListboxButton, ListboxOption, ListboxOptions} from '@headlessui/vue';
import {CheckIcon, ChevronUpDownIcon, MagnifyingGlassIcon} from '@heroicons/vue/20/solid';
import {PHONE_COUNTRIES, examplePhone, toLocalPhone} from '@/Composables/phoneCountries';

const CONTROL = 'block h-11 w-full rounded-lg border-[1px] bg-white px-[12px] text-sm text-slate-900 placeholder:text-slate-400 focus:border-sky-400 focus:outline-none focus:ring-2 focus:ring-sky-400/20';

defineProps({
    error: {type: String, default: ''},
    countryError: {type: String, default: ''},
});
// The number as typed and its ISO country; the server turns them into E.164
const phone = defineModel({type: String, default: ''});
const country = defineModel('country', {type: String, default: 'EG'});

const query = ref('');
const searchRef = ref(null);
const visibleCountries = computed(() => {
    const term = query.value.trim().toLowerCase();
    return term ? PHONE_COUNTRIES.filter((option) => option.name.toLowerCase().includes(term)) : PHONE_COUNTRIES;
});

const codeLabel = computed(() => {
    const option = PHONE_COUNTRIES.find(({id}) => id === country.value);
    return option ? `${option.id} ${option.code}` : 'Code';
});

function onOpen() {
    query.value = '';
    // Headless UI focuses the list when it opens; move focus into the search box after that
    setTimeout(() => searchRef.value?.focus(), 0);
}

// Arrows, Enter, Escape and Tab still drive the list; other keys stay in the search box
function onSearchKeydown(event) {
    if (!['ArrowDown', 'ArrowUp', 'Enter', 'Escape', 'Tab'].includes(event.key)) event.stopPropagation();
}
</script>
