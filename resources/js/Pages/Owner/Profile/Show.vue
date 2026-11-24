<template>
    <AuthenticatedLayout :nav="OWNER_NAV" title="Profile" description="Your account details.">
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
    </AuthenticatedLayout>
</template>

<script setup>
import {computed} from 'vue';
import {parsePhoneNumber} from 'libphonenumber-js/min';
import AuthenticatedLayout from '@/Layouts/Admin/AuthenticatedLayout.vue';
import VerifyCodeDropdown from '@/Components/Owner/VerifyCodeDropdown.vue';
import Panel from '@/Components/Admin/ui/Panel.vue';
import StatusBadge from '@/Components/Admin/ui/StatusBadge.vue';
import {OWNER_NAV} from '@/Components/Owner/navigation.js';

const props = defineProps({
    profile: {type: Object, required: true},
});

const readablePhone = computed(() => props.profile.phone && parsePhoneNumber(props.profile.phone).formatInternational());
</script>
