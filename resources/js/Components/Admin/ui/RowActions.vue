<template>
    <div class="flex items-center justify-end gap-1">
        <!-- Extra row actions (e.g. "Images") go before edit/delete -->
        <slot/>
        <Link v-if="editHref" :href="editHref" :title="editLabel" :aria-label="editLabel" :class="[BASE, EDIT_HOVER]">
            <PencilSquareIcon class="h-[18px] w-[18px]" aria-hidden="true"/>
        </Link>
        <!-- Modal resources: the list page opens its edit modal -->
        <button v-else-if="editable" type="button" :title="editLabel" :aria-label="editLabel" :class="[BASE, EDIT_HOVER]"
                @click="emit('edit')">
            <PencilSquareIcon class="h-[18px] w-[18px]" aria-hidden="true"/>
        </button>
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
const EDIT_HOVER = 'hover:bg-slate-100 hover:text-slate-900';

defineProps({
    // Edit on its own page
    editHref: {type: String, default: null},
    // Edit in a modal: emits `edit`
    editable: {type: Boolean, default: false},
    editLabel: {type: String, default: 'Edit'},
    deletable: {type: Boolean, default: true},
    deleteLabel: {type: String, default: 'Delete'},
});
const emit = defineEmits(['edit', 'delete']);
</script>
