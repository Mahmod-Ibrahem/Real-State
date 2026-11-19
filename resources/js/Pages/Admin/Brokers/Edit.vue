<template>
    <AuthenticatedLayout :title="`Edit ${broker.name}`">
        <BrokerForm :broker="broker"/>
    </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from '@/Layouts/Admin/AuthenticatedLayout.vue';
import BrokerForm from './BrokerForm.vue';

defineProps({
    broker: {type: Object, required: true},
});
</script>
