<template>
    <Head :title="title"/>

    <div class="flex min-h-screen flex-col items-center justify-center bg-[#F7F7F8] px-4 py-12">
        <div class="mb-6 flex items-center gap-2.5">
            <img :src="logo" alt="Qeima" class="h-12 w-auto object-contain"/>
        </div>

        <div class="w-full max-w-sm rounded-xl border border-slate-200 bg-white p-6">
            <h1 class="text-lg font-semibold text-slate-900">{{ title }}</h1>
            <p v-if="description" class="mt-1 text-sm text-slate-500">{{ description }}</p>

            <p v-if="status" class="mt-4 rounded-lg bg-green-50 px-3 py-2 text-sm text-green-700">{{ status }}</p>

            <div class="mt-5">
                <slot/>
            </div>
        </div>

        <div v-if="$slots.footer" class="mt-4 text-sm text-slate-500">
            <slot name="footer"/>
        </div>
    </div>
</template>

<script setup>
import {Head} from '@inertiajs/vue3';
import logo from '@/assets/images/logo.jpg';

defineProps({
    title: {type: String, required: true},
    description: {type: String, default: ''},
    // Server status message (e.g. "We have emailed your password reset link")
    status: {type: String, default: ''},
});
</script>
