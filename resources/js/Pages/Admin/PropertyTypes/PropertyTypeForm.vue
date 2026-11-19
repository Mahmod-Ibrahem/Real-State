<template>
    <FormModal :open="open" :title="propertyType ? `Edit ${propertyType.name}` : 'New property type'"
               :submit-label="propertyType ? 'Save changes' : 'Add type'" :processing="form.processing"
               @close="emit('close')" @submit="save">
        <TextField v-model="form.name" label="Name" placeholder="e.g. Villa" required
                   :error="form.errors.name || form.errors.database"/>
        <ImageField v-model="form.image" label="Cover image" :current-url="propertyType?.image ?? ''"
                    hint="JPG, PNG, WEBP or AVIF. You can crop it after picking." :required="!propertyType"
                    :error="form.errors.image"/>
    </FormModal>
</template>

<script setup>
import {useForm} from '@inertiajs/vue3';
import FormModal from '@/Components/Admin/ui/FormModal.vue';
import ImageField from '@/Components/Admin/ui/ImageField.vue';
import TextField from '@/Components/Admin/ui/TextField.vue';
import {currentLocale, resetFormOnOpen, saveResource} from '@/Composables/useResourceForm.js';

const props = defineProps({
    open: {type: Boolean, default: false},
    // null when creating
    propertyType: {type: Object, default: null},
});
const emit = defineEmits(['close']);

const locale = currentLocale();
const fields = () => ({
    name: props.propertyType?.name ?? '',
    // Only a newly cropped file; empty keeps the saved image
    image: null,
    locale,
});

const form = useForm(fields());
resetFormOnOpen(form, () => props.open, fields);

const save = () => saveResource(form, 'admin.property-types', 'property type',
    {id: props.propertyType?.id, multipart: true, onSuccess: () => emit('close')});
</script>
