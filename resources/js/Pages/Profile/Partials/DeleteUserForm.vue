<template>
    <Panel title="Delete account" hint="Permanently removes your account. This can't be undone.">
        <Button v-if="!confirming" variant="danger" @click="confirming = true">Delete account</Button>

        <!-- Two-step: the password is the confirmation -->
        <form v-else class="space-y-4 rounded-lg border border-red-200 bg-red-50/50 p-4" @submit.prevent="destroy">
            <p class="text-sm text-slate-700">Enter your password to confirm you want to delete your account.</p>
            <TextField v-model="form.password" label="Password" type="password" autocomplete="current-password" required
                       autofocus class="sm:max-w-sm" :error="form.errors.password"/>
            <div class="flex gap-2">
                <Button type="submit" variant="danger" :loading="form.processing">Delete my account</Button>
                <Button variant="ghost" @click="cancel">Cancel</Button>
            </div>
        </form>
    </Panel>
</template>

<script setup>
import {ref} from 'vue';
import {useForm} from '@inertiajs/vue3';
import Button from '@/Components/Admin/ui/Button.vue';
import Panel from '@/Components/Admin/ui/Panel.vue';
import TextField from '@/Components/Admin/ui/TextField.vue';

const confirming = ref(false);
const form = useForm({password: ''});

const cancel = () => {
    confirming.value = false;
    form.reset();
    form.clearErrors();
};

const destroy = () => form.delete(route('admin.profile.destroy'), {
    preserveScroll: true,
    onFinish: () => form.reset(),
});
</script>
