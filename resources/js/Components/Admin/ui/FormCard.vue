<template>
    <form class="w-full rounded-xl border border-slate-200 bg-white" @submit.prevent="emit('submit')">
        <div class="space-y-5 p-6">
            <slot/>
        </div>
        <footer class="flex items-center justify-end gap-3 rounded-b-xl border-t border-slate-200 bg-slate-50/80 px-6 py-4">
            <Button v-if="cancelHref" variant="ghost" :href="cancelHref">Cancel</Button>
            <Button type="submit" :icon="CheckIcon" :loading="processing" :disabled="disabled">
                {{ processing ? 'Saving…' : submitLabel }}
            </Button>
        </footer>
    </form>
</template>

<script setup>
import {CheckIcon} from '@heroicons/vue/20/solid';
import Button from './Button.vue';

defineProps({
    submitLabel: {type: String, default: 'Save'},
    cancelHref: {type: String, default: null},
    processing: {type: Boolean, default: false},
    disabled: {type: Boolean, default: false},
});
const emit = defineEmits(['submit']);
</script>
