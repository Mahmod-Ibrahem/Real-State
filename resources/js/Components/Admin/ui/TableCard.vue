<template>
    <div class="overflow-hidden rounded-xl border border-slate-200 bg-white">
        <div class="flex flex-col gap-3 border-b border-slate-200 px-4 py-3 md:flex-row md:items-center md:justify-between">
            <p class="text-sm text-slate-500">
                Found <span class="font-semibold text-slate-900">{{ total }}</span> {{ noun }}
            </p>

            <div class="flex flex-wrap items-center gap-2">
                <SearchBox v-if="searchable" v-model="search" :noun="noun" :client-search="clientSearch"/>
                <slot name="actions"/>
                <Button v-if="createHref" :href="createHref" :icon="PlusIcon">{{ createLabel }}</Button>
                <Button v-else-if="creatable" :icon="PlusIcon" @click="emit('create')">{{ createLabel }}</Button>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <slot/>
            </table>
        </div>

        <p v-if="empty" class="px-4 py-16 text-center text-sm text-slate-500">
            {{ search ? `No ${noun} match “${search}”.` : emptyText }}
        </p>

        <Pagination v-if="meta" :meta="meta" class="border-t border-slate-200"/>
    </div>
</template>

<script setup>
import {computed, ref, watch} from 'vue';
import {PlusIcon} from '@heroicons/vue/20/solid';
import Button from './Button.vue';
import Pagination from './Pagination.vue';
import SearchBox from './SearchBox.vue';

const props = defineProps({
    // Plural noun for the toolbar and messages: "cities"
    noun: {type: String, required: true},
    // Laravel paginator meta; omit for unpaginated lists and pass `count`
    meta: {type: Object, default: null},
    count: {type: Number, default: null},
    empty: {type: Boolean, default: false},
    emptyText: {type: String, default: 'Nothing here yet.'},
    // Create on its own page
    createHref: {type: String, default: null},
    // Create in a modal: the button emits `create`
    creatable: {type: Boolean, default: false},
    createLabel: {type: String, default: 'Add new'},
    searchable: {type: Boolean, default: true},
    // Unpaginated lists filter their own rows: bind v-model:search instead of hitting the server
    clientSearch: {type: Boolean, default: false},
});
const emit = defineEmits(['update:search', 'create']);

const total = computed(() => props.count ?? props.meta?.total ?? 0);

const search = ref('');
watch(search, (value) => props.clientSearch && emit('update:search', value));
</script>
