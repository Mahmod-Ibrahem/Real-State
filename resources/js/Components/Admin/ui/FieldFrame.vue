<template>
    <!-- Label, hint and error around any control, so every field looks the same -->
    <div>
        <label v-if="label" :for="inputId" class="mb-1.5 block text-sm font-medium text-slate-700">
            {{ label }}<span v-if="required" class="text-red-600" aria-hidden="true"> *</span>
        </label>
        <slot/>
        <p v-if="error" :id="`${inputId}-error`" class="mt-1.5 text-xs text-red-600">{{ error }}</p>
        <p v-else-if="hint" class="mt-1.5 text-xs text-slate-500">{{ hint }}</p>
    </div>
</template>

<script setup>
defineProps({
    inputId: {type: String, required: true},
    label: {type: String, default: ''},
    error: {type: String, default: ''},
    hint: {type: String, default: ''},
    required: {type: Boolean, default: false},
});
</script>
