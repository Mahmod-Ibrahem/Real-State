<template>
    <!-- Content language for translatable fields; the server keeps it in the session -->
    <div class="flex h-8 items-center rounded-lg border border-slate-200 bg-white p-0.5 text-xs font-medium"
         role="group" aria-label="Content language">
        <button v-for="option in LOCALES" :key="option.value" type="button"
                class="h-full rounded-md px-2.5 transition-colors focus:outline-none focus-visible:ring-2 focus-visible:ring-indigo-500/30"
                :class="option.value === locale ? 'bg-slate-900 text-white' : 'text-slate-500 hover:text-slate-900'"
                :aria-pressed="option.value === locale"
                @click="change(option.value)">
            {{ option.label }}
        </button>
    </div>
</template>

<script setup>
import {computed} from 'vue';
import {router, usePage} from '@inertiajs/vue3';

const LOCALES = [
    {value: 'en', label: 'EN'},
    {value: 'ar', label: 'AR'},
];

const page = usePage();
const locale = computed(() => page.props.locale);

function change(value) {
    if (value === locale.value) return;
    router.post(route('change.locale'), {locale: value}, {preserveScroll: true});
}
</script>
