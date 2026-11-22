<template>
    <AuthenticatedLayout :title="`Edit ${property.name}`">
        <template #actions>
            <ReviewStatusBadge :status="property.review_status" :statuses="review_statuses"/>
            <PropertyReviewActions :property="property"/>
        </template>

        <PropertyForm v-bind="$props" route-base="admin.properties" publishable/>
    </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from '@/Layouts/Admin/AuthenticatedLayout.vue';
import PropertyReviewActions from '@/Components/Admin/PropertyReviewActions.vue';
import PropertyForm from '@/Components/Dashboard/Properties/PropertyForm.vue';
import ReviewStatusBadge from '@/Components/Dashboard/Properties/ReviewStatusBadge.vue';
import {EDIT_PROPERTY_PROPS} from '@/Components/Dashboard/Properties/formProps.js';

defineProps(EDIT_PROPERTY_PROPS);
</script>
