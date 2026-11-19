<template>
    <AuthenticatedLayout :title="`Edit ${area.name}`">
        <AreaForm :area="area" :cities="cities"/>
    </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from '@/Layouts/Admin/AuthenticatedLayout.vue';
import AreaForm from './AreaForm.vue';

defineProps({
    area: {type: Object, required: true},
    cities: {type: Object, required: true},
});
</script>
