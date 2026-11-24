<template>
    <AuthenticatedLayout title="Users" description="Every account: staff and suppliers.">
        <TableCard noun="users" :meta="users.meta" :empty="!users.data.length"
                   :empty-text="isFiltered ? 'No users match these filters.' : 'No users yet. Add your first user.'"
                   creatable create-label="Add user" @create="modal.openCreate()">
            <template #actions>
                <div class="flex flex-wrap items-center gap-2">
                    <FilterSelect :options="statuses" :model-value="filters.status" param="status" label="Status"/>
                    <FilterSelect :options="roles.data" :model-value="filters.role" param="role" label="Role"/>
                    <FilterSelect :options="reviews" :model-value="filters.review" param="review" label="Review"/>
                    <Button v-if="isFiltered" variant="ghost" size="sm" :icon="XMarkIcon" @click="clearFilters">Clear filters</Button>
                </div>
            </template>

            <thead class="border-b border-slate-200">
            <tr>
                <TableHeadingCell>User</TableHeadingCell>
                <TableHeadingCell>Role</TableHeadingCell>
                <TableHeadingCell>Status</TableHeadingCell>
                <TableHeadingCell>Review</TableHeadingCell>
                <TableHeadingCell>Properties</TableHeadingCell>
                <TableHeadingCell>Joined</TableHeadingCell>
                <TableHeadingCell align="right"><span class="sr-only">Actions</span></TableHeadingCell>
            </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
            <tr v-for="user in users.data" :key="user.id" class="transition-colors hover:bg-slate-50">
                <TableCell>
                    <div class="flex min-w-[15rem] items-center gap-3">
                        <span class="grid h-8 w-8 shrink-0 place-content-center rounded-full bg-indigo-50 text-xs font-semibold text-indigo-700"
                              aria-hidden="true">
                            {{ initialOf(user.name) }}
                        </span>
                        <div class="min-w-0">
                            <RowTitle :title="user.name" @click="modal.openEdit(user)">{{ user.name }}</RowTitle>
                            <p class="truncate text-xs text-slate-500">{{ user.email }}</p>
                        </div>
                    </div>
                </TableCell>
                <TableCell>
                    <StatusBadge v-if="user.role_name">{{ user.role_name }}</StatusBadge>
                    <span v-else class="text-slate-400">No role</span>
                </TableCell>
                <TableCell>
                    <ToggleButton :label="user.is_active ? 'Active' : 'Disabled'" :next-label="user.is_active ? 'Disabled' : 'Active'"
                                  :tone="user.is_active ? 'success' : 'neutral'" :pressed="user.is_active"
                                  :loading="isSaving(user, 'is_active')"
                                  @toggle="quickUpdate(user, 'is_active', !user.is_active)"/>
                </TableCell>
                <TableCell>
                    <ToggleButton :label="user.under_review ? 'Under review' : 'Approved'"
                                  :next-label="user.under_review ? 'Approved' : 'Under review'"
                                  :tone="user.under_review ? 'warning' : 'success'" :pressed="!user.under_review"
                                  :loading="isSaving(user, 'under_review')"
                                  @toggle="quickUpdate(user, 'under_review', !user.under_review)"/>
                </TableCell>
                <TableCell class="tabular-nums">{{ formatNumber(user.properties_count) }}</TableCell>
                <TableCell variant="muted" class="whitespace-nowrap">{{ formatDate(user.created_at) }}</TableCell>
                <TableCell align="right">
                    <RowActions editable @edit="modal.openEdit(user)" @delete="confirmDelete('admin.users.destroy', user.id, 'user')">
                        <button v-if="canImpersonate && user.can_be_impersonated" type="button"
                                :title="`Log in as ${user.name}`" :aria-label="`Log in as ${user.name}`"
                                :disabled="impersonatingId !== null" @click="impersonate(user)"
                                class="grid h-8 w-8 place-content-center rounded-md text-slate-500 transition-colors hover:bg-slate-100 hover:text-slate-900 focus:outline-none focus-visible:ring-2 focus-visible:ring-indigo-500/30 disabled:opacity-50">
                            <ArrowPathIcon v-if="impersonatingId === user.id" class="h-[18px] w-[18px] animate-spin" aria-hidden="true"/>
                            <ArrowRightEndOnRectangleIcon v-else class="h-[18px] w-[18px]" aria-hidden="true"/>
                        </button>
                    </RowActions>
                </TableCell>
            </tr>
            </tbody>
        </TableCard>

        <UserForm :open="modal.isOpen.value" :user="modal.record.value" :roles="roles.data" @close="modal.close()"/>
    </AuthenticatedLayout>
