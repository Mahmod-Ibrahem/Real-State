<template>
    <AuthenticatedLayout title="New area">
        <AreaForm :cities="cities"/>
    </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from '@/Layouts/Admin/AuthenticatedLayout.vue';
import AreaForm from './AreaForm.vue';

defineProps({
    cities: {type: Object, required: true},
});
</script>
