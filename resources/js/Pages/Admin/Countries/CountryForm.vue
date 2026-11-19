<template>
    <FormCard :submit-label="country ? 'Save changes' : 'Add country'" :cancel-href="route('countries.index')"
              :processing="form.processing" @submit="saveResource(form, 'countries', 'country', {id: country?.id})">
        <TextField v-model="form.name" label="Name" placeholder="e.g. Egypt" required autofocus :error="form.errors.name"/>
    </FormCard>
</template>

<script setup>
import {useForm} from '@inertiajs/vue3';
import FormCard from '@/Components/Admin/ui/FormCard.vue';
import TextField from '@/Components/Admin/ui/TextField.vue';
import {currentLocale, saveResource} from '@/Composables/useResourceForm.js';

const props = defineProps({
    // null when creating
    country: {type: Object, default: null},
});

const form = useForm({
    name: props.country?.name ?? '',
    locale: currentLocale(),
});
</script>
