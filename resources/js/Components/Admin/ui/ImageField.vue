<template>
    <FieldFrame :input-id="id" :label="label" :error="error" :hint="hint" :required="required">
        <div class="flex items-center gap-4">
            <div class="grid h-20 w-28 shrink-0 place-content-center overflow-hidden rounded-lg border border-slate-200 bg-slate-50">
                <img v-if="previewUrl" :src="previewUrl" alt="" class="h-full w-full object-cover"/>
                <PhotoIcon v-else class="h-6 w-6 text-slate-300" aria-hidden="true"/>
            </div>
            <div class="min-w-0">
                <label :for="id"
                       class="inline-flex h-9 cursor-pointer items-center rounded-lg border border-slate-300 bg-white px-4 text-sm font-medium text-slate-700 transition-colors hover:bg-slate-50 focus-within:ring-2 focus-within:ring-indigo-500/30">
                    {{ previewUrl ? 'Replace image' : 'Choose image' }}
                    <input :id="id" type="file" :accept="accept" class="sr-only" @change="onChange"/>
                </label>
                <p v-if="fileName" class="mt-1 truncate text-xs text-slate-500">{{ fileName }}</p>
            </div>
        </div>
    </FieldFrame>
</template>

<script setup>
import {computed, onBeforeUnmount, ref} from 'vue';
import {PhotoIcon} from '@heroicons/vue/24/outline';
import FieldFrame from './FieldFrame.vue';
import {useFieldId} from '@/Composables/useFieldId.js';

const props = defineProps({
    // The image already saved (edit forms)
    currentUrl: {type: String, default: ''},
    label: {type: String, default: 'Image'},
    accept: {type: String, default: 'image/*'},
    error: {type: String, default: ''},
    hint: {type: String, default: ''},
    required: {type: Boolean, default: false},
});
const emit = defineEmits(['update:modelValue']);

const id = useFieldId('image');
const localUrl = ref('');
const fileName = ref('');
const previewUrl = computed(() => localUrl.value || props.currentUrl);

function onChange(event) {
    const file = event.target.files[0];
    if (!file) return;
    URL.revokeObjectURL(localUrl.value);
    localUrl.value = URL.createObjectURL(file);
    fileName.value = file.name;
    emit('update:modelValue', file);
}

onBeforeUnmount(() => URL.revokeObjectURL(localUrl.value));
</script>
