<template>
    <AuthenticatedLayout :nav="OWNER_NAV" :title="`Edit ${property.name}`">
        <template #actions>
            <ReviewStatusBadge :status="property.review_status" :statuses="review_statuses"/>
        </template>

        <div v-if="property.rejection_reason" class="mb-4 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
            <p class="font-medium">An admin rejected this listing</p>
            <p class="mt-0.5">{{ property.rejection_reason }}</p>
        </div>
        <p v-if="property.review_status !== 'pending'" class="mb-4 text-sm text-slate-500">
            Saving changes sends this listing back to review and hides it until it's approved again.
        </p>

        <PropertyForm v-bind="$props" route-base="owner.properties"/>
    </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from '@/Layouts/Admin/AuthenticatedLayout.vue';
import PropertyForm from '@/Components/Dashboard/Properties/PropertyForm.vue';
import ReviewStatusBadge from '@/Components/Dashboard/Properties/ReviewStatusBadge.vue';
import {EDIT_PROPERTY_PROPS} from '@/Components/Dashboard/Properties/formProps.js';
import {OWNER_NAV} from '@/Components/Owner/navigation.js';

defineProps(EDIT_PROPERTY_PROPS);
</script>
