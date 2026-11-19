<template>
    <AuthenticatedLayout :title="`Edit ${property.name}`">
        <PropertyForm v-bind="$props"/>
    </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from '@/Layouts/Admin/AuthenticatedLayout.vue';
import PropertyForm from './PropertyForm.vue';

defineProps({
    property: {type: Object, required: true},
    propertyTypes: {type: Object, required: true},
    propertyClassification: {type: Object, required: true},
    countries: {type: Object, required: true},
    cities: {type: Object, required: true},
    areas: {type: Object, required: true},
    purposes: {type: Array, required: true},
    statuses: {type: Array, required: true},
    features: {type: Object, required: true},
});
</script>
