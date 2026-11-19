<template>
    <!-- Full-size preview: object-contain keeps wide and tall photos whole -->
    <Teleport to="body">
        <div v-if="src" ref="root" tabindex="-1" role="dialog" aria-label="Image preview"
             class="fixed inset-0 z-[80] flex items-center justify-center bg-slate-900/85 p-6 focus:outline-none"
             @click="emit('close')" @keydown.esc="emit('close')">
            <img :src="src" alt="" class="max-h-full max-w-full rounded-lg object-contain shadow-lg" @click.stop/>
            <button type="button" aria-label="Close preview" @click="emit('close')"
                    class="absolute right-4 top-4 text-white/80 transition-colors hover:text-white">
                <XMarkIcon class="h-7 w-7" aria-hidden="true"/>
            </button>
        </div>
    </Teleport>
</template>

<script setup>
import {nextTick, ref, watch} from 'vue';
import {XMarkIcon} from '@heroicons/vue/24/outline';

const props = defineProps({
    // The image to show; empty hides the lightbox
    src: {type: String, default: ''},
});
const emit = defineEmits(['close']);

// Focus the overlay so Esc works straight away
const root = ref(null);
watch(() => props.src, (src) => src && nextTick(() => root.value?.focus()));
</script>
