<template>
    <GuestLayout title="Verify your email"
                 description="We sent you a verification link. Didn't get it? We can send another."
                 :status="status === 'verification-link-sent' ? 'A new verification link has been sent to your email.' : ''">
        <div class="flex items-center justify-between gap-3">
            <Button :loading="form.processing" @click="form.post(route('verification.send'))">Resend email</Button>
            <Link :href="route('logout')" method="post" as="button"
                  class="text-sm font-medium text-slate-600 hover:text-slate-900">
                Log out
            </Link>
        </div>
    </GuestLayout>
</template>

<script setup>
import {Link, useForm} from '@inertiajs/vue3';
import GuestLayout from '@/Layouts/Admin/GuestLayout.vue';
import Button from '@/Components/Admin/ui/Button.vue';

defineProps({
    status: {type: String, default: ''},
});

const form = useForm({});
</script>
