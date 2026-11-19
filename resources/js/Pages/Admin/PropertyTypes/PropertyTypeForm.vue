<template>
    <FormCard :submit-label="propertyType ? 'Save changes' : 'Add type'" :cancel-href="route('propertyTypes.index')"
              :processing="form.processing"
              @submit="saveResource(form, 'propertyTypes', 'property type', {id: propertyType?.id, multipart: true})">
        <TextField v-model="form.name" label="Name" placeholder="e.g. Villa" required autofocus
                   :error="form.errors.name || form.errors.database"/>
        <ImageField v-model="form.image" label="Cover image" :current-url="propertyType?.image ?? ''"
                    hint="JPG, PNG, WEBP or AVIF, up to 2 MB." :required="!propertyType" :error="form.errors.image"/>
    </FormCard>
</template>

<script setup>
import {useForm} from '@inertiajs/vue3';
import FormCard from '@/Components/Admin/ui/FormCard.vue';
import TextField from '@/Components/Admin/ui/TextField.vue';
import ImageField from '@/Components/Admin/ui/ImageField.vue';
import {currentLocale, saveResource} from '@/Composables/useResourceForm.js';

const props = defineProps({
    // null when creating
    propertyType: {type: Object, default: null},
});

const form = useForm({
    name: props.propertyType?.name ?? '',
    image: null,
    locale: currentLocale(),
});
</script>
