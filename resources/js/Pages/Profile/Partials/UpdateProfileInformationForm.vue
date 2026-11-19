<template>
    <Panel title="Profile information" hint="Shown in the admin top bar.">
        <form class="space-y-5" @submit.prevent="save">
            <div class="grid gap-5 sm:grid-cols-2">
                <TextField v-model="form.name" label="Name" autocomplete="name" required :error="form.errors.name"/>
                <TextField v-model="form.email" label="Email" type="email" autocomplete="username" required
                           :error="form.errors.email"/>
            </div>

            <div v-if="mustVerifyEmail && user.email_verified_at === null" class="rounded-lg bg-amber-50 px-3 py-2 text-sm text-amber-800">
                Your email address is unverified.
                <Link :href="route('verification.send')" method="post" as="button" class="font-medium underline">
                    Resend the verification email
                </Link>
                <span v-if="status === 'verification-link-sent'" class="block text-green-700">A new link has been sent.</span>
            </div>

            <Button type="submit" :loading="form.processing">Save</Button>
        </form>
    </Panel>
</template>

<script setup>
import {Link, useForm, usePage} from '@inertiajs/vue3';
import Button from '@/Components/Admin/ui/Button.vue';
import Panel from '@/Components/Admin/ui/Panel.vue';
import TextField from '@/Components/Admin/ui/TextField.vue';
import {showToast} from '@/Composables/useToast.js';

defineProps({
    mustVerifyEmail: {type: Boolean, default: false},
    status: {type: String, default: ''},
});

const user = usePage().props.auth.user;
const form = useForm({
    name: user.name,
    email: user.email,
});

const save = () => form.patch(route('admin.profile.update'), {
    preserveScroll: true,
    onSuccess: () => showToast('Profile updated'),
});
</script>
