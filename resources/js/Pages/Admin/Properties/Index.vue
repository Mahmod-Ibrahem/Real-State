<template>
    <AuthenticatedLayout title="Properties" description="Every listing on the site, published or not.">
        <TableCard noun="properties" :meta="properties.meta" :empty="!properties.data.length"
                   empty-text="No properties yet. Add your first property."
                   :create-href="route('properties.create')" create-label="Add property">
            <thead class="border-b border-slate-200">
            <tr>
                <TableHeadingCell>Property</TableHeadingCell>
                <TableHeadingCell>Location</TableHeadingCell>
                <TableHeadingCell>Listing</TableHeadingCell>
                <TableHeadingCell align="right">Price (EGP)</TableHeadingCell>
                <TableHeadingCell>Size</TableHeadingCell>
                <TableHeadingCell>Visibility</TableHeadingCell>
                <TableHeadingCell align="right"><span class="sr-only">Actions</span></TableHeadingCell>
            </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
            <tr v-for="property in properties.data" :key="property.id" class="transition-colors hover:bg-slate-50">
                <TableCell>
                    <div class="flex min-w-[16rem] items-center gap-3">
                        <img :src="property.image_url" alt="" class="h-10 w-14 shrink-0 rounded-md border border-slate-200 object-cover"/>
                        <div class="min-w-0">
                            <p class="truncate font-medium text-slate-900" :title="property.name">{{ property.name }}</p>
                            <p class="text-xs text-slate-500">#{{ property.id }} · {{ property.propertyType }}</p>
                        </div>
                    </div>
                </TableCell>
                <TableCell class="whitespace-nowrap">{{ property.area }}, {{ property.city }}</TableCell>
                <TableCell>
                    <div class="flex flex-wrap gap-1">
                        <StatusBadge tone="info">{{ property.purpose }}</StatusBadge>
                        <StatusBadge :tone="property.status === 'Available' ? 'success' : 'neutral'">{{ property.status }}</StatusBadge>
                    </div>
                </TableCell>
                <TableCell align="right" class="whitespace-nowrap tabular-nums">{{ formatNumber(property.price) }}</TableCell>
                <TableCell variant="muted">{{ property.bedrooms }} bd · {{ property.bathrooms }} ba · {{ formatNumber(property.sqft) }} sq ft</TableCell>
                <TableCell>
                    <StatusBadge :tone="property.is_published === 'Published' ? 'success' : 'warning'">{{ property.is_published }}</StatusBadge>
                </TableCell>
                <TableCell align="right">
                    <RowActions :edit-href="route('properties.edit', property.id)"
                                @delete="confirmDelete('properties.destroy', property.id, 'property')">
                        <Link :href="route('propertyImages.index', {propertyId: property.id})" title="Gallery" aria-label="Gallery"
                              class="grid h-8 w-8 place-content-center rounded-md text-slate-500 transition-colors hover:bg-slate-100 hover:text-slate-900">
                            <PhotoIcon class="h-[18px] w-[18px]" aria-hidden="true"/>
                        </Link>
                    </RowActions>
                </TableCell>
            </tr>
            </tbody>
        </TableCard>
    </AuthenticatedLayout>
</template>

<script setup>
import {Link} from '@inertiajs/vue3';
import {PhotoIcon} from '@heroicons/vue/24/outline';
import AuthenticatedLayout from '@/Layouts/Admin/AuthenticatedLayout.vue';
import TableCard from '@/Components/Admin/ui/TableCard.vue';
import TableHeadingCell from '@/Components/Admin/ui/TableHeadingCell.vue';
import TableCell from '@/Components/Admin/ui/TableCell.vue';
import RowActions from '@/Components/Admin/ui/RowActions.vue';
import StatusBadge from '@/Components/Admin/ui/StatusBadge.vue';
import {confirmDelete} from '@/Composables/useConfirmDelete.js';

defineProps({
    properties: {type: Object, required: true},
});

const formatNumber = (value) => Number(value ?? 0).toLocaleString('en-US');
</script>
