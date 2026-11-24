<template>
    <div ref="root" class="relative">
        <Button size="sm" :aria-expanded="open" aria-haspopup="dialog" @click="toggle">Verify</Button>

        <transition enter-active-class="transition duration-100 ease-out" enter-from-class="opacity-0"
                    leave-active-class="transition duration-75 ease-in" leave-to-class="opacity-0">
            <form v-if="open" role="dialog" aria-label="Verify your phone" @submit.prevent="verify"
                  class="absolute right-0 z-50 mt-1.5 w-72 space-y-4 rounded-lg border border-slate-200 bg-white p-4 shadow-lg">
                <p class="text-sm text-slate-600">
                    Enter the 6-digit code we texted to <span class="font-medium text-slate-900" dir="ltr">{{ phone }}</span>
                </p>
                <OtpField ref="otp" v-model="form.code" :error="form.errors.code" :disabled="form.processing"
                          @complete="verify"/>
                <div class="space-y-1">
                    <Button type="submit" class="w-full" :loading="form.processing" :disabled="form.code.length < 6">
                        Verify
                    </Button>
                    <Button variant="ghost" class="w-full" :loading="sending" @click="sendCode">Send a new code</Button>
                </div>
            </form>
        </transition>
    </div>
</template>

<script setup>
import {nextTick, ref} from 'vue';
import {router, useForm} from '@inertiajs/vue3';
import Button from '@/Components/Admin/ui/Button.vue';
import OtpField from '@/Components/Admin/ui/OtpField.vue';
import {useDismissible} from '@/Composables/useDismissible.js';
import {showToast} from '@/Composables/useToast.js';

defineProps({
    phone: {type: String, required: true},
});

const open = ref(false);
const root = ref(null);
const otp = ref(null);
const sending = ref(false);
const codeSent = ref(false);
const form = useForm({code: ''});
useDismissible(root, open);

const showServerError = (errors) => errors.database && showToast(errors.database, 'error');

async function toggle() {
    open.value = !open.value;
    if (!open.value) return;

    if (!codeSent.value) sendCode();
    await nextTick();
    otp.value?.focus();
}

function sendCode() {
    sending.value = true;
    router.post(route('supplier.phone.code'), {}, {
        preserveScroll: true,
        onSuccess: () => {
            codeSent.value = true;
            form.reset();
            form.clearErrors();
            showToast('Code sent');
        },
        onError: showServerError,
        onFinish: () => (sending.value = false),
    });
}

function verify() {
    if (form.processing) return;

    form.post(route('supplier.phone.verify'), {
        preserveScroll: true,
        onSuccess: () => {
            open.value = false;
            showToast('Phone number verified');
        },
        onError: (errors) => {
            form.reset('code');
            showServerError(errors);
            nextTick(() => otp.value?.focus());
        },
    });
}
</script>
