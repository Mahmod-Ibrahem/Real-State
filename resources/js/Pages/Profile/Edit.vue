<template>
    <AuthenticatedLayout title="Profile" description="Your name, email and password.">
        <div class="grid max-w-3xl gap-4">
            <UpdateProfileInformationForm :must-verify-email="mustVerifyEmail" :status="status"/>
            <UpdatePasswordForm/>
            <DeleteUserForm/>
        </div>
    </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from '@/Layouts/Admin/AuthenticatedLayout.vue';
import DeleteUserForm from './Partials/DeleteUserForm.vue';
import UpdatePasswordForm from './Partials/UpdatePasswordForm.vue';
import UpdateProfileInformationForm from './Partials/UpdateProfileInformationForm.vue';

defineProps({
    mustVerifyEmail: {type: Boolean, default: false},
    status: {type: String, default: ''},
});
</script>
