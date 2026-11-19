<template>
    <FormCard submit-label="Save changes" :cancel-href="route('content.index')" :processing="form.processing"
              @submit="saveResource(form, 'content', 'content', {id: basicContent.id})">
        <TextField v-model="form.name" label="Name" required :error="form.errors.name"/>
        <TextField v-model="form.content" label="Content" type="textarea" :rows="4" required
                   hint="For video blocks, paste the video URL or embed code." :error="form.errors.content"/>
    </FormCard>
</template>

<script setup>
import {useForm} from '@inertiajs/vue3';
import FormCard from '@/Components/Admin/ui/FormCard.vue';
import TextField from '@/Components/Admin/ui/TextField.vue';
import {currentLocale, saveResource} from '@/Composables/useResourceForm.js';

const props = defineProps({
    basicContent: {type: Object, required: true},
});

const form = useForm({
    name: props.basicContent.name ?? '',
    content: props.basicContent.content ?? '',
    locale: currentLocale(),
});
</script>
