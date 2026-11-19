<template>
    <GuestLayout title="Create an account">
        <form class="space-y-4" @submit.prevent="submit">
            <TextField v-model="form.name" label="Name" autocomplete="name" required autofocus :error="form.errors.name"/>
            <TextField v-model="form.email" label="Email" type="email" autocomplete="username" required :error="form.errors.email"/>
            <TextField v-model="form.password" label="Password" type="password" autocomplete="new-password" required
                       :error="form.errors.password"/>
            <TextField v-model="form.password_confirmation" label="Confirm password" type="password" autocomplete="new-password"
                       required :error="form.errors.password_confirmation"/>
            <Button type="submit" class="w-full" :loading="form.processing">Create account</Button>
        </form>

        <template #footer>
            Already registered? <Link :href="route('login')" class="font-medium text-indigo-600 hover:text-indigo-700">Sign in</Link>
        </template>
    </GuestLayout>
</template>

<script setup>
import {Link, useForm} from '@inertiajs/vue3';
import GuestLayout from '@/Layouts/Admin/GuestLayout.vue';
import Button from '@/Components/Admin/ui/Button.vue';
import TextField from '@/Components/Admin/ui/TextField.vue';

const form = useForm({
    name: '',
    email: '',
    password: '',
    password_confirmation: '',
});

const submit = () => form.post(route('register'), {onFinish: () => form.reset('password', 'password_confirmation')});
</script>
