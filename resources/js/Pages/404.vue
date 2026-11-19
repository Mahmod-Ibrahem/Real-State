<template>
    <!-- Renders in both bundles (site + /admin), so Tailwind only and no Bootstrap-colliding utilities -->
    <section class="flex min-h-screen flex-col items-center justify-between bg-slate-50 px-6 py-10 text-center">
        <span aria-hidden="true"></span>

        <div class="flex max-w-md flex-col items-center">
            <img src="/assets/images/error.png" class="h-auto max-w-full" alt="">
            <h1 class="mt-10 text-2xl font-semibold text-slate-900">Page not found</h1>
            <p class="mt-2 text-slate-500">The page you were looking for doesn't exist or has been moved.</p>
            <Link :href="route('home')"
                  class="mt-8 inline-flex h-10 items-center rounded-lg bg-indigo-600 px-6 text-sm font-medium text-white no-underline hover:bg-indigo-700 focus:outline-none focus-visible:ring-2 focus-visible:ring-indigo-500 focus-visible:ring-offset-2">
                Back to home
            </Link>
        </div>

        <p class="m-0 text-sm text-slate-400">© {{ year }} Qeima</p>
    </section>
</template>

<script setup>
import {Link} from '@inertiajs/vue3';

const year = new Date().getFullYear();
</script>
