<template>
    <div class="flex items-center justify-end gap-1">
        <!-- Extra row actions (e.g. "Images") go before edit/delete -->
        <slot/>
        <Link v-if="editHref" :href="editHref" :title="editLabel" :aria-label="editLabel" :class="[BASE, 'hover:bg-slate-100 hover:text-slate-900']">
            <PencilSquareIcon class="h-[18px] w-[18px]" aria-hidden="true"/>
        </Link>
        <button v-if="deletable" type="button" :title="deleteLabel" :aria-label="deleteLabel"
                :class="[BASE, 'hover:bg-red-50 hover:text-red-600']" @click="emit('delete')">
            <TrashIcon class="h-[18px] w-[18px]" aria-hidden="true"/>
        </button>
    </div>
</template>

<script setup>
import {Link} from '@inertiajs/vue3';
import {PencilSquareIcon, TrashIcon} from '@heroicons/vue/24/outline';

const BASE = 'grid h-8 w-8 place-content-center rounded-md text-slate-500 transition-colors focus:outline-none focus-visible:ring-2 focus-visible:ring-indigo-500/30';

defineProps({
    editHref: {type: String, default: null},
    editLabel: {type: String, default: 'Edit'},
    deletable: {type: Boolean, default: true},
    deleteLabel: {type: String, default: 'Delete'},
});
const emit = defineEmits(['delete']);
</script>
