<template>
    <div class="flex items-center gap-1.5">
        <Button v-if="property.review_status !== 'approved'" size="sm" :icon="CheckIcon" :loading="approving"
                @click="approve">Approve</Button>
        <Button v-if="property.review_status !== 'rejected'" size="sm" variant="secondary" :icon="XMarkIcon"
                @click="rejecting = true">Reject</Button>

        <FormModal :open="rejecting" :title="`Reject ${property.name}`" size="sm" submit-label="Reject"
                   description="The owner sees this reason on their dashboard and can fix the listing and resubmit."
                   :processing="form.processing" @close="rejecting = false" @submit="reject">
            <TextField v-model="form.reason" label="Reason" type="textarea" :rows="4" required
                       placeholder="e.g. The cover photo is blurry. Please upload a clearer one."
                       :error="form.errors.reason"/>
        </FormModal>
    </div>
</template>

<script setup>
import {ref} from 'vue';
import {router, useForm} from '@inertiajs/vue3';
import {CheckIcon, XMarkIcon} from '@heroicons/vue/20/solid';
import Button from '@/Components/Admin/ui/Button.vue';
import FormModal from '@/Components/Admin/ui/FormModal.vue';
import TextField from '@/Components/Admin/ui/TextField.vue';
import {showToast} from '@/Composables/useToast.js';

const props = defineProps({
    property: {type: Object, required: true},
});

const approving = ref(false);
const rejecting = ref(false);
const form = useForm({reason: ''});

const showServerError = (errors) => errors.database && showToast(errors.database, 'error');

function approve() {
    approving.value = true;
    router.patch(route('admin.properties.approve', props.property.id), {}, {
        preserveScroll: true,
        onSuccess: () => showToast(`${props.property.name} approved`),
        onError: showServerError,
        onFinish: () => (approving.value = false),
    });
}

function reject() {
    form.patch(route('admin.properties.reject', props.property.id), {
        preserveScroll: true,
        onSuccess: () => {
            rejecting.value = false;
            form.reset();
            showToast(`${props.property.name} rejected`);
        },
        onError: showServerError,
    });
}
</script>
