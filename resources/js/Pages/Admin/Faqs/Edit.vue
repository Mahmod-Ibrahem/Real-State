<template>
    <AuthenticatedLayout title="Edit FAQ">
        <FaqForm :faq="faq"/>
    </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from '@/Layouts/Admin/AuthenticatedLayout.vue';
import FaqForm from './FaqForm.vue';

defineProps({
    faq: {type: Object, required: true},
});
</script>
