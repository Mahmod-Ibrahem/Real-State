<template>
    <AuthenticatedLayout :title="`Edit ${basicContent.name}`">
        <BasicContentForm :basic-content="basicContent"/>
    </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from '@/Layouts/Admin/AuthenticatedLayout.vue';
import BasicContentForm from './BasicContentForm.vue';

defineProps({
    basicContent: {type: Object, required: true},
});
</script>
