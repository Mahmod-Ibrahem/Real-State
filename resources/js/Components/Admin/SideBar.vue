<template>
    <aside class="flex h-full w-60 flex-col bg-white">
        <Link :href="route(nav.home)" class="flex h-14 shrink-0 items-center gap-2.5 border-b border-slate-100 px-4">
            <img :src="logo" alt="Qeima" class="h-8 w-auto object-contain"/>
            <span class="text-xs font-medium uppercase tracking-wider text-slate-400">{{ nav.label }}</span>
        </Link>

        <nav class="flex-1 overflow-y-auto px-2 py-3" aria-label="Main">
            <div v-for="group in nav.groups" :key="group.label" class="mt-4 first:mt-0">
                <p class="mb-1 flex h-7 items-center px-2.5 text-[11px] font-semibold uppercase tracking-wider text-slate-400">
                    {{ group.label }}
                </p>
                <ul class="flex flex-col gap-0.5">
                    <li v-for="item in group.items" :key="item.route">
                        <Link :href="route(item.route)"
                              class="group flex h-9 min-w-0 items-center gap-2.5 rounded-lg px-2.5 text-sm transition-colors focus:outline-none focus-visible:ring-2 focus-visible:ring-indigo-500/30"
                              :class="item === activeItem
                                  ? 'bg-indigo-50 font-semibold text-indigo-700'
                                  : 'text-slate-600 hover:bg-slate-900/5 hover:text-slate-900'"
                              :aria-current="item === activeItem ? 'page' : undefined"
                              @click="emit('navigate')">
                            <component :is="item.icon" class="h-[18px] w-[18px] shrink-0"
                                       :class="item === activeItem ? 'text-indigo-600' : 'text-slate-400 group-hover:text-slate-500'"
                                       aria-hidden="true"/>
                            <span class="truncate">{{ item.label }}</span>
                            <span v-if="badgeOf(item)" class="ms-auto rounded-full bg-indigo-600 px-1.5 text-[11px] font-semibold leading-5 text-white">{{ badgeOf(item) }}</span>
                        </Link>
                    </li>
                </ul>
            </div>
        </nav>

        <!-- The public site is another bundle, so a plain link (full page load) is correct here -->
        <div class="shrink-0 border-t border-slate-100 p-2">
            <a :href="route('home')" target="_blank" rel="noopener"
               class="flex h-9 items-center gap-2.5 rounded-lg px-2.5 text-sm text-slate-600 transition-colors hover:bg-slate-900/5 hover:text-slate-900">
                <ArrowTopRightOnSquareIcon class="h-[18px] w-[18px] shrink-0 text-slate-400" aria-hidden="true"/>
                View site
            </a>
        </div>
    </aside>
</template>

<script setup>
import {computed} from 'vue';
import {Link, usePage} from '@inertiajs/vue3';
import {ArrowTopRightOnSquareIcon} from '@heroicons/vue/24/outline';
import logo from '@/assets/images/logo.jpg';
import {findNavItem} from './navigation.js';

const props = defineProps({
    nav: {type: Object, required: true},
});
const emit = defineEmits(['navigate']);
const page = usePage();

// Recomputed on every visit (page.url changes), so create/edit screens keep their section highlighted.
const activeItem = computed(() => (page.url, findNavItem(props.nav.groups, route().current())?.item));
const badgeOf = (item) => (item.badge ? page.props.auth?.[item.badge] : 0) || 0;
</script>
