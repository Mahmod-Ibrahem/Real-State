<template>
    <FormCard :submit-label="featureType ? 'Save changes' : 'Add feature type'" :cancel-href="route('feature-types.index')"
              :processing="form.processing"
              @submit="saveResource(form, 'feature-types', 'feature type', {id: featureType?.id})">
        <TextField v-model="form.name" label="Name" placeholder="e.g. Outdoor" required autofocus
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
    featureType: {type: Object, default: null},
});

const form = useForm({
    name: props.featureType?.name ?? '',
    locale: currentLocale(),
});
</script>
