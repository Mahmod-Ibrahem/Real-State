<template>
    <component :is="href ? Link : 'button'" v-bind="href ? {href} : {type, disabled: disabled || loading}"
               :class="classes">
        <svg v-if="loading" class="h-4 w-4 animate-spin" viewBox="0 0 24 24" fill="none" aria-hidden="true">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"/>
        </svg>
        <component :is="icon" v-else-if="icon" class="h-4 w-4 shrink-0" aria-hidden="true"/>
        <slot/>
    </component>
</template>

<script setup>
import {computed} from 'vue';
import {Link} from '@inertiajs/vue3';

const VARIANTS = {
    primary: 'bg-indigo-600 text-white shadow-sm hover:bg-indigo-700',
    secondary: 'border border-slate-300 bg-white text-slate-700 hover:bg-slate-50',
    ghost: 'text-slate-600 hover:bg-slate-100 hover:text-slate-900',
    danger: 'bg-red-600 text-white shadow-sm hover:bg-red-700',
};
const SIZES = {
    sm: 'h-8 px-3',
    md: 'h-9 px-4',
};

const props = defineProps({
    variant: {type: String, default: 'primary'},
    size: {type: String, default: 'md'},
    // Renders an Inertia Link instead of a <button>
    href: {type: String, default: null},
    type: {type: String, default: 'button'},
    // A heroicon component shown before the label
    icon: {type: [Object, Function], default: null},
    loading: {type: Boolean, default: false},
    disabled: {type: Boolean, default: false},
});

const classes = computed(() => [
    'inline-flex shrink-0 items-center justify-center gap-2 whitespace-nowrap rounded-lg text-sm font-medium transition-colors',
    'focus:outline-none focus-visible:ring-2 focus-visible:ring-indigo-500/30 disabled:cursor-not-allowed disabled:opacity-50',
    VARIANTS[props.variant],
    SIZES[props.size],
]);
</script>
