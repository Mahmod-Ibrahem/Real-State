<template>
    <Modal :open="Boolean(lead)" :title="lead ? `Lead from ${lead.full_name}` : ''"
           :description="lead?.property_name ?? 'Deleted property'" @close="emit('close')">
        <div v-if="lead" class="space-y-5 border-t border-slate-200 px-6 py-5">
            <dl class="grid gap-x-6 gap-y-4 sm:grid-cols-2">
                <div>
                    <dt class="text-xs font-medium text-slate-500">Phone</dt>
                    <dd class="mt-1 text-sm text-slate-900" dir="ltr">{{ lead.phone }}</dd>
                </div>
                <div>
                    <dt class="text-xs font-medium text-slate-500">Email</dt>
                    <dd class="mt-1 break-all text-sm text-slate-900">{{ lead.email ?? '—' }}</dd>
                </div>
                <div>
                    <dt class="text-xs font-medium text-slate-500">Source</dt>
                    <dd class="mt-1 inline-flex items-center gap-1.5 text-sm text-slate-900">
                        <component :is="SOURCE_ICONS[lead.source]" class="h-4 w-4 text-slate-400" aria-hidden="true"/>
                        {{ lead.source_label }}
                    </dd>
                </div>
                <div>
                    <dt class="text-xs font-medium text-slate-500">Received</dt>
                    <dd class="mt-1 text-sm text-slate-900">{{ formatDate(lead.created_at) }} · {{ formatRelativeTime(lead.created_at) }}</dd>
                </div>
                <div v-if="showOwner">
                    <dt class="text-xs font-medium text-slate-500">Owner</dt>
                    <dd class="mt-1 text-sm text-slate-900">{{ lead.owner_name ?? '—' }}</dd>
                </div>
                <div>
                    <dt class="text-xs font-medium text-slate-500">Status</dt>
                    <dd class="mt-1">
                        <InlineSelect :model-value="lead.status" :options="statuses" :label="`Status of the lead from ${lead.full_name}`"
                                      :tone="statuses.find((option) => option.id === lead.status)?.tone ?? 'neutral'"
                                      :loading="saving" @update:model-value="(status) => emit('change-status', lead, status)"/>
                    </dd>
                </div>
            </dl>

            <div>
                <p class="text-xs font-medium text-slate-500">Message</p>
                <p class="mt-1 whitespace-pre-line rounded-lg bg-slate-50 px-3 py-2.5 text-sm text-slate-700">{{ lead.message || 'No message.' }}</p>
            </div>
        </div>

        <footer v-if="lead" class="flex items-center justify-between gap-2 border-t border-slate-200 bg-slate-50/80 px-6 py-3">
            <div class="flex">
                <ContactActions :phone="lead.phone" :email="lead.email"/>
            </div>
            <Button variant="secondary" @click="emit('close')">Close</Button>
        </footer>
    </Modal>
</template>

<script setup>
import ContactActions from '@/Components/Admin/ContactActions.vue';
import Button from '@/Components/Admin/ui/Button.vue';
import InlineSelect from '@/Components/Admin/ui/InlineSelect.vue';
import Modal from '@/Components/Admin/ui/Modal.vue';
import {formatDate, formatRelativeTime} from '@/Composables/useFormatters.js';
import {SOURCE_ICONS} from './constants.js';

defineProps({
    // The lead being viewed, or null while closed
    lead: {type: Object, default: null},
    statuses: {type: Array, required: true},
    saving: {type: Boolean, default: false},
    showOwner: {type: Boolean, default: false},
});
const emit = defineEmits(['close', 'change-status']);
</script>
