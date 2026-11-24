<template>
    <FormModal :open="open" :title="`Reject ${property?.name ?? 'property'}`" size="sm" submit-label="Reject"
               description="The supplier sees this reason on their dashboard and can fix the listing and resubmit."
               :processing="form.processing" @close="emit('close')" @submit="reject">
        <TextField v-model="form.reason" label="Reason" type="textarea" :rows="4" required
                   placeholder="e.g. The cover photo is blurry. Please upload a clearer one."
                   :error="form.errors.reason"/>
    </FormModal>
</template>

<script setup>
import {useForm} from '@inertiajs/vue3';
import FormModal from '@/Components/Admin/ui/FormModal.vue';
import TextField from '@/Components/Admin/ui/TextField.vue';
import {resetFormOnOpen} from '@/Composables/useResourceForm.js';
import {showToast} from '@/Composables/useToast.js';

const props = defineProps({
    open: {type: Boolean, default: false},
    // The property being rejected; null while closed on a list page that shares one modal
    property: {type: Object, default: null},
});
const emit = defineEmits(['close']);

const form = useForm({reason: ''});
resetFormOnOpen(form, () => props.open, () => ({reason: ''}));

function reject() {
    const {id, name} = props.property;

    form.patch(route('admin.properties.reject', id), {
        preserveScroll: true,
        onSuccess: () => {
            emit('close');
            showToast(`${name} rejected`);
        },
        onError: (errors) => errors.database && showToast(errors.database, 'error'),
    });
}
</script>
