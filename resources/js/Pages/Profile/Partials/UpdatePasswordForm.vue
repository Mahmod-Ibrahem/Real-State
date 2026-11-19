<template>
    <Panel title="Password" hint="Use a long, unique password.">
        <form class="space-y-5" @submit.prevent="save">
            <TextField v-model="form.current_password" label="Current password" type="password" autocomplete="current-password"
                       required class="sm:max-w-sm" :error="form.errors.current_password"/>
            <div class="grid gap-5 sm:grid-cols-2">
                <TextField v-model="form.password" label="New password" type="password" autocomplete="new-password" required
                           :error="form.errors.password"/>
                <TextField v-model="form.password_confirmation" label="Confirm new password" type="password"
                           autocomplete="new-password" required :error="form.errors.password_confirmation"/>
            </div>
            <Button type="submit" :loading="form.processing">Update password</Button>
        </form>
    </Panel>
</template>

<script setup>
import {useForm} from '@inertiajs/vue3';
import Button from '@/Components/Admin/ui/Button.vue';
import Panel from '@/Components/Admin/ui/Panel.vue';
import TextField from '@/Components/Admin/ui/TextField.vue';
import {showToast} from '@/Composables/useToast.js';

const form = useForm({
    current_password: '',
    password: '',
    password_confirmation: '',
});

const save = () => form.put(route('password.update'), {
    preserveScroll: true,
    onSuccess: () => {
        form.reset();
        showToast('Password updated');
    },
    onError: () => form.reset('password', 'password_confirmation', 'current_password'),
});
</script>
