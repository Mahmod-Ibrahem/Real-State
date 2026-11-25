<template>
    <div ref="root" class="relative">
        <button type="button" :aria-expanded="open" aria-haspopup="menu" @click="open = !open"
                class="inline-flex h-9 items-center gap-2 rounded-lg bg-indigo-600 px-3 text-sm font-semibold text-white shadow-sm transition-colors hover:bg-indigo-700 focus:outline-none focus-visible:ring-2 focus-visible:ring-indigo-500/30">
            <component :is="active.icon" class="h-4 w-4" aria-hidden="true"/>
            <span>{{ active.name }}</span>
            <ChevronDownIcon class="h-4 w-4" aria-hidden="true"/>
        </button>

        <transition enter-active-class="transition duration-100 ease-out" enter-from-class="opacity-0"
                    leave-active-class="transition duration-75 ease-in" leave-to-class="opacity-0">
            <div v-if="open" role="menu"
                 class="absolute end-0 z-40 mt-1.5 w-40 rounded-lg border border-slate-200 bg-white p-1 shadow-lg">
                <button v-for="view in views" :key="view.id" type="button" role="menuitemradio"
                        :aria-checked="view.id === modelValue" @click="choose(view.id)"
                        class="flex h-9 w-full items-center gap-3 rounded-md px-3 text-sm transition-colors hover:bg-slate-100"
                        :class="view.id === modelValue ? 'font-medium text-indigo-700' : 'text-slate-700'">
                    <component :is="view.icon" class="h-4 w-4" aria-hidden="true"/>
                    {{ view.name }}
                </button>
            </div>
        </transition>
    </div>
</template>

<script setup>
import {computed, ref} from 'vue';
import {router} from '@inertiajs/vue3';
import {ChevronDownIcon} from '@heroicons/vue/20/solid';
import {useDismissible} from '@/Composables/useDismissible.js';

const props = defineProps({
    // [{id, name, icon}]
    views: {type: Array, required: true},
    modelValue: {type: String, required: true},
    // Query-string key the view is kept in
    param: {type: String, default: 'view'},
});

const open = ref(false);
const root = ref(null);
useDismissible(root, open);

const active = computed(() => props.views.find((view) => view.id === props.modelValue) ?? props.views[0]);

function choose(id) {
    open.value = false;
    if (id === props.modelValue) return;

    const {page, ...params} = Object.fromEntries(new URLSearchParams(window.location.search));
    router.get(window.location.pathname, {...params, [props.param]: id}, {preserveScroll: true, replace: true});
}
</script>
