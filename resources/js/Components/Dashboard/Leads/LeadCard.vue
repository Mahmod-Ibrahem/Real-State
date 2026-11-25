<template>
    <!-- Ported from Convoera's KanbanItem: border only at rest, the drag clone gets the shadow (admin.css .kanban-drag) -->
    <article class="relative cursor-grab select-none rounded-xl border border-slate-200 bg-white p-4 pb-3 transition-colors hover:border-slate-300 active:cursor-grabbing"
             :class="{'opacity-60': saving}" role="button" tabindex="0"
             :aria-label="`Lead from ${lead.full_name} about ${lead.property_name ?? 'a deleted property'}`"
             @click="emit('open', lead)" @keydown.enter="emit('open', lead)">
        <h3 class="truncate text-[15px] font-semibold leading-tight text-slate-900" :title="lead.property_name">
            {{ lead.property_name ?? 'Deleted property' }}
        </h3>

        <div class="mt-2.5 flex items-center gap-2.5">
            <span class="grid size-9 shrink-0 place-content-center rounded-full bg-indigo-50 text-xs font-semibold text-indigo-700"
                  aria-hidden="true">{{ initials }}</span>
            <div class="min-w-0">
                <p class="truncate text-sm font-medium text-slate-900">{{ lead.full_name }}</p>
                <p class="truncate text-xs text-slate-500" dir="ltr">{{ lead.phone }}</p>
            </div>
        </div>

        <p v-if="lead.message" class="mt-2 line-clamp-2 text-xs text-slate-600" :title="lead.message">{{ lead.message }}</p>
        <p v-if="showOwner && lead.owner_name" class="mt-2 truncate text-xs text-slate-500">Owner: {{ lead.owner_name }}</p>

        <footer class="mt-3 flex items-center justify-between gap-2 border-t border-slate-100 pt-2">
            <span class="inline-flex min-w-0 items-center gap-1.5 text-xs text-slate-500">
                <component :is="SOURCE_ICONS[lead.source]" class="h-3.5 w-3.5 shrink-0" aria-hidden="true"/>
                <span class="truncate">{{ lead.source_label }} · {{ formatRelativeTime(lead.created_at) }}</span>
            </span>
            <div class="-me-2 flex shrink-0" @click.stop @mousedown.stop @touchstart.stop>
                <ContactActions :phone="lead.phone" :email="lead.email"/>
            </div>
        </footer>
    </article>
</template>

<script setup>
import {computed} from 'vue';
import ContactActions from '@/Components/Admin/ContactActions.vue';
import {formatRelativeTime} from '@/Composables/useFormatters.js';
import {SOURCE_ICONS} from './constants.js';

const props = defineProps({
    lead: {type: Object, required: true},
    showOwner: {type: Boolean, default: false},
    saving: {type: Boolean, default: false},
});
const emit = defineEmits(['open']);

const initials = computed(() =>
    props.lead.full_name.split(' ').filter(Boolean).slice(0, 2).map((word) => word[0].toUpperCase()).join('')
);
</script>
