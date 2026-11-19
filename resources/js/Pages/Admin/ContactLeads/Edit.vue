<template>
    <AuthenticatedLayout :title="`Message from ${contactLead.full_name}`">
        <template #actions>
            <ContactActions :phone="contactLead.phone" :email="contactLead.email"/>
        </template>
        <ContactLeadForm :lead="contactLead"/>
    </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from '@/Layouts/Admin/AuthenticatedLayout.vue';
import ContactActions from '@/Components/Admin/ContactActions.vue';
import ContactLeadForm from './ContactLeadForm.vue';

defineProps({
    contactLead: {type: Object, required: true},
});
</script>
