<template>
    <div>
        <p v-if="label" class="mb-1.5 text-sm font-medium text-slate-700">{{ label }}</p>
        <div class="flex items-center gap-4">
            <Avatar :src="preview" :name="name || '?'" size="lg"/>
            <div class="flex flex-wrap items-center gap-2">
                <Button variant="secondary" size="sm" @click="input?.click()">{{ preview ? 'Change photo' : 'Upload photo' }}</Button>
                <Button v-if="preview" variant="ghost" size="sm" @click="remove">Remove</Button>
                <input ref="input" type="file" accept="image/jpeg,image/png,image/webp" class="sr-only" tabindex="-1"
                       aria-label="Choose a profile photo" @change="pick">
            </div>
        </div>
        <p v-if="hint && !error" class="mt-1.5 text-xs text-slate-500">{{ hint }}</p>
        <p v-if="error" class="mt-1.5 text-xs text-red-600">{{ error }}</p>

        <ImageCropDialog :open="Boolean(cropSrc)" :src="cropSrc" :aspect-ratio="1" :max-width="OUTPUT_SIZE"
                         title="Crop profile photo" confirm-label="Use photo" @confirm="useCropped" @cancel="closeCrop"/>
    </div>
</template>

<script setup>
import {computed, onBeforeUnmount, ref, watch} from 'vue';
import Avatar from './Avatar.vue';
import Button from './Button.vue';
import ImageCropDialog from './ImageCropDialog.vue';

// The server stores avatars at 400×400
const OUTPUT_SIZE = 400;

const props = defineProps({
    // The new cropped photo (File), sent as `avatar`; null keeps the saved one
    modelValue: {type: File, default: null},
    // true once the saved photo is removed, sent as `remove_avatar`
    removed: {type: Boolean, default: false},
    // URL of the saved photo, if any
    current: {type: String, default: null},
    // For the initials when there is no photo
    name: {type: String, default: ''},
    label: {type: String, default: ''},
    hint: {type: String, default: ''},
    error: {type: String, default: ''},
});
const emit = defineEmits(['update:modelValue', 'update:removed']);

const input = ref(null);
const cropSrc = ref('');
const newPhotoUrl = ref(null);

watch(() => props.modelValue, (file) => {
    if (newPhotoUrl.value) URL.revokeObjectURL(newPhotoUrl.value);
    newPhotoUrl.value = file ? URL.createObjectURL(file) : null;
});
onBeforeUnmount(() => newPhotoUrl.value && URL.revokeObjectURL(newPhotoUrl.value));

const preview = computed(() => newPhotoUrl.value ?? (props.removed ? null : props.current));

function pick(event) {
    const file = event.target.files?.[0];
    event.target.value = '';
    if (file) cropSrc.value = URL.createObjectURL(file);
}

function closeCrop() {
    URL.revokeObjectURL(cropSrc.value);
    cropSrc.value = '';
}

function useCropped(blob) {
    closeCrop();
    emit('update:modelValue', new File([blob], 'avatar.jpg', {type: 'image/jpeg'}));
    emit('update:removed', false);
}

function remove() {
    emit('update:modelValue', null);
    if (props.current) emit('update:removed', true);
}
</script>
