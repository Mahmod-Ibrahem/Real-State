<template>
    <AuthenticatedLayout title="New property">
        <PropertyForm v-bind="$props" route-base="admin.properties" publishable/>
    </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from '@/Layouts/Admin/AuthenticatedLayout.vue';
import PropertyForm from '@/Components/Dashboard/Properties/PropertyForm.vue';
import {PROPERTY_FORM_PROPS} from '@/Components/Dashboard/Properties/formProps.js';

defineProps(PROPERTY_FORM_PROPS);
</script>
