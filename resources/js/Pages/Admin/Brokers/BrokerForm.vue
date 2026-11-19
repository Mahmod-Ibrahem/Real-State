<template>
    <FormCard :submit-label="broker ? 'Save changes' : 'Add broker'" :cancel-href="route('broker.index')"
              :processing="form.processing"
              @submit="saveResource(form, 'broker', 'broker', {id: broker?.id, multipart: true})">
        <div class="grid gap-5 sm:grid-cols-2">
            <TextField v-model="form.name" label="Name" required autofocus :error="form.errors.name"/>
            <TextField v-model="form.job_title" label="Job title" placeholder="e.g. Senior Agent" required
                       :error="form.errors.job_title"/>
        </div>
        <ImageField v-model="form.image" label="Photo" :current-url="broker?.image ?? ''"
                    hint="A square portrait works best. Up to 2 MB." :error="form.errors.image"/>
        <div class="grid gap-5 sm:grid-cols-3">
            <TextField v-model="form.facebook" label="Facebook" type="url" placeholder="https://facebook.com/…"
                       :error="form.errors.facebook"/>
            <TextField v-model="form.instagram" label="Instagram" type="url" placeholder="https://instagram.com/…"
                       :error="form.errors.instagram"/>
            <TextField v-model="form.twitter" label="X / Twitter" type="url" placeholder="https://x.com/…"
                       :error="form.errors.twitter"/>
        </div>
    </FormCard>
</template>

<script setup>
import {useForm} from '@inertiajs/vue3';
import FormCard from '@/Components/Admin/ui/FormCard.vue';
import ImageField from '@/Components/Admin/ui/ImageField.vue';
import TextField from '@/Components/Admin/ui/TextField.vue';
import {currentLocale, saveResource} from '@/Composables/useResourceForm.js';

const props = defineProps({
    // null when creating
    broker: {type: Object, default: null},
});

const form = useForm({
    name: props.broker?.name ?? '',
    job_title: props.broker?.job_title ?? '',
    facebook: props.broker?.facebook ?? '',
    instagram: props.broker?.instagram ?? '',
    twitter: props.broker?.twitter ?? '',
    image: null,
    locale: currentLocale(),
});
</script>