</template>

<script setup>
import {computed, reactive, ref} from 'vue';
import {router} from '@inertiajs/vue3';
import {XMarkIcon} from '@heroicons/vue/20/solid';
import {ArrowPathIcon, ArrowRightEndOnRectangleIcon} from '@heroicons/vue/24/outline';
import AuthenticatedLayout from '@/Layouts/Admin/AuthenticatedLayout.vue';
import Button from '@/Components/Admin/ui/Button.vue';
import FilterSelect from '@/Components/Admin/ui/FilterSelect.vue';
import RowActions from '@/Components/Admin/ui/RowActions.vue';
import RowTitle from '@/Components/Admin/ui/RowTitle.vue';
import StatusBadge from '@/Components/Admin/ui/StatusBadge.vue';
import TableCard from '@/Components/Admin/ui/TableCard.vue';
import TableCell from '@/Components/Admin/ui/TableCell.vue';
import TableHeadingCell from '@/Components/Admin/ui/TableHeadingCell.vue';
import ToggleButton from '@/Components/Admin/ui/ToggleButton.vue';
import {confirmDelete} from '@/Composables/useConfirmDelete.js';
import {formatDate, formatNumber} from '@/Composables/useFormatters.js';
import {can} from '@/Composables/usePermissions.js';
import {useResourceModal} from '@/Composables/useResourceModal.js';
import {showToast} from '@/Composables/useToast.js';
import UserForm from './UserForm.vue';

const FILTER_PARAMS = ['status', 'role', 'review'];

const props = defineProps({
    users: {type: Object, required: true},
    roles: {type: Object, required: true},
    statuses: {type: Array, required: true},
    reviews: {type: Array, required: true},
    filters: {type: Object, required: true},
});

const modal = useResourceModal();

const canImpersonate = can('impersonate users');
const impersonatingId = ref(null);

function impersonate(user) {
    impersonatingId.value = user.id;
    router.post(route('admin.users.impersonate', user.id), {}, {
        onError: (errors) => errors.database && showToast(errors.database, 'error'),
        onFinish: () => (impersonatingId.value = null),
    });
}

const isFiltered = computed(() => FILTER_PARAMS.some((param) => props.filters[param] !== null));
const initialOf = (name) => name.trim().charAt(0).toUpperCase();

function clearFilters() {
    const params = Object.fromEntries(new URLSearchParams(window.location.search));
    FILTER_PARAMS.forEach((param) => delete params[param]);
    delete params.page;

    router.get(window.location.pathname, params, {preserveState: true, preserveScroll: true, replace: true});
}

const saving = reactive({});
const isSaving = (user, field) => Boolean(saving[`${user.id}.${field}`]);

function quickUpdate(user, field, value) {
    const key = `${user.id}.${field}`;
    saving[key] = true;
    router.patch(route('admin.users.account', user.id), {[field]: value}, {
        preserveScroll: true,
        onSuccess: () => showToast(`${user.name} updated`),
        onError: (errors) => showToast(Object.values(errors)[0] ?? 'The user could not be updated.', 'error'),
        onFinish: () => delete saving[key],
    });
}
</script>
