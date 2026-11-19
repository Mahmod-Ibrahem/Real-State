<template>
    <Link :href="route('favourite-listing.index')"
          :aria-label="`Favourites (${count})`"
          class="relative inline-flex items-center justify-center w-10 h-10 rounded-full bg-sky-400 text-red-600 no-underline transition-colors hover:bg-sky-500">
        <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="currentColor"
             stroke="#ffffff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
            <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/>
        </svg>
        <span class="absolute -top-2 -right-2 flex items-center justify-center min-w-[20px] h-5 px-1 text-xs font-semibold text-white bg-red-500 rounded-full">
            {{ count }}
        </span>
    </Link>
</template>

<script setup>
import {computed} from 'vue';
import {Link, usePage} from '@inertiajs/vue3';

const page = usePage();
const count = computed(() => page.props.favouriteProperty?.length ?? 0);
</script>
