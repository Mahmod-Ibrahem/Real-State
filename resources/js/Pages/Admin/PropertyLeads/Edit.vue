<template>
    <AuthenticatedLayout :title="`Lead from ${propertyLead.full_name}`">
        <template #actions>
            <ContactActions :phone="propertyLead.phone" :email="propertyLead.email"/>
        </template>
        <PropertyLeadForm :lead="propertyLead"/>
    </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from '@/Layouts/Admin/AuthenticatedLayout.vue';
import ContactActions from '@/Components/Admin/ContactActions.vue';
import PropertyLeadForm from './PropertyLeadForm.vue';

defineProps({
    propertyLead: {type: Object, required: true},
});
</script>
