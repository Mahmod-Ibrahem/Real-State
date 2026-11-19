<template>
    <AuthenticatedLayout title="New property type">
        <PropertyTypeForm/>
    </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from '@/Layouts/Admin/AuthenticatedLayout.vue';
import PropertyTypeForm from './PropertyTypeForm.vue';
</script>
