<template>
    <div class="flex items-start gap-3">
        <button :id="id" type="button" role="switch" :aria-checked="Boolean(modelValue)"
                class="relative mt-0.5 inline-flex h-5 w-9 shrink-0 rounded-full transition-colors focus:outline-none focus-visible:ring-2 focus-visible:ring-indigo-500/30"
                :class="modelValue ? 'bg-indigo-600' : 'bg-slate-300'"
                @click="emit('update:modelValue', !modelValue)">
            <span class="absolute left-0.5 top-0.5 h-4 w-4 rounded-full bg-white shadow-sm transition-transform"
                  :class="modelValue ? 'translate-x-4' : 'translate-x-0'"></span>
        </button>
        <label :for="id" class="cursor-pointer select-none">
            <span class="block text-sm font-medium text-slate-700">{{ label }}</span>
            <span v-if="description" class="block text-xs text-slate-500">{{ description }}</span>
        </label>
    </div>
</template>

<script setup>
import {useFieldId} from '@/Composables/useFieldId.js';

defineProps({
    modelValue: {type: [Boolean, Number], default: false},
    label: {type: String, required: true},
    description: {type: String, default: ''},
});
const emit = defineEmits(['update:modelValue']);

const id = useFieldId('toggle');
</script>
