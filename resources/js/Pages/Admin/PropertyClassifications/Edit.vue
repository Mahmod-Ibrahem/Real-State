<template>
    <AuthenticatedLayout :title="`Edit ${propertyClassification.name}`">
        <ClassificationForm :classification="propertyClassification"/>
    </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from '@/Layouts/Admin/AuthenticatedLayout.vue';
import ClassificationForm from './ClassificationForm.vue';

defineProps({
    propertyClassification: {type: Object, required: true},
});
</script>
