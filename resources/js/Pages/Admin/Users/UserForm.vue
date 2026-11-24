<template>
    <FormModal :open="open" :title="user ? `Edit ${user.name}` : 'New user'"
               :submit-label="user ? 'Save changes' : 'Add user'" :processing="form.processing"
               @close="emit('close')" @submit="save">
        <TextField v-model="form.name" label="Name" required :error="form.errors.name"/>
        <TextField v-model="form.email" label="Email" type="email" required :error="form.errors.email"/>
        <SelectField v-model="form.role_id" label="Role" :options="roles" placeholder="Choose a role"
                     required :error="form.errors.role_id"/>
        <TextField v-model="form.password" label="Password" type="password" :required="!user"
                   :hint="user ? 'Leave blank to keep the current password.' : ''" :error="form.errors.password"/>
        <TextField v-model="form.password_confirmation" label="Confirm password" type="password" :required="!user"/>
    </FormModal>
</template>

<script setup>
import {useForm} from '@inertiajs/vue3';
import FormModal from '@/Components/Admin/ui/FormModal.vue';
import SelectField from '@/Components/Admin/ui/SelectField.vue';
import TextField from '@/Components/Admin/ui/TextField.vue';
import {resetFormOnOpen, saveResource} from '@/Composables/useResourceForm.js';

const props = defineProps({
    open: {type: Boolean, default: false},
    // null when creating
    user: {type: Object, default: null},
    roles: {type: Array, required: true},
});
const emit = defineEmits(['close']);

const fields = () => ({
    name: props.user?.name ?? '',
    email: props.user?.email ?? '',
    role_id: props.user?.role_id ?? null,
    password: '',
    password_confirmation: '',
});

const form = useForm(fields());
resetFormOnOpen(form, () => props.open, fields);

const save = () => saveResource(form, 'admin.users', 'user', {id: props.user?.id, onSuccess: () => emit('close')});
</script>
