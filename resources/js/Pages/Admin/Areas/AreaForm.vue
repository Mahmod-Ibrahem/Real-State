<template>
    <FormModal :open="open" :title="area ? `Edit ${area.name}` : 'New area'"
               :submit-label="area ? 'Save changes' : 'Add area'" :processing="form.processing"
               @close="emit('close')" @submit="save">
        <SelectField v-model="form.city_id" label="City" :options="cityOptions" placeholder="Select a city"
                     required :error="form.errors.city_id"/>
        <TextField v-model="form.name" label="Name" placeholder="e.g. Zamalek" required
                   :error="form.errors.name || form.errors.database"/>
    </FormModal>
</template>

<script setup>
import {computed} from 'vue';
import {useForm} from '@inertiajs/vue3';
import FormModal from '@/Components/Admin/ui/FormModal.vue';
import SelectField from '@/Components/Admin/ui/SelectField.vue';
import TextField from '@/Components/Admin/ui/TextField.vue';
import {currentLocale, resetFormOnOpen, saveResource} from '@/Composables/useResourceForm.js';

const props = defineProps({
    open: {type: Boolean, default: false},
    // null when creating
    area: {type: Object, default: null},
    // [{id, name, country}]
    cities: {type: Array, required: true},
});
const emit = defineEmits(['close']);

// "Cairo (Egypt)", since the same city name can exist in two countries
const cityOptions = computed(() =>
    props.cities.map((city) => ({id: city.id, name: city.country ? `${city.name} (${city.country})` : city.name}))
);

const locale = currentLocale();
const fields = () => ({
    city_id: props.area?.cityId ?? '',
    name: props.area?.name ?? '',
    locale,
});

const form = useForm(fields());
resetFormOnOpen(form, () => props.open, fields);

const save = () => saveResource(form, 'admin.areas', 'area', {id: props.area?.id, onSuccess: () => emit('close')});
</script>
