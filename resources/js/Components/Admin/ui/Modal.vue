<template>
    <!-- The admin's only dialog shell (ADMIN_UI.md §4). Headless UI handles focus trap, Esc and nesting. -->
    <TransitionRoot appear :show="open" as="template">
        <Dialog as="div" class="relative z-50" @close="emit('close')">
            <TransitionChild as="template" enter="duration-200 ease-out" enter-from="opacity-0" enter-to="opacity-100"
                             leave="duration-150 ease-in" leave-from="opacity-100" leave-to="opacity-0">
                <div class="fixed inset-0 bg-slate-900/50" aria-hidden="true"/>
            </TransitionChild>

            <div class="fixed inset-0 overflow-y-auto">
                <div class="flex min-h-full items-center justify-center p-4">
                    <TransitionChild as="template" enter="duration-200 ease-out" enter-from="opacity-0 scale-95"
                                     enter-to="opacity-100 scale-100" leave="duration-150 ease-in"
                                     leave-from="opacity-100 scale-100" leave-to="opacity-0 scale-95">
                        <DialogPanel :class="['relative w-full overflow-hidden rounded-xl bg-white text-left shadow-lg', SIZES[size]]">
                            <header class="py-5 pl-6 pr-14">
                                <DialogTitle as="h2" class="text-base font-semibold text-slate-900">
                                    {{ title }}
                                    <span v-if="step" class="ml-1.5 text-sm font-normal text-slate-400">{{ step }}</span>
                                </DialogTitle>
                                <DialogDescription v-if="description" class="mt-0.5 text-sm text-slate-500">
                                    {{ description }}
                                </DialogDescription>
                            </header>
                            <slot/>
                            <!-- Last in the DOM so the dialog's initial focus lands on the first field, not on ×. -->
                            <button type="button" aria-label="Close" @click="emit('close')"
                                    class="absolute right-4 top-4 rounded-md p-1 text-slate-400 transition-colors hover:bg-slate-100 hover:text-slate-600 focus:outline-none focus-visible:ring-2 focus-visible:ring-indigo-500/30">
                                <XMarkIcon class="h-5 w-5" aria-hidden="true"/>
                            </button>
                        </DialogPanel>
                    </TransitionChild>
                </div>
            </div>
        </Dialog>
    </TransitionRoot>
</template>

<script setup>
import {Dialog, DialogDescription, DialogPanel, DialogTitle, TransitionChild, TransitionRoot} from '@headlessui/vue';
import {XMarkIcon} from '@heroicons/vue/24/outline';

const SIZES = {
    sm: 'max-w-md',
    md: 'max-w-lg',
    lg: 'max-w-2xl',
    xl: 'max-w-3xl',
};

defineProps({
    open: {type: Boolean, default: false},
    title: {type: String, required: true},
    description: {type: String, default: ''},
    // e.g. "2 of 5" while working through a queue
    step: {type: String, default: ''},
    size: {type: String, default: 'md'},
});
const emit = defineEmits(['close']);
</script>
