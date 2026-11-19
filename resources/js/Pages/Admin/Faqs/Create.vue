<template>
    <AuthenticatedLayout title="New FAQ">
        <FaqForm/>
    </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from '@/Layouts/Admin/AuthenticatedLayout.vue';
import FaqForm from './FaqForm.vue';
</script>
