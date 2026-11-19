<template>
    <FormCard :submit-label="area ? 'Save changes' : 'Add area'" :cancel-href="route('areas.index')"
              :processing="form.processing" @submit="saveResource(form, 'areas', 'area', {id: area?.id})">
        <div class="grid gap-5 sm:grid-cols-2">
            <SelectField v-model="form.city_id" label="City" :options="cityOptions" placeholder="Select a city"
                         required :error="form.errors.city_id"/>
            <TextField v-model="form.name" label="Name" placeholder="e.g. Zamalek" required
                       :error="form.errors.name || form.errors.database"/>
        </div>
    </FormCard>
</template>

<script setup>
import {computed} from 'vue';
import {useForm} from '@inertiajs/vue3';
import FormCard from '@/Components/Admin/ui/FormCard.vue';
import SelectField from '@/Components/Admin/ui/SelectField.vue';
import TextField from '@/Components/Admin/ui/TextField.vue';
import {currentLocale, saveResource} from '@/Composables/useResourceForm.js';

const props = defineProps({
    // null when creating
    area: {type: Object, default: null},
    cities: {type: Object, required: true},
});

// "Cairo (Egypt)", since the same city name can exist in two countries
const cityOptions = computed(() =>
    props.cities.data.map((city) => ({id: city.id, name: city.country ? `${city.name} (${city.country})` : city.name}))
);

const form = useForm({
    city_id: props.area?.cityId ?? '',
    name: props.area?.name ?? '',
    locale: currentLocale(),
});
</script>
