<template>
    <Modal :open="open" :title="title" :step="step" size="xl"
           description="Drag to move; use the handles or scroll to resize. The frame matches how it shows on the site."
           @close="emit('cancel')">
        <div class="bg-slate-900">
            <Cropper v-if="src" :key="src" ref="cropper" :src="src" class="h-[60vh] max-h-[520px]"
                     :stencil-props="{aspectRatio}" :default-size="defaultSize"
                     :canvas="{maxWidth, maxHeight: Math.round(maxWidth / aspectRatio)}" image-restriction="fit-area"/>
        </div>

        <footer class="flex flex-col-reverse items-stretch gap-3 px-6 py-4 sm:flex-row sm:items-center sm:justify-between">
            <div class="flex gap-2">
                <button v-for="turn in ROTATIONS" :key="turn.degrees" type="button" :aria-label="turn.label"
                        :title="turn.label" @click="cropper?.rotate(turn.degrees)"
                        class="grid h-9 w-9 place-content-center rounded-lg border border-slate-300 text-slate-600 transition-colors hover:bg-slate-50 focus:outline-none focus-visible:ring-2 focus-visible:ring-indigo-500/30">
                    <component :is="turn.icon" class="h-4 w-4" aria-hidden="true"/>
                </button>
            </div>
            <div class="flex flex-wrap gap-3">
                <Button v-if="showCancelAll" variant="ghost" @click="emit('cancel-all')">Cancel all</Button>
                <Button variant="secondary" @click="emit('cancel')">{{ cancelLabel }}</Button>
                <Button :loading="working" @click="confirm">{{ working ? 'Cropping…' : confirmLabel }}</Button>
            </div>
        </footer>
    </Modal>
</template>

<script setup>
import {ref} from 'vue';
import {Cropper} from 'vue-advanced-cropper';
import 'vue-advanced-cropper/dist/style.css';
import {ArrowUturnLeftIcon, ArrowUturnRightIcon} from '@heroicons/vue/24/outline';
import Button from './Button.vue';
import Modal from './Modal.vue';

const ROTATIONS = [
    {degrees: -90, label: 'Rotate left', icon: ArrowUturnLeftIcon},
    {degrees: 90, label: 'Rotate right', icon: ArrowUturnRightIcon},
];

const props = defineProps({
    open: {type: Boolean, default: false},
    // Object URL of the picked photo
    src: {type: String, default: ''},
    // Width / height of the frame, e.g. 4 / 3
    aspectRatio: {type: Number, default: 4 / 3},
    // Longest side of the output, so uploads stay small
    maxWidth: {type: Number, default: 1600},
    title: {type: String, default: 'Crop image'},
    step: {type: String, default: ''},
    confirmLabel: {type: String, default: 'Crop & use'},
    cancelLabel: {type: String, default: 'Cancel'},
    showCancelAll: {type: Boolean, default: false},
});
const emit = defineEmits(['confirm', 'cancel', 'cancel-all']);

const cropper = ref(null);
const working = ref(false);

// Start with the largest frame that fits the photo
function defaultSize({imageSize}) {
    const ratio = props.aspectRatio;
    return imageSize.width / imageSize.height > ratio
        ? {width: imageSize.height * ratio, height: imageSize.height}
        : {width: imageSize.width, height: imageSize.width / ratio};
}

function confirm() {
    const canvas = cropper.value?.getResult()?.canvas;
    if (!canvas) return;
    working.value = true;
    canvas.toBlob((blob) => {
        working.value = false;
        if (blob) emit('confirm', blob);
    }, 'image/jpeg', 0.9);
}
</script>
