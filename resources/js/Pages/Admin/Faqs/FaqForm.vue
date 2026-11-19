<template>
    <FormModal :open="open" :title="faq ? 'Edit FAQ' : 'New FAQ'" size="lg"
               :submit-label="faq ? 'Save changes' : 'Add FAQ'" :processing="form.processing"
               @close="emit('close')" @submit="save">
        <TextField v-model="form.question" label="Question" placeholder="e.g. Can foreigners buy property in Egypt?"
                   required :error="form.errors.question"/>
        <TextField v-model="form.answer" label="Answer" type="textarea" :rows="6" placeholder="The answer visitors will read"
                   required :error="form.errors.answer"/>
    </FormModal>
</template>

<script setup>
import {useForm} from '@inertiajs/vue3';
import FormModal from '@/Components/Admin/ui/FormModal.vue';
import TextField from '@/Components/Admin/ui/TextField.vue';
import {currentLocale, resetFormOnOpen, saveResource} from '@/Composables/useResourceForm.js';

const props = defineProps({
    open: {type: Boolean, default: false},
    // null when creating
    faq: {type: Object, default: null},
});
const emit = defineEmits(['close']);

const locale = currentLocale();
const fields = () => ({
    question: props.faq?.question ?? '',
    answer: props.faq?.answer ?? '',
    locale,
});

const form = useForm(fields());
resetFormOnOpen(form, () => props.open, fields);

const save = () => saveResource(form, 'admin.faqs', 'FAQ', {id: props.faq?.id, onSuccess: () => emit('close')});
</script>
