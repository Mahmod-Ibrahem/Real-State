<template>
    <FormModal :open="open" :title="classification ? `Edit ${classification.name}` : 'New classification'" size="sm"
               :submit-label="classification ? 'Save changes' : 'Add classification'" :processing="form.processing"
               @close="emit('close')" @submit="save">
        <TextField v-model="form.name" label="Name" placeholder="e.g. Luxury" required
                   hint="The home page shows the Affordable and Luxury classifications as sections."
                   :error="form.errors.name || form.errors.database"/>
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
    classification: {type: Object, default: null},
});
const emit = defineEmits(['close']);

const locale = currentLocale();
const fields = () => ({
    name: props.classification?.name ?? '',
    locale,
});

const form = useForm(fields());
resetFormOnOpen(form, () => props.open, fields);

const save = () => saveResource(form, 'propertyClassifications', 'classification',
    {id: props.classification?.id, onSuccess: () => emit('close')});
</script>
