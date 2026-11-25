<template>
    <div class="flex gap-4 overflow-x-auto pb-2">
        <section v-for="column in columns" :key="column.id"
                 class="flex max-h-[calc(100vh-14rem)] min-h-[16rem] w-[85vw] shrink-0 flex-col rounded-xl border border-t-4 border-slate-200 bg-slate-50 sm:w-80 xl:w-auto xl:min-w-[18rem] xl:flex-1"
                 :class="TONE_BORDERS[column.tone]" :aria-label="`${column.name}, ${column.count}`">
            <header class="flex items-center justify-between gap-2 px-3 py-2.5">
                <div class="flex min-w-0 items-center gap-2">
                    <span class="size-2.5 shrink-0 rounded-full" :class="TONE_DOTS[column.tone]" aria-hidden="true"></span>
                    <h2 class="truncate text-sm font-semibold text-slate-900">{{ column.name }}</h2>
                </div>
                <span class="rounded-full bg-white px-2 py-0.5 text-xs font-medium tabular-nums text-slate-500 ring-1 ring-slate-200">
                    {{ column.count }}
                </span>
            </header>

            <Draggable :list="column.items" item-key="id" group="kanban-cards" :disabled="disabled" :animation="180"
                       :force-fallback="true" ghost-class="kanban-ghost" fallback-class="kanban-drag"
                       class="min-h-0 flex-1 space-y-3 overflow-y-auto px-3 pb-3"
                       @change="onChange(column, $event)">
                <template #item="{element}">
                    <div>
                        <slot name="card" :item="element" :column="column"/>
                    </div>
                </template>
                <template #footer>
                    <p v-if="!column.items.length" class="py-10 text-center text-sm text-slate-400">{{ emptyText }}</p>
                </template>
            </Draggable>
        </section>
    </div>
</template>

<script setup>
import Draggable from 'vuedraggable';
import {TONE_BORDERS, TONE_DOTS} from './tones.js';

defineProps({
    // [{id, name, tone, count, items: [{id, …}]}]; the board moves items between these arrays while dragging
    columns: {type: Array, required: true},
    emptyText: {type: String, default: 'Nothing here'},
    disabled: {type: Boolean, default: false},
});
const emit = defineEmits(['move']);

function onChange(column, event) {
    if (event.added) {
        column.count++;
        emit('move', event.added.element, column.id);
    }
    if (event.removed) {
        column.count--;
    }
}
</script>
