<template>
    <AuthenticatedLayout title="New classification">
        <ClassificationForm/>
    </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from '@/Layouts/Admin/AuthenticatedLayout.vue';
import ClassificationForm from './ClassificationForm.vue';
</script>
