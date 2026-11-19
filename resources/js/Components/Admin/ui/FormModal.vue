<template>
    <Modal :open="open" :title="title" :description="description" :size="size" @close="close">
        <form @submit.prevent="emit('submit')">
            <div class="space-y-5 px-6 pb-6">
                <slot/>
            </div>
            <footer class="flex items-center justify-end gap-3 border-t border-slate-200 bg-slate-50/80 px-6 py-4">
                <Button variant="ghost" :disabled="processing" @click="close">Cancel</Button>
                <Button type="submit" :icon="CheckIcon" :loading="processing">
                    {{ processing ? 'Saving…' : submitLabel }}
                </Button>
            </footer>
        </form>
    </Modal>
</template>

<script setup>
import {CheckIcon} from '@heroicons/vue/20/solid';
import Button from './Button.vue';
import Modal from './Modal.vue';

const props = defineProps({
    open: {type: Boolean, default: false},
    title: {type: String, required: true},
    description: {type: String, default: ''},
    submitLabel: {type: String, default: 'Save'},
    processing: {type: Boolean, default: false},
    size: {type: String, default: 'md'},
});
const emit = defineEmits(['submit', 'close']);

// Closing mid-save would hide the result (and any validation errors) from the user
function close() {
    if (!props.processing) emit('close');
}
</script>
