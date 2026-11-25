<template>
    <AuthenticatedLayout :nav="OWNER_NAV" title="Profile" description="Your account details.">
        <div class="grid gap-4">
            <Panel title="Public profile" hint="Shown with your properties on the website.">
                <form class="space-y-5" @submit.prevent="save">
                    <AvatarField v-model="form.avatar" v-model:removed="form.remove_avatar" :current="profile.avatar"
                                 :name="profile.name" label="Photo" :error="form.errors.avatar"/>
                    <div>
                        <PhoneField v-model="form.whatsapp_phone" v-model:country="form.whatsapp_country" label="WhatsApp number"
                                    :error="form.errors.whatsapp_phone" :country-error="form.errors.whatsapp_country"/>
                        <p class="mt-1.5 text-xs text-slate-500">
                            Visitors tap WhatsApp on your properties to message this number. Leave it empty to hide the button.
                        </p>
                    </div>
                    <Button type="submit" :loading="form.processing">Save</Button>
                </form>
            </Panel>

            <Panel title="Account details">
                <dl class="-my-2 divide-y divide-slate-100">
                    <div class="py-3">
                        <dt class="text-xs font-medium text-slate-500">Name</dt>
                        <dd class="mt-1 text-sm text-slate-900">{{ profile.name }}</dd>
                    </div>
                    <div class="py-3">
                        <dt class="text-xs font-medium text-slate-500">Email</dt>
                        <dd class="mt-1 flex flex-wrap items-center gap-x-3 gap-y-2 text-sm text-slate-900">
                            <span class="break-all">{{ profile.email }}</span>
                            <StatusBadge :tone="profile.email_verified ? 'success' : 'warning'">
                                {{ profile.email_verified ? 'Verified' : 'Not verified' }}
                            </StatusBadge>
                            <VerifyCodeDropdown v-if="!profile.email_verified" channel="email" :destination="profile.email"/>
                        </dd>
                    </div>
                    <div class="py-3">
                        <dt class="text-xs font-medium text-slate-500">Phone</dt>
                        <dd v-if="profile.phone" class="mt-1 flex flex-wrap items-center gap-x-3 gap-y-2 text-sm text-slate-900">
                            <span dir="ltr" class="tabular-nums">{{ readablePhone }}</span>
                            <StatusBadge :tone="profile.phone_verified ? 'success' : 'warning'">
                                {{ profile.phone_verified ? 'Verified' : 'Not verified' }}
                            </StatusBadge>
                            <VerifyCodeDropdown v-if="!profile.phone_verified" channel="phone" :destination="readablePhone"/>
                        </dd>
                        <dd v-else class="mt-1 text-sm text-slate-400">Not added</dd>
                    </div>
                </dl>
            </Panel>
        </div>
    </AuthenticatedLayout>
</template>

<script setup>
import {computed} from 'vue';
import {useForm} from '@inertiajs/vue3';
import {parsePhoneNumber} from 'libphonenumber-js/min';
import AuthenticatedLayout from '@/Layouts/Admin/AuthenticatedLayout.vue';
import VerifyCodeDropdown from '@/Components/Owner/VerifyCodeDropdown.vue';
import AvatarField from '@/Components/Admin/ui/AvatarField.vue';
import Button from '@/Components/Admin/ui/Button.vue';
import Panel from '@/Components/Admin/ui/Panel.vue';
import PhoneField from '@/Components/Admin/ui/PhoneField.vue';
import StatusBadge from '@/Components/Admin/ui/StatusBadge.vue';
import {OWNER_NAV} from '@/Components/Owner/navigation.js';
import {splitPhone} from '@/Composables/phoneCountries.js';
import {showToast} from '@/Composables/useToast.js';

const props = defineProps({
    profile: {type: Object, required: true},
});

const readablePhone = computed(() => props.profile.phone && parsePhoneNumber(props.profile.phone).formatInternational());

const whatsApp = splitPhone(props.profile.whatsapp_phone);
const form = useForm({
    avatar: null,
    remove_avatar: false,
    whatsapp_country: whatsApp.country,
    whatsapp_phone: whatsApp.number,
});

// Sent as POST + _method so the photo uploads as multipart
const save = () => form
    .transform((data) => ({...data, whatsapp_phone: data.whatsapp_phone || null, _method: 'patch'}))
    .post(route('supplier.profile.update'), {
        preserveScroll: true,
        onSuccess: () => {
            form.reset('avatar', 'remove_avatar');
            showToast('Profile updated');
        },
        onError: (errors) => errors.database && showToast(errors.database, 'error'),
    });
</script>
