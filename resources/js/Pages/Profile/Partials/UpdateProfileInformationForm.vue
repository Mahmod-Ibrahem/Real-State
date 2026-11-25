<template>
    <Panel title="Profile information" hint="Shown in the admin top bar.">
        <form class="space-y-5" @submit.prevent="save">
            <AvatarField v-model="form.avatar" v-model:removed="form.remove_avatar" :current="user.avatar" :name="form.name"
                         label="Photo" :error="form.errors.avatar"/>
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
import {computed} from 'vue';
import {Link, useForm, usePage} from '@inertiajs/vue3';
import AvatarField from '@/Components/Admin/ui/AvatarField.vue';
import Button from '@/Components/Admin/ui/Button.vue';
import Panel from '@/Components/Admin/ui/Panel.vue';
import TextField from '@/Components/Admin/ui/TextField.vue';
import {showToast} from '@/Composables/useToast.js';

defineProps({
    mustVerifyEmail: {type: Boolean, default: false},
    status: {type: String, default: ''},
});

const page = usePage();
const user = computed(() => page.props.auth.user);
const form = useForm({
    name: user.value.name,
    email: user.value.email,
    avatar: null,
    remove_avatar: false,
});

// Sent as POST + _method so the photo uploads as multipart
const save = () => form.transform((data) => ({...data, _method: 'patch'})).post(route('admin.profile.update'), {
    preserveScroll: true,
    onSuccess: () => {
        form.reset('avatar', 'remove_avatar');
        showToast('Profile updated');
    },
});
</script>
