<template>
    <AuthenticatedLayout title="New city">
        <CityForm :countries="countries"/>
    </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from '@/Layouts/Admin/AuthenticatedLayout.vue';
import CityForm from './CityForm.vue';

defineProps({
    countries: {type: Object, required: true},
});
</script>
