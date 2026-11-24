<template>
    <div role="status"
         class="flex flex-wrap items-center justify-center gap-x-3 gap-y-1 border-b border-amber-200 bg-amber-50 px-4 py-2 text-sm text-amber-800">
        <p class="min-w-0">
            You're viewing as <span class="font-semibold">{{ user.name }}</span>
            <span class="break-all text-amber-700">({{ user.email }})</span>
        </p>
        <button type="button" :disabled="leaving" @click="leave"
                class="inline-flex items-center gap-1.5 rounded-md font-medium underline transition-colors hover:text-amber-900 focus:outline-none focus-visible:ring-2 focus-visible:ring-amber-500/40 disabled:opacity-60">
            <ArrowPathIcon v-if="leaving" class="h-4 w-4 animate-spin" aria-hidden="true"/>
            Return to my account
        </button>
    </div>
</template>

<script setup>
import {computed, ref} from 'vue';
import {router, usePage} from '@inertiajs/vue3';
import {ArrowPathIcon} from '@heroicons/vue/24/outline';
import {showToast} from '@/Composables/useToast.js';

const page = usePage();
const user = computed(() => page.props.auth.user);
const leaving = ref(false);

function leave() {
    leaving.value = true;
    router.post(route('impersonation.leave'), {}, {
        onError: (errors) => errors.database && showToast(errors.database, 'error'),
        onFinish: () => (leaving.value = false),
    });
}
</script>
