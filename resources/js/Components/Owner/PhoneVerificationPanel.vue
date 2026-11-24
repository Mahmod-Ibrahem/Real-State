<template>
    <Panel title="Verify your phone" :hint="`We'll text a 6-digit code to ${phone}`">
        <form class="flex flex-col gap-3 sm:flex-row sm:items-end" @submit.prevent="verify">
            <TextField v-if="codeSent" v-model="form.code" label="Code" class="sm:w-40" inputmode="numeric"
                       autocomplete="one-time-code" maxlength="6" placeholder="123456" required :error="form.errors.code"/>
            <div class="flex gap-2">
                <Button v-if="codeSent" type="submit" :loading="form.processing">Verify</Button>
                <Button :variant="codeSent ? 'secondary' : 'primary'" :loading="sending" @click="sendCode">
                    {{ codeSent ? 'Send a new code' : 'Send code' }}
                </Button>
            </div>
        </form>
    </Panel>
</template>

<script setup>
import {ref} from 'vue';
import {router, useForm} from '@inertiajs/vue3';
import Button from '@/Components/Admin/ui/Button.vue';
import Panel from '@/Components/Admin/ui/Panel.vue';
import TextField from '@/Components/Admin/ui/TextField.vue';
import {showToast} from '@/Composables/useToast.js';

defineProps({
    // E.164, e.g. +201001234567
    phone: {type: String, required: true},
});

const codeSent = ref(false);
const sending = ref(false);
const form = useForm({code: ''});

const showServerError = (errors) => errors.database && showToast(errors.database, 'error');

function sendCode() {
    sending.value = true;
    router.post(route('supplier.phone.code'), {}, {
        preserveScroll: true,
        onSuccess: () => {
            codeSent.value = true;
            form.reset();
            showToast('Code sent');
        },
        onError: showServerError,
        onFinish: () => (sending.value = false),
    });
}

function verify() {
    form.post(route('supplier.phone.verify'), {
        preserveScroll: true,
        onSuccess: () => showToast('Phone number verified'),
        onError: showServerError,
    });
}
</script>
