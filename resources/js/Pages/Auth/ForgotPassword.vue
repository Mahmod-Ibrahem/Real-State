<template>
    <GuestLayout title="Reset your password" description="Enter your email and we'll send you a link to choose a new password."
                 :status="status">
        <form class="space-y-4" @submit.prevent="form.post(route('password.email'))">
            <TextField v-model="form.email" label="Email" type="email" autocomplete="username" required autofocus
                       :error="form.errors.email"/>
            <Button type="submit" class="w-full" :loading="form.processing">Email reset link</Button>
        </form>

        <template #footer>
            <Link :href="route('login')" class="font-medium text-indigo-600 hover:text-indigo-700">Back to sign in</Link>
        </template>
    </GuestLayout>
</template>

<script setup>
import {Link, useForm} from '@inertiajs/vue3';
import GuestLayout from '@/Layouts/Admin/GuestLayout.vue';
import Button from '@/Components/Admin/ui/Button.vue';
import TextField from '@/Components/Admin/ui/TextField.vue';

defineProps({
    status: {type: String, default: ''},
});

const form = useForm({email: ''});
</script>
