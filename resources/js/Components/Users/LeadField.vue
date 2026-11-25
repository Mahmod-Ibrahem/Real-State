<template>
    <div>
        <textarea v-if="type === 'textarea'" v-model="value" rows="3" :placeholder="placeholder" :aria-label="placeholder"
                  :aria-invalid="Boolean(error)" :class="[CLASSES, error ? 'border-red-400' : 'border-slate-300']"></textarea>
        <input v-else v-model="value" :type="type" :placeholder="placeholder" :aria-label="placeholder" :autocomplete="autocomplete"
               :aria-invalid="Boolean(error)" :class="[CLASSES, 'h-11', error ? 'border-red-400' : 'border-slate-300']"/>
        <p v-if="error" class="m-0 mt-1 text-xs text-red-600">{{ error }}</p>
    </div>
</template>

<script setup>
const CLASSES = 'block w-full rounded-lg border-[1px] bg-white px-[12px] py-2 text-sm text-slate-900 placeholder:text-slate-400 focus:border-sky-400 focus:outline-none focus:ring-2 focus:ring-sky-400/20';

defineProps({
    type: {type: String, default: 'text'},
    placeholder: {type: String, required: true},
    autocomplete: {type: String, default: 'off'},
    error: {type: String, default: ''},
});
const value = defineModel({type: String, default: ''});
</script>
