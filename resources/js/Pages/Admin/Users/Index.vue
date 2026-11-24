<template>
    <AuthenticatedLayout title="Users" description="Every account: admins, staff and suppliers.">
        <TableCard noun="users" :meta="users.meta" :empty="!users.data.length"
                   :empty-text="role_filter ? 'No users with this role.' : 'No users yet. Add your first user.'"
                   creatable create-label="Add user" @create="modal.openCreate()">
            <template #actions>
                <FilterTabs :options="roles.data" :model-value="role_filter" param="role" label="Role"/>
            </template>

            <thead class="border-b border-slate-200">
            <tr>
                <TableHeadingCell>User</TableHeadingCell>
                <TableHeadingCell>Role</TableHeadingCell>
                <TableHeadingCell>Properties</TableHeadingCell>
                <TableHeadingCell>Joined</TableHeadingCell>
                <TableHeadingCell align="right"><span class="sr-only">Actions</span></TableHeadingCell>
            </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
            <tr v-for="user in users.data" :key="user.id" class="transition-colors hover:bg-slate-50">
                <TableCell>
                    <div class="min-w-[14rem]">
                        <RowTitle :title="user.name" @click="modal.openEdit(user)">{{ user.name }}</RowTitle>
                        <p class="text-xs text-slate-500">{{ user.email }}</p>
                    </div>
                </TableCell>
                <TableCell>
                    <StatusBadge v-if="user.role_name">{{ user.role_name }}</StatusBadge>
                    <span v-else class="text-slate-400">No role</span>
                </TableCell>
                <TableCell>{{ formatNumber(user.properties_count) }}</TableCell>
                <TableCell variant="muted">{{ formatDate(user.created_at) }}</TableCell>
                <TableCell align="right">
                    <RowActions editable @edit="modal.openEdit(user)" @delete="confirmDelete('admin.users.destroy', user.id, 'user')"/>
                </TableCell>
            </tr>
            </tbody>
        </TableCard>

        <UserForm :open="modal.isOpen.value" :user="modal.record.value" :roles="roles.data" @close="modal.close()"/>
    </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from '@/Layouts/Admin/AuthenticatedLayout.vue';
import FilterTabs from '@/Components/Admin/ui/FilterTabs.vue';
import RowActions from '@/Components/Admin/ui/RowActions.vue';
import RowTitle from '@/Components/Admin/ui/RowTitle.vue';
import StatusBadge from '@/Components/Admin/ui/StatusBadge.vue';
import TableCard from '@/Components/Admin/ui/TableCard.vue';
import TableCell from '@/Components/Admin/ui/TableCell.vue';
import TableHeadingCell from '@/Components/Admin/ui/TableHeadingCell.vue';
import {confirmDelete} from '@/Composables/useConfirmDelete.js';
import {formatDate, formatNumber} from '@/Composables/useFormatters.js';
import {useResourceModal} from '@/Composables/useResourceModal.js';
import UserForm from './UserForm.vue';

defineProps({
    users: {type: Object, required: true},
    roles: {type: Object, required: true},
    role_filter: {type: Number, default: null},
});

const modal = useResourceModal();
</script>
