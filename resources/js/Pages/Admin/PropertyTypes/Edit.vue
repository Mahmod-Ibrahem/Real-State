<template>
    <AuthenticatedLayout :title="`Edit ${propertyType.name}`">
        <PropertyTypeForm :property-type="propertyType"/>
    </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from '@/Layouts/Admin/AuthenticatedLayout.vue';
import PropertyTypeForm from './PropertyTypeForm.vue';

defineProps({
    propertyType: {type: Object, required: true},
});
</script>
