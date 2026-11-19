<template>
    <FieldFrame :input-id="id" :label="label" :error="error" :hint="hint" :required="required">
        <label :for="id"
               class="flex cursor-pointer flex-col items-center justify-center gap-1 rounded-xl border border-dashed border-slate-300 bg-slate-50 px-4 py-6 text-center transition-colors hover:border-indigo-400 hover:bg-indigo-50/40 focus-within:ring-2 focus-within:ring-indigo-500/30">
            <ArrowUpTrayIcon class="h-5 w-5 text-slate-400" aria-hidden="true"/>
            <span class="text-sm font-medium text-slate-700">Choose images</span>
            <span class="text-xs text-slate-500">You can pick several at once</span>
            <input :id="id" type="file" accept="image/*" multiple class="sr-only" @change="add"/>
        </label>

        <ul v-if="modelValue.length" class="mt-3 flex flex-wrap gap-2">
            <li v-for="(file, index) in modelValue" :key="previews[index]" class="relative">
                <img :src="previews[index]" :alt="file.name" class="h-20 w-20 rounded-lg border border-slate-200 object-cover"/>
                <button type="button" :aria-label="`Remove ${file.name}`" @click="remove(index)"
                        class="absolute -right-1.5 -top-1.5 grid h-6 w-6 place-content-center rounded-full border border-slate-200 bg-white text-slate-500 shadow-sm hover:text-red-600">
                    <XMarkIcon class="h-3.5 w-3.5" aria-hidden="true"/>
                </button>
            </li>
        </ul>
    </FieldFrame>
</template>

<script setup>
import {onBeforeUnmount, ref} from 'vue';
import {ArrowUpTrayIcon, XMarkIcon} from '@heroicons/vue/24/outline';
import FieldFrame from './FieldFrame.vue';
import {useFieldId} from '@/Composables/useFieldId.js';

const props = defineProps({
    // The files picked so far (File[])
    modelValue: {type: Array, default: () => []},
    label: {type: String, default: 'Images'},
    error: {type: String, default: ''},
    hint: {type: String, default: ''},
    required: {type: Boolean, default: false},
});
const emit = defineEmits(['update:modelValue']);

const id = useFieldId('images');
const previews = ref(props.modelValue.map((file) => URL.createObjectURL(file)));

function add(event) {
    const files = Array.from(event.target.files);
    previews.value.push(...files.map((file) => URL.createObjectURL(file)));
    emit('update:modelValue', [...props.modelValue, ...files]);
    event.target.value = ''; // allow picking the same file again after removing it
}

function remove(index) {
    URL.revokeObjectURL(previews.value[index]);
    previews.value.splice(index, 1);
    emit('update:modelValue', props.modelValue.filter((_, i) => i !== index));
}

onBeforeUnmount(() => previews.value.forEach((url) => URL.revokeObjectURL(url)));
</script>
