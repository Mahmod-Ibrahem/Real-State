<template>
    <FormModal :open="open" :title="featureType ? `Edit ${featureType.name}` : 'New feature type'" size="sm"
               :submit-label="featureType ? 'Save changes' : 'Add feature type'" :processing="form.processing"
               @close="emit('close')" @submit="save">
        <TextField v-model="form.name" label="Name" placeholder="e.g. Outdoor" required
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
    featureType: {type: Object, default: null},
});
const emit = defineEmits(['close']);

const locale = currentLocale();
const fields = () => ({
    name: props.featureType?.name ?? '',
    locale,
});

const form = useForm(fields());
resetFormOnOpen(form, () => props.open, fields);

const save = () => saveResource(form, 'feature-types', 'feature type', {id: props.featureType?.id, onSuccess: () => emit('close')});
</script>
