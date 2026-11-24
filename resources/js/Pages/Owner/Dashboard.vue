<template>
    <AuthenticatedLayout :nav="OWNER_NAV" title="Dashboard" description="Your listings and their review status.">
        <template #actions>
            <Button :href="route('supplier.properties.create')" :icon="PlusIcon">Add property</Button>
        </template>

        <div class="grid gap-4">
            <div v-if="under_review" role="status"
                 class="flex gap-3 rounded-xl border border-amber-200 bg-amber-50 px-4 py-3 text-sm text-amber-800">
                <ClockIcon class="mt-0.5 h-5 w-5 shrink-0 text-amber-500" aria-hidden="true"/>
                <p>
                    <span class="font-semibold">Your account is under review.</span>
                    You can add and edit properties, but none of them appear on the website until an admin approves your account.
                </p>
            </div>

            <div v-if="unverifiedNotice" role="status"
                 class="flex gap-3 rounded-xl border border-amber-200 bg-amber-50 px-4 py-3 text-sm text-amber-800">
                <ShieldExclamationIcon class="mt-0.5 h-5 w-5 shrink-0 text-amber-500" aria-hidden="true"/>
                <p>
                    <span class="font-semibold">{{ unverifiedNotice }}</span>
                    <Link :href="route('supplier.profile.show')" class="font-medium underline hover:text-amber-900">
                        Verify on your profile.
                    </Link>
                </p>
            </div>

            <Panel title="My listings" hint="New listings stay hidden until an admin approves them">
                <MetricGroup :metrics="listingMetrics"/>
            </Panel>

            <Panel title="Notifications">
                <template #action>
                    <button v-if="hasUnread" type="button" :disabled="markingRead" @click="markAllRead"
                            class="text-sm font-medium text-indigo-600 hover:text-indigo-700 disabled:opacity-50">
                        Mark all as read
                    </button>
                </template>
                <ul v-if="notifications.data.length" class="-mx-6 divide-y divide-slate-100">
                    <li v-for="notification in notifications.data" :key="notification.id">
                        <Link :href="route('notifications.open', notification.id)"
                              class="flex items-center justify-between gap-3 px-6 py-3 transition-colors hover:bg-slate-50">
                            <span class="flex min-w-0 items-center gap-2.5">
                                <span class="h-2 w-2 shrink-0 rounded-full" :class="notification.is_read ? 'bg-transparent' : 'bg-indigo-600'"
                                      aria-hidden="true"></span>
                                <span class="truncate text-sm" :class="notification.is_read ? 'text-slate-600' : 'font-medium text-slate-900'">
                                    {{ notification.message }}
                                </span>
                            </span>
                            <span class="shrink-0 text-xs text-slate-400">{{ formatDate(notification.created_at) }}</span>
                        </Link>
                    </li>
                </ul>
                <p v-else class="py-6 text-center text-sm text-slate-500">No notifications yet. We'll let you know when a listing is approved.</p>
            </Panel>
        </div>
    </AuthenticatedLayout>
</template>

<script setup>
import {computed, ref} from 'vue';
import {Link, router} from '@inertiajs/vue3';
import axios from 'axios';
import {PlusIcon} from '@heroicons/vue/20/solid';
import {ClockIcon, ShieldExclamationIcon} from '@heroicons/vue/24/outline';
import AuthenticatedLayout from '@/Layouts/Admin/AuthenticatedLayout.vue';
import MetricGroup from '@/Components/Admin/MetricGroup.vue';
import Button from '@/Components/Admin/ui/Button.vue';
import Panel from '@/Components/Admin/ui/Panel.vue';
import {OWNER_NAV} from '@/Components/Owner/navigation.js';
import {formatDate} from '@/Composables/useFormatters.js';

const props = defineProps({
    counts: {type: Object, required: true},
    notifications: {type: Object, required: true},
    under_review: {type: Boolean, default: false},
    unverified_email: {type: Boolean, default: false},
    unverified_phone: {type: Boolean, default: false},
});

const unverifiedNotice = computed(() => {
    if (props.unverified_email && props.unverified_phone) return "Your email address and phone number aren't verified.";
    if (props.unverified_email) return "Your email address isn't verified.";
    return props.unverified_phone ? "Your phone number isn't verified." : null;
});

const listingMetrics = computed(() => [
    {label: 'Under review', value: props.counts.pending, href: route('supplier.properties.index')},
    {label: 'Approved', value: props.counts.approved, href: route('supplier.properties.index')},
    {label: 'Rejected', value: props.counts.rejected, href: route('supplier.properties.index')},
    {label: 'Live on the site', value: props.counts.published},
]);

const hasUnread = computed(() => props.notifications.data.some((notification) => !notification.is_read));
const markingRead = ref(false);

async function markAllRead() {
    markingRead.value = true;
    try {
        await axios.patch(route('notifications.read-all'));
        router.reload({only: ['notifications']});
    } finally {
        markingRead.value = false;
    }
}
</script>
