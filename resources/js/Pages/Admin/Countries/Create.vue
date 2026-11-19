<template>
    <AuthenticatedLayout title="New country">
        <CountryForm/>
    </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from '@/Layouts/Admin/AuthenticatedLayout.vue';
import CountryForm from './CountryForm.vue';
</script>
