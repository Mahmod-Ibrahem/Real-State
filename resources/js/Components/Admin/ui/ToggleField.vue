<template>
    <!-- Boolean switch, ported from Mr Egypt Tours' CustomToggle (ADMIN_UI.md §4) -->
    <SwitchGroup as="div" class="flex items-start gap-3">
        <Switch :model-value="isOn" @update:model-value="emit('update:modelValue', $event)"
                class="relative mt-0.5 inline-flex h-5 w-9 shrink-0 cursor-pointer items-center rounded-full transition-colors focus:outline-none focus-visible:ring-2 focus-visible:ring-indigo-500/40 focus-visible:ring-offset-2"
                :class="isOn ? 'bg-indigo-600' : 'bg-slate-300'">
            <span aria-hidden="true"
                  class="inline-block h-4 w-4 transform rounded-full bg-white shadow-sm transition-transform duration-150"
                  :class="isOn ? 'translate-x-[18px]' : 'translate-x-0.5'"/>
        </Switch>
        <div class="select-none">
            <SwitchLabel class="block cursor-pointer text-sm font-medium text-slate-700">{{ label }}</SwitchLabel>
            <SwitchDescription v-if="description" class="block text-xs text-slate-500">{{ description }}</SwitchDescription>
        </div>
    </SwitchGroup>
</template>

<script setup>
import {computed} from 'vue';
import {Switch, SwitchDescription, SwitchGroup, SwitchLabel} from '@headlessui/vue';

const props = defineProps({
    // The API may send 1/0 as well as true/false
    modelValue: {type: [Boolean, Number, String], default: false},
    label: {type: String, required: true},
    description: {type: String, default: ''},
});
const emit = defineEmits(['update:modelValue']);

const isOn = computed(() => [true, 1, '1', 'true'].includes(props.modelValue));
</script>
