<template>
    <header class="relative md:absolute top-0 left-0 z-50 w-full bg-white md:bg-transparent border-b border-gray-300 md:border-none">
        <nav class="flex items-center justify-between px-6 md:px-20 py-1 md:pt-[12px] md:pb-2">
            <Link :href="route('home')" class="shrink-0">
                <img :src="logo" alt="Qeima logo" width="140" height="72"
                     class="h-16 md:h-20 w-auto object-cover"/>
            </Link>

            <!-- Desktop menu -->
            <ul class="hidden md:flex items-center gap-7 m-0 p-0 list-none">
                <li v-for="item in NAV_ITEMS" :key="item.name">
                    <Link :href="route(item.name)"
                          class="inline-block text-[17px] font-semibold tracking-wide capitalize no-underline transition duration-200 hover:scale-110"
                          :class="linkColor(item.name)"
                          :aria-current="isActive(item.name) ? 'page' : undefined">
                        {{ item.text }}
                    </Link>
                </li>
            </ul>

            <div class="flex items-center gap-2">
                <a v-for="link in accountLinks" :key="link.text" :href="link.href"
                   class="hidden md:inline-flex items-center h-10 px-[16px] text-[15px] font-semibold no-underline rounded-full transition-colors"
                   :class="link.primary ? primaryButton : `text-black ${hoverColor}`">
                    {{ link.text }}
                </a>
                <FavouritesLink/>
                <HamburgerButton class="md:hidden" :open="isMenuOpen" @toggle="isMenuOpen = !isMenuOpen"/>
            </div>
        </nav>

        <!-- Mobile menu -->
        <ul v-if="isMenuOpen" id="mobile-menu"
            class="md:hidden absolute top-full left-0 w-full m-0 px-6 py-2 list-none bg-white shadow-[0_8px_16px_rgba(0,0,0,0.08)]">
            <li v-for="item in NAV_ITEMS" :key="item.name" class="border-b border-gray-300 last:border-b-0">
                <Link :href="route(item.name)"
                      class="block py-[12px] font-bold tracking-wide no-underline"
                      :class="linkColor(item.name)"
                      @click="isMenuOpen = false">
                    {{ item.text }}
                </Link>
            </li>
            <li v-for="link in accountLinks" :key="link.text" class="border-b border-gray-300 last:border-b-0">
                <a :href="link.href" class="block py-[12px] font-bold tracking-wide no-underline"
                   :class="link.primary ? accentColor : `text-black ${hoverColor}`">
                    {{ link.text }}
                </a>
            </li>
        </ul>
    </header>
</template>

<script setup>
import {computed, ref} from 'vue';
import {Link, usePage} from '@inertiajs/vue3';
import {can} from '@/Composables/usePermissions.js';
import logo from '@/assets/images/logo.jpg';
import {route} from '../../../../../vendor/tightenco/ziggy';
import FavouritesLink from './FavouritesLink.vue';
import HamburgerButton from './HamburgerButton.vue';

// Buy / Rent live inside Listing as a Sale / Rent filter (/buy and /rent redirect there).
const NAV_ITEMS = [
    {name: 'home', text: 'Home'},
    {name: 'listing.index', text: 'Listing'},
    {name: 'sell.index', text: 'Sell'},
    {name: 'contact.index', text: 'Contact Us'},
    {name: 'features.index', text: 'Features'},
    {name: 'about.index', text: 'About Us'},
];

const props = defineProps({
    // Pages with a light hero use red accents instead of sky.
    black: {type: Boolean, default: false},
});

const VIEW_DASHBOARD = 'view dashboard';

const page = usePage();
const isMenuOpen = ref(false);

// Plain <a> links: login, register and the dashboards use the dashboard bundle, so they need a full page load
const accountLinks = computed(() => page.props.auth?.user
    ? [{text: 'Dashboard', href: route(can(VIEW_DASHBOARD) ? 'admin.dashboard' : 'supplier.dashboard'), primary: true}]
    : [
        {text: 'Login', href: route('supplier.login'), primary: false},
        {text: 'Register', href: route('supplier.register'), primary: true},
    ]);

const accentColor = computed(() => (props.black ? 'text-red-600' : 'text-sky-600'));
const hoverColor = computed(() => (props.black ? 'hover:text-red-600' : 'hover:text-sky-600'));
const primaryButton = computed(() => (props.black
    ? 'bg-red-600 text-white hover:bg-red-700'
    : 'bg-sky-600 text-white hover:bg-sky-700'));

/** 'listing.index' is active on every 'listing.*' route (index, show, ...). */
const isActive = (name) => route().current(`${name.replace(/\.index$/, '')}*`);

const linkColor = (name) => (isActive(name) ? accentColor.value : `text-black ${hoverColor.value}`);
</script>
