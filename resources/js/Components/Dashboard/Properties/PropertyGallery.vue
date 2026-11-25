<template>
    <!-- Gallery manager inside the property form, modelled on Mr Egypt Tours' TourGalleryManager.
         It has no endpoints: new and removed photos are saved with the property (ADMIN_UI.md §4, "Gallery"). -->
    <div>
        <div class="mb-3">
            <p class="text-sm font-medium text-slate-700">
                Gallery<span class="text-red-600" aria-hidden="true"> *</span>
            </p>
            <p class="mt-0.5 text-xs text-slate-500">
                <button v-if="removedIds.length && !saving" type="button" @click="emit('update:removedIds', [])"
                        class="ml-1 font-medium text-indigo-600 transition-colors hover:text-indigo-800">
                    Undo
                </button>
            </p>
        </div>

        <ImagesField v-model="newPhotos" :saved="visibleSaved" :disabled="saving" label="" crop-title="Crop gallery photo"
                     :error="error" @remove="(image) => emit('update:removedIds', [...removedIds, image.id])"/>

        <!-- Upload progress of the property save (Inertia's form.progress) -->
        <div v-if="saving && progress" class="mt-4" role="status" aria-live="polite">
            <div class="mb-1 flex justify-between text-xs text-slate-500">
                <span>Uploading photos…</span>
                <span>{{ progress.percentage }}%</span>
            </div>
            <div class="h-1.5 w-full overflow-hidden rounded-full bg-slate-100">
                <div class="h-full rounded-full bg-indigo-600 transition-all duration-300 ease-out"
                     :style="{width: `${progress.percentage}%`}"></div>
            </div>
        </div>
    </div>
</template>

<script setup>
import {computed} from 'vue';
import ImagesField from '@/Components/Admin/ui/ImagesField.vue';

const props = defineProps({
    // New cropped photos (File[]), sent as gallery_images[]
    modelValue: {type: Array, default: () => []},
    // Saved photos: [{id, url}]
    saved: {type: Array, default: () => []},
    // Ids of saved photos removed in the form, sent as removed_image_ids[]
    removedIds: {type: Array, default: () => []},
    // The form is being submitted, and its upload progress ({percentage}) if any
    saving: {type: Boolean, default: false},
    progress: {type: Object, default: null},
    error: {type: String, default: ''},
});
const emit = defineEmits(['update:modelValue', 'update:removedIds']);

const newPhotos = computed({
    get: () => props.modelValue,
    set: (files) => emit('update:modelValue', files),
});

const visibleSaved = computed(() => props.saved.filter((image) => !props.removedIds.includes(image.id)));
</script>
