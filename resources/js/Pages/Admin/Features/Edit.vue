<template>
    <AuthenticatedLayout :title="`Edit ${feature.name}`">
        <FeatureForm :feature="feature" :feature-types="featureTypes"/>
    </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from '@/Layouts/Admin/AuthenticatedLayout.vue';
import FeatureForm from './FeatureForm.vue';

defineProps({
    feature: {type: Object, required: true},
    featureTypes: {type: Array, required: true},
});
</script>
