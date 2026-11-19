<template>
    <!-- Rich text on Quill 2, used directly (no PrimeVue). Restyled in admin.css; ADMIN_UI.md §4 -->
    <FieldFrame :input-id="id" :label="label" :error="error" :hint="hint" :required="required" :class="$attrs.class">
        <div :class="['rich-text rounded-lg border bg-white transition-colors focus-within:ring-2',
                      error ? 'border-red-400 focus-within:border-red-500 focus-within:ring-red-500/15'
                            : 'border-slate-300 hover:border-slate-400 focus-within:border-indigo-500 focus-within:ring-indigo-500/15']">
            <div ref="editorRef"></div>
        </div>
    </FieldFrame>
</template>

<script setup>
import {onBeforeUnmount, onMounted, ref, watch} from 'vue';
import Quill from 'quill';
import 'quill/dist/quill.snow.css';
import FieldFrame from './FieldFrame.vue';
import {useFieldId} from '@/Composables/useFieldId.js';

defineOptions({inheritAttrs: false});

const props = defineProps({
    // HTML
    modelValue: {type: String, default: ''},
    label: {type: String, default: ''},
    placeholder: {type: String, default: ''},
    error: {type: String, default: ''},
    hint: {type: String, default: ''},
    required: {type: Boolean, default: false},
});
const emit = defineEmits(['update:modelValue']);

const TOOLBAR = [
    [{header: [2, 3, false]}],
    ['bold', 'italic', 'underline'],
    [{list: 'ordered'}, {list: 'bullet'}],
    ['link'],
    ['clean'],
];

const id = useFieldId('rich-text');
const editorRef = ref(null);
let quill = null;
// The HTML this editor last emitted, so our own updates don't get written back into it
let lastEmitted = null;

const setHtml = (html) => quill.setContents(quill.clipboard.convert({html: html ?? ''}), 'silent');

onMounted(() => {
    quill = new Quill(editorRef.value, {theme: 'snow', placeholder: props.placeholder, modules: {toolbar: TOOLBAR}});
    quill.root.id = id;
    if (props.label) quill.root.setAttribute('aria-label', props.label);
    setHtml(props.modelValue);

    quill.on('text-change', () => {
        // An emptied editor still holds "<p><br></p>"; send '' so "required" validation works
        lastEmitted = quill.getText().trim() ? quill.root.innerHTML : '';
        emit('update:modelValue', lastEmitted);
    });
});

watch(() => props.modelValue, (value) => {
    if (quill && value !== lastEmitted) setHtml(value);
});

watch(() => props.error, (error) => quill?.root.setAttribute('aria-invalid', String(Boolean(error))));

onBeforeUnmount(() => {
    quill?.off('text-change');
    quill = null;
});
</script>
