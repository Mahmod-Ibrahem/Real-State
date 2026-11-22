import {ref} from 'vue';
import axios from 'axios';

const REFRESH_EVERY_MS = 60_000;

// Module-level so the feed and its timer survive the layout remounting on every Inertia visit
const notifications = ref([]);
const unread = ref(0);
const loading = ref(false);
let timer = null;

async function refresh() {
    loading.value = true;
    try {
        const {data} = await axios.get(route('notifications.index'));
        notifications.value = data.data;
        unread.value = data.meta.unread;
    } finally {
        loading.value = false;
    }
}

async function markAllRead() {
    await axios.patch(route('notifications.read-all'));
    unread.value = 0;
    notifications.value = notifications.value.map((notification) => ({...notification, is_read: true}));
}

export function useNotificationFeed() {
    if (!timer) {
        refresh();
        timer = setInterval(() => document.hidden || refresh(), REFRESH_EVERY_MS);
    }

    return {notifications, unread, loading, markAllRead};
}
