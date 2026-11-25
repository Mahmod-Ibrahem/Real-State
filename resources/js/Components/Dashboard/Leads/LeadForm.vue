<template>
    <FormModal :open="open" :title="lead ? `Lead from ${leadName(lead)}` : 'New lead'"
               :description="lead ? (lead.property_name ?? 'Deleted property') : 'Someone who reached you outside the website.'"
               :processing="form.processing" @close="emit('close')" @submit="save">
        <dl v-if="lead" class="grid gap-x-6 gap-y-4 rounded-lg bg-slate-50 px-4 py-3 sm:grid-cols-2">
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
            <div class="sm:col-span-2">
                <dt class="text-xs font-medium text-slate-500">Message</dt>
                <dd class="mt-1 whitespace-pre-line text-sm text-slate-700">{{ lead.message || 'No message.' }}</dd>
            </div>
        </dl>

        <SelectField v-else v-model="form.property_id" label="Property" :options="properties" searchable required
                     placeholder="Choose a property" search-placeholder="Search properties…"
                     empty-message="No properties to choose from yet" :error="form.errors.property_id"/>

        <TextField v-model="form.full_name" label="Full name" autocomplete="off" :required="!lead" :error="form.errors.full_name"/>
        <TextField v-model="form.email" label="Email" type="email" autocomplete="off" :error="form.errors.email"/>
        <PhoneField v-model="form.phone" v-model:country="form.phone_country" label="Phone number"
                    :error="form.errors.phone" :country-error="form.errors.phone_country"/>
        <TextField v-if="!lead" v-model="form.message" label="Note" type="textarea" :rows="3"
                   hint="What they asked about, or how they got in touch." :error="form.errors.message"/>
    </FormModal>
</template>

<script setup>
import {useForm} from '@inertiajs/vue3';
import FormModal from '@/Components/Admin/ui/FormModal.vue';
import PhoneField from '@/Components/Admin/ui/PhoneField.vue';
import SelectField from '@/Components/Admin/ui/SelectField.vue';
import TextField from '@/Components/Admin/ui/TextField.vue';
import {splitPhone} from '@/Composables/phoneCountries.js';
import {formatDate, formatRelativeTime} from '@/Composables/useFormatters.js';
import {resetFormOnOpen, saveResource} from '@/Composables/useResourceForm.js';
import {SOURCE_ICONS, leadName} from './constants.js';

const props = defineProps({
    open: {type: Boolean, default: false},
    // The lead being edited; null when adding one
    lead: {type: Object, default: null},
    // [{id, name}]: the properties a lead can be added to
    properties: {type: Array, default: () => []},
    // admin.property-leads or supplier.leads (.store / .update)
    routeBase: {type: String, required: true},
    showOwner: {type: Boolean, default: false},
});
const emit = defineEmits(['close']);

const form = useForm({property_id: null, full_name: '', email: '', phone_country: 'EG', phone: '', message: ''});

resetFormOnOpen(form, () => props.open, () => {
    const phone = splitPhone(props.lead?.phone);

    return {
        property_id: null,
        full_name: props.lead?.full_name ?? '',
        email: props.lead?.email ?? '',
        phone_country: phone.country,
        phone: phone.number,
        message: '',
    };
});

const save = () => saveResource(form, props.routeBase, 'lead', {id: props.lead?.id, onSuccess: () => emit('close')});
</script>
