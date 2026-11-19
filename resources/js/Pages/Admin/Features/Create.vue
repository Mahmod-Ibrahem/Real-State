<template>
    <AuthenticatedLayout title="New feature">
        <FeatureForm :feature-types="featureTypes"/>
    </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from '@/Layouts/Admin/AuthenticatedLayout.vue';
import FeatureForm from './FeatureForm.vue';

defineProps({
    featureTypes: {type: Array, required: true},
});
</script>
