<template>
    <div ref="root" class="relative">
        <button type="button" :aria-expanded="open" aria-haspopup="menu" @click="open = !open"
                class="flex h-9 items-center gap-2 rounded-lg pl-1 pr-2 transition-colors hover:bg-slate-900/5 focus:outline-none focus-visible:ring-2 focus-visible:ring-indigo-500/30">
            <span class="grid h-7 w-7 place-content-center rounded-full bg-indigo-50 text-xs font-semibold text-indigo-700"
                  aria-hidden="true">{{ initials }}</span>
            <span class="hidden max-w-[10rem] truncate text-sm font-medium text-slate-700 sm:block">{{ user.name }}</span>
            <ChevronDownIcon class="h-4 w-4 text-slate-400" aria-hidden="true"/>
        </button>

        <transition enter-active-class="transition duration-100 ease-out" enter-from-class="opacity-0"
                    leave-active-class="transition duration-75 ease-in" leave-to-class="opacity-0">
            <div v-if="open" role="menu"
                 class="absolute right-0 z-50 mt-1.5 w-56 rounded-lg border border-slate-200 bg-white p-1 shadow-lg">
                <div class="border-b border-slate-100 px-2.5 pb-2 pt-1.5">
                    <p class="truncate text-sm font-medium text-slate-900">{{ user.name }}</p>
                    <p class="truncate text-xs text-slate-500">{{ user.email }}</p>
                </div>
                <Link v-if="profileRoute" :href="route(profileRoute)" role="menuitem" @click="open = false"
                      class="mt-1 flex h-8 items-center gap-2 rounded-md px-2.5 text-sm text-slate-700 hover:bg-slate-100">
                    <UserCircleIcon class="h-4 w-4 text-slate-400" aria-hidden="true"/> Profile
                </Link>
                <Link :href="route('logout')" method="post" as="button" role="menuitem"
                      class="flex h-8 w-full items-center gap-2 rounded-md px-2.5 text-sm text-slate-700 hover:bg-slate-100">
                    <ArrowRightStartOnRectangleIcon class="h-4 w-4 text-slate-400" aria-hidden="true"/> Log out
                </Link>
            </div>
        </transition>
    </div>
</template>

<script setup>
import {computed, ref} from 'vue';
import {Link, usePage} from '@inertiajs/vue3';
import {ArrowRightStartOnRectangleIcon, ChevronDownIcon, UserCircleIcon} from '@heroicons/vue/24/outline';
import {useDismissible} from '@/Composables/useDismissible.js';

defineProps({
    profileRoute: {type: String, default: null},
});

const page = usePage();
const user = computed(() => page.props.auth.user);
const initials = computed(() =>
    user.value.name.split(' ').filter(Boolean).slice(0, 2).map((word) => word[0].toUpperCase()).join('')
);

const open = ref(false);
const root = ref(null);
useDismissible(root, open);
</script>
