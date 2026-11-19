<template>
    <FormCard :submit-label="faq ? 'Save changes' : 'Add FAQ'" :cancel-href="route('faq.index')"
              :processing="form.processing" @submit="saveResource(form, 'faq', 'FAQ', {id: faq?.id})">
        <SelectField v-model="form.type" label="Category" :options="FAQ_CATEGORIES" option-value="value"
                     placeholder="Select a category" required :error="form.errors.type" class="sm:max-w-xs"/>
        <TextField v-model="form.question" label="Question" placeholder="e.g. Can foreigners buy property in Egypt?"
                   required autofocus :error="form.errors.question"/>
        <TextField v-model="form.answer" label="Answer" type="textarea" :rows="6" placeholder="The answer visitors will read"
                   required :error="form.errors.answer"/>
    </FormCard>
</template>

<script setup>
import {useForm} from '@inertiajs/vue3';
import FormCard from '@/Components/Admin/ui/FormCard.vue';
import SelectField from '@/Components/Admin/ui/SelectField.vue';
import TextField from '@/Components/Admin/ui/TextField.vue';
import {currentLocale, saveResource} from '@/Composables/useResourceForm.js';
import {FAQ_CATEGORIES} from './categories.js';

const props = defineProps({
    // null when creating
    faq: {type: Object, default: null},
});

const form = useForm({
    type: props.faq?.type ?? '',
    question: props.faq?.question ?? '',
    answer: props.faq?.answer ?? '',
    locale: currentLocale(),
});
</script>
