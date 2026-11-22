<template>
    <header class="flex h-14 shrink-0 items-center justify-between gap-3 px-3">
        <div class="flex min-w-0 items-center gap-1.5">
            <button type="button" aria-label="Toggle sidebar" @click="emit('toggle-sidebar')"
                    class="grid h-9 w-9 shrink-0 place-content-center rounded-lg text-slate-500 transition-colors hover:bg-slate-900/5 hover:text-slate-900 focus:outline-none focus-visible:ring-2 focus-visible:ring-indigo-500/30">
                <Bars3Icon class="h-5 w-5" aria-hidden="true"/>
            </button>

            <nav aria-label="Breadcrumb" class="ml-2 flex min-w-0 items-center gap-1.5 border-l border-slate-200 pl-3 text-sm">
                <template v-if="section">
                    <span class="hidden text-slate-500 md:inline">{{ section }}</span>
                    <ChevronRightIcon class="hidden h-3.5 w-3.5 shrink-0 text-slate-300 md:block" aria-hidden="true"/>
                </template>
                <span class="truncate font-medium text-slate-900">{{ crumb }}</span>
            </nav>
        </div>

        <div class="flex shrink-0 items-center gap-1.5">
            <NotificationBell v-if="can(RECEIVE_NOTIFICATIONS)"/>
            <LanguageSwitch/>
            <ProfileMenu :profile-route="nav.profileRoute"/>
        </div>
    </header>
</template>

<script setup>
import {computed} from 'vue';
import {usePage} from '@inertiajs/vue3';
import {Bars3Icon, ChevronRightIcon} from '@heroicons/vue/24/outline';
import LanguageSwitch from './LanguageSwitch.vue';
import NotificationBell from './NotificationBell.vue';
import ProfileMenu from './ProfileMenu.vue';
import {actionOf, findNavItem} from './navigation.js';
import {can} from '@/Composables/usePermissions.js';

const RECEIVE_NOTIFICATIONS = 'receive notifications';

const props = defineProps({
    // Fallback when the page isn't in the navigation (e.g. Profile)
    title: {type: String, default: ''},
    nav: {type: Object, required: true},
});
const emit = defineEmits(['toggle-sidebar']);
const page = usePage();

const routeName = computed(() => (page.url, route().current()));
const navEntry = computed(() => findNavItem(props.nav.groups, routeName.value));

// "Listings › Properties · Edit"; the group is skipped when it would repeat the page (Overview › Dashboard is fine).
const section = computed(() => navEntry.value?.group.label ?? '');
const crumb = computed(() => {
    const label = navEntry.value?.item.label ?? props.title;
    const action = actionOf(routeName.value);
    return action ? `${label} · ${action}` : label;
});
</script>
