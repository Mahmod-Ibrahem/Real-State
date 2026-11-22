<template>
    <AuthenticatedLayout :nav="OWNER_NAV" title="New property"
                         description="We'll review your listing before it goes live.">
        <PropertyForm v-bind="$props" route-base="supplier.properties" submit-label="Submit for review"/>
    </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from '@/Layouts/Admin/AuthenticatedLayout.vue';
import PropertyForm from '@/Components/Dashboard/Properties/PropertyForm.vue';
import {PROPERTY_FORM_PROPS} from '@/Components/Dashboard/Properties/formProps.js';
import {OWNER_NAV} from '@/Components/Owner/navigation.js';

defineProps(PROPERTY_FORM_PROPS);
</script>
