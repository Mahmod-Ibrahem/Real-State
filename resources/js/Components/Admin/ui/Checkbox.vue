<template>
    <!-- Chatwoot checkbox, ported from Mr Egypt Tours' shared/checkbox (ADMIN_UI.md §4) -->
    <label class="inline-flex cursor-pointer select-none items-center gap-2" :class="{'cursor-not-allowed opacity-50': disabled}">
        <span class="relative h-4 w-4 shrink-0">
            <input v-model="model" type="checkbox" :disabled="disabled" :name="name"
                   class="peer absolute inset-0 z-10 h-4 w-4 cursor-pointer appearance-none rounded border bg-none checked:bg-none border-slate-300 bg-white transition-colors checked:border-indigo-600 checked:bg-indigo-600 hover:enabled:border-indigo-400 focus:outline-none focus:ring-0 focus:ring-offset-0 focus-visible:ring-2 focus-visible:ring-indigo-500/30 disabled:cursor-not-allowed"/>
            <svg viewBox="0 0 14 14" fill="none" aria-hidden="true"
                 class="pointer-events-none absolute left-1/2 top-1/2 z-20 h-3.5 w-3.5 -translate-x-1/2 -translate-y-1/2 stroke-white opacity-0 transition-opacity duration-150 peer-checked:opacity-100">
                <path d="M3 8L6 11L11 3.5" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
        </span>
        <slot/>
    </label>
</template>

<script setup>
defineProps({
    disabled: {type: Boolean, default: false},
    name: {type: String, default: undefined},
});

const model = defineModel({type: Boolean, default: false});
</script>
