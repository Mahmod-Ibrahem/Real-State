<template>
    <FieldFrame :input-id="id" :label="label" :error="error" :hint="hint" :required="required">
        <div class="relative">
            <select :id="id" :value="modelValue ?? ''" :disabled="disabled" :required="required" :aria-invalid="Boolean(error)"
                    :class="[controlClasses(error), 'h-10 appearance-none bg-none pr-9']"
                    @change="onChange">
                <option value="" disabled>{{ placeholder }}</option>
                <option v-for="option in options" :key="option[optionValue]" :value="option[optionValue]">
                    {{ option[optionLabel] }}
                </option>
            </select>
            <ChevronUpDownIcon class="pointer-events-none absolute right-3 top-1/2 h-4 w-4 -translate-y-1/2 text-slate-400"
                               aria-hidden="true"/>
        </div>
    </FieldFrame>
</template>

<script setup>
import {ChevronUpDownIcon} from '@heroicons/vue/20/solid';
import FieldFrame from './FieldFrame.vue';
import {controlClasses, useFieldId} from '@/Composables/useFieldId.js';

const props = defineProps({
    modelValue: {type: [String, Number], default: ''},
    // [{id, name}] by default; see optionValue/optionLabel
    options: {type: Array, default: () => []},
    optionValue: {type: String, default: 'id'},
    optionLabel: {type: String, default: 'name'},
    label: {type: String, default: ''},
    placeholder: {type: String, default: 'Select…'},
    error: {type: String, default: ''},
    hint: {type: String, default: ''},
    required: {type: Boolean, default: false},
    disabled: {type: Boolean, default: false},
});
const emit = defineEmits(['update:modelValue']);

const id = useFieldId('select');

// <select> values are strings; hand back the option's original value (numeric ids stay numbers)
function onChange(event) {
    const option = props.options.find((item) => String(item[props.optionValue]) === event.target.value);
    emit('update:modelValue', option ? option[props.optionValue] : event.target.value);
}
</script>
