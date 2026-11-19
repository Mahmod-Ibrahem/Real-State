<template>
    <FieldFrame :input-id="id" :label="label" :error="error" :hint="hint" :required="required">
        <!-- Clicking opens the system file browser; the crop dialog follows -->
        <label :for="id" :style="{aspectRatio}"
               :class="['group relative flex w-full max-w-md cursor-pointer flex-col items-center justify-center overflow-hidden rounded-xl border-2 border-dashed transition-colors focus-within:ring-2 focus-within:ring-indigo-500/30',
                        displayUrl ? 'border-transparent' : error
                            ? 'border-red-300 bg-red-50/40 hover:border-red-400'
                            : 'border-slate-300 hover:border-indigo-400 hover:bg-indigo-50/50']">
            <template v-if="displayUrl">
                <img :src="displayUrl" :alt="label" class="h-full w-full object-cover"/>
                <span class="absolute inset-0 flex items-center justify-center bg-slate-900/0 text-sm font-medium text-white opacity-0 transition group-hover:bg-slate-900/45 group-hover:opacity-100">
                    Change image
                </span>
            </template>
            <span v-else class="p-6 text-center">
                <PhotoIcon class="mx-auto h-10 w-10 text-slate-300 transition-colors group-hover:text-indigo-400" aria-hidden="true"/>
                <span class="mt-2 block text-sm font-medium text-slate-700">Click to upload</span>
                <span class="block text-xs text-slate-400">You can crop it next</span>
            </span>
            <input :id="id" type="file" accept="image/*" class="sr-only" @change="onPick"/>
        </label>

        <button v-if="committedOriginal" type="button" @click="reopen"
                class="mt-2 inline-flex items-center gap-1.5 text-xs font-medium text-indigo-600 transition-colors hover:text-indigo-800">
            <ScissorsIcon class="h-3.5 w-3.5" aria-hidden="true"/>
            Adjust crop
        </button>

        <ImageCropDialog :open="cropOpen" :src="draftOriginal" :aspect-ratio="aspectRatio" :max-width="maxWidth"
                         :title="`Crop ${label.toLowerCase()}`" @confirm="applyCrop" @cancel="cancelCrop"/>
    </FieldFrame>
</template>

<script setup>
import {computed, onBeforeUnmount, ref, watch} from 'vue';
import {PhotoIcon, ScissorsIcon} from '@heroicons/vue/24/outline';
import FieldFrame from './FieldFrame.vue';
import ImageCropDialog from './ImageCropDialog.vue';
import {useFieldId} from '@/Composables/useFieldId.js';

const props = defineProps({
    // The cropped File, or null while the saved image is kept
    modelValue: {default: null},
    // URL of the image already saved (edit forms)
    currentUrl: {type: String, default: ''},
    // Width / height of the frame: 4/3 for covers, 1 for portraits
    aspectRatio: {type: Number, default: 4 / 3},
    maxWidth: {type: Number, default: 1600},
    label: {type: String, default: 'Image'},
    error: {type: String, default: ''},
    hint: {type: String, default: ''},
    required: {type: Boolean, default: false},
});
const emit = defineEmits(['update:modelValue']);

const id = useFieldId('image');

const croppedUrl = ref('');        // result shown in the box
const committedOriginal = ref(''); // photo behind croppedUrl, kept so the crop can be adjusted
const draftOriginal = ref('');     // photo in the crop dialog
const cropOpen = ref(false);
let draftName = 'image';
let committedName = 'image';

const displayUrl = computed(() => croppedUrl.value || props.currentUrl);

function onPick(event) {
    const file = event.target.files?.[0];
    event.target.value = ''; // picking the same file again still fires change
    if (!file) return;
    draftOriginal.value = URL.createObjectURL(file);
    draftName = file.name.replace(/\.[^.]+$/, '') || 'image';
    cropOpen.value = true;
}

function reopen() {
    draftOriginal.value = committedOriginal.value;
    draftName = committedName;
    cropOpen.value = true;
}

function cancelCrop() {
    cropOpen.value = false;
    if (draftOriginal.value !== committedOriginal.value) URL.revokeObjectURL(draftOriginal.value);
}

function applyCrop(blob) {
    URL.revokeObjectURL(croppedUrl.value);
    if (committedOriginal.value && committedOriginal.value !== draftOriginal.value) {
        URL.revokeObjectURL(committedOriginal.value);
    }
    committedOriginal.value = draftOriginal.value;
    committedName = draftName;
    croppedUrl.value = URL.createObjectURL(blob);
    cropOpen.value = false;
    emit('update:modelValue', new File([blob], `${draftName}.jpg`, {type: 'image/jpeg'}));
}

function clearCrop() {
    URL.revokeObjectURL(croppedUrl.value);
    URL.revokeObjectURL(committedOriginal.value);
    croppedUrl.value = '';
    committedOriginal.value = '';
}

// The parent cleared the value (form reset, modal reopened for another record): drop the local crop too
watch(() => props.modelValue, (file) => file || clearCrop());

onBeforeUnmount(clearCrop);
</script>
