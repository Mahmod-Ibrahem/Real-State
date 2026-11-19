<template>
    <AuthenticatedLayout title="New broker">
        <BrokerForm/>
    </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from '@/Layouts/Admin/AuthenticatedLayout.vue';
import BrokerForm from './BrokerForm.vue';
</script>
