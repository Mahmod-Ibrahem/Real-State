<template>
    <FormModal :open="open" :title="broker ? `Edit ${broker.name}` : 'New broker'" size="lg"
               :submit-label="broker ? 'Save changes' : 'Add broker'" :processing="form.processing"
               @close="emit('close')" @submit="save">
        <div class="grid gap-5 sm:grid-cols-[10rem_1fr]">
            <ImageField v-model="form.image" label="Photo" :current-url="broker?.image ?? ''" :aspect-ratio="1"
                        :required="!broker" :error="form.errors.image"/>
            <div class="space-y-5">
                <TextField v-model="form.name" label="Name" required :error="form.errors.name"/>
                <TextField v-model="form.job_title" label="Job title" placeholder="e.g. Senior Agent" required
                           :error="form.errors.job_title"/>
            </div>
        </div>
        <TextField v-model="form.facebook" label="Facebook" type="url" placeholder="https://facebook.com/…"
                   :error="form.errors.facebook"/>
        <TextField v-model="form.instagram" label="Instagram" type="url" placeholder="https://instagram.com/…"
                   :error="form.errors.instagram"/>
        <TextField v-model="form.twitter" label="X / Twitter" type="url" placeholder="https://x.com/…"
                   :error="form.errors.twitter"/>
    </FormModal>
</template>

<script setup>
import {useForm} from '@inertiajs/vue3';
import FormModal from '@/Components/Admin/ui/FormModal.vue';
import ImageField from '@/Components/Admin/ui/ImageField.vue';
import TextField from '@/Components/Admin/ui/TextField.vue';
import {currentLocale, resetFormOnOpen, saveResource} from '@/Composables/useResourceForm.js';

const props = defineProps({
    open: {type: Boolean, default: false},
    // null when creating
    broker: {type: Object, default: null},
});
const emit = defineEmits(['close']);

const locale = currentLocale();
const fields = () => ({
    name: props.broker?.name ?? '',
    job_title: props.broker?.job_title ?? '',
    facebook: props.broker?.facebook ?? '',
    instagram: props.broker?.instagram ?? '',
    twitter: props.broker?.twitter ?? '',
    // Only a newly cropped photo; empty keeps the saved one
    image: null,
    locale,
});

const form = useForm(fields());
resetFormOnOpen(form, () => props.open, fields);

const save = () => saveResource(form, 'broker', 'broker',
    {id: props.broker?.id, multipart: true, onSuccess: () => emit('close')});
</script>
