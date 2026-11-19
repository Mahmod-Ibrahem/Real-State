<template>
    <FormModal :open="open" :title="country ? `Edit ${country.name}` : 'New country'" size="sm"
               :submit-label="country ? 'Save changes' : 'Add country'" :processing="form.processing"
               @close="emit('close')" @submit="save">
        <TextField v-model="form.name" label="Name" placeholder="e.g. Egypt" required :error="form.errors.name"/>
    </FormModal>
</template>

<script setup>
import {useForm} from '@inertiajs/vue3';
import FormModal from '@/Components/Admin/ui/FormModal.vue';
import TextField from '@/Components/Admin/ui/TextField.vue';
import {currentLocale, resetFormOnOpen, saveResource} from '@/Composables/useResourceForm.js';

const props = defineProps({
    open: {type: Boolean, default: false},
    // null when creating
    country: {type: Object, default: null},
});
const emit = defineEmits(['close']);

const locale = currentLocale();
const fields = () => ({
    name: props.country?.name ?? '',
    locale,
});

const form = useForm(fields());
resetFormOnOpen(form, () => props.open, fields);

const save = () => saveResource(form, 'admin.countries', 'country', {id: props.country?.id, onSuccess: () => emit('close')});
</script>
