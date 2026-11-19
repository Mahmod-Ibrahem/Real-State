<template>
    <FieldFrame :input-id="id" :label="label" :error="error" :hint="hint" :required="required" :class="$attrs.class">
        <textarea v-if="type === 'textarea'" :id="id" v-bind="controlAttrs" :value="modelValue" :rows="rows"
                  :required="required" :placeholder="placeholder" :aria-invalid="Boolean(error)"
                  :class="[controlClasses(error), 'py-2 leading-relaxed']"
                  @input="emit('update:modelValue', $event.target.value)"></textarea>
        <input v-else :id="id" v-bind="controlAttrs" :type="type" :value="modelValue"
               :required="required" :placeholder="placeholder" :aria-invalid="Boolean(error)"
               :class="[controlClasses(error), 'h-10']"
               @input="emit('update:modelValue', $event.target.value)"/>
    </FieldFrame>
</template>

<script setup>
import {computed, useAttrs} from 'vue';
import FieldFrame from './FieldFrame.vue';
import {controlClasses, useFieldId} from '@/Composables/useFieldId.js';

// Layout classes (e.g. sm:col-span-2) go on the field; everything else (min, step, autofocus…) on the control
defineOptions({inheritAttrs: false});

defineProps({
    modelValue: {type: [String, Number], default: ''},
    label: {type: String, default: ''},
    type: {type: String, default: 'text'},
    placeholder: {type: String, default: ''},
    error: {type: String, default: ''},
    hint: {type: String, default: ''},
    required: {type: Boolean, default: false},
    rows: {type: Number, default: 4},
});
const emit = defineEmits(['update:modelValue']);

const attrs = useAttrs();
const controlAttrs = computed(() => {
    const {class: _layout, ...rest} = attrs;
    return rest;
});

const id = useFieldId('text');
</script>
