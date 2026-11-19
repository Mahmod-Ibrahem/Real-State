<template>
    <FormCard submit-label="Save changes" :cancel-href="route('admin.property-leads.index')" :processing="form.processing"
              @submit="saveResource(form, 'admin.property-leads', 'lead', {id: lead.id})">
        <div class="grid gap-5 sm:grid-cols-2">
            <TextField v-model="form.full_name" label="Name" required :error="form.errors.full_name"/>
            <TextField v-model="form.email" label="Email" type="email" required :error="form.errors.email"/>
            <TextField v-model="form.phone" label="Phone" type="tel" required :error="form.errors.phone"/>
            <SelectField v-model="form.property_id" label="Property" :options="properties" placeholder="Select a property"
                         required :error="form.errors.property_id"/>
        </div>
        <TextField v-model="form.message" label="Message" type="textarea" :rows="5" required :error="form.errors.message"/>
    </FormCard>
</template>

<script setup>
import {useForm} from '@inertiajs/vue3';
import FormCard from '@/Components/Admin/ui/FormCard.vue';
import SelectField from '@/Components/Admin/ui/SelectField.vue';
import TextField from '@/Components/Admin/ui/TextField.vue';
import {saveResource} from '@/Composables/useResourceForm.js';

const props = defineProps({
    lead: {type: Object, required: true},
    // [{id, name}]
    properties: {type: Array, required: true},
});

const form = useForm({
    property_id: props.lead.property_id ?? '',
    full_name: props.lead.full_name ?? '',
    email: props.lead.email ?? '',
    phone: props.lead.phone ?? '',
    message: props.lead.message ?? '',
});
</script>
