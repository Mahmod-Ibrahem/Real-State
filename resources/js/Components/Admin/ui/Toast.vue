<template>
    <div class="pointer-events-none fixed right-4 top-4 z-[100] w-[calc(100%-2rem)] sm:w-96" aria-live="polite">
        <transition enter-active-class="transition duration-200 ease-out" enter-from-class="opacity-0 -translate-y-1"
                    leave-active-class="transition duration-150 ease-in" leave-to-class="opacity-0">
            <div v-if="toast.show" role="status"
                 class="pointer-events-auto flex items-start gap-3 rounded-xl border border-slate-200 bg-white p-4 shadow-lg"
                 @mouseenter="pause" @mouseleave="schedule">
                <component :is="isError ? ExclamationCircleIcon : CheckCircleIcon" class="h-5 w-5 shrink-0"
                           :class="isError ? 'text-red-600' : 'text-green-600'" aria-hidden="true"/>
                <p class="min-w-0 flex-1 text-sm font-medium text-slate-900">{{ toast.message }}</p>
                <button type="button" aria-label="Dismiss" @click="hideToast"
                        class="-m-1 grid h-6 w-6 shrink-0 place-content-center rounded-md text-slate-400 hover:bg-slate-100 hover:text-slate-700">
                    <XMarkIcon class="h-4 w-4" aria-hidden="true"/>
                </button>
            </div>
        </transition>
    </div>
</template>

<script setup>
import {computed, watch} from 'vue';
import {CheckCircleIcon, ExclamationCircleIcon, XMarkIcon} from '@heroicons/vue/24/outline';
import {hideToast, toast} from '@/Composables/useToast.js';

const isError = computed(() => toast.value.type === 'error');

let timer = null;
const pause = () => clearTimeout(timer);
const schedule = () => {
    pause();
    timer = setTimeout(hideToast, toast.value.delay);
};

// Each new message restarts the auto-dismiss timer
watch(() => toast.value.show && toast.value.message, (visible) => visible && schedule());
</script>
