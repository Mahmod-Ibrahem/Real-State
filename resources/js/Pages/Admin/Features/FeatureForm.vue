<template>
    <FormModal :open="open" :title="feature ? `Edit ${feature.name}` : 'New feature'"
               :submit-label="feature ? 'Save changes' : 'Add feature'" :processing="form.processing"
               @close="emit('close')" @submit="save">
        <SelectField v-model="form.feature_type_id" label="Feature type" :options="featureTypes"
                     placeholder="Select a type" required :error="form.errors.feature_type_id"/>
        <TextField v-model="form.name" label="Name" placeholder="e.g. Swimming pool" required
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
    feature: {type: Object, default: null},
    // [{id, name}]
    featureTypes: {type: Array, required: true},
});
const emit = defineEmits(['close']);

const locale = currentLocale();
const fields = () => ({
    feature_type_id: props.feature?.featureTypeId ?? '',
    name: props.feature?.name ?? '',
    locale,
});

const form = useForm(fields());
resetFormOnOpen(form, () => props.open, fields);

const save = () => saveResource(form, 'propertyFeatures', 'feature', {id: props.feature?.id, onSuccess: () => emit('close')});
</script>
