<template>
    <div>
        <div role="group" aria-label="Verification code" dir="ltr" class="flex justify-between gap-2">
            <input v-for="(digit, index) in cells" :key="index" :ref="(el) => (boxes[index] = el)"
                   :value="digit" type="text" inputmode="numeric" maxlength="1"
                   :autocomplete="index === 0 ? 'one-time-code' : 'off'" :disabled="disabled"
                   :aria-label="`Digit ${index + 1} of ${length}`" :aria-invalid="Boolean(error)"
                   class="h-11 w-10 rounded-lg border bg-white text-center text-lg font-semibold tabular-nums text-slate-900 transition-colors focus:outline-none focus:ring-2 disabled:bg-slate-50 disabled:opacity-60"
                   :class="error
                       ? 'border-red-400 focus:border-red-500 focus:ring-red-500/15'
                       : 'border-slate-300 focus:border-indigo-500 focus:ring-indigo-500/15'"
                   @input="onInput(index, $event)" @keydown="onKeydown(index, $event)"
                   @paste.prevent="onPaste(index, $event)" @focus="$event.target.select()"/>
        </div>
        <p v-if="error" class="mt-1.5 text-xs text-red-600">{{ error }}</p>
    </div>
</template>

<script setup>
import {ref, watch} from 'vue';

const ARROW_STEPS = {ArrowLeft: -1, ArrowRight: 1};

const props = defineProps({
    length: {type: Number, default: 6},
    error: {type: String, default: ''},
    disabled: {type: Boolean, default: false},
});
const emit = defineEmits(['complete']);
const code = defineModel({type: String, default: ''});

const boxes = [];
const toCells = (value) => Array.from({length: props.length}, (_, index) => value[index] ?? '');
const cells = ref(toCells(code.value));

watch(code, (value) => {
    if (value !== cells.value.join('')) cells.value = toCells(value);
});

function focusBox(index) {
    boxes[Math.max(0, Math.min(index, props.length - 1))]?.focus();
}

function fill(start, typed) {
    typed.slice(0, props.length - start).split('').forEach((digit, offset) => (cells.value[start + offset] = digit));
    code.value = cells.value.join('');
    focusBox(start + typed.length);

    if (cells.value.every(Boolean)) emit('complete', code.value);
}

function onInput(index, event) {
    const typed = event.target.value.replace(/\D/g, '');
    event.target.value = cells.value[index];
    if (typed) fill(index, typed);
}

function onPaste(index, event) {
    const pasted = event.clipboardData.getData('text').replace(/\D/g, '');
    if (pasted) fill(index, pasted);
}

function onKeydown(index, event) {
    if (event.key in ARROW_STEPS) {
        event.preventDefault();
        focusBox(index + ARROW_STEPS[event.key]);
        return;
    }
    if (event.key !== 'Backspace') return;

    event.preventDefault();
    const target = cells.value[index] ? index : index - 1;
    if (target < 0) return;

    cells.value[target] = '';
    code.value = cells.value.join('');
    focusBox(target);
}

defineExpose({focus: () => focusBox(Math.max(0, cells.value.findIndex((digit) => !digit)))});
</script>
