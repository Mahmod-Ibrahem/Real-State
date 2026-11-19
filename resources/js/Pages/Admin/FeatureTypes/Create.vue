<template>
    <AuthenticatedLayout title="New feature type">
        <FeatureTypeForm/>
    </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from '@/Layouts/Admin/AuthenticatedLayout.vue';
import FeatureTypeForm from './FeatureTypeForm.vue';
</script>
