<template>
    <GuestLayout title="Choose a new password">
        <form class="space-y-4" @submit.prevent="submit">
            <TextField v-model="form.email" label="Email" type="email" autocomplete="username" required
                       :error="form.errors.email"/>
            <TextField v-model="form.password" label="New password" type="password" autocomplete="new-password" required
                       autofocus :error="form.errors.password"/>
            <TextField v-model="form.password_confirmation" label="Confirm password" type="password" autocomplete="new-password"
                       required :error="form.errors.password_confirmation"/>
            <Button type="submit" class="w-full" :loading="form.processing">Reset password</Button>
        </form>
    </GuestLayout>
</template>

<script setup>
import {useForm} from '@inertiajs/vue3';
import GuestLayout from '@/Layouts/Admin/GuestLayout.vue';
import Button from '@/Components/Admin/ui/Button.vue';
import TextField from '@/Components/Admin/ui/TextField.vue';

const props = defineProps({
    email: {type: String, required: true},
    token: {type: String, required: true},
});

const form = useForm({
    token: props.token,
    email: props.email,
    password: '',
    password_confirmation: '',
});

const submit = () => form.post(route('password.store'), {onFinish: () => form.reset('password', 'password_confirmation')});
</script>
