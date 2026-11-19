<template>
    <FormCard :submit-label="classification ? 'Save changes' : 'Add classification'"
              :cancel-href="route('propertyClassifications.index')" :processing="form.processing"
              @submit="saveResource(form, 'propertyClassifications', 'classification', {id: classification?.id})">
        <TextField v-model="form.name" label="Name" placeholder="e.g. Luxury" required autofocus
                   hint="The home page shows the Affordable and Luxury classifications as sections."
                   :error="form.errors.name || form.errors.database"/>
    </FormCard>
</template>

<script setup>
import {useForm} from '@inertiajs/vue3';
import FormCard from '@/Components/Admin/ui/FormCard.vue';
import TextField from '@/Components/Admin/ui/TextField.vue';
import {currentLocale, saveResource} from '@/Composables/useResourceForm.js';

const props = defineProps({
    // null when creating
    classification: {type: Object, default: null},
});

const form = useForm({
    name: props.classification?.name ?? '',
    locale: currentLocale(),
});
</script>
