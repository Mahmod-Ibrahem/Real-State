<template>
    <GuestLayout title="List your property" description="Create a supplier account to add and manage your listings.">
        <form class="space-y-4" @submit.prevent="submit">
            <AvatarField v-model="form.avatar" :name="form.name" label="Photo (optional)"
                         hint="Shown with your properties on the website." :error="form.errors.avatar"/>
            <TextField v-model="form.name" label="Name" autocomplete="name" required autofocus :error="form.errors.name"/>
            <TextField v-model="form.email" label="Email" type="email" autocomplete="username" required :error="form.errors.email"/>
            <PhoneField v-model="form.phone" v-model:country="form.phone_country" required
                        :error="form.errors.phone" :country-error="form.errors.phone_country"/>
            <TextField v-model="form.password" label="Password" type="password" autocomplete="new-password" required
                       :error="form.errors.password"/>
            <TextField v-model="form.password_confirmation" label="Confirm password" type="password" autocomplete="new-password"
                       required :error="form.errors.password_confirmation"/>
            <Button type="submit" class="w-full" :loading="form.processing">Create supplier account</Button>
        </form>

        <template #footer>
            Already registered? <Link :href="route('supplier.login')" class="font-medium text-indigo-600 hover:text-indigo-700">Sign in</Link>
        </template>
    </GuestLayout>
</template>

<script setup>
import {Link, useForm} from '@inertiajs/vue3';
import GuestLayout from '@/Layouts/Admin/GuestLayout.vue';
import AvatarField from '@/Components/Admin/ui/AvatarField.vue';
import Button from '@/Components/Admin/ui/Button.vue';
import PhoneField from '@/Components/Admin/ui/PhoneField.vue';
import TextField from '@/Components/Admin/ui/TextField.vue';

const form = useForm({
    name: '',
    email: '',
    phone: '',
    phone_country: 'EG',
    password: '',
    password_confirmation: '',
    avatar: null,
});

const submit = () => form.post(route('supplier.register'), {onFinish: () => form.reset('password', 'password_confirmation')});
</script>
