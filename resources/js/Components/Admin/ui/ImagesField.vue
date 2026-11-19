<template>
    <FieldFrame :input-id="id" :label="label" :error="error" :hint="hint" :required="required">
        <ul class="grid grid-cols-2 gap-3 sm:grid-cols-4 lg:grid-cols-5 xl:grid-cols-6">
            <!-- Already saved -->
            <li v-for="image in saved" :key="`saved-${image.id}`" :class="[TILE, 'border border-slate-200 bg-slate-100']">
                <button type="button" aria-label="Preview photo" :class="PREVIEW_BUTTON" @click="preview = image.url">
                    <img :src="image.url" alt="" loading="lazy" class="h-full w-full object-cover"/>
                </button>
                <button v-if="!disabled" type="button" aria-label="Remove photo" :class="CORNER_BUTTON"
                        @click="emit('remove', image)">
                    <TrashIcon class="h-4 w-4" aria-hidden="true"/>
                </button>
            </li>

            <!-- Cropped, saved with the form -->
            <li v-for="(file, index) in modelValue" :key="previewOf(file)" title="New photo, saved with the form"
                :class="[TILE, 'border-2 border-dashed border-indigo-300']">
                <button type="button" aria-label="Preview photo" :class="PREVIEW_BUTTON" @click="preview = previewOf(file)">
                    <img :src="previewOf(file)" alt="" class="h-full w-full object-cover"/>
                </button>
                <span class="pointer-events-none absolute inset-x-0 bottom-0 bg-indigo-600/85 py-0.5 text-center text-[10px] font-medium text-white">
                    New
                </span>
                <button v-if="!disabled" type="button" aria-label="Remove photo" :class="CORNER_BUTTON" @click="removeNew(index)">
                    <XMarkIcon class="h-4 w-4" aria-hidden="true"/>
                </button>
            </li>

            <!-- Add: opens the system file browser, then one crop step per photo -->
            <li>
                <label :for="id"
                       :class="['flex aspect-[4/3] flex-col items-center justify-center gap-1 rounded-lg border-2 border-dashed text-center transition-colors focus-within:ring-2 focus-within:ring-indigo-500/30',
                                disabled ? 'pointer-events-none border-slate-200 opacity-50'
                                    : error ? 'cursor-pointer border-red-300 bg-red-50/40 hover:border-red-400'
                                        : 'cursor-pointer border-slate-300 hover:border-indigo-400 hover:bg-indigo-50/50']">
                    <PlusIcon class="h-5 w-5 text-slate-400" aria-hidden="true"/>
                    <span class="text-xs font-medium text-slate-600">Add photos</span>
                    <input :id="id" type="file" accept="image/*" multiple class="sr-only" :disabled="disabled" @change="onPick"/>
                </label>
            </li>
        </ul>

        <ImageCropDialog :open="cropQueue.length > 0" :src="cropQueue[0]?.url ?? ''" :aspect-ratio="aspectRatio"
                         :title="cropTitle" :step="cropTotal > 1 ? `${cropTotal - cropQueue.length + 1} of ${cropTotal}` : ''"
                         :confirm-label="cropQueue.length > 1 ? 'Crop & next' : 'Crop & add'"
                         :cancel-label="cropQueue.length > 1 ? 'Skip' : 'Cancel'" :show-cancel-all="cropQueue.length > 1"
                         @confirm="acceptCrop" @cancel="nextCrop" @cancel-all="cancelAllCrops"/>

        <ImageLightbox :src="preview" @close="preview = ''"/>
    </FieldFrame>
</template>

<script setup>
import {onBeforeUnmount, ref, watch} from 'vue';
import {PlusIcon, TrashIcon, XMarkIcon} from '@heroicons/vue/24/outline';
import FieldFrame from './FieldFrame.vue';
import ImageCropDialog from './ImageCropDialog.vue';
import ImageLightbox from './ImageLightbox.vue';
import {useFieldId} from '@/Composables/useFieldId.js';

const TILE = 'group relative aspect-[4/3] overflow-hidden rounded-lg';
const PREVIEW_BUTTON = 'absolute inset-0 h-full w-full focus:outline-none focus-visible:ring-2 focus-visible:ring-inset focus-visible:ring-indigo-500';
const CORNER_BUTTON = 'absolute right-1.5 top-1.5 grid h-7 w-7 place-content-center rounded-md bg-white/95 text-slate-600 shadow-sm ring-1 ring-slate-200 transition hover:text-red-600 focus:opacity-100 sm:opacity-0 sm:group-hover:opacity-100';

const props = defineProps({
    // Cropped photos not saved yet (File[])
    modelValue: {type: Array, default: () => []},
    // Photos already saved, shown first: [{id, url}]
    saved: {type: Array, default: () => []},
    // While the form is saving: no adding or removing
    disabled: {type: Boolean, default: false},
    aspectRatio: {type: Number, default: 4 / 3},
    label: {type: String, default: 'Photos'},
    cropTitle: {type: String, default: 'Crop photo'},
    error: {type: String, default: ''},
    hint: {type: String, default: ''},
    required: {type: Boolean, default: false},
});
const emit = defineEmits(['update:modelValue', 'remove']);

const id = useFieldId('images');
const preview = ref('');

// ---------------------------------------------------------------- previews of new files
const previews = new Map(); // File → object URL
function previewOf(file) {
    if (!previews.has(file)) previews.set(file, URL.createObjectURL(file));
    return previews.get(file);
}

// Files leave the list when removed or saved: free their previews
watch(() => props.modelValue, (files) => {
    for (const [file, url] of previews) {
        if (!files.includes(file)) {
            URL.revokeObjectURL(url);
            previews.delete(file);
        }
    }
});

function removeNew(index) {
    emit('update:modelValue', props.modelValue.filter((_, i) => i !== index));
}

// ---------------------------------------------------------------- crop queue
const cropQueue = ref([]); // [{url, name}] picked but not cropped yet
const cropTotal = ref(0);

function onPick(event) {
    const files = Array.from(event.target.files ?? []);
    event.target.value = ''; // picking the same files again still fires change
    if (!files.length) return;
    cropQueue.value.push(...files.map((file) => ({
        url: URL.createObjectURL(file),
        name: file.name.replace(/\.[^.]+$/, '') || 'photo',
    })));
    cropTotal.value = cropQueue.value.length;
}

function nextCrop() {
    const done = cropQueue.value.shift();
    if (done) URL.revokeObjectURL(done.url);
    if (!cropQueue.value.length) cropTotal.value = 0;
}

function acceptCrop(blob) {
    const file = new File([blob], `${cropQueue.value[0].name}.jpg`, {type: 'image/jpeg'});
    emit('update:modelValue', [...props.modelValue, file]);
    nextCrop();
}

function cancelAllCrops() {
    cropQueue.value.forEach((item) => URL.revokeObjectURL(item.url));
    cropQueue.value = [];
    cropTotal.value = 0;
}

onBeforeUnmount(() => {
    cancelAllCrops();
    previews.forEach((url) => URL.revokeObjectURL(url));
});
</script>
