<template>
    <GuestLayout title="Confirm your password" description="This is a secure area. Please confirm your password to continue.">
        <form class="space-y-4" @submit.prevent="submit">
            <TextField v-model="form.password" label="Password" type="password" autocomplete="current-password" required
                       autofocus :error="form.errors.password"/>
            <Button type="submit" class="w-full" :loading="form.processing">Confirm</Button>
        </form>
    </GuestLayout>
</template>

<script setup>
import {useForm} from '@inertiajs/vue3';
import GuestLayout from '@/Layouts/Admin/GuestLayout.vue';
import Button from '@/Components/Admin/ui/Button.vue';
import TextField from '@/Components/Admin/ui/TextField.vue';

const form = useForm({password: ''});

const submit = () => form.post(route('password.confirm'), {onFinish: () => form.reset()});
</script>
