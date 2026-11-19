<template>
    <FormCard :submit-label="city ? 'Save changes' : 'Add city'" :cancel-href="route('cities.index')"
              :processing="form.processing" @submit="saveResource(form, 'cities', 'city', {id: city?.id})">
        <div class="grid gap-5 sm:grid-cols-2">
            <SelectField v-model="form.country_id" label="Country" :options="countries.data" placeholder="Select a country"
                         required :error="form.errors.country_id"/>
            <TextField v-model="form.name" label="Name" placeholder="e.g. Cairo" required
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
    city: {type: Object, default: null},
    countries: {type: Object, required: true},
});

const form = useForm({
    country_id: props.city?.countryId ?? '',
    name: props.city?.name ?? '',
    locale: currentLocale(),
});
</script>
