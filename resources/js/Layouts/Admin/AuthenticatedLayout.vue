<template>
    <Head :title="title"/>

    <div class="flex h-screen flex-col overflow-hidden bg-[#F7F7F8]">
        <TopBar :title="title" :nav="nav" @toggle-sidebar="sidebarOpen = !sidebarOpen"/>

        <div class="flex min-h-0 flex-1">
            <!-- Below lg the sidebar is a drawer over the page -->
            <transition enter-active-class="transition-opacity duration-200" enter-from-class="opacity-0"
                        leave-active-class="transition-opacity duration-150" leave-to-class="opacity-0">
                <div v-if="!isDesktop && sidebarOpen" class="fixed inset-0 z-40 bg-slate-900/30"
                     @click="sidebarOpen = false"></div>
            </transition>

            <SideBar class="z-50 shrink-0 transition-[margin,transform] duration-200 ease-out"
                     :class="isDesktop
                         ? ['mb-3 overflow-hidden rounded-xl border border-slate-200', sidebarOpen ? 'ml-3' : '-ml-60']
                         : ['fixed inset-y-0 left-0 shadow-xl', sidebarOpen ? 'translate-x-0' : '-translate-x-full']"
                     :nav="nav" @navigate="onNavigate"/>

            <main :key="$page.props.locale" class="relative min-w-0 flex-1 overflow-y-auto px-4 pb-6 pt-1 md:px-6">
                <header v-if="title" class="mb-4 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                    <div class="min-w-0">
                        <h1 class="truncate text-lg font-semibold text-slate-900">{{ title }}</h1>
                        <p v-if="description" class="text-sm text-slate-500">{{ description }}</p>
                    </div>
                    <div v-if="$slots.actions" class="flex shrink-0 flex-wrap items-center gap-2">
                        <slot name="actions"/>
                    </div>
                </header>

                <slot/>
            </main>
        </div>
    </div>

    <Toast/>
</template>

<script setup>
import {onBeforeUnmount, onMounted, ref, watch} from 'vue';
import {Head, usePage} from '@inertiajs/vue3';
import {showToast} from '@/Composables/useToast.js';
import SideBar from '@/Components/Admin/SideBar.vue';
import TopBar from '@/Components/Admin/TopBar.vue';
import Toast from '@/Components/Admin/ui/Toast.vue';
import {ADMIN_NAV} from '@/Components/Admin/navigation.js';

defineProps({
    // Page title: <title>, breadcrumb fallback and the page header
    title: {type: String, default: ''},
    description: {type: String, default: ''},
    nav: {type: Object, default: () => ADMIN_NAV},
});

const desktopQuery = window.matchMedia('(min-width: 1024px)');
const isDesktop = ref(desktopQuery.matches);
const sidebarOpen = ref(desktopQuery.matches);

// Close the drawer after picking a page on small screens
function onNavigate() {
    if (!isDesktop.value) sidebarOpen.value = false;
}

function onBreakpointChange(event) {
    isDesktop.value = event.matches;
    sidebarOpen.value = event.matches;
}

// Refusals redirected with ->with('error', …) (e.g. a duplicate property title)
const page = usePage();
watch(() => page.props.flash?.error, (message) => message && showToast(message, 'error'), {immediate: true});

onMounted(() => desktopQuery.addEventListener('change', onBreakpointChange));
onBeforeUnmount(() => desktopQuery.removeEventListener('change', onBreakpointChange));
</script>
