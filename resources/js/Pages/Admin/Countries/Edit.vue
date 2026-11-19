<template>
    <AuthenticatedLayout :title="`Edit ${country.name}`">
        <CountryForm :country="country"/>
    </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from '@/Layouts/Admin/AuthenticatedLayout.vue';
import CountryForm from './CountryForm.vue';

defineProps({
    country: {type: Object, required: true},
});
</script>
