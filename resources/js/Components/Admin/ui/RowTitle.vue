<template>
    <!-- A table row's name: opens its edit page (href) or its edit modal (click). ADMIN_UI.md §5 -->
    <Link v-if="href" :href="href" :class="CLASSES" :title="title">
        <slot/>
    </Link>
    <button v-else type="button" :class="[CLASSES, 'text-left']" :title="title" @click="emit('click')">
        <slot/>
    </button>
</template>

<script setup>
import {Link} from '@inertiajs/vue3';

const CLASSES = 'block max-w-full truncate rounded font-medium text-slate-900 transition-colors hover:text-indigo-600 focus:outline-none focus-visible:ring-2 focus-visible:ring-indigo-500/30';

defineProps({
    // Edit page; without it the title is a button that emits `click`
    href: {type: String, default: null},
    title: {type: String, default: 'Edit'},
});
const emit = defineEmits(['click']);
</script>
