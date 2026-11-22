<template>
    <div ref="root" class="relative">
        <button type="button" :aria-expanded="open" aria-haspopup="true" @click="open = !open"
                :aria-label="unread ? `Notifications, ${unread} unread` : 'Notifications'"
                class="relative grid h-9 w-9 place-content-center rounded-lg text-slate-500 transition-colors hover:bg-slate-900/5 hover:text-slate-900 focus:outline-none focus-visible:ring-2 focus-visible:ring-indigo-500/30">
            <BellIcon class="h-5 w-5" aria-hidden="true"/>
            <span v-if="unread"
                  class="absolute -right-0.5 -top-0.5 min-w-[18px] rounded-full bg-indigo-600 px-1 text-center text-[10px] font-semibold leading-[18px] text-white">
                {{ unread > 9 ? '9+' : unread }}
            </span>
        </button>

        <transition enter-active-class="transition duration-100 ease-out" enter-from-class="opacity-0"
                    leave-active-class="transition duration-75 ease-in" leave-to-class="opacity-0">
            <div v-if="open" class="absolute right-0 z-50 mt-1.5 w-80 rounded-lg border border-slate-200 bg-white shadow-lg">
                <div class="flex items-center justify-between border-b border-slate-100 px-3 py-2.5">
                    <p class="text-sm font-semibold text-slate-900">Notifications</p>
                    <button v-if="unread" type="button" :disabled="markingRead" @click="markAllRead"
                            class="text-xs font-medium text-indigo-600 hover:text-indigo-700 disabled:opacity-50">
                        Mark all as read
                    </button>
                </div>

                <p v-if="loading && !notifications.length" class="px-3 py-6 text-center text-sm text-slate-500">Loading…</p>
                <ul v-else-if="notifications.length" class="max-h-96 divide-y divide-slate-100 overflow-y-auto">
                    <li v-for="notification in notifications" :key="notification.id">
                        <Link :href="route('notifications.open', notification.id)" @click="open = false"
                              class="flex gap-2.5 px-3 py-2.5 transition-colors hover:bg-slate-50">
                            <span class="mt-1.5 h-2 w-2 shrink-0 rounded-full" aria-hidden="true"
                                  :class="notification.is_read ? 'bg-transparent' : 'bg-indigo-600'"></span>
                            <span class="min-w-0">
                                <span class="block text-sm" :class="notification.is_read ? 'text-slate-600' : 'font-medium text-slate-900'">
                                    {{ notification.message }}
                                </span>
                                <span class="mt-0.5 block text-xs text-slate-400">{{ formatDate(notification.created_at) }}</span>
                            </span>
                        </Link>
                    </li>
                </ul>
                <p v-else class="px-3 py-6 text-center text-sm text-slate-500">You're all caught up.</p>
            </div>
        </transition>
    </div>
</template>

<script setup>
import {ref} from 'vue';
import {Link} from '@inertiajs/vue3';
import {BellIcon} from '@heroicons/vue/24/outline';
import {formatDate} from '@/Composables/useFormatters.js';
import {useDismissible} from '@/Composables/useDismissible.js';
import {useNotificationFeed} from '@/Composables/useNotificationFeed.js';

const {notifications, unread, loading, markAllRead: markFeedRead} = useNotificationFeed();

const open = ref(false);
const root = ref(null);
const markingRead = ref(false);
useDismissible(root, open);

async function markAllRead() {
    markingRead.value = true;
    try {
        await markFeedRead();
    } finally {
        markingRead.value = false;
    }
}
</script>
