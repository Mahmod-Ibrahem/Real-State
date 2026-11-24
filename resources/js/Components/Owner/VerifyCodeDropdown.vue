<template>
    <div ref="root" class="relative">
        <Button size="sm" :aria-expanded="open" aria-haspopup="dialog" :loading="sending && !codeSent" @click="toggle">
            Verify
        </Button>

        <transition enter-active-class="transition duration-150 ease-out" enter-from-class="opacity-0"
                    leave-active-class="transition duration-100 ease-in" leave-to-class="opacity-0">
            <div v-if="open" role="dialog" :aria-labelledby="`verify-${channel}-title`"
                 class="absolute start-0 top-full z-50 mt-2 w-[22rem] max-w-[calc(100vw-2rem)] rounded-xl border border-slate-200 bg-white shadow-lg">
                <div class="flex items-start justify-between gap-3 border-b border-slate-100 px-4 py-3">
                    <div class="min-w-0">
                        <h3 :id="`verify-${channel}-title`" class="text-sm font-semibold text-slate-900">{{ config.title }}</h3>
                        <p class="mt-0.5 text-xs text-slate-500">
                            {{ config.hint }}
                            <span class="break-all font-medium text-slate-700" dir="ltr">{{ destination }}</span>
                        </p>
                    </div>
                    <button type="button" aria-label="Close" @click="open = false"
                            class="-me-1 grid h-7 w-7 shrink-0 place-content-center rounded-md text-slate-400 transition-colors hover:bg-slate-100 hover:text-slate-600 focus:outline-none focus-visible:ring-2 focus-visible:ring-indigo-500/30">
                        <XMarkIcon class="h-4 w-4" aria-hidden="true"/>
                    </button>
                </div>

                <form class="space-y-4 p-4" @submit.prevent="verify">
                    <OtpField ref="otp" v-model="form.code" :error="form.errors.code" :disabled="form.processing"
                              @complete="verify"/>
                    <Button type="submit" class="w-full" :loading="form.processing">{{ config.submit }}</Button>
                </form>

                <p class="rounded-b-xl border-t border-slate-100 bg-slate-50/80 px-4 py-2.5 text-xs text-slate-500">
                    Didn't get it?
                    <button type="button" :disabled="sending" @click="sendCode()"
                            class="font-medium text-indigo-600 transition-colors hover:text-indigo-700 focus:outline-none focus-visible:underline disabled:opacity-50">
                        {{ sending ? 'Sending…' : 'Send a new code' }}
                    </button>
                </p>
            </div>
        </transition>
    </div>
</template>

<script setup>
import {computed, nextTick, ref} from 'vue';
import {router, useForm} from '@inertiajs/vue3';
import {XMarkIcon} from '@heroicons/vue/24/outline';
import Button from '@/Components/Admin/ui/Button.vue';
import OtpField from '@/Components/Admin/ui/OtpField.vue';
import {useDismissible} from '@/Composables/useDismissible.js';
import {showToast} from '@/Composables/useToast.js';

const CHANNELS = {
    phone: {
        title: 'Verify your phone',
        hint: 'Enter the 6-digit code we sent to',
        submit: 'Verify phone',
        verified: 'Phone number verified',
        sendRoute: 'supplier.phone.code',
        verifyRoute: 'supplier.phone.verify',
    },
    email: {
        title: 'Verify your email',
        hint: 'Enter the 6-digit code we emailed to',
        submit: 'Verify email',
        verified: 'Email address verified',
        sendRoute: 'supplier.email.code',
        verifyRoute: 'supplier.email.verify',
    },
};

const props = defineProps({
    channel: {type: String, required: true, validator: (value) => ['phone', 'email'].includes(value)},
    // Where the code goes: the readable phone number or the email address
    destination: {type: String, required: true},
});

const config = computed(() => CHANNELS[props.channel]);

const open = ref(false);
const root = ref(null);
const otp = ref(null);
const sending = ref(false);
const codeSent = ref(false);
const form = useForm({code: ''});
useDismissible(root, open);

const showServerError = (errors) => errors.database && showToast(errors.database, 'error');

function toggle() {
    if (open.value) {
        open.value = false;
        return;
    }

    codeSent.value ? showPanel() : sendCode(showPanel);
}

async function showPanel() {
    open.value = true;
    await nextTick();
    otp.value?.focus();
}

function sendCode(onSent = () => {}) {
    sending.value = true;
    router.post(route(config.value.sendRoute), {}, {
        preserveScroll: true,
        onSuccess: () => {
            codeSent.value = true;
            form.reset();
            form.clearErrors();
            showToast('Code sent');
            onSent();
        },
        onError: showServerError,
        onFinish: () => (sending.value = false),
    });
}

function verify() {
    if (form.processing) return;

    form.post(route(config.value.verifyRoute), {
        preserveScroll: true,
        onSuccess: () => {
            open.value = false;
            showToast(config.value.verified);
        },
        onError: (errors) => {
            form.reset('code');
            showServerError(errors);
            nextTick(() => otp.value?.focus());
        },
    });
}
</script>
