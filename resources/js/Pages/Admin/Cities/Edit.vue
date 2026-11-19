<template>
    <AuthenticatedLayout :title="`Edit ${city.name}`">
        <CityForm :city="city" :countries="countries"/>
    </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from '@/Layouts/Admin/AuthenticatedLayout.vue';
import CityForm from './CityForm.vue';

defineProps({
    city: {type: Object, required: true},
    countries: {type: Object, required: true},
});
</script>
