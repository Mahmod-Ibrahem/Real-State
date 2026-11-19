<template>
    <FormModal :open="open" :title="city ? `Edit ${city.name}` : 'New city'"
               :submit-label="city ? 'Save changes' : 'Add city'" :processing="form.processing"
               @close="emit('close')" @submit="save">
        <SelectField v-model="form.country_id" label="Country" :options="countries" placeholder="Select a country"
                     required :error="form.errors.country_id"/>
        <TextField v-model="form.name" label="Name" placeholder="e.g. Cairo" required
                   :error="form.errors.name || form.errors.database"/>
    </FormModal>
</template>

<script setup>
import {useForm} from '@inertiajs/vue3';
import FormModal from '@/Components/Admin/ui/FormModal.vue';
import SelectField from '@/Components/Admin/ui/SelectField.vue';
import TextField from '@/Components/Admin/ui/TextField.vue';
import {currentLocale, resetFormOnOpen, saveResource} from '@/Composables/useResourceForm.js';

const props = defineProps({
    open: {type: Boolean, default: false},
    // null when creating
    city: {type: Object, default: null},
    // [{id, name}]
    countries: {type: Array, required: true},
});
const emit = defineEmits(['close']);

const locale = currentLocale();
const fields = () => ({
    country_id: props.city?.countryId ?? '',
    name: props.city?.name ?? '',
    locale,
});

const form = useForm(fields());
resetFormOnOpen(form, () => props.open, fields);

const save = () => saveResource(form, 'cities', 'city', {id: props.city?.id, onSuccess: () => emit('close')});
</script>
