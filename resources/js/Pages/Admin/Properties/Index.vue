<template>
    <AuthenticatedLayout title="Properties" description="Every listing on the site, published or not.">
        <TableCard noun="properties" :meta="properties.meta" :empty="!properties.data.length"
                   :empty-text="review_filter ? 'No properties with this review status.' : 'No properties yet. Add your first property.'"
                   :create-href="route('admin.properties.create')" create-label="Add property">
            <template #actions>
                <FilterTabs :options="review_statuses" :model-value="review_filter" param="review" label="Review status"/>
            </template>

            <thead class="border-b border-slate-200">
            <tr>
                <TableHeadingCell>Property</TableHeadingCell>
                <TableHeadingCell>Location</TableHeadingCell>
                <TableHeadingCell>Review</TableHeadingCell>
                <TableHeadingCell>Purpose</TableHeadingCell>
                <TableHeadingCell>Status</TableHeadingCell>
                <TableHeadingCell>Visibility</TableHeadingCell>
                <TableHeadingCell align="right">Price (EGP)</TableHeadingCell>
                <TableHeadingCell>Size</TableHeadingCell>
                <TableHeadingCell align="right"><span class="sr-only">Actions</span></TableHeadingCell>
            </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
            <tr v-for="property in properties.data" :key="property.id" class="transition-colors hover:bg-slate-50">
                <TableCell>
                    <div class="flex min-w-[16rem] items-center gap-3">
                        <img :src="property.image" alt="" class="h-10 w-14 shrink-0 rounded-md border border-slate-200 object-cover"/>
                        <div class="min-w-0">
                            <RowTitle :href="route('admin.properties.edit', property.id)" :title="property.name">{{ property.name }}</RowTitle>
                            <p class="text-xs text-slate-500">{{ property.property_type }} · {{ property.owner }}</p>
                        </div>
                    </div>
                </TableCell>
                <TableCell class="whitespace-nowrap">{{ property.area }}, {{ property.city }}</TableCell>
                <TableCell>
                    <span :title="property.rejection_reason ?? undefined">
                        <InlineSelect :model-value="property.review_status" :options="reviewOptions"
                                      :label="`Review status of ${property.name}`" :tone="toneOf(review_statuses, property.review_status)"
                                      :loading="isSaving(property, 'review_status')"
                                      @update:model-value="(status) => changeReview(property, status)"/>
                    </span>
                </TableCell>
                <TableCell>
                    <ToggleButton :label="labelOf(purposes, property.purpose)" :next-label="labelOf(purposes, otherPurpose(property))"
                                  :tone="property.purpose === 'sale' ? 'info' : 'neutral'" :pressed="property.purpose === 'sale'"
                                  :loading="isSaving(property, 'purpose')"
                                  @toggle="quickUpdate(property, 'purpose', otherPurpose(property))"/>
                </TableCell>
                <TableCell>
                    <InlineSelect :model-value="property.status" :options="statusesFor(property)"
                                  :label="`Status of ${property.name}`" :tone="property.status === 'available' ? 'success' : 'neutral'"
                                  :loading="isSaving(property, 'status')"
                                  @update:model-value="(status) => quickUpdate(property, 'status', status)"/>
                </TableCell>
                <TableCell>
                    <ToggleButton :label="property.is_published ? 'Published' : 'Draft'"
                                  :next-label="property.is_published ? 'Draft' : 'Published'"
                                  :tone="property.is_published ? 'success' : 'warning'" :pressed="property.is_published"
                                  :loading="isSaving(property, 'is_published')"
                                  @toggle="quickUpdate(property, 'is_published', !property.is_published)"/>
                </TableCell>
                <TableCell align="right" class="whitespace-nowrap tabular-nums">{{ formatNumber(property.price) }}</TableCell>
                <TableCell variant="muted" class="whitespace-nowrap">
                    {{ property.bedrooms }} bd · {{ property.bathrooms }} ba · {{ formatNumber(property.sqft) }} sq ft
                </TableCell>
                <TableCell align="right">
                    <RowActions :edit-href="route('admin.properties.edit', property.id)"
                                @delete="confirmDelete('admin.properties.destroy', property.id, 'property')"/>
                </TableCell>
            </tr>
            </tbody>
        </TableCard>

        <PropertyRejectModal :open="Boolean(rejectTarget)" :property="rejectTarget" @close="rejectTarget = null"/>
    </AuthenticatedLayout>
</template>

<script setup>
import {computed, reactive, ref} from 'vue';
import {router} from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/Admin/AuthenticatedLayout.vue';
import PropertyRejectModal from '@/Components/Admin/PropertyRejectModal.vue';
import FilterTabs from '@/Components/Admin/ui/FilterTabs.vue';
import InlineSelect from '@/Components/Admin/ui/InlineSelect.vue';
import RowActions from '@/Components/Admin/ui/RowActions.vue';
import RowTitle from '@/Components/Admin/ui/RowTitle.vue';
import TableCard from '@/Components/Admin/ui/TableCard.vue';
import TableCell from '@/Components/Admin/ui/TableCell.vue';
import TableHeadingCell from '@/Components/Admin/ui/TableHeadingCell.vue';
import ToggleButton from '@/Components/Admin/ui/ToggleButton.vue';
import {confirmDelete} from '@/Composables/useConfirmDelete.js';
import {formatNumber} from '@/Composables/useFormatters.js';
import {approveProperty} from '@/Composables/usePropertyReview.js';
import {showToast} from '@/Composables/useToast.js';

const PENDING = 'pending';
const APPROVED = 'approved';
const REJECTED = 'rejected';

const props = defineProps({
    properties: {type: Object, required: true},
    review_filter: {type: String, default: null},
    purposes: {type: Array, required: true},
    statuses: {type: Array, required: true},
    review_statuses: {type: Array, required: true},
});

const labelOf = (options, value) => options.find((option) => option.id === value)?.name ?? value;
const toneOf = (options, value) => options.find((option) => option.id === value)?.tone ?? 'neutral';
const otherPurpose = (property) => props.purposes.find((purpose) => purpose.id !== property.purpose)?.id;
const statusesFor = (property) => props.statuses.filter((status) => status.purposes.includes(property.purpose));

const saving = reactive({});
const isSaving = (property, field) => Boolean(saving[`${property.id}.${field}`]);

function quickUpdate(property, field, value) {
    const key = `${property.id}.${field}`;
    saving[key] = true;
    router.patch(route('admin.properties.listing', property.id), {[field]: value}, {
        preserveScroll: true,
        onSuccess: () => showToast(`${property.name} updated`),
        onError: (errors) => showToast(Object.values(errors)[0] ?? 'The property could not be updated.', 'error'),
        onFinish: () => delete saving[key],
    });
}

// Pending is set by submitting or editing a property, never chosen by an admin
const reviewOptions = computed(() => props.review_statuses.map((status) => ({...status, disabled: status.id === PENDING})));
const rejectTarget = ref(null);

function changeReview(property, status) {
    if (status === property.review_status) return;

    if (status === REJECTED) {
        rejectTarget.value = property;
        return;
    }
    if (status === APPROVED) {
        const key = `${property.id}.review_status`;
        saving[key] = true;
        approveProperty(property, () => delete saving[key]);
    }
}
</script>
