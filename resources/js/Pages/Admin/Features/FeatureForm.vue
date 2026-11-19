<template>
    <FormCard :submit-label="feature ? 'Save changes' : 'Add feature'" :cancel-href="route('propertyFeatures.index')"
              :processing="form.processing"
              @submit="saveResource(form, 'propertyFeatures', 'feature', {id: feature?.id})">
        <div class="grid gap-5 sm:grid-cols-2">
            <SelectField v-model="form.feature_type_id" label="Feature type" :options="featureTypes"
                         placeholder="Select a type" required :error="form.errors.feature_type_id"/>
            <TextField v-model="form.name" label="Name" placeholder="e.g. Swimming pool" required
                       :error="form.errors.name || form.errors.database"/>
        </div>
    </FormCard>
</template>

<script setup>
import {useForm} from '@inertiajs/vue3';
import FormCard from '@/Components/Admin/ui/FormCard.vue';
import SelectField from '@/Components/Admin/ui/SelectField.vue';
import TextField from '@/Components/Admin/ui/TextField.vue';
import {currentLocale, saveResource} from '@/Composables/useResourceForm.js';

const props = defineProps({
    // null when creating
    feature: {type: Object, default: null},
    // [{id, name}]
    featureTypes: {type: Array, required: true},
});

const form = useForm({
    feature_type_id: props.feature?.featureTypeId ?? '',
    name: props.feature?.name ?? '',
    locale: currentLocale(),
});
</script>
