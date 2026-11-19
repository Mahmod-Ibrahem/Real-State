<template>
    <AuthenticatedLayout :title="`Edit ${featureType.name}`">
        <FeatureTypeForm :feature-type="featureType"/>
    </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from '@/Layouts/Admin/AuthenticatedLayout.vue';
import FeatureTypeForm from './FeatureTypeForm.vue';

defineProps({
    featureType: {type: Object, required: true},
});
</script>
