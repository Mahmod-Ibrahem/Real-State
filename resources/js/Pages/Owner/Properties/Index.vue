<template>
    <AuthenticatedLayout :nav="OWNER_NAV" title="My properties"
                         description="Approved listings can be published. Editing one sends it back to review.">
        <TableCard noun="properties" :meta="properties.meta" :empty="!properties.data.length" :searchable="false"
                   empty-text="You haven't listed a property yet. Add your first one."
                   :create-href="route('supplier.properties.create')" create-label="Add property">
            <thead class="border-b border-slate-200">
            <tr>
                <TableHeadingCell>Property</TableHeadingCell>
                <TableHeadingCell>Location</TableHeadingCell>
                <TableHeadingCell>Review</TableHeadingCell>
                <TableHeadingCell>Visibility</TableHeadingCell>
                <TableHeadingCell align="right">Price (EGP)</TableHeadingCell>
            </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
            <tr v-for="property in properties.data" :key="property.id" class="transition-colors hover:bg-slate-50">
                <TableCell>
                    <div class="flex min-w-[16rem] items-center gap-3">
                        <img :src="property.image" alt="" class="h-10 w-14 shrink-0 rounded-md border border-slate-200 object-cover"/>
                        <div class="min-w-0">
                            <RowTitle :href="route('supplier.properties.edit', property.id)" :title="property.name">{{ property.name }}</RowTitle>
                            <p class="text-xs text-slate-500">{{ property.property_type }}</p>
                        </div>
                    </div>
                </TableCell>
                <TableCell class="whitespace-nowrap">{{ property.area }}, {{ property.city }}</TableCell>
                <TableCell>
                    <div class="flex max-w-xs flex-col items-start gap-1">
                        <ReviewStatusBadge :status="property.review_status" :statuses="review_statuses"/>
                        <p v-if="property.rejection_reason" class="text-xs text-red-600">{{ property.rejection_reason }}</p>
                    </div>
                </TableCell>
                <TableCell>
                    <ToggleButton v-if="property.review_status === 'approved'"
                                  :label="property.is_published ? 'Published' : 'Draft'"
                                  :next-label="property.is_published ? 'Draft' : 'Published'"
                                  :tone="property.is_published ? 'success' : 'warning'" :pressed="property.is_published"
                                  :loading="publishing === property.id" @toggle="togglePublished(property)"/>
                    <StatusBadge v-else title="You can publish it once an admin approves it">Draft</StatusBadge>
                </TableCell>
                <TableCell align="right" class="whitespace-nowrap tabular-nums">{{ formatNumber(property.price) }}</TableCell>
            </tr>
            </tbody>
        </TableCard>
    </AuthenticatedLayout>
</template>

<script setup>
import {ref} from 'vue';
import {router} from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/Admin/AuthenticatedLayout.vue';
import RowTitle from '@/Components/Admin/ui/RowTitle.vue';
import StatusBadge from '@/Components/Admin/ui/StatusBadge.vue';
import TableCard from '@/Components/Admin/ui/TableCard.vue';
import TableCell from '@/Components/Admin/ui/TableCell.vue';
import TableHeadingCell from '@/Components/Admin/ui/TableHeadingCell.vue';
import ToggleButton from '@/Components/Admin/ui/ToggleButton.vue';
import ReviewStatusBadge from '@/Components/Dashboard/Properties/ReviewStatusBadge.vue';
import {OWNER_NAV} from '@/Components/Owner/navigation.js';
import {formatNumber} from '@/Composables/useFormatters.js';
import {showToast} from '@/Composables/useToast.js';

defineProps({
    properties: {type: Object, required: true},
    review_statuses: {type: Array, required: true},
});

const publishing = ref(null);

function togglePublished(property) {
    publishing.value = property.id;
    const action = property.is_published ? 'unpublish' : 'publish';
    router.patch(route(`supplier.properties.${action}`, property.id), {}, {
        preserveScroll: true,
        onSuccess: () => showToast(`${property.name} ${property.is_published ? 'unpublished' : 'published'}`),
        onError: (errors) => showToast(errors.database ?? 'The property could not be updated.', 'error'),
        onFinish: () => (publishing.value = null),
    });
}
</script>
